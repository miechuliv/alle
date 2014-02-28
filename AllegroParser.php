<?php
/**
 * Created by JetBrains PhpStorm.
 * User: miechuliv
 * Date: 09.02.14
 * Time: 15:55
 * To change this template use File | Settings | File Templates.
 */

class AllegroParser {

    private $allegroUrl = 'http://allegro.pl/';
    private $searchSubCategories = true;
    private $currentSubCategory;
    private $currentSubCategoryId;
    private $currentUserProfile;
    private $currentAuction;
    private $snoopy;
    private $dom;
    private $auctionDom;
    private $isCompanyFlag = false;

    public function setCurrentSubCategory($currentSubCategory)
    {
        $this->currentSubCategory = $currentSubCategory;
    }

    public function setSnoopy(Snoopy $snoopy)
    {
        $this->snoopy = $snoopy;
    }

    public function setSearchSubCategories($searchSubCategories)
    {
        $this->searchSubCategories = $searchSubCategories;
    }

    private function getCategoryIdFromUrl($url)
    {
        $p = explode('?',$url);

        $t = explode('-',$p[0]);

        return array_shift($t);
    }

    private function addParams(&$url)
    {
        if(strpos($url,'?')!==false)
        {
            $url .= '&buyUsed=1&limit=180';
        }
        else
        {
            $url .= '?buyUsed=1&limit=180';
        }


    }

    function getListing()
    {
        $this->emptySellers();

        $url = $this->allegroUrl.$this->currentSubCategory;
        $this->addParams($url);
        $this->snoopy->fetch($url);

        $dom = new DOMDocument();

        error_reporting(0);
        ini_set('display_errors', '0');

        $dom->loadHTML($this->snoopy->results);

        $this->dom = $dom;



        error_reporting(E_ALL);
        ini_set('display_errors', '1');

        $subcategories = $this->getSubcategories();

        if($this->searchSubCategories AND is_array($subcategories) AND !empty($subcategories))
        {
            foreach($subcategories as $subcategory)
            {
                $this->currentSubCategory =$subcategory;
                $this->currentSubCategoryId = $this->getCategoryIdFromUrl($subcategory);
                $this->getListing();
            }
        }
        else
        {
             // jesteśmy już na dnie kategorri i nie da się niżej
            // sprawdzamy ile jest stron w listowaniu
            echo 'in';
            $limit  = $this->getPagination();




            if($limit > 1)
            {

                for($i = 2;$i <= $limit; $i++)
                {
                    $url .= '&p='.$i;
                    $this->snoopy->fetch($url);

                    $dom = new DOMDocument();

                    error_reporting(0);
                    ini_set('display_errors', '0');

                    $dom->loadHTML($this->snoopy->results);

                    $this->dom = $dom;

                    error_reporting(E_ALL);
                    ini_set('display_errors', '1');

                    $auctions = $this->getAuctions();

                    foreach($auctions as $auction)
                    {
                        $this->getAuctionData($auction);
                    }
                }


            }
            else
            {
                $auctions = $this->getAuctions();

                foreach($auctions as $auction)
                {
                    $this->getAuctionData($auction);
                }
            }


        }
    }

    private function getAuctionData($auctionUrl)
    {


        $url = $this->allegroUrl.$auctionUrl;

        $this->snoopy->fetch($url);

        $dom = new DOMDocument();

        error_reporting(0);
        ini_set('display_errors', '0');

        $dom->loadHTML($this->snoopy->results);

        error_reporting(E_ALL);
        ini_set('display_errors', '1');

        $this->auctionDom = $dom;

        $data = array(
            'auction_page' => $this->allegroUrl.$auctionUrl,
            'condition' => 'used',
        );


        $this->currentAuction = $auctionUrl;

        $continue = $this->getSellerInfo($data,$auctionUrl);


        error_reporting(E_ALL);
        ini_set('display_errors', '1');


        if($continue)
        {
            $this->parseSellerTemplate($data);

            if(!isset($data['telephone']) OR !isset($data['email']) OR(!$data['telephone'] OR  !$data['email']))
            {
                $this->parseSellerProfile($data,$data['allegro_id']);
            }

            $this->saveSeller($data);
        }




    }

    private function findSeller($allegro_id,$category_id,$category_name)
    {
        $res = SellerCategories::find(array('conditions' => array(' allegro_id = ? AND category_id = ?', $allegro_id, $category_id)));


        if(count($res) > 1)
        {
            throw new Exception("pod allegro_id ".$allegro_id." nie powinno być wiecej niż 1 wyników ");
        }
        elseif(count($res) > 0)
        {
            return true;
        }
        else
        {
            // dodajem gościa do tej kategorii
            $c = new SellerCategories(array(
                'allegro_id' => $allegro_id,
                'category_id' => $category_id,
                'category_name' => $category_name,
            ));

            $c->save();

            // sprawdzamy czy nie ma go w innych kategoriach

            $res = Seller::find(array('conditions' => array(' allegro_id = ? ', $allegro_id)));

            if(!$res)
            {
                return false;
            }
            else
            {
                return true;
            }


        }
    }


    private function  saveSeller($data)
    {

            $seller = new Seller($data);



            if($seller->is_invalid())
            {
                foreach($seller->errors->full_messages() as $error)
                {
                    var_dump($error);
                }
                var_dump($this->currentSubCategory);
                var_dump($this->currentAuction);
                var_dump($this->currentUserProfile);
                throw new Exception("Problem z zapisem sellera ");
            }

            $seller->save();
    }

    private function emptySellers()
    {
        Seller::table()->delete(array());
        SellerCategories::table()->delete(array());
    }

    private function getSellerInfo(&$data,$auctionUrl)
    {
        $finder = new DomXPath($this->auctionDom);
        $classname='sellerDetails';
        $details = $finder->query("//*[contains(concat(' ', normalize-space(@class), ' '), ' $classname ')]");

        if($details->length)
        {
            $dt = $details->item(0)->getElementsByTagName('dt');


                $user_id = $dt->item(0)->getAttribute('data-seller');

                $user_name = $dt->item(0)->nodeValue;

                $data['allegro_id'] = $user_id;

                $data['seller_profile'] = $this->allegroUrl.'my_page.php?uid='.$user_id;

                $data['seller_name'] = strip_tags($user_name);


        }

        if(!isset($data['seller_name']) OR !$data['seller_name'])
        {
            var_dump($auctionUrl);
            var_dump($details);
            throw new Exception("Problem ze sciaganiem imienia");
        }

        if(!isset($data['allegro_id']))
        {
             throw new Exception("Nie udało się odnależć user_id dla ".$auctionUrl." w".$this->allegroUrl.$this->currentSubCategory);
        }
        elseif($this->findSeller($data['allegro_id'],$this->currentSubCategoryId,$this->allegroUrl.$this->currentSubCategory)!=false)
        {
            // jest już zapisany w tej kategorii
             return false;
        }


        // compay data
        $companyDataLink = $this->auctionDom->getElementById('companyDataLink');

        if(is_object($companyDataLink))
        {
            $companyData = $companyDataLink->getAttribute('data-company');

            if($companyData)
            {
                $ar = json_decode($companyData);
                  $url = $this->allegroUrl.'company_icon_get_data_ajax.php?user='. $ar->user .'&item='. $ar->item .'&category='. $ar->category;
                  $this->snoopy->fetch($url);

                  $smallDom = new DOMDocument();
                  $smallDom->loadHTML($this->snoopy->results);

                  $p = $smallDom->getElementsByTagName('p');


                    $data['name'] = $p->item(0)->nodeValue;
                    $data['company_name'] = $p->item(1)->nodeValue;
                $data['address'] = $p->item(2)->nodeValue;
                $tmp = explode(' ',trim($p->item(3)->nodeValue));

                $data['zip_code'] = $tmp[0];
                $data['city'] = isset($tmp[1])?$tmp[1]:false;

                $data['regon'] = str_ireplace('REGON: ','',$p->item(4)->nodeValue);
                $data['nip'] = str_ireplace('NIP: ','',$p->item(5)->nodeValue);

                $this->isCompanyFlag = true;

            }
            else
            {
                $this->isCompanyFlag = false;
            }
        }
        else
        {
            $this->isCompanyFlag = false;
        }

        return true;

    }

    private function parseSellerProfile(&$data,$allegro_id)
    {

        $url = $this->allegroUrl.'my_page.php?uid='.$allegro_id;

        $this->currentUserProfile = $url;

        $this->snoopy->fetch($url);

        error_reporting(0);
        ini_set('display_errors', '0');
        $smallDom = new DOMDocument();
        $smallDom->loadHTML($this->snoopy->results);
        error_reporting(E_ALL);
        ini_set('display_errors', '1');

        $template = $smallDom->getElementById('user_field');


        // szukaj email

        $string = $smallDom->saveHTML($template);

        $email = array();

        preg_match("/[_a-z0-9-]*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,3})/",$string,$email);



        if(!empty($email))
        {
            $data['email'] = $email[0];
        }

        // gg


        $gg = array();

        preg_match('/([gG]{2}:?+\s?+[0-9]{0,10})|(((Gadu-Gadu|gadu-gadu):?+\s?+[0-9]{0,10}))/',$string,$gg);


        if(!empty($gg))
        {
            $data['gg'] = strip_tags(trim(str_ireplace('gg','',trim($gg[0]))));
        }

        // telefon
        // wzorce xxx-xxx-xxx xxxxxxxxx xxx xxx xxx

        $telephone = array();

        preg_match('/([0-9]{3}+(\s|-)+[0-9]{2,3}+(\s|-)+[0-9]{2,3}+(\s|-)+([0-9]{2})?+[^0-9])|([^0-9]+[0-9]{9}+[^0-9])|([^0-9]+(00)?\+?\s?[0-9]{2}+[0-9]{9}+[^0-9])/',$string,$telephone);


        if(!empty($telephone))
        {

            $data['telephone'] = strip_tags($telephone[0]);
        }

        // telefon stacjonarny
        // wzorce: xxx-xx-xx xxxxxxx xxx xx xx
        $telephone_2 = array();

        preg_match('/([0-9]{3}+(\s|-)+[0-9]{2}+(\s|-)+[0-9]{2}+[^0-9])|((?:[^0-9])+[0-9]{7}+(?:[^0-9]))|((?:[^0-9])+0[0-9]{2}+[0-9]{7}+[^0-9])/',$string,$telephone_2);


        if(!empty($telephone_2))
        {

            $data['telephone_2'] = strip_tags($telephone_2[0]);
        }



        if(!$this->isCompanyFlag)
        {

            // kod pocztowy

            $zip_code = array();

            preg_match('/[^0-9]+[0-9]{2}+-+[0-9]{3}+([^0-9])/',$string,$zip_code);


            if(!empty($zip_code))
            {

                $data['zip_code'] = strip_tags($zip_code[0]);
            }

            // ulica



            $address = array();

            preg_match('/(?:ul|UL)+.?+[^0-9]*+[0-9]{0,5}/',$string,$address);


            if(!empty($address))
            {

                $data['address'] = strip_tags($address[0]);
            }


        }


    }
    /*
     * parsuje szablon sprzdawcy w poszukiwaniu e-maila, telefonu , gg i jesli wczesniej niezaleziono innycjh danych
     */
    private function parseSellerTemplate(&$data)
    {
        $template = $this->auctionDom->getElementById('user_field');


        // szukaj email

        $string = $this->auctionDom->saveHTML($template);

        $email = array();

        preg_match("/[_a-z0-9-]*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,3})/",$string,$email);



        if(!empty($email))
        {
            $data['email'] = $email[0];
        }

        // gg


        $gg = array();

        preg_match('/([gG]{2}:?+\s?+[0-9]{0,10})|(((Gadu-Gadu|gadu-gadu):?+\s?+[0-9]{0,10}))/',$string,$gg);


        if(!empty($gg))
        {
            $data['gg'] = strip_tags(trim(str_ireplace('gg','',trim($gg[0]))));
        }

        // telefon
        // wzorce xxx-xxx-xxx xxxxxxxxx xxx xxx xxx

        $telephone = array();

        preg_match('/([0-9]{3}+(\s|-)+[0-9]{2,3}+(\s|-)+[0-9]{2,3}+(\s|-)+([0-9]{2})?+[^0-9])|([^0-9]+[0-9]{9}+[^0-9])|([^0-9]+(00)?\+?\s?[0-9]{2}+[0-9]{9}+[^0-9])/',$string,$telephone);


        if(!empty($telephone))
        {

            $data['telephone'] = strip_tags($telephone[0]);
        }

        // telefon stacjonarny
        // wzorce: xxx-xx-xx xxxxxxx xxx xx xx
        $telephone_2 = array();

        preg_match('/([0-9]{3}+(\s|-)+[0-9]{2}+(\s|-)+[0-9]{2}+[^0-9])|((?:[^0-9])+[0-9]{7}+(?:[^0-9]))|((?:[^0-9])+0[0-9]{2}+[0-9]{7}+[^0-9])/',$string,$telephone_2);


        if(!empty($telephone_2))
        {

            $data['telephone_2'] = strip_tags($telephone_2[0]);
        }



        if(!$this->isCompanyFlag)
        {

            // kod pocztowy

            $zip_code = array();

            preg_match('/[^0-9]+[0-9]{2}+-+[0-9]{3}+([^0-9])/',$string,$zip_code);


            if(!empty($zip_code))
            {

                $data['zip_code'] = strip_tags($zip_code[0]);
            }

            // ulica



            $address = array();

            preg_match('/(?:ul|UL)+.?+[^0-9]*+[0-9]{0,5}/',$string,$address);


            if(!empty($address))
            {

                $data['address'] = strip_tags($address[0]);
            }


        }


    }



    private function getAuctions()
    {
        $finder = new DomXPath($this->dom);
        $classname='details';
        $details = $finder->query("//*[contains(concat(' ', normalize-space(@class), ' '), ' $classname ')]");

        $auctions = array();

        foreach($details as $detail)
        {
            $a = $detail->getElementsByTagName('a');


            $auctions[] = $a->item(0)->getAttribute('href');
        }

        return $auctions;
    }

    function getPagination()
    {
        $finder = new DomXPath($this->dom);
        $classname='pager-nav';
        $pag = $finder->query("//*[contains(concat(' ', normalize-space(@class), ' '), ' $classname ')]");

        if($pag->length > 0)
        {
            $span = $pag->item(0)->getElementsByTagName('span');

            if($span->length > 0 )
            {
                 $last = $span->item($span->length-2);

                 return $last->nodeValue;
            }
            else{
                return false;
            }
        }
        else
        {
            return false;
        }
    }

    function getSubcategories()
    {



        // sprawdzamy czy nie jesteśmy juz w najniższej kategorii
        $finder = new DomXPath($this->dom);
        $classname='sidebar-cat current';
        $current = $finder->query("//*[contains(concat(' ', normalize-space(@class), ' '), ' $classname ')]");

        if($current->length > 0)
        {
            return false;
        }



        $finder = new DomXPath($this->dom);
        $classname='sidebar-cat';
        $nodes = $finder->query("//*[contains(concat(' ', normalize-space(@class), ' '), ' $classname ')]");

        $links = array();





        foreach($nodes as $node)
        {

             $a = $node->getElementsByTagName('a');


              $links[] = $a->item(0)->getAttribute('href');
        }

        return $links;




    }


}
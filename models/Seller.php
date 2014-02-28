<?php
/**
 * Created by JetBrains PhpStorm.
 * User: miechuliv
 * Date: 09.02.14
 * Time: 16:00
 * To change this template use File | Settings | File Templates.
 */

class Seller extends ActiveRecord\Model{

    static $before_validation = array('add_date');

    static $before_validation_on_create = array('add_date');



    static $validates_presence_of = array(
        array('allegro_id'),
        array('date_added'),
       // array('email'),
      //  array('telephone'),
        array('seller_name'),
        array('condition'),
    );

    static $validates_numerucality_of = array(
        array('allegro_id'),

    );

    public function add_date()
    {
        $d = new DateTime();

        $this->date_added = $d->format('Y-m-d h:i:s');

    }




}
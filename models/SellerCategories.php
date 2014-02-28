<?php
/**
 * Created by JetBrains PhpStorm.
 * User: miechuliv
 * Date: 15.02.14
 * Time: 18:23
 * To change this template use File | Settings | File Templates.
 */

class SellerCategories extends ActiveRecord\Model{

    static $validates_presence_of = array(
       array('allegro_id'),
        array('category_id'),
        array('category_name'),
    );

    static $validates_numerucality_of = array(
       array('allegro_id'),
        array('category_id'),
    );

}
<?php

require_once('./libs/Activerecord/ActiveRecord.php');
require_once('./libs/Snoopy/Snoopy.class.php');
require_once('./AllegroParser.php');

ActiveRecord\Config::initialize(function($cfg)
{
   $cfg->set_model_directory('models');
   $cfg->set_connections(array(
       'development' => 'mysql://root@localhost/allegro?charset=utf8'
   ));
});

error_reporting(E_ALL);
ini_set('display_errors', '1');
//$seller = Seller::create(array('name' => 'sdlfsdf'));

//$seller->save(
set_time_limit(1000000000);

$snoopy = new Snoopy();

$allegro = new AllegroParser();
$allegro->setSearchSubCategories(true);
$allegro->setSnoopy($snoopy);
$allegro->setCurrentSubCategory('czesci-samochodowe-620');



$allegro->getListing();


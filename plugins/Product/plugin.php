<?php

$info_array = array(
	"description"=>  __d(__PRODUCT_LOCALE,'product_plugin_detail') ,
	"website"    => "springdesigng.com",
	"author"     => __d(__PRODUCT_LOCALE,'bahar_group'),
	"email"      => "info@springdesigng.com",
	"version"    => "1.0",
);
$install_query_array[]="
CREATE TABLE IF NOT EXISTS `products` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `product_category_id` int(11) NOT NULL,
  `title` varchar(300) COLLATE utf8_persian_ci NOT NULL,
  `mini_detail` varchar(500) COLLATE utf8_persian_ci NOT NULL,
  `detail` text COLLATE utf8_persian_ci NOT NULL,
  `status` tinyint(4) NOT NULL,
  `created` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `product_category_id` (`product_category_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_persian_ci AUTO_INCREMENT=1 ;
";
$install_query_array[] = "
CREATE TABLE IF NOT EXISTS `productimages` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `product_id` bigint(20) NOT NULL,
  `title` varchar(200) COLLATE utf8_persian_ci NOT NULL,
  `image` varchar(200) COLLATE utf8_persian_ci NOT NULL,
  `created` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `product_id` (`product_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_persian_ci AUTO_INCREMENT=1 ;
";
$install_query_array[] = "
CREATE TABLE IF NOT EXISTS `productcategories` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `parent_id` int(11) NOT NULL,
  `title` varchar(200) COLLATE utf8_persian_ci NOT NULL,
  `slug` varchar(200) COLLATE utf8_persian_ci NOT NULL,
  `arrangment` int(11) NOT NULL,
  `status` tinyint(4) NOT NULL,
  `created` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `parent_id` (`parent_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_persian_ci AUTO_INCREMENT=1 ;
";
$remove_query_array[]="DROP TABLE `products` ";
$remove_query_array[]="DROP TABLE `productimages` ";
$remove_query_array[]="DROP TABLE `productcategories` ";

$install_menu_query[] = array("name"=>__d(__PRODUCT_LOCALE,'product_managment'),"controller"=>'products',"action"=>"admin_index","action_name"=>__d(__PRODUCT_LOCALE,'product_list'));
$install_menu_query[] = array("name"=>__d(__PRODUCT_LOCALE,'product_managment'),"controller"=>'products',"action"=>"admin_add","action_name"=>__d(__PRODUCT_LOCALE,'add_product'));
$install_menu_query[] = array("name"=>__d(__PRODUCT_LOCALE,'product_managment'),"controller"=>'products',"action"=>"admin_edit","action_name"=>__d(__PRODUCT_LOCALE,'edit_product'));
$install_menu_query[] = array("name"=>__d(__PRODUCT_LOCALE,'product_managment'),"controller"=>'products',"action"=>"admin_delete","action_name"=>__d(__PRODUCT_LOCALE,'delete_product'));
$remove_menu_query[] = array("controller"=>'products');

?>

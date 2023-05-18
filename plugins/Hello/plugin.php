<?php

$info_array = array(
	"description"=>  __d('hello','hello_plugin_detail') ,
	"website"    => "springdesigng.com",
	"author"     => __d('hello','bahar_group'),
	"email"      => "info@springdesigng.com",
	"version"    => "1.0",
);
/*$install_query_array[1]="
CREATE TABLE `newsletter` (
  `newsletter_id` int(11) NOT NULL auto_increment,
  `email` varchar(500) collate utf8_persian_ci NOT NULL,
  `active` tinyint(2) NOT NULL default '0',
  PRIMARY KEY  (`newsletter_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_persian_ci AUTO_INCREMENT=1 ;
";*/
//$remove_query_array[1]="DROP TABLE `newsletter` ";

$install_menu_query[] = array("name"=>'مدیریت نقش ها',"controller"=>'tests',"action"=>"admin_add","action_name"=>'افزودن نقش');
$install_menu_query[] = array("name"=>'مدیریت نقش ها',"controller"=>'tests',"action"=>"admin_delete","action_name"=>'حذف نقش');
$remove_menu_query[] = array("controller"=>'tests');

?>

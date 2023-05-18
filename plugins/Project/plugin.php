<?php

$info_array = array(
	"description"=>  __d(__PROJECT_LOCALE,'project_plugin_detail') ,
	"website"    => "springdesigng.com",
	"author"     => __d(__PROJECT_LOCALE,'bahar_group'),
	"email"      => "info@springdesigng.com",
	"version"    => "1.0",
);
$install_query_array[]="
CREATE TABLE IF NOT EXISTS `projects` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `title` varchar(300) COLLATE utf8_persian_ci NOT NULL,
  `detail` text COLLATE utf8_persian_ci NOT NULL,
  `status` tinyint(4) NOT NULL,
  `created` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_persian_ci AUTO_INCREMENT=1 ;
";
$install_query_array[] = "
CREATE TABLE IF NOT EXISTS `projectimages` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `project_id` bigint(20) NOT NULL,
  `title` varchar(200) COLLATE utf8_persian_ci NOT NULL,
  `image` varchar(200) COLLATE utf8_persian_ci NOT NULL,
  `created` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `project_id` (`project_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_persian_ci AUTO_INCREMENT=1 ;
";
$remove_query_array[]="DROP TABLE `projects` ";
$remove_query_array[]="DROP TABLE `projectimages` ";

$install_menu_query[] = array("name"=>__d(__PROJECT_LOCALE,'project_managment'),"controller"=>'projects',"action"=>"admin_index","action_name"=>__d(__PROJECT_LOCALE,'project_list'));
$install_menu_query[] = array("name"=>__d(__PROJECT_LOCALE,'project_managment'),"controller"=>'projects',"action"=>"admin_add","action_name"=>__d(__PROJECT_LOCALE,'add_project'));
$install_menu_query[] = array("name"=>__d(__PROJECT_LOCALE,'project_managment'),"controller"=>'projects',"action"=>"admin_edit","action_name"=>__d(__PROJECT_LOCALE,'edit_project'));
$install_menu_query[] = array("name"=>__d(__PROJECT_LOCALE,'project_managment'),"controller"=>'projects',"action"=>"admin_delete","action_name"=>__d(__PROJECT_LOCALE,'delete_project'));
$remove_menu_query[] = array("controller"=>'projects');

?>

<?php

Router::connect('/', array(
		'controller'=> 'pages',
		'action'    => 'display',
		'home'));

//Router::connect(' / pages/*', array('controller' => 'pages', 'action' => 'display'));
Router::connect('/admin', array(
		'controller'=> 'users',
		'action'    => 'dashboard',
		'admin'     => true));
Router::connect('/:language/:controller/:action/*', array(), array('language'=>
		'[a-z]{3}'));

Router::connect('/sitemap', array('controller'=> 'sitemaps','action'    =>
		'index'));

CakePlugin::routes();

require CAKE . 'Config' . DS . 'routes.php';

<?php

// Autoload vendor libraries
require '../../vendor/autoload.php';

// Autoload project classe
spl_autoload_register('my_autoload'); 
function my_autoload( $class, $dir = null ) {
	if ( is_null( $dir ) ) {
	  $dir = './';
	}

	foreach (scandir( $dir ) as $file ) {
		// directory?
		if ( is_dir( $dir.$file ) && substr( $file, 0, 1 ) !== '.' )
			my_autoload( $class, $dir.$file.'/' );

		// php file?
		if ( substr( $file, 0, 2 ) !== '._' && preg_match( "/.php$/i" , $file ) ) {
			// filename matches class?
			if ( str_replace( '.php', '', $file ) == $class || str_replace( '.class.php', '', $file ) == $class ) {
				include $dir . $file;
			}
		}
	}
}

// Work around for Slim on GAE
if(!isset($_SERVER['SERVER_PORT'])) $_SERVER['SERVER_PORT'] = 443;

// Initialize Twig view
$twigView = new TwigView();
$twigView->twigTemplateDirs = array('view/');

// Initialize app
$app = new \Slim\Slim(array(
	'log.writer' => new LogWriter(),
	'view' => $twigView
));

// Google App Engine doesn't set $_SERVER['PATH_INFO']
$app->environment['PATH_INFO'] = $_SERVER['REQUEST_URI'];

// Routes
$app->get('/', 'MainController::index');

// Run app
$app->run();

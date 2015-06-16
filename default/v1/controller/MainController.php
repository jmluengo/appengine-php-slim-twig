<?php

/**
 * Main controller
 *
 * Custom controller class to handle requests
 */
class MainController {

	/**
     * Handle index request
     *
     */
	public static function index() {
		// Get slim app
		$app = \Slim\Slim::getInstance();
		
		// Do controller staff...

		// Render main/index template
		$app->render('main/index.twig', array('message' => 'Hello world!'));	
	}
}

<?php
class PoP_AWS_Initialization {

	function initialize(){

		// load_plugin_textdomain('pop-useravatar-aws', false, dirname(plugin_basename(__FILE__)).'/languages');
		
		/**---------------------------------------------------------------------------------------------------------------
		 * Load AWS
		 * ---------------------------------------------------------------------------------------------------------------*/
		require_once 'config/load.php';

		/**---------------------------------------------------------------------------------------------------------------
		 * Load AWS SDK
		 * ---------------------------------------------------------------------------------------------------------------*/
		require_once 'includes/load.php';

		// // If we have installed plug-in "Amazon Web Services", then the AWS SDK has already been loaded by it
		// if (!class_exists('Amazon_Web_Services')) {
			
		// 	// AWS SDK Version 2.8.27, taken from https://github.com/aws/aws-sdk-php/releases
		// 	require 'includes/aws/aws-autoloader.php';
		// }

		/**---------------------------------------------------------------------------------------------------------------
		 * Load the Library
		 * ---------------------------------------------------------------------------------------------------------------*/
		require_once 'library/load.php';
	}
}

/**---------------------------------------------------------------------------------------------------------------
 * Initialization
 * ---------------------------------------------------------------------------------------------------------------*/
// global $PoP_AWS_Initialization;
// $PoP_AWS_Initialization = new PoP_AWS_Initialization();
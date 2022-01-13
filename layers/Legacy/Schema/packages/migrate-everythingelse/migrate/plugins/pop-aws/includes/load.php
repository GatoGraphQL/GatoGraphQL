<?php

\PoP\Root\App::getHookManager()->addAction('plugins_loaded', 'popAwsInitIncludes', 115);
function popAwsInitIncludes() {
	
	// If we have installed plug-in "Amazon Web Services", then the AWS SDK has already been loaded by it
	if (!class_exists('Amazon_Web_Services')) {
		
		// AWS SDK Version 2.8.27, taken from https://github.com/aws/aws-sdk-php/releases
		require 'aws/aws-autoloader.php';
	}
}


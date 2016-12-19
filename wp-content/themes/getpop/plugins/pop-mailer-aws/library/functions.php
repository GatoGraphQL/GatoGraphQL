<?php

add_filter('PoP_Mailer_AWS_Engine:upload_to_s3:configuration', 'getpop_mailer_email_configuration', 1000);
function getpop_mailer_email_configuration($configuration) {

	// Must explicitly change the name from POP_WEBSITE, because it doesn't work for "getpop-demo"
	$configuration['website'] = 'getpop';
	return $configuration;
}
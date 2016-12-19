<?php

add_filter('PoP_Mailer_AWS_Engine:upload_to_s3:configuration', 'tppdebate_mailer_email_configuration', 1000);
function tppdebate_mailer_email_configuration($configuration) {

	// Must explicitly change the name from POP_WEBSITE, because it doesn't work for "tppdebate_my" or "tppdebate_ar"
	$configuration['website'] = 'tppdebate';
	return $configuration;
}
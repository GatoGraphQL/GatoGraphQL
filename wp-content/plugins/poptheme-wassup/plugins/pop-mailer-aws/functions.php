<?php

add_filter('PoP_Mailer_AWS_Engine:upload_to_s3:configuration', 'poptheme_wassup_email_configuration');
function poptheme_wassup_email_configuration($configuration) {

	$logo = gd_logo();
	$configuration['images']['logo'] = $logo[0];
	return $configuration;
}
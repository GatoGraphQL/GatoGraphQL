<?php

add_filter('PoP_Mailer_AWS_Engine:upload_to_s3:configuration', 'pop_mailer_cluster_email_configuration');
function pop_mailer_cluster_email_configuration($configuration) {

	$configuration['website'] = POP_WEBSITE;
	return $configuration;
}
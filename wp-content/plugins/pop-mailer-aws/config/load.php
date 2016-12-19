<?php

add_action('init', 'pop_mailer_aws_init_constants', 100);
function pop_mailer_aws_init_constants() {
	
	require_once 'constants.php';
}
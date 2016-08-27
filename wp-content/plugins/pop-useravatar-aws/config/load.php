<?php

add_action('init', 'pop_useravatar_aws_init_constants', 100);
function pop_useravatar_aws_init_constants() {
	
	require_once 'constants.php';
}
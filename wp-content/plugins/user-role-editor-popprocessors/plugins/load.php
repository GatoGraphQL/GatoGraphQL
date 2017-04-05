<?php

if (class_exists('acf'))	
	require_once 'advanced-custom-fields/load.php';

if(defined('AAL_POP_VERSION'))
	require_once 'aryo-activity-log-pop/load.php';
	
if (defined('POP_EMAILSENDER_INITIALIZED'))
	require_once 'pop-emailsender/load.php';		
	
// if (defined('POP_CDN_INITIALIZED')) {
// 	require_once 'pop-cdn/load.php';		
// }


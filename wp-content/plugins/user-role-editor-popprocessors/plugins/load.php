<?php

if (class_exists('acf'))	
	require_once 'advanced-custom-fields/load.php';

if(defined('AAL_POP_VERSION'))
	require_once 'aryo-activity-log-pop/load.php';
	
// if (class_exists('EM_Event'))	
// 	require_once 'events-manager/load.php';
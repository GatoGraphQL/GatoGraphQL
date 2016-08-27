<?php

//-------------------------------------------------------------------------------------
// Load Plugin-specific Libraries
//-------------------------------------------------------------------------------------
	
if (class_exists('EM_Event'))	
	require_once 'events-manager/load.php';

// if(defined('AAL_POPPROCESSORS_INITIALIZED'))
if(defined('AAL_POPPROCESSORS_VERSION'))
	require_once 'aryo-activity-log-popprocessors/load.php';
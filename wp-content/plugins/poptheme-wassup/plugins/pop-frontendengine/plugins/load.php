<?php

//-------------------------------------------------------------------------------------
// Load Plugin-specific Libraries
//-------------------------------------------------------------------------------------

if (defined('QTX_VERSION'))
	require_once 'qtranslate-x/load.php';

if (defined('POP_MULTIDOMAIN_INITIALIZED')) {
	require_once 'pop-multidomain/load.php';		
}

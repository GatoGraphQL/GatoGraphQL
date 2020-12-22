<?php

use PoP\ComponentModel\State\ApplicationState;

// Returns true if the response format must be in JSON
function doingJson()
{
	$vars = ApplicationState::getVars();
	return $vars['output'] == GD_URLPARAM_OUTPUT_JSON;
    // return isset($_REQUEST[GD_URLPARAM_OUTPUT]) && $_REQUEST[GD_URLPARAM_OUTPUT] == GD_URLPARAM_OUTPUT_JSON;
}

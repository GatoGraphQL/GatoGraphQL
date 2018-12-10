<?php

// Returns true if the response format must be in JSON
function doing_json() {

	return isset($_REQUEST[GD_URLPARAM_OUTPUT]) && $_REQUEST[GD_URLPARAM_OUTPUT] == GD_URLPARAM_OUTPUT_JSON;
}

<?php

class PoP_CMS_FunctionAPI_Base {

	function __construct() {

		PoP_CMS_FunctionAPI_Factory::set_instance($this);
	}
}
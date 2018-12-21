<?php
namespace PoP\CMS;

abstract class FunctionAPI_Base {

	function __construct() {

		FunctionAPI_Factory::set_instance($this);
	}
}
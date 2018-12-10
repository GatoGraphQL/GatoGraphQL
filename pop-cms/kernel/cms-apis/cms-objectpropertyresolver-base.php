<?php

class PoP_CMS_ObjectPropertyResolver_Base {

	function __construct() {

		PoP_CMS_ObjectPropertyResolver_Factory::set_instance($this);
	}
}
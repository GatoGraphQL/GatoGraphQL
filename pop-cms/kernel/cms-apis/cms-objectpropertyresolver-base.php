<?php
namespace PoP\CMS;

abstract class ObjectPropertyResolver_Base {

	function __construct() {

		ObjectPropertyResolver_Factory::set_instance($this);
	}
}
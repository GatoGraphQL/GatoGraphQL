<?php
namespace PoP\CMS;

class FunctionAPI_Factory {

	protected static $instance;

	public static function set_instance(FunctionAPI $instance) {

		self::$instance = $instance;
	}

	public static function get_instance(): ?FunctionAPI {

		return self::$instance;
	}
}
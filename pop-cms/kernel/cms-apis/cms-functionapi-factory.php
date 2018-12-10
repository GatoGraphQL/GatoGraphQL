<?php

class PoP_CMS_FunctionAPI_Factory {

	protected static $instance;

	public static function set_instance(PoP_CMS_FunctionAPI $instance) {

		self::$instance = $instance;
	}

	public static function get_instance(): ?PoP_CMS_FunctionAPI {

		return self::$instance;
	}
}
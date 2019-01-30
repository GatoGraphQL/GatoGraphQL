<?php
namespace PoP\Engine;

class DataQuery_Manager_Factory {

	protected static $instance;

	public static function set_instance(DataQuery_Manager $instance) {

		self::$instance = $instance;
	}

	public static function get_instance(): ?DataQuery_Manager {

		return self::$instance;
	}
}
<?php
namespace PoP\Engine;

class DataStructureFormat_Manager_Factory {

	protected static $instance;

	public static function set_instance(DataStructureFormat_Manager $instance) {

		self::$instance = $instance;
	}

	public static function get_instance(): ?DataStructureFormat_Manager {

		return self::$instance;
	}
}
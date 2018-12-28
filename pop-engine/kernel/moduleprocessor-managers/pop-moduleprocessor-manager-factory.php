<?php
namespace PoP\Engine;

class ModuleProcessor_Manager_Factory {

	protected static $instance;

	public static function set_instance(ModuleProcessor_Manager $instance) {

		self::$instance = $instance;
	}

	public static function get_instance(): ?ModuleProcessor_Manager {

		return self::$instance;
	}
}
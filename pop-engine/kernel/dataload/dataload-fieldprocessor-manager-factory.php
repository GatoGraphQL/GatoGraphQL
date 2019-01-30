<?php
namespace PoP\Engine;

class FieldProcessor_Manager_Factory {

	protected static $instance;

	public static function set_instance(FieldProcessor_Manager $instance) {

		self::$instance = $instance;
	}

	public static function get_instance(): ?FieldProcessor_Manager {

		return self::$instance;
	}
}
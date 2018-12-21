<?php
namespace PoP\Engine;

class ActionExecution_Manager_Factory {

	protected static $instance;

	public static function set_instance(ActionExecution_Manager $instance) {

		self::$instance = $instance;
	}

	public static function get_instance(): ?ActionExecution_Manager {

		return self::$instance;
	}
}
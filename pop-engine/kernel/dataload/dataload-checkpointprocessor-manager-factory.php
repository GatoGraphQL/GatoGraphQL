<?php
namespace PoP\Engine;

class CheckpointProcessor_Manager_Factory {

	protected static $instance;

	public static function set_instance(CheckpointProcessor_Manager $instance) {

		self::$instance = $instance;
	}

	public static function get_instance(): ?CheckpointProcessor_Manager {

		return self::$instance;
	}
}
<?php
namespace PoP\Engine;

class MemoryManager_Factory {

	protected static $instance;

	public static function set_instance(MemoryManager $instance) {

		self::$instance = $instance;
	}

	public static function get_instance(): ?MemoryManager {

		return self::$instance;
	}
}
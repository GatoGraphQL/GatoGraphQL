<?php
namespace PoP\Engine;

class CacheManager_Factory {

	protected static $instance;

	public static function set_instance(CacheManager $instance) {

		self::$instance = $instance;
	}

	public static function get_instance(): ?CacheManager {

		return self::$instance;
	}
}
<?php
namespace PoP\Engine\FileStorage;

class FileJSONStorage_Factory {

	protected static $instance;

	public static function set_instance(FileJSONStorage $instance) {

		self::$instance = $instance;
	}

	public static function get_instance(): ?FileJSONStorage {

		return self::$instance;
	}
}
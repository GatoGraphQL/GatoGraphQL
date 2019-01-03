<?php
namespace PoP\Engine\FileStorage;

class FileStorage_Factory {

	protected static $instance;

	public static function set_instance(FileStorage $instance) {

		self::$instance = $instance;
	}

	public static function get_instance(): ?FileStorage {

		return self::$instance;
	}
}
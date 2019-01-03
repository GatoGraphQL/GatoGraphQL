<?php
namespace PoP\Engine\Server;

class DefinitionManager_Factory {

	protected static $instance;

	public static function set_instance(DefinitionManager $instance) {

		self::$instance = $instance;
	}

	public static function get_instance(): ?DefinitionManager {

		return self::$instance;
	}
}
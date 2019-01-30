<?php
namespace PoP\Engine;

class QueryHandler_Manager_Factory {

	protected static $instance;

	public static function set_instance(QueryHandler_Manager $instance) {

		self::$instance = $instance;
	}

	public static function get_instance(): ?QueryHandler_Manager {

		return self::$instance;
	}
}
<?php
namespace PoP\CMS;

class ObjectPropertyResolver_Factory {

	protected static $instance;

	public static function set_instance(ObjectPropertyResolver $instance) {

		self::$instance = $instance;
	}

	public static function get_instance(): ?ObjectPropertyResolver {

		return self::$instance;
	}
}
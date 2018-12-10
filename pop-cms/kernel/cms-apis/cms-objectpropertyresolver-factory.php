<?php

class PoP_CMS_ObjectPropertyResolver_Factory {

	protected static $instance;

	public static function set_instance(PoP_CMS_ObjectPropertyResolver $instance) {

		self::$instance = $instance;
	}

	public static function get_instance(): ?PoP_CMS_ObjectPropertyResolver {

		return self::$instance;
	}
}
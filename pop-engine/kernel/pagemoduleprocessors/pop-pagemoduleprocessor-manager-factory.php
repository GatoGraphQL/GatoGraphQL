<?php

class PoPEngine_Module_PageModuleProcessorManager_Factory {

	protected static $instance;

	public static function set_instance($instance) {
		self::$instance = $instance;
	}

	public static function get_instance() {

		return self::$instance;
	}
}
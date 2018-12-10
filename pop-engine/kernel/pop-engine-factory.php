<?php

class PoP_Engine_Factory {

	protected static $instance;

	public static function set_instance($instance) {
		self::$instance = $instance;
	}

	public static function get_instance() {

		return self::$instance;
	}

	public static function output() {
		self::$instance->output();
	}
}

// // For HTML Output: call output function on the footer (it won't get called for JSON output)
// add_action('wp_footer', array(PoP_Engine_Factory::class, 'output'));

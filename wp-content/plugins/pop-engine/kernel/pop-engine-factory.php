<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Manager (Handlebars)
 *
 * ---------------------------------------------------------------------------------------------------------------*/

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

	public static function output_end() {
		self::$instance->output_end();
	}

	// public static function generate_json() {
	// 	self::$instance->generate_json();
	// }
}

// For HTML Output: call output function on the footer (it won't get called for JSON output)
add_action('wp_footer', array('PoP_Engine_Factory', 'output'));
// Priority 1000: execute at the very end. Used for printing the crawlable data, which can come last
add_action('wp_print_footer_scripts', array('PoP_Engine_Factory', 'output_end'), 1000);

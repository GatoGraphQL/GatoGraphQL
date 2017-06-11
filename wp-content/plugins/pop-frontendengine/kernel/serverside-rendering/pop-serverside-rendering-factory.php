<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Manager (Handlebars)
 *
 * ---------------------------------------------------------------------------------------------------------------*/

class PoP_ServerSideRendering_Factory {

	protected static $instance;

	public static function set_instance($instance) {
		self::$instance = $instance;
	}

	public static function get_instance() {

		return self::$instance;
	}

	// public static function render_pagesection($template_id) {
	// 	self::$instance->render_pagesection($template_id);
	// }
}

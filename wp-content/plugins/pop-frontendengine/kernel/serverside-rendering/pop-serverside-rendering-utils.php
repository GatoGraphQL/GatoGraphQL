<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Manager (Handlebars)
 *
 * ---------------------------------------------------------------------------------------------------------------*/

class PoP_ServerSideRendering_Utils {

	protected static $scripts;
	
	public static function init() {

		self::$scripts = array();

		// Priority 1: after printing 'wp_print_head_scripts' in the footer (priority 1)
		add_action('wp_footer', array('PoP_ServerSideRendering_Utils', 'print_scripts'), 2);
	}
	
	public static function print_scripts() {
	
		if (PoP_Frontend_ServerUtils::use_fastboot()) {
			
			if (self::$scripts) {

				// Print all the scripts under one unique <script> tag
				printf(
					'<script type="text/javascript">%s</script>',
					implode(PHP_EOL, self::$scripts)
				);
			}
		}
	}
	
	public static function render_pagesection($pagesection_settings_id, $target = null) {

		$html = PoP_ServerSideRendering_Factory::get_instance()->render_target($pagesection_settings_id, null, $target);

		if (PoP_Frontend_ServerUtils::use_fastboot()) {
			
			// Extract all <script> tags out, to be added once again after including jquery.js in the footer
			// $match[2] has the javascript code, without the <script tag
	        $html = preg_replace_callback('#<script(.*?)>(.*?)</script>#is', function($match) { self::$scripts[] = $match[2]; return ''; }, $html);
	    }

		return $html;
	}
}

/**---------------------------------------------------------------------------------------------------------------
 * Initialization
 * ---------------------------------------------------------------------------------------------------------------*/
PoP_ServerSideRendering_Utils::init();

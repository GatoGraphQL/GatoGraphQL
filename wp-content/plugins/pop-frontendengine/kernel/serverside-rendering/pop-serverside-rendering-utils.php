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
	
		// Add the script tags once again if we defined to have them after the html
		// If doing disable_js, then do nothing
		if (PoP_Frontend_ServerUtils::scripts_after_html()) {
			
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

		// Extract the script tags if either we need to add them after the html, or remove all JS
		if (PoP_Frontend_ServerUtils::scripts_after_html() || PoP_Frontend_ServerUtils::disable_js()) {
			
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

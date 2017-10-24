<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Core (reusable) functions of the website
 *
 * ---------------------------------------------------------------------------------------------------------------*/

class EM_PoPProcessors_ConstantsUtils {

	protected static /*$initialized, */$location_page_urls;

	public static function init() {

		// if (!self::$initialized) {

		// 	self::$initialized = true;
		// Allow PoP MultiDomain to add entries for external domains
		self::$location_page_urls = apply_filters(
			'EM_PoPProcessors_ConstantsUtils:location_page_urls',
			array(
				get_site_url() => get_permalink(POP_EM_POPPROCESSORS_PAGE_LOCATIONSMAP),
			)
		);

		add_filter('gd_jquery_constants', array('EM_PoPProcessors_ConstantsUtils', 'jquery_constants'));
		// }
	}
	
	public static function jquery_constants($jquery_constants) {

		$jquery_constants['LOCATIONS_PAGE_URL'] = self::$location_page_urls;
		return $jquery_constants;
	}

	public static function get_location_page_url($domain) {

		// self::init();
		return self::$location_page_urls[$domain];
	}
}

/**---------------------------------------------------------------------------------------------------------------
 * Initialization
 * ---------------------------------------------------------------------------------------------------------------*/
add_action('init', array('EM_PoPProcessors_ConstantsUtils', 'init'));
// EM_PoPProcessors_ConstantsUtils::init();
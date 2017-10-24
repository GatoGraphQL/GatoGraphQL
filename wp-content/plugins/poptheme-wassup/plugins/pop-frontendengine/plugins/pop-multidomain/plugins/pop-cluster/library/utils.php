<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Manager (Handlebars)
 *
 * ---------------------------------------------------------------------------------------------------------------*/

class PoPTheme_Wassup_FrontEnd_MultiDomain_Cluster_Utils {

	public static function init() {
		
		add_filter(
			'PoP_MultiDomain_Utils:transform_url',
			array('PoPTheme_Wassup_FrontEnd_MultiDomain_Cluster_Utils', 'transform_url'),
			10,
			5
		);
	}

	public static function transform_url($transformed_url, $subpath, $url, $domain, $website_name) {
		
		// Replace the subpath /WEBSITE_NAME/ (after wp-content/pop-frontend/) with the external website name
		return str_replace('/'.POP_WEBSITE.'/', '/'.$website_name.'/', $transformed_url);
	}
}

/**---------------------------------------------------------------------------------------------------------------
 * Initialization
 * ---------------------------------------------------------------------------------------------------------------*/
PoPTheme_Wassup_FrontEnd_MultiDomain_Cluster_Utils::init();

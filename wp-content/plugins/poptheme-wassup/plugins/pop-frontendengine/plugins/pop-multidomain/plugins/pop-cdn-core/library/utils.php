<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Manager (Handlebars)
 *
 * ---------------------------------------------------------------------------------------------------------------*/

class PoPTheme_Wassup_FrontEnd_MultiDomain_CDN_Utils {

	public static function add_config_sources(&$sources, $domain, $website_name) {
		
		global $pop_cdncore_configfile_generator;
		$cdnconfig_url = $pop_cdncore_configfile_generator->get_fileurl();

		$sources[$domain] = array(
			PoP_MultiDomain_Utils::transform_url($cdnconfig_url, $domain, $website_name),
		);
	}
}
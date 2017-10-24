<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Manager (Handlebars)
 *
 * ---------------------------------------------------------------------------------------------------------------*/

class PoPTheme_Wassup_FrontEnd_MultiDomain_Utils {

	public static function add_config_sources(&$sources, $domain, $website_name, $theme, $thememode) {
		
		// There are 2 files per external domain: the config.js file, and the initialresources.js file
		global $pop_resourceloader_configfile_generator, $pop_resourceloader_initialresources_configfile_generator;
		$config_url = $pop_resourceloader_configfile_generator->get_fileurl();
		$initialresources_url = $pop_resourceloader_initialresources_configfile_generator->get_fileurl();

		$sources[$domain] = array(
			PoP_MultiDomain_Utils::transform_url($config_url, $domain, $website_name, $theme, $thememode),
			PoP_MultiDomain_Utils::transform_url($initialresources_url, $domain, $website_name, $theme, $thememode),
		);
	}
}
<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Configuration
 *
 * ---------------------------------------------------------------------------------------------------------------*/

class PoP_VarsUtils {

	public static function get_vars_identifier() {

		$vars = GD_TemplateManager_Utils::get_vars();
		$identifier = '';
		if ($output = $vars['output']) {	

			// output is important to differentiate between first load (loading also the frames) and all subsequent output=json requests
			$identifier .= '-'.$output;
		}
		if ($format = $vars['format']) {
			
			$identifier .= '-'.$format;
		}
		if ($target = $vars['target']) {
			
			$identifier .= '-'.$target;
		}
		if ($pagesection = $vars['pagesection']) {
			
			$identifier .= '-'.$pagesection;
		}
		if ($tab = $vars['tab']) {
			
			$identifier .= '-'.str_replace(array('-', '/'), '', $tab);
		}
		if ($action = $vars['action']) {
			
			$identifier .= '-'.str_replace('-', '', $action);
		}
		if ($action = $vars['action']) {
			
			$identifier .= '-'.str_replace('-', '', $action);
		}
		if ($config = $vars['config']) {
			
			$identifier .= '-'.str_replace(',', '', $config);
		}
		if ($datastructure = $vars['datastructure']) {
			
			$identifier .= '-'.$datastructure;
		}
		if ($module = $vars['module']) {
			
			// Module is also needed, because the cached data-settings from module=datasettings and module=data are different
			$identifier .= '-'.$module;
		}
		if ($mangled = $vars['mangled']) {
			
			// By default it is mangled. To make it non-mangled, url must have param "mangled=none",
			// so only in these exceptional cases the identifier will add this parameter
			$identifier .= '-'.$mangled;
		}
		if ($theme = $vars['theme']) {
			
			$identifier .= '-'.str_replace('-', '', $theme);
		}
		if ($thememode = $vars['thememode']) {
			
			$identifier .= '-'.str_replace('-', '', $thememode);
		}
		if ($themestyle = $vars['themestyle']) {
			
			$identifier .= '-'.str_replace('-', '', $themestyle);
		}
		// if ($domain = $vars['domain']) {
			
		// 	$identifier .= '-'.str_replace('-', '', GD_TemplateManager_Utils::get_domain_id($domain));
		// }
		// Comment Leo 05/04/2017: do also add the version, because otherwise there are PHP errors
		// happening from stale configuration that is not deleted, and still served, after a new version is deployed
		// By adding the version, that will not happen anymore
		$identifier .= '-'.str_replace('.', '', pop_version());

		// Comment Leo 05/04/2017: Also add the template-definition type, for 2 reasons:
		// 1. It allows to create the 2 versions (DEV/PROD) of the configuration files, to compare/debug them side by side
		// 2. It allows to switch from DEV/PROD without having to delete the pop-cache
		$identifier .= '-'.POP_SERVER_TEMPLATEDEFINITION_TYPE;

		// Allow for plug-ins to add their own vars. Eg: URE source parameter
		$identifier = apply_filters('GD_Template_CacheProcessor:add_vars', $identifier);

		return $identifier;
	}
}
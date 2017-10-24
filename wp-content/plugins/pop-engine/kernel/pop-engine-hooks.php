<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Manager (Handlebars)
 *
 * ---------------------------------------------------------------------------------------------------------------*/

class PoP_Engine_Hooks {

	function __construct() {

		// Add functions as hooks, so we allow PoP_WPAPI to set the 'global-state' first
		add_filter(
			'GD_TemplateManager_Utils:get_vars',
			array($this, 'add_vars')
		);
		add_filter(
			'GD_Template_CacheProcessor:add_vars',
			array($this, 'add_vars_identifier')
		);
	}

	public function add_vars($vars) {

		// Allow WP API to set the "global-state" first
		// Each page is an independent configuration
		if ($vars['global-state']['is-page']) {

			global $gd_dataquery_manager;
			$post = $vars['global-state']['post'];

			// Special pages: dataqueries' cacheablepages serve layouts, noncacheable pages serve fields.
			// So the settings for these pages depend on the URL params
			if (in_array($post->ID, $gd_dataquery_manager->get_noncacheablepages())) {

				if ($fields = $_REQUEST[GD_URLPARAM_FIELDS]) {

					$fields = is_array($fields) ? $fields : array($fields);
					$vars['fields'] = $fields;
				}
			}
			elseif (in_array($post->ID, $gd_dataquery_manager->get_cacheablepages())) {

				if ($layouts = $_REQUEST[GD_URLPARAM_LAYOUTS]) {
					
					$layouts = is_array($layouts) ? $layouts : array($layouts);
					$vars['layouts'] = $layouts;
				}
			}
		}

		return $vars;
	}

	public function add_vars_identifier($identifier) {

		// Allow WP API to set the "global-state" first
		// Each page is an independent configuration
		$vars = GD_TemplateManager_Utils::get_vars();
		if ($vars['global-state']['is-page']) {

			global $gd_dataquery_manager;
			$post = $vars['global-state']['post'];

			// Special pages: dataqueries' cacheablepages serve layouts, noncacheable pages serve fields.
			// So the settings for these pages depend on the URL params
			if (in_array($post->ID, $gd_dataquery_manager->get_noncacheablepages())) {

				// if ($fields = $_REQUEST['fields']) {
				if ($fields = $vars['fields']) {
					// if (!is_array($fields)) {
					// 	$fields = array($fields);
					// }
					// Do not add the values straight since they are too long, do a hash instead to shorten it
					// $filename .= '-fields_'.sha1(implode('', $fields));
					$identifier .= '-'.implode('', $fields);
				}
			}
			elseif (in_array($post->ID, $gd_dataquery_manager->get_cacheablepages())) {

				// if ($layouts = $_REQUEST['layouts']) {
				if ($layouts = $vars['layouts']) {
					// if (!is_array($layouts)) {
					// 	$layouts = array($layouts);
					// }
					// Do not add the values straight since they are too long, do a hash instead to shorten it
					// $filename .= '-layouts_'.sha1(implode('', $layouts));
					$identifier .= '-'.implode('', $layouts);
				}
			}
		}

		return $identifier;
	}
}

/**---------------------------------------------------------------------------------------------------------------
 * Initialization
 * ---------------------------------------------------------------------------------------------------------------*/
new PoP_Engine_Hooks();


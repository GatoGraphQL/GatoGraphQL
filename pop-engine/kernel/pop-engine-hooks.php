<?php

class PoP_Engine_Hooks {

	function __construct() {

		// Add functions as hooks, so we allow PoP_Application to set the 'global-state' first
		add_action(
			'PoP_ModuleManager_Utils:add_vars',
			array($this, 'add_vars'),
			10,
			1
		);
		add_filter(
			'PoP_Module_ModelInstanceProcessor:model_instance_components_from_vars',
			array($this, 'get_model_instance_components_from_vars')
		);
	}

	public function add_vars($vars_in_array) {

		// Allow WP API to set the "global-state" first
		// Each page is an independent configuration
		$vars = &$vars_in_array[0];
		if ($vars['global-state']['is-page']) {

			global $gd_dataquery_manager;
			$post = $vars['global-state']['queried-object'];

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
	}

	public function get_model_instance_components_from_vars($components) {

		// Allow WP API to set the "global-state" first
		// Each page is an independent configuration
		$vars = PoP_ModuleManager_Vars::get_vars();
		if ($vars['global-state']['is-page']) {

			global $gd_dataquery_manager;
			$post = $vars['global-state']['queried-object'];

			// Special pages: dataqueries' cacheablepages serve layouts, noncacheable pages serve fields.
			// So the settings for these pages depend on the URL params
			if (in_array($post->ID, $gd_dataquery_manager->get_noncacheablepages())) {

				if ($fields = $vars['fields']) {

					$components[] = __('fields:', 'pop-engine').implode('.', $fields);
				}
			}
			elseif (in_array($post->ID, $gd_dataquery_manager->get_cacheablepages())) {

				if ($layouts = $vars['layouts']) {

					$components[] = __('layouts:', 'pop-engine').implode('.', $layouts);
				}
			}
		}

		return $components;
	}
}

/**---------------------------------------------------------------------------------------------------------------
 * Initialization
 * ---------------------------------------------------------------------------------------------------------------*/
new PoP_Engine_Hooks();


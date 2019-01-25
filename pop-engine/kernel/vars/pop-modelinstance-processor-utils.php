<?php
namespace PoP\Engine;

class ModelInstanceProcessor_Utils {

	public static function get_model_instance_id() {

		// The string is too long. Use a hashing function to shorten it
		return md5(implode('-', self::get_model_instance_components()));
	}

	public static function get_model_instance_components() {

		$components = array();

		// Comment Leo 05/04/2017: do also add the version, because otherwise there are PHP errors
		// happening from stale configuration that is not deleted, and still served, after a new version is deployed
		// By adding the version, that will not happen anymore
		$components[] = __('version:', 'pop-engine').pop_version();

		// Mix the information specific to the module, with that present in $vars
		return apply_filters(
			'ModelInstanceProcessor:model_instance_components',
			array_merge(
				$components,
				self::get_model_instance_components_from_vars()
			)
		);
	}

	protected static function get_model_instance_components_from_vars() {

		$components = array();
		
		$vars = Engine_Vars::get_vars();
		$cmsapi = \PoP\CMS\FunctionAPI_Factory::get_instance();

		// There will always be a hierarchy. Add it.
		$hierarchy = $vars['hierarchy'];
		$components[] = __('hierarchy:', 'pop-engine').$hierarchy;

		// Properties specific to each hierarchy
		switch ($hierarchy) {

			case GD_SETTINGS_HIERARCHY_PAGE:

				$page_id = $vars['global-state']['queried-object-id'];

				// Each page may be an independent configuration or not, so allow to configure it through hooks. By default it is true, so it's a conservative approach
				$component_types = apply_filters(
					'\PoP\Engine\ModelInstanceProcessor_Utils:components_from_vars:type:page',
					array(
						POP_MODELINSTANCECOMPONENTTYPE_PAGE_ID,
					)
				);
				if (in_array(POP_MODELINSTANCECOMPONENTTYPE_PAGE_ID, $component_types)) {

					// We add the page path to help understand what file it is, in addition to the ID (to make sure to make the configuration unique to that page)
					$components[] = __('page id:', 'pop-engine').Utils::get_page_path($page_id).$page_id;
				}
				// If the page id is added, then there's no need to check the maincontentmodule, since it adds no information
				elseif (in_array(POP_MODELINSTANCECOMPONENTTYPE_PAGE_MAINCONTENTMODULE, $component_types)) {

					// If different pages share the same main content module, then they are sharing the same configuration.
					// Eg: a website with all documentation pages may need to generate the configuration only once, for the first page, and then from then on just load data
			        $pop_module_pagemoduleprocessor_manager = PageModuleProcessorManager_Factory::get_instance();
					$components[] = __('main content module:', 'pop-engine').$pop_module_pagemoduleprocessor_manager->get_page_module_by_most_allmatching_vars_properties(POP_PAGEMODULEGROUPPLACEHOLDER_MAINCONTENTMODULE, $page_id);
				}
				break;
		}

		// Other properties
		if ($format = $vars['format']) {
			
			$components[] = __('format:', 'pop-engine').$format;
		}
		if ($target = $vars['target']) {
			
			$components[] = __('target:', 'pop-engine').$target;
		}
		if ($tab = $vars['tab']) {
			
			$components[] = __('tab:', 'pop-engine').$tab;
		}
		if ($action = $vars['action']) {
			
			$components[] = __('action:', 'pop-engine').$action;
		}
		if ($config = $vars['config']) {
			
			$components[] = __('config:', 'pop-engine').$config;
		}
		if ($modulefilter = $vars['modulefilter']) {
			
			$components[] = __('module filter:', 'pop-engine').$modulefilter;

			if ($modulefilter == POP_MODULEFILTER_MODULEPATHS && ($modulepaths = $vars['modulepaths'])) {

				$paths = array();
				foreach ($modulepaths as $modulepath) {
					$paths[] = ModulePathManager_Utils::stringify_module_path($modulepath);
				}
				
				$components[] = __('module paths:', 'pop-engine').implode(',', $paths);
			}
			elseif ($modulefilter == POP_MODULEFILTER_HEADMODULE && ($headmodule = $vars['headmodule'])) {

				$components[] = __('head module:', 'pop-engine').$headmodule;
			}
		}

		// Can the configuration change when doing a POST or GET?
		if (apply_filters('\PoP\Engine\ModelInstanceProcessor_Utils:components_from_vars:post-or-get-change', false)) {
		
			$components[] = __('operation:', 'pop-engine').(doing_post() ? 'post' : 'get');
		}
		if ($mangled = $vars['mangled']) {
			
			// By default it is mangled. To make it non-mangled, url must have param "mangled=none",
			// so only in these exceptional cases the identifier will add this parameter
			$components[] = __('mangled:', 'pop-engine').$mangled;
		}

		// Allow for plug-ins to add their own vars. Eg: URE source parameter
		$components = apply_filters('ModelInstanceProcessor:model_instance_components_from_vars', $components);

		return $components;
	}

	protected function get_single_categories($post_id) {

		$cats = array();
		$cmsapi = \PoP\CMS\FunctionAPI_Factory::get_instance();
		if ($cmsapi->get_post_type($post_id) == 'post') {

			foreach ($cmsapi->get_the_category($post_id) as $cat) {
				$cats[] = $cat->slug.$cat->term_id;
			}
		}

		// Allow for plug-ins to add their own categories. Eg: Events
		return apply_filters('ModelInstanceProcessor:get_categories', $cats, $post_id);
	}
}
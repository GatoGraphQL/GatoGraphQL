<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Manager (Handlebars)
 *
 * ---------------------------------------------------------------------------------------------------------------*/

class PoP_CSSResourceLoaderProcessor_Manager {

	protected /*$first_style, */$inline_resources;

	function __construct() {

		$this->inline_resources = array();

		add_action('wp_head', array($this, 'print_styles'));
	}

	function print_style($resource) {

		global $pop_resourceloaderprocessor_manager;
		$file = $pop_resourceloaderprocessor_manager->get_file_path($resource);
        $file_contents = file_get_contents($file);
		// $resource_id = PoP_ResourceLoaderProcessorUtils::get_noconflict_resource_name($resource);
		$resource_id = $pop_resourceloaderprocessor_manager->get_handle($resource);

		return sprintf(
			'<style id="%s" type="text/css">%s</style>',
			$resource_id,
			$file_contents
		);
	}

	function print_styles() {

		if ($this->inline_resources) {

			echo implode(PHP_EOL, array_map(array($this, 'print_style'), $this->inline_resources));
		}
	}

	function print_inline_resources($resources) {

		$this->inline_resources = $resources;
	}

	protected function get_filegenerator($enqueuefile_type, $across_thememodes) {

		if ($enqueuefile_type == 'bundlegroup') {

			if ($across_thememodes) {

				global $pop_resourceloader_acrossthememodes_cssbundlegroupfilegenerator;
				return $pop_resourceloader_acrossthememodes_cssbundlegroupfilegenerator;
			}

			global $pop_resourceloader_singlethememode_cssbundlegroupfilegenerator;
			return $pop_resourceloader_singlethememode_cssbundlegroupfilegenerator;
		}
		elseif ($enqueuefile_type == 'bundle') {

			if ($across_thememodes) {

				global $pop_resourceloader_acrossthememodes_cssbundlefilegenerator;
				return $pop_resourceloader_acrossthememodes_cssbundlefilegenerator;
			}

			global $pop_resourceloader_singlethememode_cssbundlefilegenerator;
			return $pop_resourceloader_singlethememode_cssbundlefilegenerator;
		}

		return null;
	}

	function enqueue_styles($across_thememodes, $resources, $bundle_ids, $bundlegroup_ids) {

		global $pop_resourceloaderprocessor_manager;

		// We can only enqueue the resources that do NOT go in the body or are inlined. 
		// Those ones will be added when doing $popResourceLoader->includeResources (in the body), or hardcoded (inline, such as utils-inline.js)
		$resources = $pop_resourceloaderprocessor_manager->get_enqueuable_resources($resources);

		// Enqueue the resources/bundles/bundlegroups
		// In order to calculate the bundle(group) ids, we need to substract first those resources which do not can_bundle, since they will not be inside the bundle files
		$enqueuefile_type = PoP_Frontend_ServerUtils::get_enqueuefile_type();
		$loading_bundle = $enqueuefile_type == 'bundlegroup' || $enqueuefile_type == 'bundle';
		$bundlestyles_properties = array();
		if ($loading_bundle) {

			$version = pop_version();
			$filegenerator = $this->get_filegenerator($enqueuefile_type, $across_thememodes);
			
			// Enqueue the bundleGroups
			if ($enqueuefile_type == 'bundlegroup') {

				foreach ($bundlegroup_ids as $bundleGroupId) {

					$filegenerator->set_filename($bundleGroupId.'.css');

					// Add 'pop-' before the registered name, to avoid conflicts with external parties (eg: WP also registers style "utils")
					$style = 'pop-bundlegroup-'.$bundleGroupId;
					$bundlestyles_properties[] = array(
						'style' => $style,
						'file-url' => $filegenerator->get_fileurl(),
						'version' => PoP_ResourceLoaderProcessorUtils::get_bundlegroup_version($bundleGroupId),
					);
				}
			}	
			// Enqueue the bundles
			elseif ($enqueuefile_type == 'bundle') {

				foreach ($bundle_ids as $bundleId) {

					$filegenerator->set_filename($bundleId.'.css');

					// Add 'pop-' before the registered name, to avoid conflicts with external parties (eg: WP also registers style "utils")
					$style = 'pop-bundle-'.$bundleId;
					$bundlestyles_properties[] = array(
						'style' => $style,
						'file-url' => $filegenerator->get_fileurl(),
						'version' => PoP_ResourceLoaderProcessorUtils::get_bundle_version($bundleId),
					);
				}
			}

			// For bundles and bundlegroups, those requests that can be bundled will be inside the bundle, so remove from the resources
			$canbundle_resources = $pop_resourceloaderprocessor_manager->filter_can_bundle($resources);
			$resources = array_values(array_diff(
				$resources,
				$canbundle_resources
			));
		}

		// // When enqueuing "vendor", "normal", "dynamic" and "template", this function will be called several times
		// // So just save the first script the first time
		// if (!$this->first_style) {	

		// 	// Save the name for the first enqueued resource/bundle/bundleGroup, to localize it
		// 	if ($resources) {

		// 		$this->first_style = PoP_ResourceLoaderProcessorUtils::get_noconflict_resource_name($resources[0]);
		// 	}
		// 	elseif ($bundlestyles_properties) {

		// 		$this->first_style = $bundlestyles_properties[0]['style'];
		// 	}
		// }
		
		// Enqueue either all the resources, or those who can not be bundled
		// Do it first, because these resources are most likely to be depended-by the styles in the bundle
		// (including all external resources, when doing POP_SERVER_ACCESSEXTERNALCDNRESOURCES = true)
		foreach ($resources as $resource) {

			// Enqueue the resource
			$processor = $this->get_processor($resource);

			// Make sure the file_url is not null (eg: POP_RESOURCELOADER_CDNCONFIG_EXTERNAL, without parameter "url" will return null)
			if ($file_url = $processor->get_file_url($resource)) {
			
				// Comment Leo 13/11/2017: if a dependency in inside the bundle, then the corresponding handle will never be registered and this resource will not be added to the page
				// Then, check for the dependencies only when loading resources, not bundle(group)s
				$dependencies = array();
				if (!$loading_bundle) {

					// We must filter the dependencies to only CSS files, for if a script has a dependency to a style or viceversa (WP won't load the resource then)
					$dependencies = $processor->get_dependencies($resource);
					$dependencies = $pop_resourceloaderprocessor_manager->filter_css($dependencies);

					// Filter out the dependencies which are inline or in-body
					$dependencies = $pop_resourceloaderprocessor_manager->get_enqueuable_resources($dependencies);

					// Add 'pop-' before the registered name, to avoid conflicts with external parties (eg: WP also registers style "utils")				
					// $dependencies = array_map(array('PoP_ResourceLoaderProcessorUtils', 'get_noconflict_resource_name'), $dependencies);
					$dependencies = array_map(array('PoP_ResourceLoaderProcessorUtils', 'get_handle'), $dependencies);
				}

				// Add 'pop-' before the registered name, to avoid conflicts with external parties (eg: WP also registers style "utils")
				// $style = PoP_ResourceLoaderProcessorUtils::get_noconflict_resource_name($resource);
				$style = $processor->get_handle($resource);
				wp_register_style($style, $file_url, $dependencies, $processor->get_version($resource));
				wp_enqueue_style($style);
			}
		}

		// Enqueue all the added styles, if all of the needed bundle(group)s exist
		foreach ($bundlestyles_properties as $style_properties) {

			$style = $style_properties['style'];
			$file_url = $style_properties['file-url'];
			$bundlefile_version = $style_properties['version'] ?? $version;

			wp_register_style($style, $file_url, array(), $bundlefile_version);
			wp_enqueue_style($style);
		}
	}

	// --------------------------------------------------
	// FUNCTIONS FROM $pop_resourceloaderprocessor_manager;
	// --------------------------------------------------

	function get_processor($resource) {

		global $pop_resourceloaderprocessor_manager;
		return $pop_resourceloaderprocessor_manager->get_processor($resource);
	}
}

/**---------------------------------------------------------------------------------------------------------------
 * Initialization
 * ---------------------------------------------------------------------------------------------------------------*/
global $pop_cssresourceloaderprocessor_manager;
$pop_cssresourceloaderprocessor_manager = new PoP_CSSResourceLoaderProcessor_Manager();

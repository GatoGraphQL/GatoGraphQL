<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Manager (Handlebars)
 *
 * ---------------------------------------------------------------------------------------------------------------*/

class PoP_CSSResourceLoaderProcessor_Manager {

	protected $first_style;

	function enqueue_resources($resources, $bundle_ids, $bundlegroup_ids/*, $remove_bundled_resources = true*/) {

		global $pop_resourceloaderprocessor_manager;

		// Enqueue the resources/bundles/bundlegroups
		// In order to calculate the bundle(group) ids, we need to substract first those resources which do not can_bundle, since they will not be inside the bundle files
		$enqueuefile_type = PoP_Frontend_ServerUtils::get_enqueuefile_type();
		$loading_bundle = $enqueuefile_type == 'bundlegroup' || $enqueuefile_type == 'bundle';
		$bundlestyles_properties = array();
		if ($loading_bundle) {

			$version = pop_version();

			$fallback = false;
			
			// Enqueue the bundleGroups
			if ($enqueuefile_type == 'bundlegroup') {

				// Enqueue either all the resources, or those who can not be bundled
				global $pop_resourceloader_cssbundlegroupfilegenerator;
				foreach ($bundlegroup_ids as $bundleGroupId) {

					$pop_resourceloader_cssbundlegroupfilegenerator->set_filename($bundleGroupId);
					$pop_resourceloader_cssbundlegroupfilegenerator->set_extension('.css');

					if ($pop_resourceloader_cssbundlegroupfilegenerator->file_exists()) {

						// Add 'pop-' before the registered name, to avoid conflicts with external parties (eg: WP also registers style "utils")
						$style = 'pop-bundlegroup-'.$bundleGroupId;
						$bundlestyles_properties[] = array(
							'style' => $style,
							'file-url' => $pop_resourceloader_cssbundlegroupfilegenerator->get_fileurl(),
						);
					}
					else {

						// If any normal bundle(group) does not exist, fallback on loading resources
						$fallback = true;

						// We can skip iterating
						break;
					}
				}
			}	
			// Enqueue the bundles
			elseif ($enqueuefile_type == 'bundle') {

				// Enqueue either all the resources, or those who can not be bundled
				global $pop_resourceloader_cssbundlefilegenerator;
				foreach ($bundle_ids as $bundleId) {

					$pop_resourceloader_cssbundlefilegenerator->set_filename($bundleId);
					$pop_resourceloader_cssbundlefilegenerator->set_extension('.css');

					// Check if the file exists
					if ($pop_resourceloader_cssbundlefilegenerator->file_exists()) {

						// Add 'pop-' before the registered name, to avoid conflicts with external parties (eg: WP also registers style "utils")
						$style = 'pop-bundle-'.$bundleId;
						$bundlestyles_properties[] = array(
							'style' => $style,
							'file-url' => $pop_resourceloader_cssbundlefilegenerator->get_fileurl(),
						);
					}
					else {

						// If any normal bundle(group) does not exist, fallback on loading resources
						$fallback = true;

						// We can skip iterating
						break;
					}
				}
			}

			// Enqueue all the added styles, if all of the needed bundle(group)s exist
			if (!$fallback) {

				// For bundles and bundlegroups, those requests that can be bundled will be inside the bundle, so remove from the resources
				$canbundle_resources = $pop_resourceloaderprocessor_manager->filter_can_bundle($resources);
				$resources = array_values(array_diff(
					$resources,
					$canbundle_resources
				));
			}
		}	

		// Save the name for the first enqueued resource/bundle/bundleGroup, to localize it
		if ($resources) {

			$this->first_style = PoP_ResourceLoaderProcessorUtils::get_noconflict_resource_name($resources[0]);
		}
		elseif ($bundlestyles_properties) {

			$this->first_style = $bundlestyles_properties[0]['style'];
		}
		
		// Enqueue either all the resources, or those who can not be bundled
		// Do it first, because these resources are most likely to be depended-by the styles in the bundle
		// (including all external resources, when doing POP_SERVER_ACCESSEXTERNALCDNRESOURCES = true)
		foreach ($resources as $resource) {

			// Enqueue the resource
			$processor = $this->get_processor($resource);
			
			// Comment Leo 13/11/2017: if a dependency in inside the bundle, then the corresponding handle will never be registered and this resource will not be added to the page
			// Then, check for the dependencies only when loading resources, not bundle(group)s
			$dependencies = array();
			if (!$loading_bundle) {

				// We must filter the dependencies to only CSS files, for if a script has a dependency to a style or viceversa (WP won't load the resource then)
				$dependencies = $processor->get_dependencies($resource);
				$dependencies = $pop_resourceloaderprocessor_manager->filter_css($dependencies);
				$dependencies = array_map(array('PoP_ResourceLoaderProcessorUtils', 'get_noconflict_resource_name'), $dependencies);
			}

			// Add 'pop-' before the registered name, to avoid conflicts with external parties (eg: WP also registers style "utils")
			$style = PoP_ResourceLoaderProcessorUtils::get_noconflict_resource_name($resource);
			wp_register_style($style, $processor->get_file_url($resource), $dependencies, $processor->get_version($resource));
			wp_enqueue_style($style);
		}

		// Enqueue all the added styles, if all of the needed bundle(group)s exist
		foreach ($bundlestyles_properties as $style_properties) {

			$style = $style_properties['style'];
			$file_url = $style_properties['file-url'];

			wp_register_style($style, $file_url, array(), $version);
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

<?php
use PoP\ComponentModel\Facades\Info\ApplicationInfoFacade;
use PoP\ComponentModel\State\ApplicationState;
use PoP\Hooks\Facades\HooksAPIFacade;

class PoP_CSSResourceLoaderProcessorManager {

	protected /*$first_style, */$inline_resources;

	public function __construct() {

		$this->inline_resources = array();

		HooksAPIFacade::getInstance()->addAction('popcms:head', array($this, 'printStyles'));
	}

	function printStyle(array $resource) {

		global $pop_resourceloaderprocessor_manager;
		$file = $pop_resourceloaderprocessor_manager->getFilePath($resource);
        $file_contents = file_get_contents($file);
		// $resource_id = PoP_ResourceLoaderProcessorUtils::getNoconflictResourceName($resource);
		$resource_id = $pop_resourceloaderprocessor_manager->getHandle($resource);

		return sprintf(
			'<style id="%s" type="text/css">%s</style>',
			$resource_id,
			$file_contents
		);
	}

	function printStyles() {

		if ($this->inline_resources) {

			echo implode(PHP_EOL, array_map(array($this, 'printStyle'), $this->inline_resources));
		}
	}

	function printInlineResources($resources) {

		$this->inline_resources = $resources;
	}

	protected function getFile($enqueuefile_type, $acrossThememodes) {

		if ($enqueuefile_type == 'bundlegroup') {

			if ($acrossThememodes) {

				global $pop_resourceloader_acrossthememodes_cssbundlegroupfile;
				return $pop_resourceloader_acrossthememodes_cssbundlegroupfile;
			}

			global $pop_resourceloader_singlethememode_cssbundlegroupfile;
			return $pop_resourceloader_singlethememode_cssbundlegroupfile;
		}
		elseif ($enqueuefile_type == 'bundle') {

			if ($acrossThememodes) {

				global $pop_resourceloader_acrossthememodes_cssbundlefile;
				return $pop_resourceloader_acrossthememodes_cssbundlefile;
			}

			global $pop_resourceloader_singlethememode_cssbundlefile;
			return $pop_resourceloader_singlethememode_cssbundlefile;
		}

		return null;
	}

	function enqueueStyles($acrossThememodes, $resources, $bundle_ids, $bundlegroup_ids) {

		global $pop_resourceloaderprocessor_manager;
		$htmlcssplatformapi = \PoP\EngineHTMLCSSPlatform\FunctionAPIFactory::getInstance();

		// We can only enqueue the resources that do NOT go in the body or are inlined. 
		// Those ones will be added when doing $popResourceLoader->includeResources (in the body), or hardcoded (inline, such as utils-inline.js)
		$resources = $pop_resourceloaderprocessor_manager->getEnqueuableResources($resources);

		// Enqueue the resources/bundles/bundlegroups
		// In order to calculate the bundle(group) ids, we need to substract first those resources which do not canBundle, since they will not be inside the bundle files
		$enqueuefile_type = PoP_ResourceLoader_ServerUtils::getEnqueuefileType();
		$loading_bundle = $enqueuefile_type == 'bundlegroup' || $enqueuefile_type == 'bundle';
		$bundlestyles_properties = array();
		if ($loading_bundle) {
			$version = ApplicationInfoFacade::getInstance()->getVersion();
			$file = $this->getFile($enqueuefile_type, $acrossThememodes);
			
			// Enqueue the bundleGroups
			if ($enqueuefile_type == 'bundlegroup') {

				foreach ($bundlegroup_ids as $bundleGroupId) {

					$file->setFilename($bundleGroupId.'.css');

					// Add 'pop-' before the registered name, to avoid conflicts with external parties (eg: WP also registers style "utils")
					$style = 'pop-bundlegroup-'.$bundleGroupId;
					$bundlestyles_properties[] = array(
						'style' => $style,
						'file-url' => $file->getFileurl(),
						'version' => PoP_ResourceLoaderProcessorUtils::getBundlegroupVersion($bundleGroupId),
					);
				}
			}	
			// Enqueue the bundles
			elseif ($enqueuefile_type == 'bundle') {

				foreach ($bundle_ids as $bundleId) {

					$file->setFilename($bundleId.'.css');

					// Add 'pop-' before the registered name, to avoid conflicts with external parties (eg: WP also registers style "utils")
					$style = 'pop-bundle-'.$bundleId;
					$bundlestyles_properties[] = array(
						'style' => $style,
						'file-url' => $file->getFileurl(),
						'version' => PoP_ResourceLoaderProcessorUtils::getBundleVersion($bundleId),
					);
				}
			}

			// For bundles and bundlegroups, those requests that can be bundled will be inside the bundle, so remove from the resources
			$canbundle_resources = $pop_resourceloaderprocessor_manager->filterCanBundle($resources);
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

		// 		$this->first_style = PoP_ResourceLoaderProcessorUtils::getNoconflictResourceName($resources[0]);
		// 	}
		// 	elseif ($bundlestyles_properties) {

		// 		$this->first_style = $bundlestyles_properties[0]['style'];
		// 	}
		// }
		
		// Enqueue either all the resources, or those who can not be bundled
		// Do it first, because these resources are most likely to be depended-by the styles in the bundle
		// (including all external resources, when doing ACCESS_EXTERNAL_CDN_RESOURCES = true)
		foreach ($resources as $resource) {

			// Enqueue the resource
			$processor = $this->getProcessor($resource);

			// Make sure the file_url is not null (eg: POP_RESOURCELOADER_CDNCONFIG_EXTERNAL, without parameter "url" will return null)
			if ($file_url = $processor->getFileUrl($resource)) {
			
				// Comment Leo 13/11/2017: if a dependency in inside the bundle, then the corresponding handle will never be registered and this resource will not be added to the page
				// Then, check for the dependencies only when loading resources, not bundle(group)s
				$dependencies = array();
				if (!$loading_bundle) {

					// We must filter the dependencies to only CSS files, for if a script has a dependency to a style or viceversa (WP won't load the resource then)
					$dependencies = $processor->getDependencies($resource);
					$dependencies = $pop_resourceloaderprocessor_manager->filterCss($dependencies);

					// Filter out the dependencies which are inline or in-body
					$dependencies = $pop_resourceloaderprocessor_manager->getEnqueuableResources($dependencies);

					// Add 'pop-' before the registered name, to avoid conflicts with external parties (eg: WP also registers style "utils")				
					$dependencies = array_map(array(PoP_ResourceLoaderProcessorUtils::class, 'getHandle'), $dependencies);
				}

				// Add 'pop-' before the registered name, to avoid conflicts with external parties (eg: WP also registers style "utils")
				// $style = PoP_ResourceLoaderProcessorUtils::getNoconflictResourceName($resource);
				$style = $processor->getHandle($resource);
				$htmlcssplatformapi->registerStyle($style, $file_url, $dependencies, $processor->getVersion($resource));
				$htmlcssplatformapi->enqueueStyle($style);
			}
		}

		// Enqueue all the added styles, if all of the needed bundle(group)s exist
		foreach ($bundlestyles_properties as $style_properties) {

			$style = $style_properties['style'];
			$file_url = $style_properties['file-url'];
			$bundlefile_version = $style_properties['version'] ?? $version;

			$htmlcssplatformapi->registerStyle($style, $file_url, array(), $bundlefile_version);
			$htmlcssplatformapi->enqueueStyle($style);
		}
	}

	// --------------------------------------------------
	// FUNCTIONS FROM $pop_resourceloaderprocessor_manager;
	// --------------------------------------------------

	function getProcessor(array $resource) {

		global $pop_resourceloaderprocessor_manager;
		return $pop_resourceloaderprocessor_manager->getProcessor($resource);
	}
}

/**
 * Initialization
 */
global $pop_cssresourceloaderprocessor_manager;
$pop_cssresourceloaderprocessor_manager = new PoP_CSSResourceLoaderProcessorManager();

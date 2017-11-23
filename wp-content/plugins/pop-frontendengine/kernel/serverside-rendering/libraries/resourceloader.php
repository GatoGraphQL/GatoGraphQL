<?php
class PoP_ServerSide_ResourceLoader {

	// The values here will be populated from resourceloader-config.js,
	// on a domain by domain basis
	public $config;

	// Keep a list of all loading resources
	// private $loading;
	// private $errorLoading;
	// private $loadingURLs;

	// Keep a list of all loaded resources. All resources are called always the same among different domains,
	// so one list here listing all of them works
	public $loaded;
	// Loaded bundles and bundleGroups depend on their domains, since their names change among domains
	// private $loadedByDomain;

	function __construct() {

		PoP_ServerSide_Libraries_Factory::set_resourceloader_instance($this);
		
		// Initialize internal variables
		$this->config = array();
		// $this->loading = array(
		// 	'resources' => array(),
		// );
		// $this->errorLoading = array(
		// 	'resources' => array(),
		// );
		// $this->loadingURLs = array();
		$this->loaded = array();
		// $this->loadedByDomain = array();
	}

	//-------------------------------------------------
	// PUBLIC but NOT EXPOSED functions
	//-------------------------------------------------

	protected function includeResource($resource) {

		$config = $this->getConfigByDomain($this->domain);
		$resource_id = PoP_ResourceLoaderProcessorUtils::get_noconflict_resource_name($resource);
		$include_type = PoP_Frontend_ServerUtils::get_templateresources_include_type();

		// Include the script/style link
		if ($include_type == 'body') {

			$source = $config['sources'][$resource];
			// $type = $config['types'][$resource];
			
			// if ($type == POP_RESOURCELOADER_RESOURCETYPE_CSS) {
			if (in_array($resource, $config['types'][POP_RESOURCELOADER_RESOURCETYPE_CSS])) {

				return sprintf(
					'<link id="%s" rel="stylesheet" href="%s">',
					$resource_id,
					$source
				);
			}
			// else if ($type == POP_RESOURCELOADER_RESOURCETYPE_JS) {
			elseif (in_array($resource, $config['types'][POP_RESOURCELOADER_RESOURCETYPE_JS])) {

				return sprintf(
					'<script id="%s" type="text/javascript" src="%s"></script>',
					$resource_id,
					$source
				);
			}
		}
		// Include the content of the file
		elseif ($include_type == 'body-inline') {

			global $pop_resourceloaderprocessor_manager;
			$file = $pop_resourceloaderprocessor_manager->get_file_path($resource);
            $file_contents = file_get_contents($file);

			if (in_array($resource, $config['types'][POP_RESOURCELOADER_RESOURCETYPE_CSS])) {

				return sprintf(
					'<style id="%s" type="text/css">%s</style>',
					$resource_id,
					$file_contents
				);
			}
			// else if ($type == POP_RESOURCELOADER_RESOURCETYPE_JS) {
			elseif (in_array($resource, $config['types'][POP_RESOURCELOADER_RESOURCETYPE_JS])) {

				return sprintf(
					'<script id="%s" type="text/javascript">%s</script>',
					$resource_id,
					$file_contents
				);
			}
		}		

		return '';
	}

	function includeResources($domain, $resources, $ignoreAlreadyIncluded) {

		if (!$resources) {

			return '';
		}

		// Remove the resources that have been included already
		if ($ignoreAlreadyIncluded) {
			
			$resources = array_diff(
				$resources,
				$this->loaded
			);
		}

		// Mark the resources as already included
		$this->loaded = array_merge(
			$this->loaded,
			$resources
		);

		// Map the resources to their tags. First set the domain so it can be accessed in that function
		$this->domain = $domain;
		$tags = array_map(array($this, 'includeResource'), $resources);

		return implode('', $tags);
	}

	function getConfigByDomain($domain) {

		return $this->config[$domain];

		// // Check we have a config for this domain
		// $config = $this->config[$domain];
		// if (!$config && $domain != get_site_url()) {

		// 	// If we don't have a config, and the domain is not local, then try the local domain
		// 	// (This is needed for if the external resourceloader-config.js file has not been loaded yet. 
		// 	// This may happen often, as loading this file is asynchronous, so needing to check the URL path
		// 	// will happen before the script is loaded)
		// 	$config = $this->config[get_site_url()];
		// }
		
		// return $config ?? array();
	}

	// function getConfig($url) {

	// 	$domain = get_domain($url);
	// 	return $this->getConfigByDomain($domain);
	// }
}

/**---------------------------------------------------------------------------------------------------------------
 * Initialization
 * ---------------------------------------------------------------------------------------------------------------*/
new PoP_ServerSide_ResourceLoader();

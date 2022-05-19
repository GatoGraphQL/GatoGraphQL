<?php
use PoP\ComponentModel\App;
use PoP\ComponentModel\ItemProcessors\ItemProcessorManagerTrait;
use PoP\ComponentModel\ItemProcessors\ProcessorItemUtils;
use PoP\ComponentModel\Misc\GeneralUtils;

class PoP_ResourceLoaderProcessorManager implements ResourceLoaderProcessorManagerInterface {

	use ItemProcessorManagerTrait {
		getItemProcessor as upstreamGetItemProcessor;
	}

    /**
     * @var array<string, object>
     */
    private array $itemFullNameProcessorInstances = [];

    public function getLoadedItems(): array
    {
        // Return a list of all loaded items
        return array_map(
            [ProcessorItemUtils::class, 'getItemFromFullName'],
            array_keys($this->itemFullNameProcessorInstances)
        );
    }

    public function getLoadedItemFullNameProcessorInstances(): array
    {
        return $this->itemFullNameProcessorInstances;
    }

    public function getItemProcessor(array $item): mixed
    {
		$hasItemBeenLoaded = $this->hasItemBeenLoaded($item);
        
		$processorInstance = $this->upstreamGetItemProcessor($item);

        // Return the reference to the ItemProcessor instance, and created first if it doesn't exist
        if (!$hasItemBeenLoaded) {
            // Keep a copy of what instance was generated for which item;
            $itemFullName = ProcessorItemUtils::getItemFullName($item);
            $this->itemFullNameProcessorInstances[$itemFullName] = $processorInstance;
        }

        return $processorInstance;
    }

    public function getProcessor(array $item): PoP_ResourceLoaderProcessor
    {
        return $this->getItemProcessor($item);
    }
    
    public function getLoadedResourceFullNameProcessorInstances() 
    {
        return $this->getLoadedItemFullNameProcessorInstances();
    }
    
    public function getLoadedResources() 
    {
        // Return a list of all loaded resources
        return $this->getLoadedItems();
    }
	
	function decoratesResource(array $resource) {

		$decorated_resources = $this->getProcessor($resource)->getDecoratedResources($resource);
		if (in_array($this->maybe_decorated_resource, $decorated_resources)) {

			return $resource;
		}

		return null;
	}
	
	function getDecorators(array $resource) {

		// Return the list of all resources which are decorating the given $resource
		$resources = $this->getLoadedResources();
		$this->maybe_decorated_resource = $resource;
		$decorators = array_values(array_filter(array_map($this->decoratesResource(...), $resources)));
		return $decorators;
	}

	function getFileUrl(array $resource, $add_version = false) {

		$url = $this->getProcessor($resource)->getFileUrl($resource);
		if ($add_version) {
			
			// External files do not have a $version defined (since it's already harcoded in the file path)
			// Whenever there is no version defined, WordPress will add the WP version in `function do_item`, 
			// in file wp-includes/class.wp-scripts.php
			// called from adding our scripts through `wp_enqueue_script`
			// So then we must get that version here, so that it will always match
			$version = $this->getProcessor($resource)->getVersion($resource);
			if (!$version) {
		        $cmsengineapi = \PoP\Engine\FunctionAPIFactory::getInstance();
				$version = $cmsengineapi->getVersion();
			}
	        $url = GeneralUtils::addQueryArgs([
				'ver' => $version, 
			], $url);
		}
		return $url;
	}

	function getType(array $resource) {

		return $this->getProcessor($resource)->getType($resource);
	}

	function getSubtype(array $resource) {

		return $this->getProcessor($resource)->getSubtype($resource);
	}

	function getFilePath(array $resource) {

		return $this->getProcessor($resource)->getFilePath($resource);
	}

	function getHandle(array $resource) {

		return $this->getProcessor($resource)->getHandle($resource);
	}

	function getAssetPath(array $resource) {

		return $this->getProcessor($resource)->getAssetPath($resource);
	}

	function getEnqueuableResources($resources) {

		// We can only enqueue the resources that do NOT go in the body or are inlined. 
		// Those ones will be added when doing $popResourceLoader->includeResources (in the body), or hardcoded (inline, such as utils-inline.js)
		if ($in_body_resources = $this->filterInBody($resources)) {

			$resources = array_values(array_diff(
				$resources,
				$in_body_resources
			));
		}
		if ($inline_resources = $this->filterInline($resources)) {

			$resources = array_values(array_diff(
				$resources,
				$inline_resources
			));
		}

		return $resources;
	}

	function filterJs($resources) {

		return array_values(array_filter($resources, $this->isJs(...)));
	}

	function isJs(array $resource) {

		return $this->getProcessor($resource)->getType($resource) == POP_RESOURCELOADER_RESOURCETYPE_JS;
	}

	function filterCss($resources) {

		return array_values(array_filter($resources, $this->isCss(...)));
	}

	function isCss(array $resource) {

		return $this->getProcessor($resource)->getType($resource) == POP_RESOURCELOADER_RESOURCETYPE_CSS;
	}

	function filterVendor($resources) {

		return array_values(array_filter($resources, $this->isVendor(...)));
	}

	function isVendor(array $resource) {

		return $this->getProcessor($resource)->getSubtype($resource) == POP_RESOURCELOADER_RESOURCESUBTYPE_VENDOR;
	}

	function filterDynamic($resources) {

		return array_values(array_filter($resources, $this->isDynamic(...)));
	}

	function isDynamic(array $resource) {

		return $this->getProcessor($resource)->getSubtype($resource) == POP_RESOURCELOADER_RESOURCESUBTYPE_DYNAMIC;
	}

	function filterTemplate($resources) {

		return array_values(array_filter($resources, $this->isTemplate(...)));
	}

	function isTemplate(array $resource) {

		return $this->getProcessor($resource)->getSubtype($resource) == POP_RESOURCELOADER_RESOURCESUBTYPE_TEMPLATE;
	}

	function filterInBody($resources) {

		if (PoP_ResourceLoader_ServerUtils::includeResourcesInBody()) {

			// Extract all the resources added through PoP_Processor->getResources($component, $props)
			$engineState = App::getEngineState();
			if ($modules_resources = $engineState->helperCalculations['component-resources']) {
				
				return array_values(array_intersect($resources, $modules_resources));
			}
		}

		return array();

		// return array_filter($resources, $this->inBody(...));
	}

	// function inBody(array $resource) {

	// 	return $this->getProcessor($resource)->inBody($resource);
	// }

	function filterInline($resources) {

		return array_values(array_filter($resources, $this->inline(...)));
	}

	function inline(array $resource) {

		return $this->getProcessor($resource)->inline($resource);
	}

	function filterCanBundle($resources) {

		// Remove all resources which go in the body, those cannot be bundled
		if ($in_body_resources = $this->filterInBody($resources)) {
			
			$resources = array_values(array_diff(
				$resources,
				$in_body_resources
			));
		}
		if ($inline_resources = $this->filterInline($resources)) {
			
			$resources = array_values(array_diff(
				$resources,
				$inline_resources
			));
		}
		
		return array_values(array_filter($resources, $this->canBundle(...)));
	}

	function canBundle(array $resource) {

		return $this->getProcessor($resource)->canBundle($resource);
	}

	function addResource(&$resources, $resource/*, $resource_key*/) {

		// Enqueue the resource
		// if (!in_array($resource, $this->enqueued_resources)) {
		if (!in_array($resource, $resources)) {
		// if (!in_array($resource, arrayFlatten(array_values($resources)))) {

			// // Say that no need to add this resource
			// $this->enqueued_resources[] = $resource;

			// $resources[$resource_key][] = $resource;
			$resources[] = $resource;
		}
	}

	function addResourceDependencies(&$resources, array $resource, $addResource/*, $resource_key = ''*/) {

		// // Say that no need to add this resource
		// $this->enqueued_resources[] = $resource;

		$processor = $this->getProcessor($resource);

		// First enqueue the dependencies, then continue to enqueue the needed resources
		$dependencies = $processor->getDependencies($resource);
		// foreach ($dependencies as $dependency_resource/* => $dependency_resource_methods*/) {
		// 	$this->addResourcesFromJsobjects($resources, $dependency_resource/*, $dependency_resource_methods*/);
		// }
		foreach ($dependencies as $dependency_resource/* => $dependency_resource_methods*/) {
			
			// if (!in_array($dependency_resource, $this->enqueued_resources)) {
			if (!in_array($dependency_resource, $resources)) {

				$this->addResourceDependencies($resources, $dependency_resource, true/*, 'external'*/);
			}
		}
	
		// Enqueue the resource, at the end, after its dependencies have been added
		if ($addResource) {
			$this->addResource($resources, $resource/*, $resource_key*/);
		}

		// Add the decorators after the resource
		$decorators = $processor->getDecorators($resource);
		// foreach ($dependencies as $dependency_resource/* => $dependency_resource_methods*/) {
		// 	$this->addResourcesFromJsobjects($resources, $dependency_resource/*, $dependency_resource_methods*/);
		// }
		foreach ($decorators as $decorator_resource) {
			
			// if (!in_array($dependency_resource, $this->enqueued_resources)) {
			if (!in_array($decorator_resource, $resources)) {

				$this->addResourceDependencies($resources, $decorator_resource, true/*, 'external'*/);
			}
		}
	}
}

/**
 * Initialization
 */
global $pop_resourceloaderprocessor_manager;
$pop_resourceloaderprocessor_manager = new PoP_ResourceLoaderProcessorManager();

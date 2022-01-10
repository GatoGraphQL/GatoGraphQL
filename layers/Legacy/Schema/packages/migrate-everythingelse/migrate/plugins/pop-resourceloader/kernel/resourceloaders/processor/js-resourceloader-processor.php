<?php
use PoP\ComponentModel\Facades\Cache\TransientCacheManagerFacade;

class PoP_JSResourceLoaderProcessor extends PoP_ResourceLoaderProcessor {

	public function __construct() {

		global $pop_jsresourceloaderprocessor_manager;
		$pop_jsresourceloaderprocessor_manager->add($this, $this->getResourcesToProcess());
	}

	function getType(array $resource) {

		return POP_RESOURCELOADER_RESOURCETYPE_JS;
	}

	function getSuffix(array $resource) {

		return (PoP_WebPlatform_ServerUtils::useMinifiedResources() ? '.min' : '').'.js';
	}

	function extractMapping(array $resource) {

		return true;
	}

	function inFooter(array $resource) {

		return true;
	}

	function getJsobjects(array $resource) {

		return array();
	}

	function getGlobalscopeMethodCalls(array $resource) {

		return array();
	}

	function getScripttagAttributes(array $resource, $model_instance_id) {

		if ($this->isAsync($resource)) {

			return "async='async'";
		}
		// canDefer: allows the templates to check if we are doing serverside-rendering, because .tmpl files cannot be made "defer" when doing client-side rendering
		elseif ($this->canDefer($resource) && $this->isDefer($resource, $model_instance_id)) {

			return "defer='defer'";
		}

		// return parent::getHtmltagAttributes($resource);
		return '';
	}

	function isAsync(array $resource) {

		return false;
	}

	protected function canDefer(array $resource) {

		// canDefer: allows the templates to check if we are doing serverside-rendering, because .tmpl files cannot be made "defer" when doing client-side rendering
		return true;
	}

	// isDefer function is relative to the specific vars,
	// since a resource may be defer for a page, but not for another
	// This is evident when generating all the bundle(group) files, in which isDefer is accessed to calculate the deferred bundle(group)s
	function isDefer(array $resource, $model_instance_id) {

		// If these resources have been marked as 'noncritical', then defer loading them
		if (PoP_WebPlatform_ServerUtils::useProgressiveBooting()) {

			$memorymanager = TransientCacheManagerFacade::getInstance();
			if ($noncritical_resources = $memorymanager->getComponentModelCache($model_instance_id, POP_MEMORYTYPE_NONCRITICALRESOURCES)) {

				return in_array($resource, $noncritical_resources);
			}
		}

		return false;
	}

	function asyncLoadInOrder(array $resource) {

		// If the resource is either a decorator, or being decorated, then load them in order
		// Otherwise, by default, no need to load it in order
		return !empty($this->getDecoratedResources($resource)) || !empty($this->getDecorators($resource));
	}
}

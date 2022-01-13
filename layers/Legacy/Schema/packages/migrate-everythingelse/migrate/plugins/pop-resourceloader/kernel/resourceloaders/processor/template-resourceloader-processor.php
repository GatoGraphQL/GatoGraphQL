<?php
use PoP\ComponentModel\Facades\Cache\TransientCacheManagerFacade;
use PoP\Root\Facades\Hooks\HooksAPIFacade;

abstract class PoP_TemplateResourceLoaderProcessor extends PoP_JSResourceLoaderProcessor {

	// function init() {

	// 	parent::init();

	// 	// In addition only for the templates, create a mapping between template name and resources,
	// 	// so that from templates we can obtain directly what are those resources
	// 	global $pop_templateresourceloaderprocessor_manager;
	// 	$pop_templateresourceloaderprocessor_manager->add($this, $this->getResourcesToProcess());
	// }

	function getSubtype(array $resource) {

		return POP_RESOURCELOADER_RESOURCESUBTYPE_TEMPLATE;
	}

	function getSuffix(array $resource) {

		return '.tmpl.js';
	}

	abstract public function getTemplate(array $resource);

	public function getFilename(array $resource)
    {
        // The template holds the resource's filename
        return $this->getTemplate($resource);
    }

	protected function canDefer(array $resource) {

		// Javascript Template files can only be deferred when doing server-side rendering
		if (defined('POP_SSR_INITIALIZED')) {

			return !PoP_SSR_ServerUtils::disableServerSideRendering();
		}

		return parent::canDefer($resource);
	}

	function isDefer(array $resource, $model_instance_id) {

		// When first loading the website, if doing serverside-rendering, then most of the javascript template files
		// are actually not needed. They are needed only when the block is lazy-loaded, or when performing a dynamic action,
		// such as showing the events calendar (rendered through JS) or appending more posts to the feed
		if (PoP_ResourceLoader_ServerUtils::useCodeSplitting()) {

			// Instead of checking from the current dynamic-templates, get the value from the resource-cache,
			// so that it also works for when generating the bundle(group) files during the /generate-theme/ process
			$memorymanager = TransientCacheManagerFacade::getInstance();
			if ($dynamic_template_resources = $memorymanager->getComponentModelCache($model_instance_id, POP_MEMORYTYPE_DYNAMICTEMPLATERESOURCES)) {

				// Comment Leo 20/11/2017: taking a very aggressive approach: make all templates be deferred,
				// unless they are inside a dynamic component (this could also be made deferred, but to be on the safe side,
				// as in any JS method needing a .tmpl template being set as 'critical', then keep it like this)
				return !in_array($this->getTemplate($resource), $dynamic_template_resources);
			}
		}

		return parent::isDefer($resource, $model_instance_id);
	}

	function getDependencies(array $resource) {

		$dependencies = parent::getDependencies($resource);

		// All templates depend on the handlebars runtime. Allow plugins to add their own dependencies
		if ($template_dependencies = HooksAPIFacade::getInstance()->applyFilters(
			'PoP_TemplateResourceLoaderProcessor:dependencies',
			[
				[PoP_FrontEnd_VendorJSResourceLoaderProcessor::class, PoP_FrontEnd_VendorJSResourceLoaderProcessor::RESOURCE_EXTERNAL_HANDLEBARS],
				[PoP_FrontEnd_HandlebarsHelpersJSResourceLoaderProcessor::class, PoP_FrontEnd_HandlebarsHelpersJSResourceLoaderProcessor::RESOURCE_HANDLEBARSHELPERS_KERNEL],
			]
		)) {
			$dependencies = array_merge(
				$dependencies,
				$template_dependencies
			);
		}

		return $dependencies;
	}

	function extractMapping(array $resource) {

		return false;
	}
}

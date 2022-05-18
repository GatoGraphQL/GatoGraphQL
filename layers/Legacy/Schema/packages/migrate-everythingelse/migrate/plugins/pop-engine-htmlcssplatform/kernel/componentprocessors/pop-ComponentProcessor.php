<?php
use PoP\Application\ComponentProcessors\AbstractQueryDataComponentProcessor;
use PoP\ComponentModel\ModuleInfo as ComponentModelModuleInfo;
use PoP\Root\Facades\Instances\InstanceManagerFacade;
use PoP\ComponentModel\State\ApplicationState;
use PoP\Definitions\Facades\DefinitionManagerFacade;

abstract class PoP_HTMLCSSPlatformQueryDataComponentProcessorBase extends AbstractQueryDataComponentProcessor
{
    public function fixedId(array $componentVariation, array &$props): bool
    {

        // fixedId if false, it will add a counter at the end of the newly generated ID using {{#generateId}}
        // This is to avoid different HTML elements having the same ID
        // However, whenever we need to reference the ID of that element on PHP code (eg: to print in settings a Bootstrap collapse href="#id")
        // Then we gotta make that ID fixed, it won't add the counter in {{#generateId}}, so the same ID can be calculated in PHP
        // More often than not, whenever we need to invoke function ->getFrontendId() in PHP, then fixedId will have to be true

        // If the parent set the 'frontend-id' on a component variation, then treat it as fixed
        if ($this->getProp($componentVariation, $props, 'frontend-id')) {
            return true;
        }

        return false;
    }

    public function isFrontendIdUnique(array $componentVariation, array &$props): bool
    {

        // If defined by $props, use it first. Otherwise, it's false
        return $this->getProp($componentVariation, $props, 'unique-frontend-id') ?? false;
    }

    public function getFrontendId(array $componentVariation, array &$props)
    {

        // Allow a parent component variation to set this value
        if ($frontend_id = $this->getProp($componentVariation, $props, 'frontend-id')) {
            return $frontend_id;
        }

        $id = $this->getID($componentVariation, $props);

        // If the ID in the htmlcssplatform is not unique, then we gotta make it unique by adding ComponentModelModuleInfo::get('unique-id') at the end
        // Since ComponentModelModuleInfo::get('unique-id') will change its value when fetching pageSection, this allows to add an HTML element
        // similar to an existing one but with a different ID
        // pageSections themselves only get drawn at the beginning and are never re-generated. So for them, their ID is already unique
        if (!$this->isFrontendIdUnique($componentVariation, $props)) {
            return $id.ComponentModelModuleInfo::get('unique-id');
        }

        return $id;
    }

    public function getID(array $componentVariation, array &$props): string
    {
        $moduleOutputName = \PoP\ComponentModel\Facades\Modules\ModuleHelpersFacade::getInstance()->getModuleOutputName($componentVariation);
        // if ($this->fixedId($componentVariation, $props)) {
        // 	$pagesection_settings_id = $props['pagesection-moduleoutputname'];
        // 	$block_settings_id = $props['block-moduleoutputname'];
        // 	return $pagesection_settings_id.'_'.$block_settings_id.'_'.$moduleOutputName;
        // }

        return $moduleOutputName;
    }

    public function getDbobjectParams(array $componentVariation): array
    {
        return array();
    }

    /**
     * @todo Migrate from string to LeafModuleField
     *
     * @return \PoP\ComponentModel\GraphQLEngine\Model\ComponentModelSpec\LeafModuleField[]
     */
    public function getDataFields(array $componentVariation, array &$props): array
    {
        $ret = parent::getDataFields($componentVariation, $props);

        if ($dbobject_params = $this->getProp($componentVariation, $props, 'dbobject-params')) {
            $ret = array_merge(
                $ret,
                array_values($dbobject_params)
            );
        }

        return $ret;
    }

    public function getTemplateResource(array $componentVariation, array &$props): ?array
    {
        return null;
    }

    public function getExtraTemplateResources(array $componentVariation, array &$props): array
    {

        // Load additional template sources to render the view
        $ret = array();

        if ($appendable = $this->getProp($componentVariation, $props, 'appendable')) {
            $ret['class-extensions'][] = [PoP_FrontEnd_TemplateResourceLoaderProcessor::class, PoP_FrontEnd_TemplateResourceLoaderProcessor::RESOURCE_EXTENSIONAPPENDABLECLASS];
        }

        return $ret;
    }

    // function getModuleCb(array $componentVariation, array &$props) {

    // 	// Allow to be set from upper modules. Eg: from the embed Modal to the embedPreview layout,
    // 	// so it knows it must refresh it value when it opens
    // 	return $this->getProp($componentVariation, $props, 'component variation-cb');
    // }
    // function getModuleCbActions(array $componentVariation, array &$props) {

    // 	return null;
    // }

    // function getModulePath(array $componentVariation, array &$props) {

    // 	// Allow to be set from upper modules. Eg: Datum Dynamic Layout can set it to its triggered component variation,
    // 	// which will need to be rendered dynamically on the htmlcssplatform on runtime
    // 	if ($this->getProp($componentVariation, $props, 'component variation-path')) {

    // 		return true;
    // 	}

    // 	return $this->getModuleCb($componentVariation, $props);
    // }

    public function getTemplateResourcesMergedmoduletree(array $componentVariation, array &$props): array
    {
        return $this->executeOnSelfAndMergeWithComponentVariations('getTemplateResources', __FUNCTION__, $componentVariation, $props, false);
    }

    public function getTemplateResources(array $componentVariation, array &$props): array
    {

        // We must send always the template, even if it's similar to the component variation,
        // so that we have the information of all required templates for the ResourceLoader
        // Eg: otherwise loading template 'status' fails
        $templateResource = $this->getTemplateResource($componentVariation, $props);
        return array_merge(
            $templateResource ? array($templateResource) : array(),
            arrayFlatten(
                array_values(
                    $this->getExtraTemplateResources($componentVariation, $props)
                ),
                true
            )
        );
    }

    // function getModulesCbs(array $componentVariation, array &$props) {

    // 	$componentprocessor_manager = ComponentProcessorManagerFacade::getInstance();

    // 	// Return initialized empty array at the last level
    // 	$ret = array(
    // 		'cbs' => array(),
    // 		'actions' => array()
    // 	);

    // 	// Has this level a component variation cb?
    // 	if ($module_cb = $this->getModuleCb($componentVariation, $props)) {

    // 		// Key: component variation / Value: path to arrive to this component variation
    // 		$ret['cbs'][] = $componentVariation;

    // 		// The cb applies to what actions
    // 		if ($module_cb_actions = $this->getModuleCbActions($componentVariation, $props)) {

    // 			$ret['actions'][$componentVariation[1]] = $module_cb_actions;
    // 		}
    // 	}
    // 	foreach ($this->getSubmodulesByGroup($componentVariation) as $submodule) {

    // 		if ($submodule_ret = $componentprocessor_manager->getProcessor($submodule)->getModulesCbs($submodule, $props)) {

    // 			$ret['cbs'] = array_merge(
    // 				$ret['cbs'],
    // 				$submodule_ret['cbs']
    // 			);
    // 			$ret['actions'] = array_merge(
    // 				$ret['actions'],
    // 				$submodule_ret['actions']
    // 			);
    // 		}
    // 	}

    // 	return $ret;
    // }

    // function getModulesPaths(array $componentVariation, array &$props) {

    // 	$componentprocessor_manager = ComponentProcessorManagerFacade::getInstance();

    // 	$moduleOutputName = \PoP\ComponentModel\Facades\Modules\ModuleHelpersFacade::getInstance()->getModuleOutputName($componentVariation);

    // 	// Return initialized empty array at the last level
    // 	$ret = array();

    // 	// Has this level a component variation cb?
    // 	if ($module_path = $this->getModulePath($componentVariation, $props)) {

    // 		// Key: component variation / Value: path to arrive to this component variation
    // 		$ret[$componentVariation[1]] = array(ComponentModelModuleInfo::get('response-prop-submodules'), $moduleOutputName);
    // 	}

    // 	// Add the path from this component variation to its components
    // 	$submodules = $this->getSubmodulesByGroup($componentVariation);
    // 	foreach ($submodules as $submodule) {

    // 		if ($submodule_ret = $componentprocessor_manager->getProcessor($submodule)->getModulesPaths($submodule, $props)) {

    // 			// Add the extra path to the component variation
    // 			foreach ($submodule_ret as $submodule_module => $submodule_module_path) {

    // 				$ret[$submodule_module] = array_merge(
    // 					array(ComponentModelModuleInfo::get('response-prop-submodules'), $moduleOutputName),
    // 					$submodule_module_path
    // 				);
    // 			}
    // 		}
    // 	}

    // 	return $ret;
    // }


    public function getMutableonrequestConfiguration(array $componentVariation, array &$props): array
    {
        $ret = parent::getMutableonrequestConfiguration($componentVariation, $props);

        // Validate that the strata includes the required stratum
        if (!in_array(POP_STRATUM_HTMLCSS, \PoP\Root\App::getState('strata'))) {
            return $ret;
        }

        if ($class = $this->getProp($componentVariation, $props, 'runtime-class')) {
            $ret['runtime-class'] = $class;
        }

        if ($this->fixedId($componentVariation, $props)) {

            // Whenever the id is fixed, we can already know what the front-end id will be
            // this is needed for the component variation to print its id in advance
            $ret[GD_JS_FRONTENDID] = $this->getFrontendId($componentVariation, $props);
        }

        // Allow CSS to Styles to modify these value
        return \PoP\Root\App::applyFilters(
            'PoP_HTMLCSSPlatformQueryDataComponentProcessorBase:component variation-mutableonrequest-configuration',
            $ret,
            $componentVariation,
            $props,
            $this
        );
    }

    public function addHTMLCSSPlatformModuleConfiguration(&$ret, array $componentVariation, array &$props)
    {

        // Do nothing. Override
    }

    public function getImmutableConfiguration(array $componentVariation, array &$props): array
    {
        $ret = parent::getImmutableConfiguration($componentVariation, $props);

        // Validate that the strata includes the required stratum
        if (!in_array(POP_STRATUM_HTMLCSS, \PoP\Root\App::getState('strata'))) {
            return $ret;
        }

        $instanceManager = InstanceManagerFacade::getInstance();
        global $pop_resourceloaderprocessor_manager;
        $templateResource = $this->getTemplateResource($componentVariation, $props);
        $resourceprocessor = $pop_resourceloaderprocessor_manager->getProcessor($templateResource);
        $ret[GD_JS_TEMPLATE] = $resourceprocessor->getTemplate($templateResource);

        // Add the htmlcssplatform stuff
        $this->addHTMLCSSPlatformModuleConfiguration($ret, $componentVariation, $props);

        $ret['id'] = $this->getID($componentVariation, $props);
        if ($this->fixedId($componentVariation, $props)) {
            $ret[GD_JS_FIXEDID] = true;
        }
        if ($is_id_unique = $this->isFrontendIdUnique($componentVariation, $props)) {
            $ret[GD_JS_ISIDUNIQUE] = true;
        }

        // Output the 'class'. Check if to convert it to styles, for emails
        $classs = '';
        if ($class = $this->getProp($componentVariation, $props, 'class')) {
            $classs = $class;
        }
        if ($classes = $this->getProp($componentVariation, $props, 'classes')) {
            $classs .= ($classs ? ' ' : '').implode(' ', array_unique($classes));
        }
        if ($classs) {
            $ret[GD_JS_CLASS] = $classs;
        }

        if ($params = $this->getProp($componentVariation, $props, 'params')) {
            $ret[GD_JS_PARAMS] = $params;
        }
        if ($dbobject_params = $this->getProp($componentVariation, $props, 'dbobject-params')) {
            $ret[GD_JS_DBOBJECTPARAMS] = $dbobject_params;
        }
        if ($previousmodules_ids = $this->getProp($componentVariation, $props, 'previousmodules-ids')) {
            // We receive entries of key => component variation, convert them to key => moduleOutputName
            $ret[GD_JS_PREVIOUSMODULESIDS] = array_map(
                [\PoP\ComponentModel\Facades\Modules\ModuleHelpersFacade::getInstance(), 'getModuleOutputName'],
                $previousmodules_ids
            );
        }

        // Load additional/extension templates?
        if ($extra_template_resources = $this->getExtraTemplateResources($componentVariation, $props)) {
            $ret[POP_JS_TEMPLATES] = array_map(
                function($resources) use($pop_resourceloaderprocessor_manager) {
                    return array_map(
                        function($resource) use($pop_resourceloaderprocessor_manager) {
                            return $pop_resourceloaderprocessor_manager->getProcessor($resource)->getTemplate($resource);
                        },
                        $resources
                    );
                },
                $extra_template_resources
            );
        }

        // Allow PoP Resource Loader to inject this value
        return \PoP\Root\App::applyFilters(
            'PoP_HTMLCSSPlatformQueryDataComponentProcessorBase:component variation-immutable-configuration',
            $ret,
            $componentVariation,
            $props,
            $this
        );
    }

    protected function addModuleNameAsClass(array $componentVariation, array &$props)
    {
        return false;
    }

    public function initHTMLCSSPlatformModelProps(array $componentVariation, array &$props)
    {
        if ($this->addModuleNameAsClass($componentVariation, $props)) {
            $this->appendProp($componentVariation, $props, 'class', DefinitionManagerFacade::getInstance()->getOriginalName($componentVariation));
        }

        // // Add the properties below either as static or mutableonrequest
        // if (in_array($this->getDatasource($componentVariation, $props), array(
        // 	\PoP\ComponentModel\Constants\DataSources::IMMUTABLE,
        // 	\PoP\ComponentModel\Constants\DataSources::MUTABLEONMODEL,
        // ))) {

        // 	$this->setModuleHTMLCSSPlatformProps($componentVariation, $props);
        // }
    }

    public function initModelProps(array $componentVariation, array &$props): void
    {
        // Validate that the strata includes the required stratum
        if (in_array(POP_STRATUM_HTMLCSS, \PoP\Root\App::getState('strata'))) {

            if ($dbobject_params = $this->getDbobjectParams($componentVariation)) {
                $this->mergeProp($componentVariation, $props, 'dbobject-params', $dbobject_params);
            }

            $this->initHTMLCSSPlatformModelProps($componentVariation, $props);
        }

        parent::initModelProps($componentVariation, $props);
    }

    public function initHTMLCSSPlatformRequestProps(array $componentVariation, array &$props)
    {

        // // Add the properties below either as static or mutableonrequest
        // if ($this->getDatasource($componentVariation, $props) == \PoP\ComponentModel\Constants\DataSources::MUTABLEONREQUEST) {

        // 	$this->setModuleHTMLCSSPlatformProps($componentVariation, $props);
        // }
    }

    public function initRequestProps(array $componentVariation, array &$props): void
    {
        // Validate that the strata includes the required stratum
        if (in_array(POP_STRATUM_HTMLCSS, \PoP\Root\App::getState('strata'))) {
            $this->initHTMLCSSPlatformRequestProps($componentVariation, $props);
        }
        parent::initRequestProps($componentVariation, $props);
    }
}

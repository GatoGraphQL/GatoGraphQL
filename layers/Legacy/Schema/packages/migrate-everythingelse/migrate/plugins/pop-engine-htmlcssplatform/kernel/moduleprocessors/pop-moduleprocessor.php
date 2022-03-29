<?php
use PoP\Application\ModuleProcessors\AbstractQueryDataModuleProcessor;
use PoP\ComponentModel\ComponentInfo as ComponentModelComponentInfo;
use PoP\Root\Facades\Instances\InstanceManagerFacade;
use PoP\ComponentModel\State\ApplicationState;
use PoP\Definitions\Facades\DefinitionManagerFacade;

abstract class PoP_HTMLCSSPlatformQueryDataModuleProcessorBase extends AbstractQueryDataModuleProcessor
{
    public function fixedId(array $module, array &$props): bool
    {

        // fixedId if false, it will add a counter at the end of the newly generated ID using {{#generateId}}
        // This is to avoid different HTML elements having the same ID
        // However, whenever we need to reference the ID of that element on PHP code (eg: to print in settings a Bootstrap collapse href="#id")
        // Then we gotta make that ID fixed, it won't add the counter in {{#generateId}}, so the same ID can be calculated in PHP
        // More often than not, whenever we need to invoke function ->getFrontendId() in PHP, then fixedId will have to be true

        // If the parent set the 'frontend-id' on a module, then treat it as fixed
        if ($this->getProp($module, $props, 'frontend-id')) {
            return true;
        }

        return false;
    }

    public function isFrontendIdUnique(array $module, array &$props): bool
    {

        // If defined by $props, use it first. Otherwise, it's false
        return $this->getProp($module, $props, 'unique-frontend-id') ?? false;
    }

    public function getFrontendId(array $module, array &$props)
    {

        // Allow a parent module to set this value
        if ($frontend_id = $this->getProp($module, $props, 'frontend-id')) {
            return $frontend_id;
        }

        $id = $this->getID($module, $props);

        // If the ID in the htmlcssplatform is not unique, then we gotta make it unique by adding ComponentModelComponentInfo::get('unique-id') at the end
        // Since ComponentModelComponentInfo::get('unique-id') will change its value when fetching pageSection, this allows to add an HTML element
        // similar to an existing one but with a different ID
        // pageSections themselves only get drawn at the beginning and are never re-generated. So for them, their ID is already unique
        if (!$this->isFrontendIdUnique($module, $props)) {
            return $id.ComponentModelComponentInfo::get('unique-id');
        }

        return $id;
    }

    public function getID(array $module, array &$props): string
    {
        $moduleOutputName = \PoP\ComponentModel\Facades\Modules\ModuleHelpersFacade::getInstance()->getModuleOutputName($module);
        // if ($this->fixedId($module, $props)) {
        // 	$pagesection_settings_id = $props['pagesection-moduleoutputname'];
        // 	$block_settings_id = $props['block-moduleoutputname'];
        // 	return $pagesection_settings_id.'_'.$block_settings_id.'_'.$moduleOutputName;
        // }

        return $moduleOutputName;
    }

    public function getDbobjectParams(array $module): array
    {
        return array();
    }

    /**
     * @todo Migrate from string to LeafModuleField
     *
     * @return \PoP\ComponentModel\GraphQLModel\ComponentModelSpec\Ast\LeafModuleField[]
     */
    public function getDataFields(array $module, array &$props): array
    {
        $ret = parent::getDataFields($module, $props);

        if ($dbobject_params = $this->getProp($module, $props, 'dbobject-params')) {
            $ret = array_merge(
                $ret,
                array_values($dbobject_params)
            );
        }

        return $ret;
    }

    public function getTemplateResource(array $module, array &$props): ?array
    {
        return null;
    }

    public function getExtraTemplateResources(array $module, array &$props): array
    {

        // Load additional template sources to render the view
        $ret = array();

        if ($appendable = $this->getProp($module, $props, 'appendable')) {
            $ret['class-extensions'][] = [PoP_FrontEnd_TemplateResourceLoaderProcessor::class, PoP_FrontEnd_TemplateResourceLoaderProcessor::RESOURCE_EXTENSIONAPPENDABLECLASS];
        }

        return $ret;
    }

    // function getModuleCb(array $module, array &$props) {

    // 	// Allow to be set from upper modules. Eg: from the embed Modal to the embedPreview layout,
    // 	// so it knows it must refresh it value when it opens
    // 	return $this->getProp($module, $props, 'module-cb');
    // }
    // function getModuleCbActions(array $module, array &$props) {

    // 	return null;
    // }

    // function getModulePath(array $module, array &$props) {

    // 	// Allow to be set from upper modules. Eg: Datum Dynamic Layout can set it to its triggered module,
    // 	// which will need to be rendered dynamically on the htmlcssplatform on runtime
    // 	if ($this->getProp($module, $props, 'module-path')) {

    // 		return true;
    // 	}

    // 	return $this->getModuleCb($module, $props);
    // }

    public function getTemplateResourcesMergedmoduletree(array $module, array &$props): array
    {
        return $this->executeOnSelfAndMergeWithModules('getTemplateResources', __FUNCTION__, $module, $props, false);
    }

    public function getTemplateResources(array $module, array &$props): array
    {

        // We must send always the template, even if it's similar to the module,
        // so that we have the information of all required templates for the ResourceLoader
        // Eg: otherwise loading template 'status' fails
        $templateResource = $this->getTemplateResource($module, $props);
        return array_merge(
            $templateResource ? array($templateResource) : array(),
            arrayFlatten(
                array_values(
                    $this->getExtraTemplateResources($module, $props)
                ),
                true
            )
        );
    }

    // function getModulesCbs(array $module, array &$props) {

    // 	$moduleprocessor_manager = ModuleProcessorManagerFacade::getInstance();

    // 	// Return initialized empty array at the last level
    // 	$ret = array(
    // 		'cbs' => array(),
    // 		'actions' => array()
    // 	);

    // 	// Has this level a module cb?
    // 	if ($module_cb = $this->getModuleCb($module, $props)) {

    // 		// Key: module / Value: path to arrive to this module
    // 		$ret['cbs'][] = $module;

    // 		// The cb applies to what actions
    // 		if ($module_cb_actions = $this->getModuleCbActions($module, $props)) {

    // 			$ret['actions'][$module[1]] = $module_cb_actions;
    // 		}
    // 	}
    // 	foreach ($this->getSubmodulesByGroup($module) as $submodule) {

    // 		if ($submodule_ret = $moduleprocessor_manager->getProcessor($submodule)->getModulesCbs($submodule, $props)) {

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

    // function getModulesPaths(array $module, array &$props) {

    // 	$moduleprocessor_manager = ModuleProcessorManagerFacade::getInstance();

    // 	$moduleOutputName = \PoP\ComponentModel\Facades\Modules\ModuleHelpersFacade::getInstance()->getModuleOutputName($module);

    // 	// Return initialized empty array at the last level
    // 	$ret = array();

    // 	// Has this level a module cb?
    // 	if ($module_path = $this->getModulePath($module, $props)) {

    // 		// Key: module / Value: path to arrive to this module
    // 		$ret[$module[1]] = array(ComponentModelComponentInfo::get('response-prop-submodules'), $moduleOutputName);
    // 	}

    // 	// Add the path from this module to its components
    // 	$submodules = $this->getSubmodulesByGroup($module);
    // 	foreach ($submodules as $submodule) {

    // 		if ($submodule_ret = $moduleprocessor_manager->getProcessor($submodule)->getModulesPaths($submodule, $props)) {

    // 			// Add the extra path to the module
    // 			foreach ($submodule_ret as $submodule_module => $submodule_module_path) {

    // 				$ret[$submodule_module] = array_merge(
    // 					array(ComponentModelComponentInfo::get('response-prop-submodules'), $moduleOutputName),
    // 					$submodule_module_path
    // 				);
    // 			}
    // 		}
    // 	}

    // 	return $ret;
    // }


    public function getMutableonrequestConfiguration(array $module, array &$props): array
    {
        $ret = parent::getMutableonrequestConfiguration($module, $props);

        // Validate that the strata includes the required stratum
        if (!in_array(POP_STRATUM_HTMLCSS, \PoP\Root\App::getState('strata'))) {
            return $ret;
        }

        if ($class = $this->getProp($module, $props, 'runtime-class')) {
            $ret['runtime-class'] = $class;
        }

        if ($this->fixedId($module, $props)) {

            // Whenever the id is fixed, we can already know what the front-end id will be
            // this is needed for the module to print its id in advance
            $ret[GD_JS_FRONTENDID] = $this->getFrontendId($module, $props);
        }

        // Allow CSS to Styles to modify these value
        return \PoP\Root\App::applyFilters(
            'PoP_HTMLCSSPlatformQueryDataModuleProcessorBase:module-mutableonrequest-configuration',
            $ret,
            $module,
            $props,
            $this
        );
    }

    public function addHTMLCSSPlatformModuleConfiguration(&$ret, array $module, array &$props)
    {

        // Do nothing. Override
    }

    public function getImmutableConfiguration(array $module, array &$props): array
    {
        $ret = parent::getImmutableConfiguration($module, $props);

        // Validate that the strata includes the required stratum
        if (!in_array(POP_STRATUM_HTMLCSS, \PoP\Root\App::getState('strata'))) {
            return $ret;
        }

        $instanceManager = InstanceManagerFacade::getInstance();
        global $pop_resourceloaderprocessor_manager;
        $templateResource = $this->getTemplateResource($module, $props);
        $resourceprocessor = $pop_resourceloaderprocessor_manager->getProcessor($templateResource);
        $ret[GD_JS_TEMPLATE] = $resourceprocessor->getTemplate($templateResource);

        // Add the htmlcssplatform stuff
        $this->addHTMLCSSPlatformModuleConfiguration($ret, $module, $props);

        $ret['id'] = $this->getID($module, $props);
        if ($this->fixedId($module, $props)) {
            $ret[GD_JS_FIXEDID] = true;
        }
        if ($is_id_unique = $this->isFrontendIdUnique($module, $props)) {
            $ret[GD_JS_ISIDUNIQUE] = true;
        }

        // Output the 'class'. Check if to convert it to styles, for emails
        $classs = '';
        if ($class = $this->getProp($module, $props, 'class')) {
            $classs = $class;
        }
        if ($classes = $this->getProp($module, $props, 'classes')) {
            $classs .= ($classs ? ' ' : '').implode(' ', array_unique($classes));
        }
        if ($classs) {
            $ret[GD_JS_CLASS] = $classs;
        }

        if ($params = $this->getProp($module, $props, 'params')) {
            $ret[GD_JS_PARAMS] = $params;
        }
        if ($dbobject_params = $this->getProp($module, $props, 'dbobject-params')) {
            $ret[GD_JS_DBOBJECTPARAMS] = $dbobject_params;
        }
        if ($previousmodules_ids = $this->getProp($module, $props, 'previousmodules-ids')) {
            // We receive entries of key => module, convert them to key => moduleOutputName
            $ret[GD_JS_PREVIOUSMODULESIDS] = array_map(
                [\PoP\ComponentModel\Facades\Modules\ModuleHelpersFacade::getInstance(), 'getModuleOutputName'],
                $previousmodules_ids
            );
        }

        // Load additional/extension templates?
        if ($extra_template_resources = $this->getExtraTemplateResources($module, $props)) {
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
            'PoP_HTMLCSSPlatformQueryDataModuleProcessorBase:module-immutable-configuration',
            $ret,
            $module,
            $props,
            $this
        );
    }

    protected function addModuleNameAsClass(array $module, array &$props)
    {
        return false;
    }

    public function initHTMLCSSPlatformModelProps(array $module, array &$props)
    {
        if ($this->addModuleNameAsClass($module, $props)) {
            $this->appendProp($module, $props, 'class', DefinitionManagerFacade::getInstance()->getOriginalName($module));
        }

        // // Add the properties below either as static or mutableonrequest
        // if (in_array($this->getDatasource($module, $props), array(
        // 	\PoP\ComponentModel\Constants\DataSources::IMMUTABLE,
        // 	\PoP\ComponentModel\Constants\DataSources::MUTABLEONMODEL,
        // ))) {

        // 	$this->setModuleHTMLCSSPlatformProps($module, $props);
        // }
    }

    public function initModelProps(array $module, array &$props): void
    {
        // Validate that the strata includes the required stratum
        if (in_array(POP_STRATUM_HTMLCSS, \PoP\Root\App::getState('strata'))) {

            if ($dbobject_params = $this->getDbobjectParams($module)) {
                $this->mergeProp($module, $props, 'dbobject-params', $dbobject_params);
            }

            $this->initHTMLCSSPlatformModelProps($module, $props);
        }

        parent::initModelProps($module, $props);
    }

    public function initHTMLCSSPlatformRequestProps(array $module, array &$props)
    {

        // // Add the properties below either as static or mutableonrequest
        // if ($this->getDatasource($module, $props) == \PoP\ComponentModel\Constants\DataSources::MUTABLEONREQUEST) {

        // 	$this->setModuleHTMLCSSPlatformProps($module, $props);
        // }
    }

    public function initRequestProps(array $module, array &$props): void
    {
        // Validate that the strata includes the required stratum
        if (in_array(POP_STRATUM_HTMLCSS, \PoP\Root\App::getState('strata'))) {
            $this->initHTMLCSSPlatformRequestProps($module, $props);
        }
        parent::initRequestProps($module, $props);
    }
}

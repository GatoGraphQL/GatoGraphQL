<?php
use PoP\Application\ComponentProcessors\AbstractQueryDataComponentProcessor;
use PoP\ComponentModel\ModuleInfo as ComponentModelModuleInfo;
use PoP\Root\Facades\Instances\InstanceManagerFacade;
use PoP\ComponentModel\State\ApplicationState;
use PoP\Definitions\Facades\DefinitionManagerFacade;

abstract class PoP_HTMLCSSPlatformQueryDataComponentProcessorBase extends AbstractQueryDataComponentProcessor
{
    public function fixedId(array $component, array &$props): bool
    {

        // fixedId if false, it will add a counter at the end of the newly generated ID using {{#generateId}}
        // This is to avoid different HTML elements having the same ID
        // However, whenever we need to reference the ID of that element on PHP code (eg: to print in settings a Bootstrap collapse href="#id")
        // Then we gotta make that ID fixed, it won't add the counter in {{#generateId}}, so the same ID can be calculated in PHP
        // More often than not, whenever we need to invoke function ->getFrontendId() in PHP, then fixedId will have to be true

        // If the parent set the 'frontend-id' on a component, then treat it as fixed
        if ($this->getProp($component, $props, 'frontend-id')) {
            return true;
        }

        return false;
    }

    public function isFrontendIdUnique(array $component, array &$props): bool
    {

        // If defined by $props, use it first. Otherwise, it's false
        return $this->getProp($component, $props, 'unique-frontend-id') ?? false;
    }

    public function getFrontendId(array $component, array &$props)
    {

        // Allow a parent component to set this value
        if ($frontend_id = $this->getProp($component, $props, 'frontend-id')) {
            return $frontend_id;
        }

        $id = $this->getID($component, $props);

        // If the ID in the htmlcssplatform is not unique, then we gotta make it unique by adding ComponentModelModuleInfo::get('unique-id') at the end
        // Since ComponentModelModuleInfo::get('unique-id') will change its value when fetching pageSection, this allows to add an HTML element
        // similar to an existing one but with a different ID
        // pageSections themselves only get drawn at the beginning and are never re-generated. So for them, their ID is already unique
        if (!$this->isFrontendIdUnique($component, $props)) {
            return $id.ComponentModelModuleInfo::get('unique-id');
        }

        return $id;
    }

    public function getID(array $component, array &$props): string
    {
        $moduleOutputName = \PoP\ComponentModel\Facades\Modules\ModuleHelpersFacade::getInstance()->getModuleOutputName($component);
        // if ($this->fixedId($component, $props)) {
        // 	$pagesection_settings_id = $props['pagesection-componentoutputname'];
        // 	$block_settings_id = $props['block-componentoutputname'];
        // 	return $pagesection_settings_id.'_'.$block_settings_id.'_'.$moduleOutputName;
        // }

        return $moduleOutputName;
    }

    public function getDbobjectParams(array $component): array
    {
        return array();
    }

    /**
     * @todo Migrate from string to LeafModuleField
     *
     * @return \PoP\ComponentModel\GraphQLEngine\Model\ComponentModelSpec\LeafModuleField[]
     */
    public function getDataFields(array $component, array &$props): array
    {
        $ret = parent::getDataFields($component, $props);

        if ($dbobject_params = $this->getProp($component, $props, 'dbobject-params')) {
            $ret = array_merge(
                $ret,
                array_values($dbobject_params)
            );
        }

        return $ret;
    }

    public function getTemplateResource(array $component, array &$props): ?array
    {
        return null;
    }

    public function getExtraTemplateResources(array $component, array &$props): array
    {

        // Load additional template sources to render the view
        $ret = array();

        if ($appendable = $this->getProp($component, $props, 'appendable')) {
            $ret['class-extensions'][] = [PoP_FrontEnd_TemplateResourceLoaderProcessor::class, PoP_FrontEnd_TemplateResourceLoaderProcessor::RESOURCE_EXTENSIONAPPENDABLECLASS];
        }

        return $ret;
    }

    // function getModuleCb(array $component, array &$props) {

    // 	// Allow to be set from upper modules. Eg: from the embed Modal to the embedPreview layout,
    // 	// so it knows it must refresh it value when it opens
    // 	return $this->getProp($component, $props, 'component-cb');
    // }
    // function getModuleCbActions(array $component, array &$props) {

    // 	return null;
    // }

    // function getComponentPath(array $component, array &$props) {

    // 	// Allow to be set from upper modules. Eg: Datum Dynamic Layout can set it to its triggered component,
    // 	// which will need to be rendered dynamically on the htmlcssplatform on runtime
    // 	if ($this->getProp($component, $props, 'component-path')) {

    // 		return true;
    // 	}

    // 	return $this->getModuleCb($component, $props);
    // }

    public function getTemplateResourcesMergedmoduletree(array $component, array &$props): array
    {
        return $this->executeOnSelfAndMergeWithComponents('getTemplateResources', __FUNCTION__, $component, $props, false);
    }

    public function getTemplateResources(array $component, array &$props): array
    {

        // We must send always the template, even if it's similar to the component,
        // so that we have the information of all required templates for the ResourceLoader
        // Eg: otherwise loading template 'status' fails
        $templateResource = $this->getTemplateResource($component, $props);
        return array_merge(
            $templateResource ? array($templateResource) : array(),
            arrayFlatten(
                array_values(
                    $this->getExtraTemplateResources($component, $props)
                ),
                true
            )
        );
    }

    // function getModulesCbs(array $component, array &$props) {

    // 	$componentprocessor_manager = ComponentProcessorManagerFacade::getInstance();

    // 	// Return initialized empty array at the last level
    // 	$ret = array(
    // 		'cbs' => array(),
    // 		'actions' => array()
    // 	);

    // 	// Has this level a component cb?
    // 	if ($module_cb = $this->getModuleCb($component, $props)) {

    // 		// Key: component / Value: path to arrive to this component
    // 		$ret['cbs'][] = $component;

    // 		// The cb applies to what actions
    // 		if ($module_cb_actions = $this->getModuleCbActions($component, $props)) {

    // 			$ret['actions'][$component[1]] = $module_cb_actions;
    // 		}
    // 	}
    // 	foreach ($this->getSubcomponentsByGroup($component) as $subComponent) {

    // 		if ($subcomponent_ret = $componentprocessor_manager->getProcessor($subComponent)->getModulesCbs($subComponent, $props)) {

    // 			$ret['cbs'] = array_merge(
    // 				$ret['cbs'],
    // 				$subcomponent_ret['cbs']
    // 			);
    // 			$ret['actions'] = array_merge(
    // 				$ret['actions'],
    // 				$subcomponent_ret['actions']
    // 			);
    // 		}
    // 	}

    // 	return $ret;
    // }

    // function getModulesPaths(array $component, array &$props) {

    // 	$componentprocessor_manager = ComponentProcessorManagerFacade::getInstance();

    // 	$moduleOutputName = \PoP\ComponentModel\Facades\Modules\ModuleHelpersFacade::getInstance()->getModuleOutputName($component);

    // 	// Return initialized empty array at the last level
    // 	$ret = array();

    // 	// Has this level a component cb?
    // 	if ($module_path = $this->getComponentPath($component, $props)) {

    // 		// Key: component / Value: path to arrive to this component
    // 		$ret[$component[1]] = array(ComponentModelModuleInfo::get('response-prop-subcomponents'), $moduleOutputName);
    // 	}

    // 	// Add the path from this component to its components
    // 	$subComponents = $this->getSubcomponentsByGroup($component);
    // 	foreach ($subComponents as $subComponent) {

    // 		if ($subcomponent_ret = $componentprocessor_manager->getProcessor($subComponent)->getModulesPaths($subComponent, $props)) {

    // 			// Add the extra path to the component
    // 			foreach ($subcomponent_ret as $subcomponent_component => $subcomponent_component_path) {

    // 				$ret[$subcomponent_component] = array_merge(
    // 					array(ComponentModelModuleInfo::get('response-prop-subcomponents'), $moduleOutputName),
    // 					$subcomponent_component_path
    // 				);
    // 			}
    // 		}
    // 	}

    // 	return $ret;
    // }


    public function getMutableonrequestConfiguration(array $component, array &$props): array
    {
        $ret = parent::getMutableonrequestConfiguration($component, $props);

        // Validate that the strata includes the required stratum
        if (!in_array(POP_STRATUM_HTMLCSS, \PoP\Root\App::getState('strata'))) {
            return $ret;
        }

        if ($class = $this->getProp($component, $props, 'runtime-class')) {
            $ret['runtime-class'] = $class;
        }

        if ($this->fixedId($component, $props)) {

            // Whenever the id is fixed, we can already know what the front-end id will be
            // this is needed for the component to print its id in advance
            $ret[GD_JS_FRONTENDID] = $this->getFrontendId($component, $props);
        }

        // Allow CSS to Styles to modify these value
        return \PoP\Root\App::applyFilters(
            'PoP_HTMLCSSPlatformQueryDataComponentProcessorBase:component-mutableonrequest-configuration',
            $ret,
            $component,
            $props,
            $this
        );
    }

    public function addHTMLCSSPlatformModuleConfiguration(&$ret, array $component, array &$props)
    {

        // Do nothing. Override
    }

    public function getImmutableConfiguration(array $component, array &$props): array
    {
        $ret = parent::getImmutableConfiguration($component, $props);

        // Validate that the strata includes the required stratum
        if (!in_array(POP_STRATUM_HTMLCSS, \PoP\Root\App::getState('strata'))) {
            return $ret;
        }

        $instanceManager = InstanceManagerFacade::getInstance();
        global $pop_resourceloaderprocessor_manager;
        $templateResource = $this->getTemplateResource($component, $props);
        $resourceprocessor = $pop_resourceloaderprocessor_manager->getProcessor($templateResource);
        $ret[GD_JS_TEMPLATE] = $resourceprocessor->getTemplate($templateResource);

        // Add the htmlcssplatform stuff
        $this->addHTMLCSSPlatformModuleConfiguration($ret, $component, $props);

        $ret['id'] = $this->getID($component, $props);
        if ($this->fixedId($component, $props)) {
            $ret[GD_JS_FIXEDID] = true;
        }
        if ($is_id_unique = $this->isFrontendIdUnique($component, $props)) {
            $ret[GD_JS_ISIDUNIQUE] = true;
        }

        // Output the 'class'. Check if to convert it to styles, for emails
        $classs = '';
        if ($class = $this->getProp($component, $props, 'class')) {
            $classs = $class;
        }
        if ($classes = $this->getProp($component, $props, 'classes')) {
            $classs .= ($classs ? ' ' : '').implode(' ', array_unique($classes));
        }
        if ($classs) {
            $ret[GD_JS_CLASS] = $classs;
        }

        if ($params = $this->getProp($component, $props, 'params')) {
            $ret[GD_JS_PARAMS] = $params;
        }
        if ($dbobject_params = $this->getProp($component, $props, 'dbobject-params')) {
            $ret[GD_JS_DBOBJECTPARAMS] = $dbobject_params;
        }
        if ($previousmodules_ids = $this->getProp($component, $props, 'previouscomponents-ids')) {
            // We receive entries of key => component, convert them to key => moduleOutputName
            $ret[GD_JS_PREVIOUSCOMPONENTSIDS] = array_map(
                [\PoP\ComponentModel\Facades\Modules\ModuleHelpersFacade::getInstance(), 'getModuleOutputName'],
                $previousmodules_ids
            );
        }

        // Load additional/extension templates?
        if ($extra_template_resources = $this->getExtraTemplateResources($component, $props)) {
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
            'PoP_HTMLCSSPlatformQueryDataComponentProcessorBase:component-immutable-configuration',
            $ret,
            $component,
            $props,
            $this
        );
    }

    protected function addModuleNameAsClass(array $component, array &$props)
    {
        return false;
    }

    public function initHTMLCSSPlatformModelProps(array $component, array &$props)
    {
        if ($this->addModuleNameAsClass($component, $props)) {
            $this->appendProp($component, $props, 'class', DefinitionManagerFacade::getInstance()->getOriginalName($component));
        }

        // // Add the properties below either as static or mutableonrequest
        // if (in_array($this->getDatasource($component, $props), array(
        // 	\PoP\ComponentModel\Constants\DataSources::IMMUTABLE,
        // 	\PoP\ComponentModel\Constants\DataSources::MUTABLEONMODEL,
        // ))) {

        // 	$this->setModuleHTMLCSSPlatformProps($component, $props);
        // }
    }

    public function initModelProps(array $component, array &$props): void
    {
        // Validate that the strata includes the required stratum
        if (in_array(POP_STRATUM_HTMLCSS, \PoP\Root\App::getState('strata'))) {

            if ($dbobject_params = $this->getDbobjectParams($component)) {
                $this->mergeProp($component, $props, 'dbobject-params', $dbobject_params);
            }

            $this->initHTMLCSSPlatformModelProps($component, $props);
        }

        parent::initModelProps($component, $props);
    }

    public function initHTMLCSSPlatformRequestProps(array $component, array &$props)
    {

        // // Add the properties below either as static or mutableonrequest
        // if ($this->getDatasource($component, $props) == \PoP\ComponentModel\Constants\DataSources::MUTABLEONREQUEST) {

        // 	$this->setModuleHTMLCSSPlatformProps($component, $props);
        // }
    }

    public function initRequestProps(array $component, array &$props): void
    {
        // Validate that the strata includes the required stratum
        if (in_array(POP_STRATUM_HTMLCSS, \PoP\Root\App::getState('strata'))) {
            $this->initHTMLCSSPlatformRequestProps($component, $props);
        }
        parent::initRequestProps($component, $props);
    }
}

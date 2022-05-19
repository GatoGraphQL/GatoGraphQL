<?php
use PoP\ComponentModel\ModuleInfo as ComponentModelModuleInfo;
use PoP\ComponentModel\Facades\ComponentFiltering\ComponentFilterManagerFacade;
use PoP\ComponentModel\ComponentProcessors\AbstractModuleDecoratorProcessor;

class PoP_ResourceModuleDecoratorProcessor extends AbstractModuleDecoratorProcessor {

    //-------------------------------------------------
    // PROTECTED Functions
    //-------------------------------------------------

    protected function getModuledecoratorprocessorManager() {

        global $pop_resourcemoduledecoratorprocessor_manager;
        return $pop_resourcemoduledecoratorprocessor_manager;
    }

    //-------------------------------------------------
    // PUBLIC Functions
    //-------------------------------------------------

    function getResourcesMergedmoduletree(array $component, array &$props) {

        return array_unique(
            $this->executeOnSelfAndMergeWithComponents('getResources', __FUNCTION__, $component, $props, false),
            SORT_REGULAR
        );
    }

    function getResources(array $component, array &$props) {

        $processor = $this->getDecoratedcomponentProcessor($component);

        // Allow the theme to hook in the needed CSS resources. It could be based on the $component, or its module source, then pass both these values
        global $pop_resourceloaderprocessor_manager;
        $templateResource = $processor->getTemplateResource($component, $props);
        $resourceprocessor = $pop_resourceloaderprocessor_manager->getProcessor($templateResource);
        return array_values(
            array_unique(
                \PoP\Root\App::applyFilters(
                    'PoP_WebPlatformQueryDataComponentProcessorBase:component-resources',
                    array(),
                    $component,
                    $templateResource,
                    $resourceprocessor->getTemplate($templateResource),
                    $props,
                    $processor,
                    $this
                ),
                SORT_REGULAR
            )
        );
    }

    // function getDynamicModulesResources(array $component, array &$props) {
    function getDynamicResourcesMergedmoduletree(array $component, array &$props) {

        $modulefilter_manager = ComponentFilterManagerFacade::getInstance();
        $componentFullName = \PoP\ComponentModel\Facades\Modules\ModuleHelpersFacade::getInstance()->getModuleFullName($component);

        // If the module path has been set to true, then from this module downwards all modules are dynamic
        if ($this->isDynamicModule($component, $props)) {

            // If componentPaths is provided, and we haven't reached the destination module yet, then do not execute the function at this level
            if (!$modulefilter_manager->excludeSubcomponent($component, $props)) {

                return $this->getResourcesMergedmoduletree($component, $props);
            }
            else {

                return array();
            }
        }

        // If not, then keep iterating down the road
        $ret = array();

        $subComponents = $this->getDecoratedcomponentProcessor($component)->getAllSubcomponents($component);
        $subComponents = $modulefilter_manager->removeExcludedSubcomponents($component, $subComponents);

        // This function must be called always, to register matching modules into requestmeta.filtermodules even when the module has no subcomponents
        $modulefilter_manager->prepareForPropagation($component, $props);
        foreach ($subComponents as $subComponent) {

            if ($subcomponent_ret = $this->getComponentProcessordecorator($subComponent)->getDynamicResourcesMergedmoduletree($subComponent, $props[$componentFullName][ComponentModelModuleInfo::get('response-prop-subcomponents')])) {

                $ret = array_unique(
                    array_merge(
                        $ret,
                        $subcomponent_ret
                    ),
                    SORT_REGULAR
                );
            }
        }
        $modulefilter_manager->restoreFromPropagation($component, $props);

        return $ret;
    }

    function isDynamicModule(array $component, array &$props) {

        // // By default: does it have a path?
        // return $this->getDecoratedcomponentProcessor($component)->getComponentPath($component, $props);
        return $this->getDecoratedcomponentProcessor($component)->getProp($component, $props, 'dynamic-component');
    }

    // function getModulesResources(array $component, array &$props) {

    //     // Return initialized empty array at the last level
    //     $ret = array();
    //     if ($resources = $this->getResources($component, $props)) {
    //         $ret[$component[1]] = $resources;
    //     }

    //     foreach ($this->getDecoratedcomponentProcessor($component)->get_descendant_components_to_propagate($component) as $subComponent) {

    //         if ($subcomponent_ret = $this->getComponentProcessordecorator($subComponent)->getModulesResources($subComponent, $props[$componentFullName][ComponentModelModuleInfo::get('response-prop-subcomponents')])) {

    //             $ret = array_merge(
    //                 $ret,
    //                 $subcomponent_ret
    //             );
    //         }
    //     }

    //     return $ret;
    // }

    function getDynamicTemplateResourcesMergedmoduletree(array $component, array &$props) {

        $processor = $this->getDecoratedcomponentProcessor($component);
        $componentFullName = \PoP\ComponentModel\Facades\Modules\ModuleHelpersFacade::getInstance()->getModuleFullName($component);

        // If componentPaths is provided, and we haven't reached the destination module yet, then do not execute the function at this level
        $modulefilter_manager = ComponentFilterManagerFacade::getInstance();

        // If the module path has been set to true, then from this module downwards all modules are dynamic
        if ($this->isDynamicModule($component, $props)) {

            if (!$modulefilter_manager->excludeSubcomponent($component, $props)) {

                return $processor->getTemplateResourcesMergedmoduletree($component, $props);
            }
            else {

                return array();
            }
        }

        // If not, then keep iterating down the road
        $ret = array();

        $subComponents = $processor->getAllSubcomponents($component);
        $subComponents = $modulefilter_manager->removeExcludedSubcomponents($component, $subComponents);

        // This function must be called always, to register matching modules into requestmeta.filtermodules even when the module has no subcomponents
        $modulefilter_manager->prepareForPropagation($component, $props);
        foreach ($subComponents as $subComponent) {

            if ($subcomponent_ret = $this->getComponentProcessordecorator($subComponent)->getDynamicTemplateResourcesMergedmoduletree($subComponent, $props[$componentFullName][ComponentModelModuleInfo::get('response-prop-subcomponents')])) {

                $ret = array_unique(
                    array_merge(
                        $ret,
                        $subcomponent_ret
                    ),
                    SORT_REGULAR
                );
            }
        }
        $modulefilter_manager->restoreFromPropagation($component, $props);

        return $ret;
    }
}

/**
 * Settings Initialization
 */
global $pop_resourcemoduledecoratorprocessor_manager;
$pop_resourcemoduledecoratorprocessor_manager->add(PoP_WebPlatformQueryDataComponentProcessorBase::class, PoP_ResourceModuleDecoratorProcessor::class);
// $pop_resourcemoduledecoratorprocessor_manager->add(PoP_Module_ProcessorBaseWrapper::class, PoP_ResourceModuleDecoratorProcessor::class);

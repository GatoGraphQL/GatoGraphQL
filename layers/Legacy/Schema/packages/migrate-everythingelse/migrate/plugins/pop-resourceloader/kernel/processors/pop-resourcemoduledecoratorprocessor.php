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

    function getResourcesMergedmoduletree(array $componentVariation, array &$props) {

        return array_unique(
            $this->executeOnSelfAndMergeWithComponentVariations('getResources', __FUNCTION__, $componentVariation, $props, false),
            SORT_REGULAR
        );
    }

    function getResources(array $componentVariation, array &$props) {

        $processor = $this->getDecoratedcomponentProcessor($componentVariation);

        // Allow the theme to hook in the needed CSS resources. It could be based on the $componentVariation, or its module source, then pass both these values
        global $pop_resourceloaderprocessor_manager;
        $templateResource = $processor->getTemplateResource($componentVariation, $props);
        $resourceprocessor = $pop_resourceloaderprocessor_manager->getProcessor($templateResource);
        return array_values(
            array_unique(
                \PoP\Root\App::applyFilters(
                    'PoP_WebPlatformQueryDataComponentProcessorBase:module-resources',
                    array(),
                    $componentVariation,
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

    // function getDynamicModulesResources(array $componentVariation, array &$props) {
    function getDynamicResourcesMergedmoduletree(array $componentVariation, array &$props) {

        $modulefilter_manager = ComponentFilterManagerFacade::getInstance();
        $moduleFullName = \PoP\ComponentModel\Facades\Modules\ModuleHelpersFacade::getInstance()->getModuleFullName($componentVariation);

        // If the module path has been set to true, then from this module downwards all modules are dynamic
        if ($this->isDynamicModule($componentVariation, $props)) {

            // If modulepaths is provided, and we haven't reached the destination module yet, then do not execute the function at this level
            if (!$modulefilter_manager->excludeModule($componentVariation, $props)) {

                return $this->getResourcesMergedmoduletree($componentVariation, $props);
            }
            else {

                return array();
            }
        }

        // If not, then keep iterating down the road
        $ret = array();

        $submodules = $this->getDecoratedcomponentProcessor($componentVariation)->getAllSubmodules($componentVariation);
        $submodules = $modulefilter_manager->removeExcludedSubmodules($componentVariation, $submodules);

        // This function must be called always, to register matching modules into requestmeta.filtermodules even when the module has no submodules
        $modulefilter_manager->prepareForPropagation($componentVariation, $props);
        foreach ($submodules as $submodule) {

            if ($submodule_ret = $this->getComponentProcessordecorator($submodule)->getDynamicResourcesMergedmoduletree($submodule, $props[$moduleFullName][ComponentModelModuleInfo::get('response-prop-submodules')])) {

                $ret = array_unique(
                    array_merge(
                        $ret,
                        $submodule_ret
                    ),
                    SORT_REGULAR
                );
            }
        }
        $modulefilter_manager->restoreFromPropagation($componentVariation, $props);

        return $ret;
    }

    function isDynamicModule(array $componentVariation, array &$props) {

        // // By default: does it have a path?
        // return $this->getDecoratedcomponentProcessor($componentVariation)->getModulePath($componentVariation, $props);
        return $this->getDecoratedcomponentProcessor($componentVariation)->getProp($componentVariation, $props, 'dynamic-module');
    }

    // function getModulesResources(array $componentVariation, array &$props) {

    //     // Return initialized empty array at the last level
    //     $ret = array();
    //     if ($resources = $this->getResources($componentVariation, $props)) {
    //         $ret[$componentVariation[1]] = $resources;
    //     }

    //     foreach ($this->getDecoratedcomponentProcessor($componentVariation)->get_descendant_modules_to_propagate($componentVariation) as $submodule) {

    //         if ($submodule_ret = $this->getComponentProcessordecorator($submodule)->getModulesResources($submodule, $props[$moduleFullName][ComponentModelModuleInfo::get('response-prop-submodules')])) {

    //             $ret = array_merge(
    //                 $ret,
    //                 $submodule_ret
    //             );
    //         }
    //     }

    //     return $ret;
    // }

    function getDynamicTemplateResourcesMergedmoduletree(array $componentVariation, array &$props) {

        $processor = $this->getDecoratedcomponentProcessor($componentVariation);
        $moduleFullName = \PoP\ComponentModel\Facades\Modules\ModuleHelpersFacade::getInstance()->getModuleFullName($componentVariation);

        // If modulepaths is provided, and we haven't reached the destination module yet, then do not execute the function at this level
        $modulefilter_manager = ComponentFilterManagerFacade::getInstance();

        // If the module path has been set to true, then from this module downwards all modules are dynamic
        if ($this->isDynamicModule($componentVariation, $props)) {

            if (!$modulefilter_manager->excludeModule($componentVariation, $props)) {

                return $processor->getTemplateResourcesMergedmoduletree($componentVariation, $props);
            }
            else {

                return array();
            }
        }

        // If not, then keep iterating down the road
        $ret = array();

        $submodules = $processor->getAllSubmodules($componentVariation);
        $submodules = $modulefilter_manager->removeExcludedSubmodules($componentVariation, $submodules);

        // This function must be called always, to register matching modules into requestmeta.filtermodules even when the module has no submodules
        $modulefilter_manager->prepareForPropagation($componentVariation, $props);
        foreach ($submodules as $submodule) {

            if ($submodule_ret = $this->getComponentProcessordecorator($submodule)->getDynamicTemplateResourcesMergedmoduletree($submodule, $props[$moduleFullName][ComponentModelModuleInfo::get('response-prop-submodules')])) {

                $ret = array_unique(
                    array_merge(
                        $ret,
                        $submodule_ret
                    ),
                    SORT_REGULAR
                );
            }
        }
        $modulefilter_manager->restoreFromPropagation($componentVariation, $props);

        return $ret;
    }
}

/**
 * Settings Initialization
 */
global $pop_resourcemoduledecoratorprocessor_manager;
$pop_resourcemoduledecoratorprocessor_manager->add(PoP_WebPlatformQueryDataComponentProcessorBase::class, PoP_ResourceModuleDecoratorProcessor::class);
// $pop_resourcemoduledecoratorprocessor_manager->add(PoP_Module_ProcessorBaseWrapper::class, PoP_ResourceModuleDecoratorProcessor::class);

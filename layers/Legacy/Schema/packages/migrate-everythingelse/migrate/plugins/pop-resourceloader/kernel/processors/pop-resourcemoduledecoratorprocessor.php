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

    function getResourcesMergedmoduletree(array $module, array &$props) {

        return array_unique(
            $this->executeOnSelfAndMergeWithComponentVariations('getResources', __FUNCTION__, $module, $props, false),
            SORT_REGULAR
        );
    }

    function getResources(array $module, array &$props) {

        $processor = $this->getDecoratedcomponentProcessor($module);

        // Allow the theme to hook in the needed CSS resources. It could be based on the $module, or its module source, then pass both these values
        global $pop_resourceloaderprocessor_manager;
        $templateResource = $processor->getTemplateResource($module, $props);
        $resourceprocessor = $pop_resourceloaderprocessor_manager->getProcessor($templateResource);
        return array_values(
            array_unique(
                \PoP\Root\App::applyFilters(
                    'PoP_WebPlatformQueryDataComponentProcessorBase:module-resources',
                    array(),
                    $module,
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

    // function getDynamicModulesResources(array $module, array &$props) {
    function getDynamicResourcesMergedmoduletree(array $module, array &$props) {

        $modulefilter_manager = ComponentFilterManagerFacade::getInstance();
        $moduleFullName = \PoP\ComponentModel\Facades\Modules\ModuleHelpersFacade::getInstance()->getModuleFullName($module);

        // If the module path has been set to true, then from this module downwards all modules are dynamic
        if ($this->isDynamicModule($module, $props)) {

            // If modulepaths is provided, and we haven't reached the destination module yet, then do not execute the function at this level
            if (!$modulefilter_manager->excludeModule($module, $props)) {

                return $this->getResourcesMergedmoduletree($module, $props);
            }
            else {

                return array();
            }
        }

        // If not, then keep iterating down the road
        $ret = array();

        $submodules = $this->getDecoratedcomponentProcessor($module)->getAllSubmodules($module);
        $submodules = $modulefilter_manager->removeExcludedSubmodules($module, $submodules);

        // This function must be called always, to register matching modules into requestmeta.filtermodules even when the module has no submodules
        $modulefilter_manager->prepareForPropagation($module, $props);
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
        $modulefilter_manager->restoreFromPropagation($module, $props);

        return $ret;
    }

    function isDynamicModule(array $module, array &$props) {

        // // By default: does it have a path?
        // return $this->getDecoratedcomponentProcessor($module)->getModulePath($module, $props);
        return $this->getDecoratedcomponentProcessor($module)->getProp($module, $props, 'dynamic-module');
    }

    // function getModulesResources(array $module, array &$props) {

    //     // Return initialized empty array at the last level
    //     $ret = array();
    //     if ($resources = $this->getResources($module, $props)) {
    //         $ret[$module[1]] = $resources;
    //     }

    //     foreach ($this->getDecoratedcomponentProcessor($module)->get_descendant_modules_to_propagate($module) as $submodule) {

    //         if ($submodule_ret = $this->getComponentProcessordecorator($submodule)->getModulesResources($submodule, $props[$moduleFullName][ComponentModelModuleInfo::get('response-prop-submodules')])) {

    //             $ret = array_merge(
    //                 $ret,
    //                 $submodule_ret
    //             );
    //         }
    //     }

    //     return $ret;
    // }

    function getDynamicTemplateResourcesMergedmoduletree(array $module, array &$props) {

        $processor = $this->getDecoratedcomponentProcessor($module);
        $moduleFullName = \PoP\ComponentModel\Facades\Modules\ModuleHelpersFacade::getInstance()->getModuleFullName($module);

        // If modulepaths is provided, and we haven't reached the destination module yet, then do not execute the function at this level
        $modulefilter_manager = ComponentFilterManagerFacade::getInstance();

        // If the module path has been set to true, then from this module downwards all modules are dynamic
        if ($this->isDynamicModule($module, $props)) {

            if (!$modulefilter_manager->excludeModule($module, $props)) {

                return $processor->getTemplateResourcesMergedmoduletree($module, $props);
            }
            else {

                return array();
            }
        }

        // If not, then keep iterating down the road
        $ret = array();

        $submodules = $processor->getAllSubmodules($module);
        $submodules = $modulefilter_manager->removeExcludedSubmodules($module, $submodules);

        // This function must be called always, to register matching modules into requestmeta.filtermodules even when the module has no submodules
        $modulefilter_manager->prepareForPropagation($module, $props);
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
        $modulefilter_manager->restoreFromPropagation($module, $props);

        return $ret;
    }
}

/**
 * Settings Initialization
 */
global $pop_resourcemoduledecoratorprocessor_manager;
$pop_resourcemoduledecoratorprocessor_manager->add(PoP_WebPlatformQueryDataComponentProcessorBase::class, PoP_ResourceModuleDecoratorProcessor::class);
// $pop_resourcemoduledecoratorprocessor_manager->add(PoP_Module_ProcessorBaseWrapper::class, PoP_ResourceModuleDecoratorProcessor::class);

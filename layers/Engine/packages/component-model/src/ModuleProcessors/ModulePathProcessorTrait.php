<?php

declare(strict_types=1);

namespace PoP\ComponentModel\ModuleProcessors;

use PoP\ComponentModel\Facades\ModuleFiltering\ModuleFilterManagerFacade;
use PoP\ComponentModel\Facades\ModuleProcessors\ModuleProcessorManagerFacade;
use PoP\ComponentModel\Modules\ModuleUtils;

trait ModulePathProcessorTrait
{
    protected function getModuleProcessor(array $module)
    {
        $moduleprocessor_manager = ModuleProcessorManagerFacade::getInstance();
        return $moduleprocessor_manager->getProcessor($module);
    }

    protected function executeOnSelfAndPropagateToDatasetmodules($eval_self_fn, $propagate_fn, array $module, array &$props, array $data_properties, $dataaccess_checkpoint_validation, $actionexecution_checkpoint_validation, $executed, $dbobjectids)
    {
        $ret = array();
        $key = ModuleUtils::getModuleOutputName($module);
        $moduleFullName = ModuleUtils::getModuleFullName($module);

        // If modulepaths is provided, and we haven't reached the destination module yet, then do not execute the function at this level
        $modulefilter_manager = ModuleFilterManagerFacade::getInstance();
        if (!$modulefilter_manager->excludeModule($module, $props)) {
            if ($module_ret = $this->$eval_self_fn($module, $props, $data_properties, $dataaccess_checkpoint_validation, $actionexecution_checkpoint_validation, $executed, $dbobjectids)) {
                $ret[$key] = $module_ret;
            }
        }

        // Stop iterating when the submodule starts a new cycle of loading data
        $submodules = array_filter($this->getAllSubmodules($module), function ($submodule) {
            return !$this->getModuleProcessor($submodule)->startDataloadingSection($submodule);
        });
        $submodules = $modulefilter_manager->removeExcludedSubmodules($module, $submodules);

        // This function must be called always, to register matching modules into requestmeta.filtermodules even when the module has no submodules
        $modulefilter_manager->prepareForPropagation($module, $props);
        $submodules_ret = array();
        foreach ($submodules as $submodule) {
            $submodules_ret = array_merge(
                $submodules_ret,
                $this->getModuleProcessor($submodule)->$propagate_fn($submodule, $props[$moduleFullName][POP_PROPS_SUBMODULES], $data_properties, $dataaccess_checkpoint_validation, $actionexecution_checkpoint_validation, $executed, $dbobjectids)
            );
        }
        if ($submodules_ret) {
            $ret[$key][GD_JS_SUBMODULES] = $submodules_ret;
        }
        $modulefilter_manager->restoreFromPropagation($module, $props);

        return $ret;
    }

    protected function executeOnSelfAndMergeWithDatasetmodules($eval_self_fn, $propagate_fn, array $module, array &$props, array $data_properties, $dataaccess_checkpoint_validation, $actionexecution_checkpoint_validation, $executed, $dbobjectids)
    {
        $moduleFullName = ModuleUtils::getModuleFullName($module);

        // If modulepaths is provided, and we haven't reached the destination module yet, then do not execute the function at this level
        $modulefilter_manager = ModuleFilterManagerFacade::getInstance();
        if (!$modulefilter_manager->excludeModule($module, $props)) {
            $ret = $this->$eval_self_fn($module, $props, $data_properties, $dataaccess_checkpoint_validation, $actionexecution_checkpoint_validation, $executed, $dbobjectids);
        } else {
            $ret = array();
        }

        // Stop iterating when the submodule starts a new cycle of loading data
        $submodules = array_filter($this->getAllSubmodules($module), function ($submodule) {
            return !$this->getModuleProcessor($submodule)->startDataloadingSection($submodule);
        });
        $submodules = $modulefilter_manager->removeExcludedSubmodules($module, $submodules);

        // This function must be called always, to register matching modules into requestmeta.filtermodules even when the module has no submodules
        $modulefilter_manager->prepareForPropagation($module, $props);
        foreach ($submodules as $submodule) {
            $ret = array_merge_recursive(
                $ret,
                $this->getModuleProcessor($submodule)->$propagate_fn($submodule, $props[$moduleFullName][POP_PROPS_SUBMODULES], $data_properties, $dataaccess_checkpoint_validation, $actionexecution_checkpoint_validation, $executed, $dbobjectids)
            );
        }
        $modulefilter_manager->restoreFromPropagation($module, $props);

        return $ret;
    }

    // $use_module_output_name_as_key: For response structures (eg: configuration, feedback, etc) must be true
    // for internal structures (eg: $props, $data_properties) no need
    protected function executeOnSelfAndPropagateToModules($eval_self_fn, $propagate_fn, array $module, array &$props, $use_module_output_name_as_key = true, $options = array())
    {
        $ret = array();
        $moduleFullName = ModuleUtils::getModuleFullName($module);
        $key = $use_module_output_name_as_key ? ModuleUtils::getModuleOutputName($module) : $moduleFullName;

        // If modulepaths is provided, and we haven't reached the destination module yet, then do not execute the function at this level
        $modulefilter_manager = ModuleFilterManagerFacade::getInstance();
        if (!$modulefilter_manager->excludeModule($module, $props)) {
            // Maybe only execute function on the dataloading modules
            if (!isset($options['only-execute-on-dataloading-modules']) || !$options['only-execute-on-dataloading-modules'] || $this->getModuleProcessor($module)->startDataloadingSection($module)) {
                if ($module_ret = $this->$eval_self_fn($module, $props)) {
                    $ret[$key] = $module_ret;
                }
            }
        }

        $submodules = $this->getAllSubmodules($module);
        $submodules = $modulefilter_manager->removeExcludedSubmodules($module, $submodules);

        // This function must be called always, to register matching modules into requestmeta.filtermodules even when the module has no submodules
        $modulefilter_manager->prepareForPropagation($module, $props);
        $submodules_ret = array();
        foreach ($submodules as $submodule) {
            $submodules_ret = array_merge(
                $submodules_ret,
                $this->getModuleProcessor($submodule)->$propagate_fn($submodule, $props[$moduleFullName][POP_PROPS_SUBMODULES])
            );
        }
        if ($submodules_ret) {
            $ret[$key][GD_JS_SUBMODULES] = $submodules_ret;
        }
        $modulefilter_manager->restoreFromPropagation($module, $props);

        return $ret;
    }

    protected function executeOnSelfAndMergeWithModules($eval_self_fn, $propagate_fn, array $module, array &$props, $recursive = true)
    {
        $moduleFullName = ModuleUtils::getModuleFullName($module);

        // If modulepaths is provided, and we haven't reached the destination module yet, then do not execute the function at this level
        $modulefilter_manager = ModuleFilterManagerFacade::getInstance();
        if (!$modulefilter_manager->excludeModule($module, $props)) {
            $ret = $this->$eval_self_fn($module, $props);
        } else {
            $ret = array();
        }

        $submodules = $this->getAllSubmodules($module);
        $submodules = $modulefilter_manager->removeExcludedSubmodules($module, $submodules);

        // This function must be called always, to register matching modules into requestmeta.filtermodules even when the module has no submodules
        $modulefilter_manager->prepareForPropagation($module, $props);
        foreach ($submodules as $submodule) {
            $submodule_ret = $this->getModuleProcessor($submodule)->$propagate_fn($submodule, $props[$moduleFullName][POP_PROPS_SUBMODULES], $recursive);
            $ret = $recursive ?
                array_merge_recursive(
                    $ret,
                    $submodule_ret
                ) :
                array_unique(
                    array_values(
                        array_merge(
                            $ret,
                            $submodule_ret
                        )
                    )
                );
        }
        $modulefilter_manager->restoreFromPropagation($module, $props);

        return $ret;
    }
}

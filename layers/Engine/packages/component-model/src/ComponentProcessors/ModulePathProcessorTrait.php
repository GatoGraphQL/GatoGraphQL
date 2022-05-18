<?php

declare(strict_types=1);

namespace PoP\ComponentModel\ComponentProcessors;

use PoP\ComponentModel\Module;
use PoP\ComponentModel\ModuleInfo;
use PoP\ComponentModel\Constants\Props;
use PoP\Root\Feedback\FeedbackItemResolution;
use PoP\ComponentModel\ModuleFiltering\ModuleFilterManagerInterface;
use PoP\ComponentModel\Modules\ModuleHelpersInterface;
use PoP\Root\App;

trait ModulePathProcessorTrait
{
    abstract protected function getComponentProcessorManager(): ComponentProcessorManagerInterface;
    abstract protected function getModuleFilterManager(): ModuleFilterManagerInterface;
    abstract protected function getModuleHelpers(): ModuleHelpersInterface;

    protected function getComponentProcessor(array $componentVariation)
    {
        return $this->getComponentProcessorManager()->getProcessor($componentVariation);
    }

    protected function executeOnSelfAndPropagateToDatasetmodules($eval_self_fn, $propagate_fn, array $componentVariation, array &$props, array $data_properties, ?FeedbackItemResolution $dataaccess_checkpoint_validation, ?FeedbackItemResolution $actionexecution_checkpoint_validation, ?array $executed, array $dbobjectids)
    {
        $ret = [];
        $key = $this->getModuleHelpers()->getModuleOutputName($componentVariation);
        $moduleFullName = $this->getModuleHelpers()->getModuleFullName($componentVariation);

        // If modulepaths is provided, and we haven't reached the destination module yet, then do not execute the function at this level
        if (!$this->getModuleFilterManager()->excludeModule($componentVariation, $props)) {
            if ($module_ret = $this->$eval_self_fn($componentVariation, $props, $data_properties, $dataaccess_checkpoint_validation, $actionexecution_checkpoint_validation, $executed, $dbobjectids)) {
                $ret[$key] = $module_ret;
            }
        }

        // Stop iterating when the submodule starts a new cycle of loading data
        $submodules = array_filter($this->getAllSubmodules($componentVariation), function ($submodule) {
            return !$this->getComponentProcessor($submodule)->startDataloadingSection($submodule);
        });
        $submodules = $this->getModuleFilterManager()->removeExcludedSubmodules($componentVariation, $submodules);

        // This function must be called always, to register matching modules into requestmeta.filtermodules even when the module has no submodules
        $this->getModuleFilterManager()->prepareForPropagation($componentVariation, $props);
        $submodules_ret = array();
        foreach ($submodules as $submodule) {
            $submodules_ret = array_merge(
                $submodules_ret,
                $this->getComponentProcessor($submodule)->$propagate_fn($submodule, $props[$moduleFullName][Props::SUBMODULES], $data_properties, $dataaccess_checkpoint_validation, $actionexecution_checkpoint_validation, $executed, $dbobjectids)
            );
        }
        if ($submodules_ret) {
            /** @var ModuleInfo */
            $moduleInfo = App::getModule(Module::class)->getInfo();
            $submodulesOutputProperty = $moduleInfo->getSubmodulesOutputProperty();
            $ret[$key][$submodulesOutputProperty] = $submodules_ret;
        }
        $this->getModuleFilterManager()->restoreFromPropagation($componentVariation, $props);

        return $ret;
    }

    protected function executeOnSelfAndMergeWithDatasetmodules($eval_self_fn, $propagate_fn, array $componentVariation, array &$props, array $data_properties, ?FeedbackItemResolution $dataaccess_checkpoint_validation, ?FeedbackItemResolution $actionexecution_checkpoint_validation, ?array $executed, array $dbobjectids)
    {
        $moduleFullName = $this->getModuleHelpers()->getModuleFullName($componentVariation);

        // If modulepaths is provided, and we haven't reached the destination module yet, then do not execute the function at this level
        if (!$this->getModuleFilterManager()->excludeModule($componentVariation, $props)) {
            $ret = $this->$eval_self_fn($componentVariation, $props, $data_properties, $dataaccess_checkpoint_validation, $actionexecution_checkpoint_validation, $executed, $dbobjectids);
        } else {
            $ret = [];
        }

        // Stop iterating when the submodule starts a new cycle of loading data
        $submodules = array_filter($this->getAllSubmodules($componentVariation), function ($submodule) {
            return !$this->getComponentProcessor($submodule)->startDataloadingSection($submodule);
        });
        $submodules = $this->getModuleFilterManager()->removeExcludedSubmodules($componentVariation, $submodules);

        // This function must be called always, to register matching modules into requestmeta.filtermodules even when the module has no submodules
        $this->getModuleFilterManager()->prepareForPropagation($componentVariation, $props);
        foreach ($submodules as $submodule) {
            $ret = array_merge_recursive(
                $ret,
                $this->getComponentProcessor($submodule)->$propagate_fn($submodule, $props[$moduleFullName][Props::SUBMODULES], $data_properties, $dataaccess_checkpoint_validation, $actionexecution_checkpoint_validation, $executed, $dbobjectids)
            );
        }
        $this->getModuleFilterManager()->restoreFromPropagation($componentVariation, $props);

        return $ret;
    }

    // $use_module_output_name_as_key: For response structures (eg: configuration, feedback, etc) must be true
    // for internal structures (eg: $props, $data_properties) no need
    protected function executeOnSelfAndPropagateToModules($eval_self_fn, $propagate_fn, array $componentVariation, array &$props, $use_module_output_name_as_key = true, $options = array())
    {
        $ret = [];
        $moduleFullName = $this->getModuleHelpers()->getModuleFullName($componentVariation);
        $key = $use_module_output_name_as_key ? $this->getModuleHelpers()->getModuleOutputName($componentVariation) : $moduleFullName;

        // If modulepaths is provided, and we haven't reached the destination module yet, then do not execute the function at this level
        if (!$this->getModuleFilterManager()->excludeModule($componentVariation, $props)) {
            // Maybe only execute function on the dataloading modules
            if (!isset($options['only-execute-on-dataloading-modules']) || !$options['only-execute-on-dataloading-modules'] || $this->getComponentProcessor($componentVariation)->startDataloadingSection($componentVariation)) {
                if ($module_ret = $this->$eval_self_fn($componentVariation, $props)) {
                    $ret[$key] = $module_ret;
                }
            }
        }

        $submodules = $this->getAllSubmodules($componentVariation);
        $submodules = $this->getModuleFilterManager()->removeExcludedSubmodules($componentVariation, $submodules);

        // This function must be called always, to register matching modules into requestmeta.filtermodules even when the module has no submodules
        $this->getModuleFilterManager()->prepareForPropagation($componentVariation, $props);
        $submodules_ret = array();
        foreach ($submodules as $submodule) {
            $submodules_ret = array_merge(
                $submodules_ret,
                $this->getComponentProcessor($submodule)->$propagate_fn($submodule, $props[$moduleFullName][Props::SUBMODULES])
            );
        }
        if ($submodules_ret) {
            /** @var ModuleInfo */
            $moduleInfo = App::getModule(Module::class)->getInfo();
            $submodulesOutputProperty = $moduleInfo->getSubmodulesOutputProperty();
            $ret[$key][$submodulesOutputProperty] = $submodules_ret;
        }
        $this->getModuleFilterManager()->restoreFromPropagation($componentVariation, $props);

        return $ret;
    }

    protected function executeOnSelfAndMergeWithModules($eval_self_fn, $propagate_fn, array $componentVariation, array &$props, $recursive = true)
    {
        $moduleFullName = $this->getModuleHelpers()->getModuleFullName($componentVariation);

        // If modulepaths is provided, and we haven't reached the destination module yet, then do not execute the function at this level
        if (!$this->getModuleFilterManager()->excludeModule($componentVariation, $props)) {
            $ret = $this->$eval_self_fn($componentVariation, $props);
        } else {
            $ret = [];
        }

        $submodules = $this->getAllSubmodules($componentVariation);
        $submodules = $this->getModuleFilterManager()->removeExcludedSubmodules($componentVariation, $submodules);

        // This function must be called always, to register matching modules into requestmeta.filtermodules even when the module has no submodules
        $this->getModuleFilterManager()->prepareForPropagation($componentVariation, $props);
        foreach ($submodules as $submodule) {
            $submodule_ret = $this->getComponentProcessor($submodule)->$propagate_fn($submodule, $props[$moduleFullName][Props::SUBMODULES], $recursive);
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
        $this->getModuleFilterManager()->restoreFromPropagation($componentVariation, $props);

        return $ret;
    }
}

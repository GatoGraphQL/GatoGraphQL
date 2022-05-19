<?php

declare(strict_types=1);

namespace PoP\ComponentModel\ComponentProcessors;

use PoP\ComponentModel\Module;
use PoP\ComponentModel\ModuleInfo;
use PoP\ComponentModel\Constants\Props;
use PoP\Root\Feedback\FeedbackItemResolution;
use PoP\ComponentModel\ComponentFiltering\ComponentFilterManagerInterface;
use PoP\ComponentModel\Modules\ModuleHelpersInterface;
use PoP\Root\App;

trait ModulePathProcessorTrait
{
    abstract protected function getComponentProcessorManager(): ComponentProcessorManagerInterface;
    abstract protected function getComponentFilterManager(): ComponentFilterManagerInterface;
    abstract protected function getModuleHelpers(): ModuleHelpersInterface;

    protected function getComponentProcessor(array $component)
    {
        return $this->getComponentProcessorManager()->getProcessor($component);
    }

    protected function executeOnSelfAndPropagateToDatasetmodules($eval_self_fn, $propagate_fn, array $component, array &$props, array $data_properties, ?FeedbackItemResolution $dataaccess_checkpoint_validation, ?FeedbackItemResolution $actionexecution_checkpoint_validation, ?array $executed, array $dbobjectids)
    {
        $ret = [];
        $key = $this->getModuleHelpers()->getModuleOutputName($component);
        $componentFullName = $this->getModuleHelpers()->getModuleFullName($component);

        // If componentPaths is provided, and we haven't reached the destination component yet, then do not execute the function at this level
        if (!$this->getComponentFilterManager()->excludeSubcomponent($component, $props)) {
            if ($component_ret = $this->$eval_self_fn($component, $props, $data_properties, $dataaccess_checkpoint_validation, $actionexecution_checkpoint_validation, $executed, $dbobjectids)) {
                $ret[$key] = $component_ret;
            }
        }

        // Stop iterating when the subcomponent starts a new cycle of loading data
        $subComponents = array_filter($this->getAllSubcomponents($component), function ($subComponent) {
            return !$this->getComponentProcessor($subComponent)->startDataloadingSection($subComponent);
        });
        $subComponents = $this->getComponentFilterManager()->removeExcludedSubcomponents($component, $subComponents);

        // This function must be called always, to register matching modules into requestmeta.filtermodules even when the component has no subcomponents
        $this->getComponentFilterManager()->prepareForPropagation($component, $props);
        $subcomponents_ret = array();
        foreach ($subComponents as $subComponent) {
            $subcomponents_ret = array_merge(
                $subcomponents_ret,
                $this->getComponentProcessor($subComponent)->$propagate_fn($subComponent, $props[$componentFullName][Props::SUBCOMPONENTS], $data_properties, $dataaccess_checkpoint_validation, $actionexecution_checkpoint_validation, $executed, $dbobjectids)
            );
        }
        if ($subcomponents_ret) {
            /** @var ModuleInfo */
            $moduleInfo = App::getModule(Module::class)->getInfo();
            $subcomponentsOutputProperty = $moduleInfo->getSubcomponentsOutputProperty();
            $ret[$key][$subcomponentsOutputProperty] = $subcomponents_ret;
        }
        $this->getComponentFilterManager()->restoreFromPropagation($component, $props);

        return $ret;
    }

    protected function executeOnSelfAndMergeWithDatasetmodules($eval_self_fn, $propagate_fn, array $component, array &$props, array $data_properties, ?FeedbackItemResolution $dataaccess_checkpoint_validation, ?FeedbackItemResolution $actionexecution_checkpoint_validation, ?array $executed, array $dbobjectids)
    {
        $componentFullName = $this->getModuleHelpers()->getModuleFullName($component);

        // If componentPaths is provided, and we haven't reached the destination component yet, then do not execute the function at this level
        if (!$this->getComponentFilterManager()->excludeSubcomponent($component, $props)) {
            $ret = $this->$eval_self_fn($component, $props, $data_properties, $dataaccess_checkpoint_validation, $actionexecution_checkpoint_validation, $executed, $dbobjectids);
        } else {
            $ret = [];
        }

        // Stop iterating when the subcomponent starts a new cycle of loading data
        $subComponents = array_filter($this->getAllSubcomponents($component), function ($subComponent) {
            return !$this->getComponentProcessor($subComponent)->startDataloadingSection($subComponent);
        });
        $subComponents = $this->getComponentFilterManager()->removeExcludedSubcomponents($component, $subComponents);

        // This function must be called always, to register matching modules into requestmeta.filtermodules even when the component has no subcomponents
        $this->getComponentFilterManager()->prepareForPropagation($component, $props);
        foreach ($subComponents as $subComponent) {
            $ret = array_merge_recursive(
                $ret,
                $this->getComponentProcessor($subComponent)->$propagate_fn($subComponent, $props[$componentFullName][Props::SUBCOMPONENTS], $data_properties, $dataaccess_checkpoint_validation, $actionexecution_checkpoint_validation, $executed, $dbobjectids)
            );
        }
        $this->getComponentFilterManager()->restoreFromPropagation($component, $props);

        return $ret;
    }

    // $use_component_output_name_as_key: For response structures (eg: configuration, feedback, etc) must be true
    // for internal structures (eg: $props, $data_properties) no need
    protected function executeOnSelfAndPropagateToComponents($eval_self_fn, $propagate_fn, array $component, array &$props, $use_component_output_name_as_key = true, $options = array())
    {
        $ret = [];
        $componentFullName = $this->getModuleHelpers()->getModuleFullName($component);
        $key = $use_component_output_name_as_key ? $this->getModuleHelpers()->getModuleOutputName($component) : $componentFullName;

        // If componentPaths is provided, and we haven't reached the destination component yet, then do not execute the function at this level
        if (!$this->getComponentFilterManager()->excludeSubcomponent($component, $props)) {
            // Maybe only execute function on the dataloading modules
            if (!isset($options['only-execute-on-dataloading-components']) || !$options['only-execute-on-dataloading-components'] || $this->getComponentProcessor($component)->startDataloadingSection($component)) {
                if ($component_ret = $this->$eval_self_fn($component, $props)) {
                    $ret[$key] = $component_ret;
                }
            }
        }

        $subComponents = $this->getAllSubcomponents($component);
        $subComponents = $this->getComponentFilterManager()->removeExcludedSubcomponents($component, $subComponents);

        // This function must be called always, to register matching modules into requestmeta.filtermodules even when the component has no subcomponents
        $this->getComponentFilterManager()->prepareForPropagation($component, $props);
        $subcomponents_ret = array();
        foreach ($subComponents as $subComponent) {
            $subcomponents_ret = array_merge(
                $subcomponents_ret,
                $this->getComponentProcessor($subComponent)->$propagate_fn($subComponent, $props[$componentFullName][Props::SUBCOMPONENTS])
            );
        }
        if ($subcomponents_ret) {
            /** @var ModuleInfo */
            $moduleInfo = App::getModule(Module::class)->getInfo();
            $subcomponentsOutputProperty = $moduleInfo->getSubcomponentsOutputProperty();
            $ret[$key][$subcomponentsOutputProperty] = $subcomponents_ret;
        }
        $this->getComponentFilterManager()->restoreFromPropagation($component, $props);

        return $ret;
    }

    protected function executeOnSelfAndMergeWithComponents($eval_self_fn, $propagate_fn, array $component, array &$props, $recursive = true)
    {
        $componentFullName = $this->getModuleHelpers()->getModuleFullName($component);

        // If componentPaths is provided, and we haven't reached the destination component yet, then do not execute the function at this level
        if (!$this->getComponentFilterManager()->excludeSubcomponent($component, $props)) {
            $ret = $this->$eval_self_fn($component, $props);
        } else {
            $ret = [];
        }

        $subComponents = $this->getAllSubcomponents($component);
        $subComponents = $this->getComponentFilterManager()->removeExcludedSubcomponents($component, $subComponents);

        // This function must be called always, to register matching modules into requestmeta.filtermodules even when the component has no subcomponents
        $this->getComponentFilterManager()->prepareForPropagation($component, $props);
        foreach ($subComponents as $subComponent) {
            $subcomponent_ret = $this->getComponentProcessor($subComponent)->$propagate_fn($subComponent, $props[$componentFullName][Props::SUBCOMPONENTS], $recursive);
            $ret = $recursive ?
                array_merge_recursive(
                    $ret,
                    $subcomponent_ret
                ) :
                array_unique(
                    array_values(
                        array_merge(
                            $ret,
                            $subcomponent_ret
                        )
                    )
                );
        }
        $this->getComponentFilterManager()->restoreFromPropagation($component, $props);

        return $ret;
    }
}

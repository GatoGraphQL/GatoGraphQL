<?php

declare(strict_types=1);

namespace PoP\ComponentModel\ComponentProcessors;

use PoP\ComponentModel\Component\Component;
use PoP\ComponentModel\ComponentFiltering\ComponentFilterManagerInterface;
use PoP\ComponentModel\ComponentHelpers\ComponentHelpersInterface;
use PoP\ComponentModel\Constants\Props;
use PoP\ComponentModel\Module;
use PoP\ComponentModel\ModuleInfo;
use PoP\Root\App;
use PoP\ComponentModel\Feedback\FeedbackItemResolution;

trait ComponentPathProcessorTrait
{
    abstract protected function getComponentProcessorManager(): ComponentProcessorManagerInterface;
    abstract protected function getComponentFilterManager(): ComponentFilterManagerInterface;
    abstract protected function getComponentHelpers(): ComponentHelpersInterface;

    protected function getComponentProcessor(Component $component): ComponentProcessorInterface
    {
        return $this->getComponentProcessorManager()->getComponentProcessor($component);
    }

    /**
     * @return array<int|string,mixed>
     * @param array<string,mixed> $props
     * @param array<string,mixed> $data_properties
     * @param array<string|int> $objectIDs
     * @param array<string,mixed>|null $executed
     */
    protected function executeOnSelfAndPropagateToDatasetComponents(string $eval_self_fn, string $propagate_fn, Component $component, array &$props, array $data_properties, ?FeedbackItemResolution $dataaccess_checkpoint_validation, ?FeedbackItemResolution $actionexecution_checkpoint_validation, ?array $executed, array $objectIDs): array
    {
        $ret = [];
        $key = $this->getComponentHelpers()->getComponentOutputName($component);
        $componentFullName = $this->getComponentHelpers()->getComponentFullName($component);

        // If componentPaths is provided, and we haven't reached the destination component yet, then do not execute the function at this level
        if (!$this->getComponentFilterManager()->excludeSubcomponent($component, $props)) {
            if ($component_ret = $this->$eval_self_fn($component, $props, $data_properties, $dataaccess_checkpoint_validation, $actionexecution_checkpoint_validation, $executed, $objectIDs)) {
                $ret[$key] = $component_ret;
            }
        }

        // Stop iterating when the subcomponent starts a new cycle of loading data
        $subcomponents = array_filter($this->getAllSubcomponents($component), function ($subcomponent): bool {
            return !$this->getComponentProcessor($subcomponent)->startDataloadingSection($subcomponent);
        });
        $subcomponents = $this->getComponentFilterManager()->removeExcludedSubcomponents($component, $subcomponents);

        // This function must be called always, to register matching modules into requestmeta.filtermodules even when the component has no subcomponents
        $this->getComponentFilterManager()->prepareForPropagation($component, $props);
        $subcomponents_ret = array();
        foreach ($subcomponents as $subcomponent) {
            $subcomponents_ret = array_merge(
                $subcomponents_ret,
                $this->getComponentProcessor($subcomponent)->$propagate_fn($subcomponent, $props[$componentFullName][Props::SUBCOMPONENTS], $data_properties, $dataaccess_checkpoint_validation, $actionexecution_checkpoint_validation, $executed, $objectIDs)
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

    /**
     * @return mixed[]
     * @param array<string,mixed> $props
     * @param array<string,mixed> $data_properties
     * @param array<string|int> $objectIDs
     * @param array<string,mixed>|null $executed
     */
    protected function executeOnSelfAndMergeWithDatasetComponents(string $eval_self_fn, string $propagate_fn, Component $component, array &$props, array $data_properties, ?FeedbackItemResolution $dataaccess_checkpoint_validation, ?FeedbackItemResolution $actionexecution_checkpoint_validation, ?array $executed, array $objectIDs): array
    {
        $componentFullName = $this->getComponentHelpers()->getComponentFullName($component);

        // If componentPaths is provided, and we haven't reached the destination component yet, then do not execute the function at this level
        if (!$this->getComponentFilterManager()->excludeSubcomponent($component, $props)) {
            $ret = $this->$eval_self_fn($component, $props, $data_properties, $dataaccess_checkpoint_validation, $actionexecution_checkpoint_validation, $executed, $objectIDs);
        } else {
            $ret = [];
        }

        // Stop iterating when the subcomponent starts a new cycle of loading data
        $subcomponents = array_filter($this->getAllSubcomponents($component), function ($subcomponent): bool {
            return !$this->getComponentProcessor($subcomponent)->startDataloadingSection($subcomponent);
        });
        $subcomponents = $this->getComponentFilterManager()->removeExcludedSubcomponents($component, $subcomponents);

        // This function must be called always, to register matching modules into requestmeta.filtermodules even when the component has no subcomponents
        $this->getComponentFilterManager()->prepareForPropagation($component, $props);
        foreach ($subcomponents as $subcomponent) {
            $ret = array_merge_recursive(
                $ret,
                $this->getComponentProcessor($subcomponent)->$propagate_fn($subcomponent, $props[$componentFullName][Props::SUBCOMPONENTS], $data_properties, $dataaccess_checkpoint_validation, $actionexecution_checkpoint_validation, $executed, $objectIDs)
            );
        }
        $this->getComponentFilterManager()->restoreFromPropagation($component, $props);

        return $ret;
    }

    /**
     * @param string $eval_self_fn Function name
     * @param string $propagate_fn Function name
     * @param boolean $use_component_output_name_as_key For response structures (eg: configuration, feedback, etc) must be `true`, for internal structures (eg: $props, $data_properties) no need
     * @return mixed[]
     * @param array<string,mixed> $props
     * @param array<string,mixed> $options
     */
    protected function executeOnSelfAndPropagateToComponents(string $eval_self_fn, string $propagate_fn, Component $component, array &$props, bool $use_component_output_name_as_key = true, array $options = array()): array
    {
        $ret = [];
        $componentFullName = $this->getComponentHelpers()->getComponentFullName($component);
        $key = $use_component_output_name_as_key ? $this->getComponentHelpers()->getComponentOutputName($component) : $componentFullName;

        // If componentPaths is provided, and we haven't reached the destination component yet, then do not execute the function at this level
        if (!$this->getComponentFilterManager()->excludeSubcomponent($component, $props)) {
            // Maybe only execute function on the dataloading modules
            if (!isset($options['only-execute-on-dataloading-components']) || !$options['only-execute-on-dataloading-components'] || $this->getComponentProcessor($component)->startDataloadingSection($component)) {
                if ($component_ret = $this->$eval_self_fn($component, $props)) {
                    $ret[$key] = $component_ret;
                }
            }
        }

        $subcomponents = $this->getAllSubcomponents($component);
        $subcomponents = $this->getComponentFilterManager()->removeExcludedSubcomponents($component, $subcomponents);

        // This function must be called always, to register matching modules into requestmeta.filtermodules even when the component has no subcomponents
        $this->getComponentFilterManager()->prepareForPropagation($component, $props);
        $subcomponents_ret = array();
        foreach ($subcomponents as $subcomponent) {
            $subcomponents_ret = array_merge(
                $subcomponents_ret,
                $this->getComponentProcessor($subcomponent)->$propagate_fn($subcomponent, $props[$componentFullName][Props::SUBCOMPONENTS])
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

    /**
     * @return mixed[]
     * @param array<string,mixed> $props
     */
    protected function executeOnSelfAndMergeWithComponents(string $eval_self_fn, string $propagate_fn, Component $component, array &$props, bool $recursive = true): array
    {
        $componentFullName = $this->getComponentHelpers()->getComponentFullName($component);

        // If componentPaths is provided, and we haven't reached the destination component yet, then do not execute the function at this level
        if (!$this->getComponentFilterManager()->excludeSubcomponent($component, $props)) {
            $ret = $this->$eval_self_fn($component, $props);
        } else {
            $ret = [];
        }

        $subcomponents = $this->getAllSubcomponents($component);
        $subcomponents = $this->getComponentFilterManager()->removeExcludedSubcomponents($component, $subcomponents);

        // This function must be called always, to register matching modules into requestmeta.filtermodules even when the component has no subcomponents
        $this->getComponentFilterManager()->prepareForPropagation($component, $props);
        foreach ($subcomponents as $subcomponent) {
            $subcomponent_ret = $this->getComponentProcessor($subcomponent)->$propagate_fn($subcomponent, $props[$componentFullName][Props::SUBCOMPONENTS], $recursive);
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

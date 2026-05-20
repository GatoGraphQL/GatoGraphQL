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
        $componentHelpers = $this->getComponentHelpers();
        $key = $componentHelpers->getComponentOutputName($component);
        $componentFullName = $componentHelpers->getComponentFullName($component);
        $componentFilterManager = $this->getComponentFilterManager();
        $componentProcessorManager = $this->getComponentProcessorManager();

        // If componentPaths is provided, and we haven't reached the destination component yet, then do not execute the function at this level
        if (!$componentFilterManager->excludeSubcomponent($component, $props)) {
            if ($component_ret = $this->$eval_self_fn($component, $props, $data_properties, $dataaccess_checkpoint_validation, $actionexecution_checkpoint_validation, $executed, $objectIDs)) {
                $ret[$key] = $component_ret;
            }
        }

        $subcomponents = $componentFilterManager->removeExcludedSubcomponents($component, $this->getAllSubcomponents($component));

        // This function must be called always, to register matching modules into requestmeta.filtermodules even when the component has no subcomponents
        $componentFilterManager->prepareForPropagation($component, $props);
        $subcomponents_ret = array();
        $subProps = &$props[$componentFullName][Props::SUBCOMPONENTS];
        foreach ($subcomponents as $subcomponent) {
            // Stop iterating when the subcomponent starts a new cycle of loading data.
            // (Inlined from a previous `array_filter` so each kept subcomponent
            // pays only one `getComponentProcessor` lookup rather than two.)
            $subcomponentProcessor = $componentProcessorManager->getComponentProcessor($subcomponent);
            if ($subcomponentProcessor->startDataloadingSection($subcomponent)) {
                continue;
            }
            $subcomponents_ret = array_merge(
                $subcomponents_ret,
                $subcomponentProcessor->$propagate_fn($subcomponent, $subProps, $data_properties, $dataaccess_checkpoint_validation, $actionexecution_checkpoint_validation, $executed, $objectIDs)
            );
        }
        if ($subcomponents_ret) {
            /** @var ModuleInfo */
            $moduleInfo = App::getModule(Module::class)->getInfo();
            $subcomponentsOutputProperty = $moduleInfo->getSubcomponentsOutputProperty();
            $ret[$key][$subcomponentsOutputProperty] = $subcomponents_ret;
        }
        $componentFilterManager->restoreFromPropagation($component, $props);

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
        $componentFilterManager = $this->getComponentFilterManager();
        $componentProcessorManager = $this->getComponentProcessorManager();

        // If componentPaths is provided, and we haven't reached the destination component yet, then do not execute the function at this level
        if (!$componentFilterManager->excludeSubcomponent($component, $props)) {
            $ret = $this->$eval_self_fn($component, $props, $data_properties, $dataaccess_checkpoint_validation, $actionexecution_checkpoint_validation, $executed, $objectIDs);
        } else {
            $ret = [];
        }

        $subcomponents = $componentFilterManager->removeExcludedSubcomponents($component, $this->getAllSubcomponents($component));

        // This function must be called always, to register matching modules into requestmeta.filtermodules even when the component has no subcomponents
        $componentFilterManager->prepareForPropagation($component, $props);
        $subProps = &$props[$componentFullName][Props::SUBCOMPONENTS];
        foreach ($subcomponents as $subcomponent) {
            // Stop iterating when the subcomponent starts a new cycle of loading data
            // (inlined from a previous `array_filter` — see sibling method).
            $subcomponentProcessor = $componentProcessorManager->getComponentProcessor($subcomponent);
            if ($subcomponentProcessor->startDataloadingSection($subcomponent)) {
                continue;
            }
            $ret = array_merge_recursive(
                $ret,
                $subcomponentProcessor->$propagate_fn($subcomponent, $subProps, $data_properties, $dataaccess_checkpoint_validation, $actionexecution_checkpoint_validation, $executed, $objectIDs)
            );
        }
        $componentFilterManager->restoreFromPropagation($component, $props);

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
        $componentHelpers = $this->getComponentHelpers();
        $componentFullName = $componentHelpers->getComponentFullName($component);
        $key = $use_component_output_name_as_key ? $componentHelpers->getComponentOutputName($component) : $componentFullName;
        $componentFilterManager = $this->getComponentFilterManager();
        $componentProcessorManager = $this->getComponentProcessorManager();

        // If componentPaths is provided, and we haven't reached the destination component yet, then do not execute the function at this level
        if (!$componentFilterManager->excludeSubcomponent($component, $props)) {
            // Maybe only execute function on the dataloading modules
            if (!isset($options['only-execute-on-dataloading-components']) || !$options['only-execute-on-dataloading-components'] || $componentProcessorManager->getComponentProcessor($component)->startDataloadingSection($component)) {
                if ($component_ret = $this->$eval_self_fn($component, $props)) {
                    $ret[$key] = $component_ret;
                }
            }
        }

        $subcomponents = $componentFilterManager->removeExcludedSubcomponents($component, $this->getAllSubcomponents($component));

        // This function must be called always, to register matching modules into requestmeta.filtermodules even when the component has no subcomponents
        $componentFilterManager->prepareForPropagation($component, $props);
        $subProps = &$props[$componentFullName][Props::SUBCOMPONENTS];
        /**
         * Collect each subcomponent's result and merge once at the end
         * via variadic spread, instead of `array_merge` *inside* the loop
         * which reallocates an O(current accumulator) buffer per iteration
         * (quadratic). With 88K calls/request this was a primary hotspot.
         *
         * @var array<array<string,mixed>>
         */
        $subResults = [];
        foreach ($subcomponents as $subcomponent) {
            $subResult = $componentProcessorManager->getComponentProcessor($subcomponent)->$propagate_fn($subcomponent, $subProps);
            if ($subResult) {
                $subResults[] = $subResult;
            }
        }
        $subcomponents_ret = $subResults === [] ? [] : array_merge(...$subResults);
        if ($subcomponents_ret) {
            /** @var ModuleInfo */
            $moduleInfo = App::getModule(Module::class)->getInfo();
            $subcomponentsOutputProperty = $moduleInfo->getSubcomponentsOutputProperty();
            $ret[$key][$subcomponentsOutputProperty] = $subcomponents_ret;
        }
        $componentFilterManager->restoreFromPropagation($component, $props);

        return $ret;
    }

    /**
     * @return mixed[]
     * @param array<string,mixed> $props
     */
    protected function executeOnSelfAndMergeWithComponents(string $eval_self_fn, string $propagate_fn, Component $component, array &$props, bool $recursive = true): array
    {
        $componentFullName = $this->getComponentHelpers()->getComponentFullName($component);
        $componentFilterManager = $this->getComponentFilterManager();
        $componentProcessorManager = $this->getComponentProcessorManager();

        // If componentPaths is provided, and we haven't reached the destination component yet, then do not execute the function at this level
        if (!$componentFilterManager->excludeSubcomponent($component, $props)) {
            $ret = $this->$eval_self_fn($component, $props);
        } else {
            $ret = [];
        }

        $subcomponents = $componentFilterManager->removeExcludedSubcomponents($component, $this->getAllSubcomponents($component));

        // This function must be called always, to register matching modules into requestmeta.filtermodules even when the component has no subcomponents
        $componentFilterManager->prepareForPropagation($component, $props);
        $subProps = &$props[$componentFullName][Props::SUBCOMPONENTS];
        foreach ($subcomponents as $subcomponent) {
            $subcomponent_ret = $componentProcessorManager->getComponentProcessor($subcomponent)->$propagate_fn($subcomponent, $subProps, $recursive);
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
        $componentFilterManager->restoreFromPropagation($component, $props);

        return $ret;
    }
}

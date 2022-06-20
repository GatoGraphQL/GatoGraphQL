<?php

declare(strict_types=1);

namespace PoP\ConfigurationComponentModel\ComponentProcessors;

use PoP\ComponentModel\Component\Component;
use PoP\ComponentModel\Checkpoints\CheckpointInterface;
use PoP\ComponentModel\ComponentProcessors\AbstractComponentProcessor as UpstreamAbstractComponentProcessor;
use PoP\ComponentModel\ComponentProcessors\FormattableModuleInterface;
use PoP\ComponentModel\Constants\DataLoading;
use PoP\ComponentModel\Misc\GeneralUtils;
use PoP\ComponentModel\Settings\SettingsManagerFactory;
use PoP\ConfigurationComponentModel\Constants\Params;
use PoP\Definitions\Constants\Params as DefinitionsParams;
use PoP\Root\App;
use PoP\Root\Feedback\FeedbackItemResolution;

abstract class AbstractComponentProcessor extends UpstreamAbstractComponentProcessor implements ComponentProcessorInterface
{
    //-------------------------------------------------
    // New PUBLIC Functions: Model Static Settings
    //-------------------------------------------------
    public function getImmutableSettingsComponentTree(Component $component, array &$props): array
    {
        return $this->executeOnSelfAndPropagateToComponents('getImmutableSettings', __FUNCTION__, $component, $props);
    }

    public function getImmutableSettings(Component $component, array &$props): array
    {
        $ret = array();

        if ($configuration = $this->getImmutableConfiguration($component, $props)) {
            $ret['configuration'] = $configuration;
        }

        if ($outputKeys = $this->getFieldOutputKeyToTypeOutputKeys($component, $props)) {
            $ret['outputKeys'] = $outputKeys;
        }

        return $ret;
    }

    public function getImmutableConfiguration(Component $component, array &$props): array
    {
        return array();
    }

    //-------------------------------------------------
    // New PUBLIC Functions: Model Stateful Settings
    //-------------------------------------------------

    public function getMutableonmodelSettingsComponentTree(Component $component, array &$props): array
    {
        return $this->executeOnSelfAndPropagateToComponents('getMutableonmodelSettings', __FUNCTION__, $component, $props);
    }

    public function getMutableonmodelSettings(Component $component, array &$props): array
    {
        $ret = array();

        if ($configuration = $this->getMutableonmodelConfiguration($component, $props)) {
            $ret['configuration'] = $configuration;
        }

        return $ret;
    }

    public function getMutableonmodelConfiguration(Component $component, array &$props): array
    {
        return array();
    }

    //-------------------------------------------------
    // New PUBLIC Functions: Stateful Settings
    //-------------------------------------------------

    public function getMutableonrequestSettingsComponentTree(Component $component, array &$props): array
    {
        return $this->executeOnSelfAndPropagateToComponents('getMutableonrequestSettings', __FUNCTION__, $component, $props);
    }

    public function getMutableonrequestSettings(Component $component, array &$props): array
    {
        $ret = array();

        if ($configuration = $this->getMutableonrequestConfiguration($component, $props)) {
            $ret['configuration'] = $configuration;
        }

        return $ret;
    }

    public function getMutableonrequestConfiguration(Component $component, array &$props): array
    {
        return array();
    }

    //-------------------------------------------------
    // Others
    //-------------------------------------------------

    public function getRelevantRoute(Component $component, array &$props): ?string
    {
        return null;
    }

    public function getRelevantRouteCheckpointTarget(Component $component, array &$props): string
    {
        return DataLoading::DATA_ACCESS_CHECKPOINTS;
    }

    /**
     * @param CheckpointInterface[] $checkpoints
     * @return CheckpointInterface[]
     */
    protected function maybeOverrideCheckpoints(array $checkpoints): array
    {
        // Allow URE to add the extra checkpoint condition of the user having the Profile role
        return App::applyFilters(
            'ComponentProcessor:checkpoints',
            $checkpoints
        );
    }

    /**
     * @return CheckpointInterface[]
     */
    public function getDataAccessCheckpoints(Component $component, array &$props): array
    {
        if ($route = $this->getRelevantRoute($component, $props)) {
            if ($this->getRelevantRouteCheckpointTarget($component, $props) == DataLoading::DATA_ACCESS_CHECKPOINTS) {
                return $this->maybeOverrideCheckpoints(SettingsManagerFactory::getInstance()->getRouteCheckpoints($route));
            }
        }

        return parent::getDataAccessCheckpoints($component, $props);
    }

    public function getActionExecutionCheckpoints(Component $component, array &$props): array
    {
        if ($route = $this->getRelevantRoute($component, $props)) {
            if ($this->getRelevantRouteCheckpointTarget($component, $props) == DataLoading::ACTION_EXECUTION_CHECKPOINTS) {
                return $this->maybeOverrideCheckpoints(SettingsManagerFactory::getInstance()->getRouteCheckpoints($route));
            }
        }

        return parent::getActionExecutionCheckpoints($component, $props);
    }

    public function getMutableonrequestHeaddatasetcomponentDataProperties(Component $component, array &$props): array
    {
        $ret = parent::getMutableonrequestHeaddatasetcomponentDataProperties($component, $props);

        if ($dataload_source = $this->getDataloadSource($component, $props)) {
            $ret[DataloadingConstants::SOURCE] = $dataload_source;
        }

        return $ret;
    }

    public function getDatasetmeta(Component $component, array &$props, array $data_properties, ?FeedbackItemResolution $dataaccess_checkpoint_validation, ?FeedbackItemResolution $actionexecution_checkpoint_validation, ?array $executed, array $objectIDOrIDs): array
    {
        $ret = parent::getDatasetmeta($component, $props, $data_properties, $dataaccess_checkpoint_validation, $actionexecution_checkpoint_validation, $executed, $objectIDOrIDs);

        if ($dataload_source = $data_properties[DataloadingConstants::SOURCE] ?? null) {
            $ret['dataloadsource'] = $dataload_source;
        }

        return $ret;
    }

    public function getDataloadSource(Component $component, array &$props): ?string
    {
        if (!App::isHTTPRequest()) {
            return null;
        }

        // Because a component can interact with itself by adding ?componentPaths=...,
        // then, by default, we simply set the dataload source to point to itself!
        $stringified_component_propagation_current_path = $this->getComponentPathHelpers()->getStringifiedModulePropagationCurrentPath($component);
        $ret = GeneralUtils::addQueryArgs(
            [
                Params::COMPONENTFILTER => $this->getComponentPaths()->getName(),
                Params::COMPONENTPATHS . '[]' => $stringified_component_propagation_current_path,
            ],
            $this->getRequestHelperService()->getCurrentURL()
        );

        // If we are in the API currently, stay in the API
        if ($scheme = App::getState('scheme')) {
            $ret = GeneralUtils::addQueryArgs([
                Params::SCHEME => $scheme,
            ], $ret);
        }

        // Allow to add extra componentPaths set from above
        if ($extra_component_paths = $this->getProp($component, $props, 'dataload-source-add-componentPaths')) {
            foreach ($extra_component_paths as $componentPath) {
                $ret = GeneralUtils::addQueryArgs([
                    Params::COMPONENTPATHS . '[]' => $this->getComponentPathHelpers()->stringifyComponentPath($componentPath),
                ], $ret);
            }
        }

        // Add the actionpath too
        if ($this->getComponentMutationResolverBridge($component) !== null) {
            $ret = GeneralUtils::addQueryArgs([
                Params::ACTION_PATH => $stringified_component_propagation_current_path,
            ], $ret);
        }

        // If mangled, make it mandle
        if ($mangled = App::getState('mangled')) {
            $ret = GeneralUtils::addQueryArgs([
                DefinitionsParams::MANGLED => $mangled,
            ], $ret);
        }

        // Add the format to the query url
        if ($this instanceof FormattableModuleInterface) {
            if ($format = $this->getFormat($component)) {
                $ret = GeneralUtils::addQueryArgs([
                    Params::FORMAT => $format,
                ], $ret);
            }
        }

        return $ret;
    }
}

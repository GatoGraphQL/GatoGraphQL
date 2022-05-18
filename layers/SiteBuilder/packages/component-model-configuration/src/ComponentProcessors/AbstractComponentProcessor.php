<?php

declare(strict_types=1);

namespace PoP\ConfigurationComponentModel\ComponentProcessors;

use PoP\ComponentModel\Constants\DataLoading;
use PoP\Root\Feedback\FeedbackItemResolution;
use PoP\ComponentModel\Misc\GeneralUtils;
use PoP\ComponentModel\ComponentProcessors\AbstractComponentProcessor as UpstreamAbstractComponentProcessor;
use PoP\ComponentModel\ComponentProcessors\FormattableModuleInterface;
use PoP\ComponentModel\Settings\SettingsManagerFactory;
use PoP\ConfigurationComponentModel\Constants\Params;
use PoP\Definitions\Constants\Params as DefinitionsParams;
use PoP\Root\App;

abstract class AbstractComponentProcessor extends UpstreamAbstractComponentProcessor implements ComponentProcessorInterface
{
    //-------------------------------------------------
    // New PUBLIC Functions: Model Static Settings
    //-------------------------------------------------
    public function getImmutableSettingsModuletree(array $componentVariation, array &$props): array
    {
        return $this->executeOnSelfAndPropagateToComponentVariations('getImmutableSettings', __FUNCTION__, $componentVariation, $props);
    }

    public function getImmutableSettings(array $componentVariation, array &$props): array
    {
        $ret = array();

        if ($configuration = $this->getImmutableConfiguration($componentVariation, $props)) {
            $ret['configuration'] = $configuration;
        }

        if ($database_keys = $this->getDatabaseKeys($componentVariation, $props)) {
            $ret['dbkeys'] = $database_keys;
        }

        return $ret;
    }

    public function getImmutableConfiguration(array $componentVariation, array &$props): array
    {
        return array();
    }

    //-------------------------------------------------
    // New PUBLIC Functions: Model Stateful Settings
    //-------------------------------------------------

    public function getMutableonmodelSettingsModuletree(array $componentVariation, array &$props): array
    {
        return $this->executeOnSelfAndPropagateToComponentVariations('getMutableonmodelSettings', __FUNCTION__, $componentVariation, $props);
    }

    public function getMutableonmodelSettings(array $componentVariation, array &$props): array
    {
        $ret = array();

        if ($configuration = $this->getMutableonmodelConfiguration($componentVariation, $props)) {
            $ret['configuration'] = $configuration;
        }

        return $ret;
    }

    public function getMutableonmodelConfiguration(array $componentVariation, array &$props): array
    {
        return array();
    }

    //-------------------------------------------------
    // New PUBLIC Functions: Stateful Settings
    //-------------------------------------------------

    public function getMutableonrequestSettingsModuletree(array $componentVariation, array &$props): array
    {
        return $this->executeOnSelfAndPropagateToComponentVariations('getMutableonrequestSettings', __FUNCTION__, $componentVariation, $props);
    }

    public function getMutableonrequestSettings(array $componentVariation, array &$props): array
    {
        $ret = array();

        if ($configuration = $this->getMutableonrequestConfiguration($componentVariation, $props)) {
            $ret['configuration'] = $configuration;
        }

        return $ret;
    }

    public function getMutableonrequestConfiguration(array $componentVariation, array &$props): array
    {
        return array();
    }

    //-------------------------------------------------
    // Others
    //-------------------------------------------------

    public function getRelevantRoute(array $componentVariation, array &$props): ?string
    {
        return null;
    }

    public function getRelevantRouteCheckpointTarget(array $componentVariation, array &$props): string
    {
        return DataLoading::DATA_ACCESS_CHECKPOINTS;
    }

    protected function maybeOverrideCheckpoints($checkpoints)
    {
        // Allow URE to add the extra checkpoint condition of the user having the Profile role
        return App::applyFilters(
            'ComponentProcessor:checkpoints',
            $checkpoints
        );
    }

    public function getDataAccessCheckpoints(array $componentVariation, array &$props): array
    {
        if ($route = $this->getRelevantRoute($componentVariation, $props)) {
            if ($this->getRelevantRouteCheckpointTarget($componentVariation, $props) == DataLoading::DATA_ACCESS_CHECKPOINTS) {
                return $this->maybeOverrideCheckpoints(SettingsManagerFactory::getInstance()->getCheckpoints($route));
            }
        }

        return parent::getDataAccessCheckpoints($componentVariation, $props);
    }

    public function getActionExecutionCheckpoints(array $componentVariation, array &$props): array
    {
        if ($route = $this->getRelevantRoute($componentVariation, $props)) {
            if ($this->getRelevantRouteCheckpointTarget($componentVariation, $props) == DataLoading::ACTION_EXECUTION_CHECKPOINTS) {
                return $this->maybeOverrideCheckpoints(SettingsManagerFactory::getInstance()->getCheckpoints($route));
            }
        }

        return parent::getActionExecutionCheckpoints($componentVariation, $props);
    }

    public function getMutableonrequestHeaddatasetmoduleDataProperties(array $componentVariation, array &$props): array
    {
        $ret = parent::getMutableonrequestHeaddatasetmoduleDataProperties($componentVariation, $props);

        if ($dataload_source = $this->getDataloadSource($componentVariation, $props)) {
            $ret[DataloadingConstants::SOURCE] = $dataload_source;
        }

        return $ret;
    }

    public function getDatasetmeta(array $componentVariation, array &$props, array $data_properties, ?FeedbackItemResolution $dataaccess_checkpoint_validation, ?FeedbackItemResolution $actionexecution_checkpoint_validation, ?array $executed, array $dbObjectIDOrIDs): array
    {
        $ret = parent::getDatasetmeta($componentVariation, $props, $data_properties, $dataaccess_checkpoint_validation, $actionexecution_checkpoint_validation, $executed, $dbObjectIDOrIDs);

        if ($dataload_source = $data_properties[DataloadingConstants::SOURCE] ?? null) {
            $ret['dataloadsource'] = $dataload_source;
        }

        return $ret;
    }

    public function getDataloadSource(array $componentVariation, array &$props): ?string
    {
        if (!App::isHTTPRequest()) {
            return null;
        }

        // Because a component can interact with itself by adding ?componentVariationPaths=...,
        // then, by default, we simply set the dataload source to point to itself!
        $stringified_module_propagation_current_path = $this->getModulePathHelpers()->getStringifiedModulePropagationCurrentPath($componentVariation);
        $ret = GeneralUtils::addQueryArgs(
            [
                Params::MODULEFILTER => $this->getModulePaths()->getName(),
                Params::MODULEPATHS . '[]' => $stringified_module_propagation_current_path,
            ],
            $this->getRequestHelperService()->getCurrentURL()
        );

        // If we are in the API currently, stay in the API
        if ($scheme = App::getState('scheme')) {
            $ret = GeneralUtils::addQueryArgs([
                Params::SCHEME => $scheme,
            ], $ret);
        }

        // Allow to add extra componentVariationPaths set from above
        if ($extra_module_paths = $this->getProp($componentVariation, $props, 'dataload-source-add-componentVariationPaths')) {
            foreach ($extra_module_paths as $componentVariationPath) {
                $ret = GeneralUtils::addQueryArgs([
                    Params::MODULEPATHS . '[]' => $this->getModulePathHelpers()->stringifyModulePath($componentVariationPath),
                ], $ret);
            }
        }

        // Add the actionpath too
        if ($this->getComponentMutationResolverBridge($componentVariation) !== null) {
            $ret = GeneralUtils::addQueryArgs([
                Params::ACTION_PATH => $stringified_module_propagation_current_path,
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
            if ($format = $this->getFormat($componentVariation)) {
                $ret = GeneralUtils::addQueryArgs([
                    Params::FORMAT => $format,
                ], $ret);
            }
        }

        return $ret;
    }
}

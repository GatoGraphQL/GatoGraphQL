<?php

declare(strict_types=1);

namespace PoP\ConfigurationComponentModel\ModuleProcessors;

use PoP\ComponentModel\Constants\DataLoading;
use PoP\ComponentModel\Feedback\FeedbackItemResolution;
use PoP\ComponentModel\Misc\GeneralUtils;
use PoP\ComponentModel\ModuleProcessors\AbstractModuleProcessor as UpstreamAbstractModuleProcessor;
use PoP\ComponentModel\ModuleProcessors\FormattableModuleInterface;
use PoP\ComponentModel\Settings\SettingsManagerFactory;
use PoP\ConfigurationComponentModel\Constants\Params;
use PoP\Definitions\Constants\Params as DefinitionsParams;
use PoP\Root\App;

abstract class AbstractModuleProcessor extends UpstreamAbstractModuleProcessor implements ModuleProcessorInterface
{
    //-------------------------------------------------
    // New PUBLIC Functions: Model Static Settings
    //-------------------------------------------------
    public function getImmutableSettingsModuletree(array $module, array &$props): array
    {
        return $this->executeOnSelfAndPropagateToModules('getImmutableSettings', __FUNCTION__, $module, $props);
    }

    public function getImmutableSettings(array $module, array &$props): array
    {
        $ret = array();

        if ($configuration = $this->getImmutableConfiguration($module, $props)) {
            $ret['configuration'] = $configuration;
        }

        if ($database_keys = $this->getDatabaseKeys($module, $props)) {
            $ret['dbkeys'] = $database_keys;
        }

        return $ret;
    }

    public function getImmutableConfiguration(array $module, array &$props): array
    {
        return array();
    }

    //-------------------------------------------------
    // New PUBLIC Functions: Model Stateful Settings
    //-------------------------------------------------

    public function getMutableonmodelSettingsModuletree(array $module, array &$props): array
    {
        return $this->executeOnSelfAndPropagateToModules('getMutableonmodelSettings', __FUNCTION__, $module, $props);
    }

    public function getMutableonmodelSettings(array $module, array &$props): array
    {
        $ret = array();

        if ($configuration = $this->getMutableonmodelConfiguration($module, $props)) {
            $ret['configuration'] = $configuration;
        }

        return $ret;
    }

    public function getMutableonmodelConfiguration(array $module, array &$props): array
    {
        return array();
    }

    //-------------------------------------------------
    // New PUBLIC Functions: Stateful Settings
    //-------------------------------------------------

    public function getMutableonrequestSettingsModuletree(array $module, array &$props): array
    {
        return $this->executeOnSelfAndPropagateToModules('getMutableonrequestSettings', __FUNCTION__, $module, $props);
    }

    public function getMutableonrequestSettings(array $module, array &$props): array
    {
        $ret = array();

        if ($configuration = $this->getMutableonrequestConfiguration($module, $props)) {
            $ret['configuration'] = $configuration;
        }

        return $ret;
    }

    public function getMutableonrequestConfiguration(array $module, array &$props): array
    {
        return array();
    }

    //-------------------------------------------------
    // Others
    //-------------------------------------------------

    public function getRelevantRoute(array $module, array &$props): ?string
    {
        return null;
    }

    public function getRelevantRouteCheckpointTarget(array $module, array &$props): string
    {
        return DataLoading::DATA_ACCESS_CHECKPOINTS;
    }

    protected function maybeOverrideCheckpoints($checkpoints)
    {
        // Allow URE to add the extra checkpoint condition of the user having the Profile role
        return App::applyFilters(
            'ModuleProcessor:checkpoints',
            $checkpoints
        );
    }

    public function getDataAccessCheckpoints(array $module, array &$props): array
    {
        if ($route = $this->getRelevantRoute($module, $props)) {
            if ($this->getRelevantRouteCheckpointTarget($module, $props) == DataLoading::DATA_ACCESS_CHECKPOINTS) {
                return $this->maybeOverrideCheckpoints(SettingsManagerFactory::getInstance()->getCheckpoints($route));
            }
        }

        return parent::getDataAccessCheckpoints($module, $props);
    }

    public function getActionExecutionCheckpoints(array $module, array &$props): array
    {
        if ($route = $this->getRelevantRoute($module, $props)) {
            if ($this->getRelevantRouteCheckpointTarget($module, $props) == DataLoading::ACTION_EXECUTION_CHECKPOINTS) {
                return $this->maybeOverrideCheckpoints(SettingsManagerFactory::getInstance()->getCheckpoints($route));
            }
        }

        return parent::getActionExecutionCheckpoints($module, $props);
    }

    public function getMutableonrequestHeaddatasetmoduleDataProperties(array $module, array &$props): array
    {
        $ret = parent::getMutableonrequestHeaddatasetmoduleDataProperties($module, $props);

        if ($dataload_source = $this->getDataloadSource($module, $props)) {
            $ret[DataloadingConstants::SOURCE] = $dataload_source;
        }

        return $ret;
    }

    public function getDatasetmeta(array $module, array &$props, array $data_properties, ?FeedbackItemResolution $dataaccess_checkpoint_validation, ?FeedbackItemResolution $actionexecution_checkpoint_validation, ?array $executed, array $dbObjectIDOrIDs): array
    {
        $ret = parent::getDatasetmeta($module, $props, $data_properties, $dataaccess_checkpoint_validation, $actionexecution_checkpoint_validation, $executed, $dbObjectIDOrIDs);

        if ($dataload_source = $data_properties[DataloadingConstants::SOURCE] ?? null) {
            $ret['dataloadsource'] = $dataload_source;
        }

        return $ret;
    }

    public function getDataloadSource(array $module, array &$props): ?string
    {
        if (!App::isHTTPRequest()) {
            return null;
        }

        // Because a component can interact with itself by adding ?modulepaths=...,
        // then, by default, we simply set the dataload source to point to itself!
        $stringified_module_propagation_current_path = $this->getModulePathHelpers()->getStringifiedModulePropagationCurrentPath($module);
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

        // Allow to add extra modulepaths set from above
        if ($extra_module_paths = $this->getProp($module, $props, 'dataload-source-add-modulepaths')) {
            foreach ($extra_module_paths as $modulepath) {
                $ret = GeneralUtils::addQueryArgs([
                    Params::MODULEPATHS . '[]' => $this->getModulePathHelpers()->stringifyModulePath($modulepath),
                ], $ret);
            }
        }

        // Add the actionpath too
        if ($this->getComponentMutationResolverBridge($module) !== null) {
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
            if ($format = $this->getFormat($module)) {
                $ret = GeneralUtils::addQueryArgs([
                    Params::FORMAT => $format,
                ], $ret);
            }
        }

        return $ret;
    }
}

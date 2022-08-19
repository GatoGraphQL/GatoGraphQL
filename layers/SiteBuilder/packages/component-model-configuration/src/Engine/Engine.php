<?php

declare(strict_types=1);

namespace PoP\ConfigurationComponentModel\Engine;

use PoP\ComponentModel\App;
use PoP\ComponentModel\Component\Component;
use PoP\ComponentModel\Constants\DataOutputModes;
use PoP\ComponentModel\Constants\DataSourceSelectors;
use PoP\ComponentModel\Constants\Response;
use PoP\ComponentModel\GraphQLEngine\Model\ComponentModelSpec\ComponentFieldNodeInterface;
use PoP\ComponentModel\GraphQLEngine\Model\ComponentModelSpec\LeafComponentFieldNode;
use PoP\ComponentModel\Misc\RequestUtils;
use PoP\ComponentModel\Module as ComponentModelModule;
use PoP\ComponentModel\ModuleConfiguration as ComponentModelModuleConfiguration;
use PoP\ComponentModel\Settings\SettingsManagerFactory;
use PoP\ConfigurationComponentModel\Constants\DataOutputItems;
use PoP\ConfigurationComponentModel\Constants\Params;
use PoP\Engine\Engine\Engine as UpstreamEngine;
use PoP\Engine\Exception\ContractNotSatisfiedException;
use PoP\Engine\FunctionAPIFactory;
use PoP\GraphQLParser\Spec\Parser\Ast\LeafField;
use PoP\GraphQLParser\ASTNodes\ASTNodesFactory;

class Engine extends UpstreamEngine implements EngineInterface
{
    const CACHETYPE_IMMUTABLESETTINGS = 'static-settings';
    const CACHETYPE_STATEFULSETTINGS = 'stateful-settings';

    protected function generateData(): void
    {
        /** @var LegacyPoP\LooseContracts\LooseContractManagerInterface */
        $looseContractManager = $this->getLooseContractManager();

        // Check if there are hooks that must be implemented by the CMS, that have not been done so.
        if ($notImplementedHooks = $looseContractManager->getNotImplementedRequiredHooks()) {
            throw new ContractNotSatisfiedException(
                sprintf(
                    $this->__('The following hooks have not been implemented by the CMS: "%s". Hence, we can\'t continue.'),
                    implode($this->__('", "'), $notImplementedHooks)
                )
            );
        }

        parent::generateData();
    }

    protected function processAndGenerateData(): void
    {
        parent::processAndGenerateData();

        // Validate that the strata includes the required stratum
        if (!in_array(POP_STRATUM_CONFIGURATION, App::getState('strata'))) {
            return;
        }

        $engineState = App::getEngineState();

        // Get the entry module based on the application configuration and the nature
        $component = $this->getEntryComponent();

        // Externalize logic into function so it can be overridden by PoP Web Platform Engine
        $dataoutputitems = App::getState('dataoutputitems');

        $data = [];
        if (in_array(DataOutputItems::COMPONENTSETTINGS, $dataoutputitems)) {
            $data = array_merge(
                $data,
                $this->getComponentSettings($component, $engineState->model_props, $engineState->props)
            );
        }

        // Do array_replace_recursive because it may already contain data from doing 'extra-uris'
        $engineState->data = array_replace_recursive(
            $engineState->data,
            $data
        );
    }

    /**
     * @param array<string,mixed> $props
     */
    public function getComponentSettings(Component $component, $model_props, array &$props)
    {
        $ret = array();

        $processor = $this->getComponentProcessorManager()->getComponentProcessor($component);
        /** @var ComponentModelModuleConfiguration */
        $moduleConfiguration = App::getModule(ComponentModelModule::class)->getConfiguration();
        $useCache = $moduleConfiguration->useComponentModelCache();

        // From the state we know if to process static/staful content or both
        $datasourceselector = App::getState('datasourceselector');
        $dataoutputmode = App::getState('dataoutputmode');

        // First check if there's a cache stored
        $immutable_settings = $mutableonmodel_settings = null;
        if ($useCache) {
            $immutable_settings = $this->getPersistentCache()->getCacheByModelInstance(self::CACHETYPE_IMMUTABLESETTINGS);
            $mutableonmodel_settings = $this->getPersistentCache()->getCacheByModelInstance(self::CACHETYPE_STATEFULSETTINGS);
        }

        // If there is no cached one, generate the configuration and cache it
        if ($immutable_settings === null) {
            $immutable_settings = $processor->getImmutableSettingsComponentTree($component, $model_props);
            if ($useCache) {
                $this->getPersistentCache()->storeCacheByModelInstance(self::CACHETYPE_IMMUTABLESETTINGS, $immutable_settings);
            }
        }
        if ($mutableonmodel_settings === null) {
            $mutableonmodel_settings = $processor->getMutableonmodelSettingsComponentTree($component, $model_props);
            if ($useCache) {
                $this->getPersistentCache()->storeCacheByModelInstance(self::CACHETYPE_STATEFULSETTINGS, $mutableonmodel_settings);
            }
        }
        if ($datasourceselector == DataSourceSelectors::MODELANDREQUEST) {
            $mutableonrequest_settings = $processor->getMutableonrequestSettingsComponentTree($component, $props);
        }

        // If there are multiple URIs, then the results must be returned under the corresponding $model_instance_id for "mutableonmodel", and $url for "mutableonrequest"
        list($has_extra_routes, $model_instance_id, $current_uri) = $this->listExtraRouteVars();

        if ($dataoutputmode == DataOutputModes::SPLITBYSOURCES) {
            // Save the model settings
            if ($immutable_settings) {
                $ret['componentsettings']['immutable'] = $immutable_settings;
            }
            if ($mutableonmodel_settings) {
                $ret['componentsettings']['mutableonmodel'] = $has_extra_routes ? array($model_instance_id => $mutableonmodel_settings) : $mutableonmodel_settings;
            }
            if ($mutableonrequest_settings) {
                $ret['componentsettings']['mutableonrequest'] = $has_extra_routes ? array($current_uri => $mutableonrequest_settings) : $mutableonrequest_settings;
            }
        } elseif ($dataoutputmode == DataOutputModes::COMBINED) {
            // If everything is combined, then it belongs under "mutableonrequest"
            if (
                $combined_settings = array_merge_recursive(
                    $immutable_settings ?? array(),
                    $mutableonmodel_settings ?? array(),
                    $mutableonrequest_settings ?? array()
                )
            ) {
                $ret['componentsettings'] = $has_extra_routes ? array($current_uri => $combined_settings) : $combined_settings;
            }
        }

        return $ret;
    }

    public function maybeRedirectAndExit(): void
    {
        if ($redirect = SettingsManagerFactory::getInstance()->getRedirectUrl()) {
            if ($query = App::server('QUERY_STRING')) {
                $redirect .= '?' . $query;
            }

            $cmsengineapi = FunctionAPIFactory::getInstance();
            $cmsengineapi->redirect($redirect);
            exit;
        }
    }

    public function generateDataAndPrepareResponse(): void
    {
        // Before anything: check if to do a redirect, and exit
        $this->maybeRedirectAndExit();

        parent::generateDataAndPrepareResponse();
    }

    /**
     * @return array<string,mixed>
     */
    public function getSiteMeta(): array
    {
        $meta = parent::getSiteMeta();
        if ($this->addSiteMeta()) {
            if (App::getState('stratum')) {
                $meta[Params::STRATUM] = App::getState('stratum');
            }
            if (App::getState('format')) {
                $meta[Params::SETTINGSFORMAT] = App::getState('format');
            }
        }
        return App::applyFilters(
            '\PoPSiteBuilder\ComponentModel\Engine:site-meta',
            $meta
        );
    }

    public function addSiteMeta(): bool
    {
        return RequestUtils::fetchingSite();
    }

    /**
     * @return array<string,mixed>
     */
    public function getRequestMeta(): array
    {
        $meta = parent::getRequestMeta();

        // Any errors? Send them back
        if (RequestUtils::$errors) {
            $meta[Response::ERROR] = count(RequestUtils::$errors) > 1 ?
                $this->__('Oops, there were some errors:', 'pop-engine') . implode('<br/>', RequestUtils::$errors)
                : $this->__('Oops, there was an error: ', 'pop-engine') . RequestUtils::$errors[0];
        }

        return App::applyFilters(
            '\PoPSiteBuilder\ComponentModel\Engine:request-meta',
            $meta
        );
    }

    /**
     * @return ComponentFieldNodeInterface[]
     */
    protected function getDBObjectMandatoryFields(): array
    {
        // Make sure to always add the 'id' data-field, since that's the key for the dbobject in the client database
        return [
            ...parent::getDBObjectMandatoryFields(),
            new LeafComponentFieldNode(
                new LeafField(
                    'id',
                    null,
                    [],
                    [],
                    ASTNodesFactory::getNonSpecificLocation()
                )
            ),
        ];
    }
}

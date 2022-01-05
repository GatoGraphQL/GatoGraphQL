<?php

declare(strict_types=1);

namespace PoP\ConfigurationComponentModel\Engine;

use PoP\Root\Managers\ComponentManager;
use PoP\ComponentModel\Component as ComponentModelComponent;
use PoP\ComponentModel\ComponentConfiguration as ComponentModelComponentConfiguration;
use PoP\ComponentModel\Constants\DataOutputModes;
use PoP\ComponentModel\Constants\DataSourceSelectors;
use PoP\ComponentModel\Constants\Response;
use PoP\ComponentModel\Misc\RequestUtils;
use PoP\ComponentModel\Settings\SettingsManagerFactory;
use PoP\ComponentModel\State\ApplicationState;
use PoP\ConfigurationComponentModel\Constants\DataOutputItems;
use PoP\ConfigurationComponentModel\Constants\Params;
use PoP\Engine\Engine\Engine as UpstreamEngine;
use PoP\Engine\FunctionAPIFactory;

class Engine extends UpstreamEngine implements EngineInterface
{
    const CACHETYPE_IMMUTABLESETTINGS = 'static-settings';
    const CACHETYPE_STATEFULSETTINGS = 'stateful-settings';

    protected function processAndGenerateData(): void
    {
        parent::processAndGenerateData();

        // Validate that the strata includes the required stratum
        $vars = ApplicationState::getVars();
        if (!in_array(POP_STRATUM_CONFIGURATION, $vars['strata'])) {
            return;
        }

        // Get the entry module based on the application configuration and the nature
        $module = $this->getEntryModule();

        // Externalize logic into function so it can be overridden by PoP Web Platform Engine
        $dataoutputitems = $vars['dataoutputitems'];

        $data = [];
        if (in_array(DataOutputItems::MODULESETTINGS, $dataoutputitems)) {
            $data = array_merge(
                $data,
                $this->getModuleSettings($module, $this->model_props, $this->props)
            );
        }

        // Do array_replace_recursive because it may already contain data from doing 'extra-uris'
        $this->data = array_replace_recursive(
            $this->data,
            $data
        );
    }

    public function getModuleSettings(array $module, $model_props, array &$props)
    {
        $ret = array();

        $processor = $this->getModuleProcessorManager()->getProcessor($module);
        /** @var ComponentModelComponentConfiguration */
        $componentConfiguration = \PoP\Engine\App::getComponentManager()->getComponent(ComponentModelComponent::class)->getConfiguration();
        if ($useCache = $componentConfiguration->useComponentModelCache()) {
            $useCache = $this->persistentCache !== null;
        }

        // From the state we know if to process static/staful content or both
        $vars = ApplicationState::getVars();
        $datasources = $vars['datasources'];
        $dataoutputmode = $vars['dataoutputmode'];

        // First check if there's a cache stored
        $immutable_settings = $mutableonmodel_settings = null;
        if ($useCache) {
            $immutable_settings = $this->persistentCache->getCacheByModelInstance(self::CACHETYPE_IMMUTABLESETTINGS);
            $mutableonmodel_settings = $this->persistentCache->getCacheByModelInstance(self::CACHETYPE_STATEFULSETTINGS);
        }

        // If there is no cached one, generate the configuration and cache it
        if ($immutable_settings === null) {
            $immutable_settings = $processor->getImmutableSettingsModuletree($module, $model_props);
            if ($useCache) {
                $this->persistentCache->storeCacheByModelInstance(self::CACHETYPE_IMMUTABLESETTINGS, $immutable_settings);
            }
        }
        if ($mutableonmodel_settings === null) {
            $mutableonmodel_settings = $processor->getMutableonmodelSettingsModuletree($module, $model_props);
            if ($useCache) {
                $this->persistentCache->storeCacheByModelInstance(self::CACHETYPE_STATEFULSETTINGS, $mutableonmodel_settings);
            }
        }
        if ($datasources == DataSourceSelectors::MODELANDREQUEST) {
            $mutableonrequest_settings = $processor->getMutableonrequestSettingsModuletree($module, $props);
        }

        // If there are multiple URIs, then the results must be returned under the corresponding $model_instance_id for "mutableonmodel", and $url for "mutableonrequest"
        list($has_extra_routes, $model_instance_id, $current_uri) = $this->listExtraRouteVars();

        if ($dataoutputmode == DataOutputModes::SPLITBYSOURCES) {
            // Save the model settings
            if ($immutable_settings) {
                $ret['modulesettings']['immutable'] = $immutable_settings;
            }
            if ($mutableonmodel_settings) {
                $ret['modulesettings']['mutableonmodel'] = $has_extra_routes ? array($model_instance_id => $mutableonmodel_settings) : $mutableonmodel_settings;
            }
            if ($mutableonrequest_settings) {
                $ret['modulesettings']['mutableonrequest'] = $has_extra_routes ? array($current_uri => $mutableonrequest_settings) : $mutableonrequest_settings;
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
                $ret['modulesettings'] = $has_extra_routes ? array($current_uri => $combined_settings) : $combined_settings;
            }
        }

        return $ret;
    }

    public function maybeRedirectAndExit(): void
    {
        if ($redirect = SettingsManagerFactory::getInstance()->getRedirectUrl()) {
            if ($query = $_SERVER['QUERY_STRING']) {
                $redirect .= '?' . $query;
            }

            $cmsengineapi = FunctionAPIFactory::getInstance();
            $cmsengineapi->redirect($redirect);
            exit;
        }
    }

    public function outputResponse(): void
    {
        // Before anything: check if to do a redirect, and exit
        $this->maybeRedirectAndExit();

        parent::outputResponse();
    }

    public function getSiteMeta(): array
    {
        $meta = parent::getSiteMeta();
        if ($this->addSiteMeta()) {
            $vars = ApplicationState::getVars();
            if ($vars['stratum'] ?? null) {
                $meta[Params::STRATUM] = $vars['stratum'];
            }
        }
        return $this->getHooksAPI()->applyFilters(
            '\PoPSiteBuilder\ComponentModel\Engine:site-meta',
            $meta
        );
    }

    public function addSiteMeta(): bool
    {
        return RequestUtils::fetchingSite();
    }

    public function getRequestMeta(): array
    {
        $meta = parent::getRequestMeta();

        // Any errors? Send them back
        if (RequestUtils::$errors) {
            $meta[Response::ERROR] = count(RequestUtils::$errors) > 1 ?
                $this->getTranslationAPI()->__('Oops, there were some errors:', 'pop-engine') . implode('<br/>', RequestUtils::$errors)
                : $this->getTranslationAPI()->__('Oops, there was an error: ', 'pop-engine') . RequestUtils::$errors[0];
        }

        return $this->getHooksAPI()->applyFilters(
            '\PoPSiteBuilder\ComponentModel\Engine:request-meta',
            $meta
        );
    }
}

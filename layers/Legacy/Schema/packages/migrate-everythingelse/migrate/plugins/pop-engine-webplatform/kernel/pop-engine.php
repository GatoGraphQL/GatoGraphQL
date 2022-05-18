<?php

use PoP\ComponentModel\ModuleConfiguration as ComponentModelModuleConfiguration;
use PoP\ComponentModel\ModuleInfo as ComponentModelModuleInfo;
use PoP\ComponentModel\Facades\Cache\PersistentCacheFacade;
use PoP\ComponentModel\Facades\Info\ApplicationInfoFacade;
use PoP\ComponentModel\Facades\ComponentProcessors\ComponentProcessorManagerFacade;
use PoP\Root\Feedback\FeedbackItemResolution;
use PoP\ComponentModel\ComponentProcessors\DataloadingConstants;
use PoP\ComponentModel\State\ApplicationState;
use PoP\Root\App;

class PoPWebPlatform_Engine extends \PoP\ConfigurationComponentModel\Engine\Engine
{
    public $scripts;
    public $enqueue;
    public $scripttag_attributes;
    public $intercept_urls;
    public $immutable_componentjsdata;
    public $mutableonmodel_componentjsdata;
    public $mutableonrequest_componentjsdata;

    public function __construct()
    {
        // Print needed scripts
        $this->scripts = $this->enqueue = $this->scripttag_attributes = array();
        \PoP\Root\App::addAction('popcms:printFooterScripts', $this->printScripts(...));

        // Priority 60: after priority 50 in wp-content/plugins/pop-engine-webplatform/kernel/resourceloader/initialization.php
        \PoP\Root\App::addAction('popcms:enqueueScripts', $this->enqueueScripts(...), 60);

        // Allow to add attributes crossorigin="anonymous"
        // Taken from https://stackoverflow.com/questions/18944027/how-do-i-defer-or-async-this-wordpress-javascript-snippet-to-load-lastly-for-fas
        \PoP\Root\App::addFilter(
            'PoP_HTMLTags_Utils:scripttag_attributes',
            $this->getScripttagAttributes(...)
        );
    }

    public function getModuleSettings(array $component, $model_props, array &$props)
    {
        $ret = parent::getModuleSettings($component, $model_props, $props);

        // Validate that the strata includes the required stratum
        if (!in_array(POP_STRATUM_WEB, App::getState('strata'))) {
            return $ret;
        }

        $componentprocessor_manager = ComponentProcessorManagerFacade::getInstance();
        $processor = $componentprocessor_manager->getProcessor($component);

        $cachemanager = null;
        if ($useCache = ComponentModelModuleConfiguration::useComponentModelCache()) {
            $cachemanager = PersistentCacheFacade::getInstance();
        }

        // From the state we know if to process static/staful content or both
        $datasourceselector = App::getState('datasourceselector');
        $dataoutputmode = App::getState('dataoutputmode');
        $dataoutputitems = App::getState('dataoutputitems');

        $add_settings = in_array(\PoP\ConfigurationComponentModel\Constants\DataOutputItems::COMPONENTSETTINGS, $dataoutputitems);

        if ($add_settings) {
            $immutable_jssettings = $mutableonmodel_jssettings = null;
            if ($useCache) {
                $immutable_jssettings = $cachemanager->getCacheByModelInstance(POP_CACHETYPE_STATICJSSETTINGS);
                $mutableonmodel_jssettings = $cachemanager->getCacheByModelInstance(POP_CACHETYPE_STATEFULJSSETTINGS);
            }
            if ($immutable_jssettings === null) {
                $immutable_jssettings = $processor->getImmutableJssettingsModuletree($component, $model_props);
                if ($useCache) {
                    $cachemanager->storeCacheByModelInstance(POP_CACHETYPE_STATICJSSETTINGS, $immutable_jssettings);
                }
            }
            if ($mutableonmodel_jssettings === null) {
                $mutableonmodel_jssettings = $processor->getMutableonmodelJssettingsModuletree($component, $model_props);
                if ($useCache) {
                    $cachemanager->storeCacheByModelInstance(POP_CACHETYPE_STATEFULJSSETTINGS, $mutableonmodel_jssettings);
                }
            }

            if ($datasourceselector == \PoP\ComponentModel\Constants\DataSourceSelectors::MODELANDREQUEST) {
                $mutableonrequest_jssettings = $processor->getMutableonrequestJssettingsModuletree($component, $props);
            }

            // If there are multiple URIs, then the results must be returned under the corresponding $model_instance_id for "mutableonmodel", and $url for "mutableonrequest"
            list($has_extra_routes, $model_instance_id, $current_uri) = $this->listExtraRouteVars();

            if ($dataoutputmode == \PoP\ComponentModel\Constants\DataOutputModes::SPLITBYSOURCES) {

                // Save the model settings
                if ($immutable_jssettings) {
                    $ret['modulejssettings']['immutable'] = $immutable_jssettings;
                }
                if ($mutableonmodel_jssettings) {
                    $ret['modulejssettings']['mutableonmodel'] = $has_extra_routes ? array($model_instance_id => $mutableonmodel_jssettings) : $mutableonmodel_jssettings;
                }
                if ($mutableonrequest_jssettings) {
                    $ret['modulejssettings']['mutableonrequest'] = $has_extra_routes ? array($current_uri => $mutableonrequest_jssettings) : $mutableonrequest_jssettings;
                }
            } elseif ($dataoutputmode == \PoP\ComponentModel\Constants\DataOutputModes::COMBINED) {

                // If everything is combined, then it belongs under "mutableonrequest"
                if ($combined_jssettings = array_merge_recursive(
                    $immutable_jssettings ?? array(),
                    $mutableonmodel_jssettings ?? array(),
                    $mutableonrequest_jssettings ?? array()
                )) {
                    $ret['modulejssettings'] = $has_extra_routes ? array($current_uri => $combined_jssettings) : $combined_jssettings;
                }
            }
        }

        return $ret;
    }

    protected function processAndGenerateData(): void
    {
        // Initialize/Reset the JS module data
        $this->immutable_componentjsdata = $this->mutableonmodel_componentjsdata = $this->mutableonrequest_componentjsdata = array();

        parent::processAndGenerateData();
    }

    // This function is not private, so it can be accessed by the automated emails to regenerate the html for each user
    public function getModuleData(array $root_component, array $root_model_props, array $root_props): array
    {
        $ret = parent::getModuleData($root_component, $root_model_props, $root_props);

        // Only add the extra information if the entry-module is of the right object class
        $componentprocessor_manager = ComponentProcessorManagerFacade::getInstance();
        $root_processor = $componentprocessor_manager->getProcessor($root_component);

        // Only add the extra information if the entry-module is of the right object class
        if ($root_processor instanceof PoP_WebPlatformQueryDataComponentProcessorBase) {

            $dataoutputmode = App::getState('dataoutputmode');

            // If there are multiple URIs, then the results must be returned under the corresponding $model_instance_id for "mutableonmodel", and $url for "mutableonrequest"
            list($has_extra_routes, $model_instance_id, $current_uri) = $this->listExtraRouteVars();

            if ($dataoutputmode == \PoP\ComponentModel\Constants\DataOutputModes::SPLITBYSOURCES) {
                if ($this->immutable_componentjsdata) {
                    $ret['modulejsdata']['immutable'] = $this->immutable_componentjsdata;
                }
                if ($this->mutableonmodel_componentjsdata) {
                    $ret['modulejsdata']['mutableonmodel'] = $has_extra_routes ? array($model_instance_id => $this->mutableonmodel_componentjsdata) : $this->mutableonmodel_componentjsdata;
                }
                if ($this->mutableonrequest_componentjsdata) {
                    $ret['modulejsdata']['mutableonrequest'] = $has_extra_routes ? array($current_uri => $this->mutableonrequest_componentjsdata) : $this->mutableonrequest_componentjsdata;
                }
            } elseif ($dataoutputmode == \PoP\ComponentModel\Constants\DataOutputModes::COMBINED) {

                // If everything is combined, then it belongs under "mutableonrequest"
                if ($combined_componentjsdata = array_merge_recursive(
                    $this->immutable_componentjsdata,
                    $this->mutableonmodel_componentjsdata,
                    $this->mutableonrequest_componentjsdata
                )) {
                    $ret['modulejsdata'] = $has_extra_routes ? array($current_uri => $combined_componentjsdata) : $combined_componentjsdata;
                }
            }

            // Specify all the URLs to be intercepted by the current page. This is needed to obtain their configuration in the webplatform, under this page's URL
            $this->intercept_urls = $root_processor->getIntercepturlsMergedmoduletree($root_component, $root_props);
        }

        return $ret;
    }

    protected function processAndAddModuleData(
        array $module_path,
        array $component,
        array &$props,
        array $data_properties,
        ?FeedbackItemResolution $dataaccess_checkpoint_validation,
        $mutation_checkpoint_validation,
        $executed,
        $objectIDs
    ): void {
        parent::processAndAddModuleData($module_path, $component, $props, $data_properties, $dataaccess_checkpoint_validation, $actionexecution_checkpoint_validation, $executed, $objectIDs);

        // Validate that the strata includes the required stratum
        if (!in_array(POP_STRATUM_WEB, App::getState('strata'))) {
            return;
        }

        $componentprocessor_manager = ComponentProcessorManagerFacade::getInstance();
        $processor = $componentprocessor_manager->getProcessor($component);

        $datasource = $data_properties[DataloadingConstants::DATASOURCE];

        // Save the results on either the static or mutableonrequest branches
        if ($datasource == \PoP\ComponentModel\Constants\DataSources::IMMUTABLE) {
            $modulejsdata = &$this->immutable_componentjsdata;
        } elseif ($datasource == \PoP\ComponentModel\Constants\DataSources::MUTABLEONMODEL) {
            $modulejsdata = &$this->mutableonmodel_componentjsdata;
        } elseif ($datasource == \PoP\ComponentModel\Constants\DataSources::MUTABLEONREQUEST) {
            $modulejsdata = &$this->mutableonrequest_componentjsdata;
        }

        // Integrate the JS feedback into $modulejsdata
        if (!is_null($modulejsdata)) {

            // Add the feedback into the object
            if ($feedback = $processor->getJsdataFeedbackDatasetmoduletree($component, $props, $data_properties, $dataaccess_checkpoint_validation, $actionexecution_checkpoint_validation, $executed, $objectIDs)) {

                // Advance the position of the array into the current module
                foreach ($module_path as $subComponent) {
                    $submoduleOutputName = \PoP\ComponentModel\Facades\Modules\ModuleHelpersFacade::getInstance()->getModuleOutputName($subComponent);
                    $modulejsdata[$submoduleOutputName][ComponentModelModuleInfo::get('response-prop-subcomponents')] = $modulejsdata[$submoduleOutputName][ComponentModelModuleInfo::get('response-prop-subcomponents')] ?? array();
                    $modulejsdata = &$modulejsdata[$submoduleOutputName][ComponentModelModuleInfo::get('response-prop-subcomponents')];
                }
                // Merge the JS feedback in
                $modulejsdata = array_merge_recursive(
                    $modulejsdata,
                    $feedback
                );
            }
        }
    }

    public function getRequestMeta(): array
    {
        $meta = parent::getRequestMeta();

        // Validate that the strata includes the required stratum
        if (!in_array(POP_STRATUM_WEB, App::getState('strata'))) {
            return $meta;
        }

        $settingsmanager = \PoP\ComponentModel\Settings\SettingsManagerFactory::getInstance();

        // Silent document? (Opposite to Update the browser URL and Title?)
        if ($settingsmanager->silentDocument()) {
            $meta[GD_URLPARAM_SILENTDOCUMENT] = true;
        }

        // Store page in localStorage?
        if ($settingsmanager->storeLocal()) {
            $meta[GD_URLPARAM_STORELOCAL] = true;
        }

        // Indicate if a page can have multiple instances of it open (eg: Add Post)
        if ($settingsmanager->isMultipleopen()) {
            $meta[POP_JS_ISMULTIPLEOPEN] = true;
        }
        if ($this->intercept_urls) {
            $meta[GD_URLPARAM_INTERCEPTURLS] = $this->intercept_urls;
        }

        // If preloading the request, then do not render it
        if (in_array(GD_URLPARAM_ACTION_PRELOAD, App::getState('actions'))) {
            $meta[POP_JS_DONOTRENDER] = true;
        }

        $meta = \PoP\Root\App::applyFilters(
            'PoPWebPlatform_Engine:request-meta',
            $meta
        );

        return $meta;
    }

    // Allow to add attributes 'async' or 'defer' to the script tag
    public function getScripttagAttributes($scripttag_attributes)
    {
        return array_merge(
            $scripttag_attributes,
            $this->scripttag_attributes
        );
    }

    // Allow PoPWebPlatform_Engine to override this function
    protected function getEncodedDataObject(array $data): array
    {
        $data = parent::getEncodedDataObject($data);

        // Validate that the strata includes the required stratum
        if (!in_array(POP_STRATUM_WEB, App::getState('strata'))) {
            return $data;
        }

        // Allow PoP Server-Side Rendering to inject this value
        $data = \PoP\Root\App::applyFilters(
            'PoPWebPlatform_Engine:encoded-data-object',
            $data,
            $this
        );

        return $data;
    }

    // These functions are needed by PoP Web Platform Engine Optimizations
    public function addEnqueueItem($item)
    {
        $this->enqueue[] = $item;
    }
    public function addScriptsItem($item)
    {
        $this->scripts[] = $item;
    }

    public function printScripts()
    {
        if ($this->scripts) {
            printf(
                '<script type="text/javascript">%s</script>',
                implode(PHP_EOL, $this->scripts)
            );
        }
    }

    public function enqueueScripts()
    {
        $version = ApplicationInfoFacade::getInstance()->getVersion();
        $cmswebplatformapi = \PoP\EngineWebPlatform\FunctionAPIFactory::getInstance();

        $script = 'pop';
        if (PoP_WebPlatform_ServerUtils::useBundledResources()) {

            // The bundle application file must be registered under "pop-app"
            $script = 'pop-app';
        }
        // Allow PoP Resource Loader to set its own "first script" instead
        $script = \PoP\Root\App::applyFilters(
            'PoPWebPlatform_Engine:enqueue-scripts:first-script-handle',
            $script
        );
        foreach ($this->enqueue as $item) {

            // When using the 'app-bundle' then there is no $first_script, just use 'pop'
            $dependencies = array($script);
            $cmswebplatformapi->registerScript('pop-'.$item['property'], $item['file-url'], $dependencies, $version, true);
            $cmswebplatformapi->enqueueScript('pop-'.$item['property']);
            if ($scripttag_attributes = $item['scripttag-attributes']) {
                $this->scripttag_attributes['pop-'.$item['property']] = $scripttag_attributes;
            }
        }
    }

    // protected function getJsonModuleImmutableSettings(array $component, array &$props) {

    // 	$componentprocessor_manager = ComponentProcessorManagerFacade::getInstance();

    // 	$json_settings = parent::getJsonModuleImmutableSettings($component, $props);

    // 	// Otherwise, get the dynamic configuration
    // 	$processor = $componentprocessor_manager->getProcessor($component);

    // 	$json_settings['modules-cbs'] = $processor->getModulesCbs($component, $props);
    // 	$json_settings['modules-paths'] = $processor->getModulesPaths($component, $props);
    // 	$json_settings['js-settings'] = $processor->get_js_settings($component, $props);
    // 	$json_settings['jsmethods'] = array(
    // 		'pagesection' => $processor->getPagesectionJsmethods($component, $props),
    // 		'block' => $processor->get_block_jsmethods($component, $props)
    // 	);
    // 	$json_settings['templates'] = $processor->getTemplates($component, $props);

    // 	return \PoP\Root\App::applyFilters(
    // 		'PoPWebPlatform_Engine:json-module-immutable-settings',
    // 		$json_settings,
    // 		$component,
    // 		$props,
    // 		$processor
    // 	);
    // }

    // protected function getJsonModuleMutableonrequestSettings(array $component, array &$props) {

    // 	$componentprocessor_manager = ComponentProcessorManagerFacade::getInstance();

    // 	$json_runtimesettings = parent::getJsonModuleMutableonrequestSettings($component, $props);

    // 	// Otherwise, get the dynamic configuration
    // 	$processor = $componentprocessor_manager->getProcessor($component);

    // 	$json_runtimesettings['js-settings'] = $processor->get_js_runtimesettings($component, $props);

    // 	return $json_runtimesettings;
    // }
}

/**
 * Initialization
 */
new PoPWebPlatform_Engine();

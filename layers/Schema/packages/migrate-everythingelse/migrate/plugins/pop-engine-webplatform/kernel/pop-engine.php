<?php

use PoP\ComponentModel\Modules\ModuleUtils;
use PoP\Hooks\Facades\HooksAPIFacade;
use PoP\ComponentModel\Facades\Cache\PersistentCacheFacade;
use PoP\ComponentModel\Facades\ModuleProcessors\ModuleProcessorManagerFacade;
use PoP\ComponentModel\ModuleProcessors\DataloadingConstants;
use PoP\ComponentModel\ComponentConfiguration as ComponentModelComponentConfiguration;
use PoP\ComponentModel\State\ApplicationState;

class PoPWebPlatform_Engine extends \PoP\ConfigurationComponentModel\Engine\Engine
{
    public $scripts;
    public $enqueue;
    public $scripttag_attributes;
    public $intercept_urls;
    public $immutable_modulejsdata;
    public $mutableonmodel_modulejsdata;
    public $mutableonrequest_modulejsdata;

    public function __construct()
    {
        // Print needed scripts
        $this->scripts = $this->enqueue = $this->scripttag_attributes = array();
        HooksAPIFacade::getInstance()->addAction('popcms:printFooterScripts', array($this, 'printScripts'));

        // Priority 60: after priority 50 in wp-content/plugins/pop-engine-webplatform/kernel/resourceloader/initialization.php
        HooksAPIFacade::getInstance()->addAction('popcms:enqueueScripts', array($this, 'enqueueScripts'), 60);

        // Allow to add attributes crossorigin="anonymous"
        // Taken from https://stackoverflow.com/questions/18944027/how-do-i-defer-or-async-this-wordpress-javascript-snippet-to-load-lastly-for-fas
        HooksAPIFacade::getInstance()->addFilter(
            'PoP_HTMLTags_Utils:scripttag_attributes',
            array($this, 'getScripttagAttributes')
        );
    }

    public function getModuleSettings(array $module, $model_props, array &$props)
    {
        $ret = parent::getModuleSettings($module, $model_props, $props);

        // Validate that the strata includes the required stratum
        $vars = ApplicationState::getVars();
        if (!in_array(POP_STRATUM_WEB, $vars['strata'])) {
            return $ret;
        }

        $moduleprocessor_manager = ModuleProcessorManagerFacade::getInstance();
        $processor = $moduleprocessor_manager->getProcessor($module);

        if ($useCache = ComponentModelComponentConfiguration::useComponentModelCache()) {
            $cachemanager = PersistentCacheFacade::getInstance();
            $useCache = !is_null($cachemanager);
        }

        // From the state we know if to process static/staful content or both
        $vars = ApplicationState::getVars();
        $datasources = $vars['datasources'];
        $dataoutputmode = $vars['dataoutputmode'];
        $dataoutputitems = $vars['dataoutputitems'];

        $add_settings = in_array(GD_URLPARAM_DATAOUTPUTITEMS_MODULESETTINGS, $dataoutputitems);

        if ($add_settings) {
            $immutable_jssettings = $mutableonmodel_jssettings = null;
            if ($useCache) {
                $immutable_jssettings = $cachemanager->getCacheByModelInstance(POP_CACHETYPE_STATICJSSETTINGS);
                $mutableonmodel_jssettings = $cachemanager->getCacheByModelInstance(POP_CACHETYPE_STATEFULJSSETTINGS);
            }
            if ($immutable_jssettings === null) {
                $immutable_jssettings = $processor->getImmutableJssettingsModuletree($module, $model_props);
                if ($useCache) {
                    $cachemanager->storeCacheByModelInstance(POP_CACHETYPE_STATICJSSETTINGS, $immutable_jssettings);
                }
            }
            if ($mutableonmodel_jssettings === null) {
                $mutableonmodel_jssettings = $processor->getMutableonmodelJssettingsModuletree($module, $model_props);
                if ($useCache) {
                    $cachemanager->storeCacheByModelInstance(POP_CACHETYPE_STATEFULJSSETTINGS, $mutableonmodel_jssettings);
                }
            }

            if ($datasources == GD_URLPARAM_DATASOURCES_MODELANDREQUEST) {
                $mutableonrequest_jssettings = $processor->getMutableonrequestJssettingsModuletree($module, $props);
            }

            // If there are multiple URIs, then the results must be returned under the corresponding $model_instance_id for "mutableonmodel", and $url for "mutableonrequest"
            list($has_extra_routes, $model_instance_id, $current_uri) = $this->listExtraRouteVars();

            if ($dataoutputmode == GD_URLPARAM_DATAOUTPUTMODE_SPLITBYSOURCES) {

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
            } elseif ($dataoutputmode == GD_URLPARAM_DATAOUTPUTMODE_COMBINED) {

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

    protected function processAndGenerateData()
    {
        // Initialize/Reset the JS module data
        $this->immutable_modulejsdata = $this->mutableonmodel_modulejsdata = $this->mutableonrequest_modulejsdata = array();

        parent::processAndGenerateData();
    }

    // This function is not private, so it can be accessed by the automated emails to regenerate the html for each user
    public function getModuleData($root_module, $root_model_props, $root_props)
    {
        $ret = parent::getModuleData($root_module, $root_model_props, $root_props);

        // Only add the extra information if the entry-module is of the right object class
        $moduleprocessor_manager = ModuleProcessorManagerFacade::getInstance();
        $root_processor = $moduleprocessor_manager->getProcessor($root_module);

        // Only add the extra information if the entry-module is of the right object class
        if ($root_processor instanceof PoP_WebPlatformQueryDataModuleProcessorBase) {

            $vars = ApplicationState::getVars();
            $dataoutputmode = $vars['dataoutputmode'];

            // If there are multiple URIs, then the results must be returned under the corresponding $model_instance_id for "mutableonmodel", and $url for "mutableonrequest"
            list($has_extra_routes, $model_instance_id, $current_uri) = $this->listExtraRouteVars();

            if ($dataoutputmode == GD_URLPARAM_DATAOUTPUTMODE_SPLITBYSOURCES) {
                if ($this->immutable_modulejsdata) {
                    $ret['modulejsdata']['immutable'] = $this->immutable_modulejsdata;
                }
                if ($this->mutableonmodel_modulejsdata) {
                    $ret['modulejsdata']['mutableonmodel'] = $has_extra_routes ? array($model_instance_id => $this->mutableonmodel_modulejsdata) : $this->mutableonmodel_modulejsdata;
                }
                if ($this->mutableonrequest_modulejsdata) {
                    $ret['modulejsdata']['mutableonrequest'] = $has_extra_routes ? array($current_uri => $this->mutableonrequest_modulejsdata) : $this->mutableonrequest_modulejsdata;
                }
            } elseif ($dataoutputmode == GD_URLPARAM_DATAOUTPUTMODE_COMBINED) {

                // If everything is combined, then it belongs under "mutableonrequest"
                if ($combined_modulejsdata = array_merge_recursive(
                    $this->immutable_modulejsdata,
                    $this->mutableonmodel_modulejsdata,
                    $this->mutableonrequest_modulejsdata
                )) {
                    $ret['modulejsdata'] = $has_extra_routes ? array($current_uri => $combined_modulejsdata) : $combined_modulejsdata;
                }
            }

            // Specify all the URLs to be intercepted by the current page. This is needed to obtain their configuration in the webplatform, under this page's URL
            $this->intercept_urls = $root_processor->getIntercepturlsMergedmoduletree($root_module, $root_props);
        }

        return $ret;
    }

    protected function processAndAddModuleData($module_path, array $module, array &$props, array $data_properties, $dataaccess_checkpoint_validation, $actionexecution_checkpoint_validation, $executed, $dbObjectIDs)
    {
        parent::processAndAddModuleData($module_path, $module, $props, $data_properties, $dataaccess_checkpoint_validation, $actionexecution_checkpoint_validation, $executed, $dbObjectIDs);

        // Validate that the strata includes the required stratum
        $vars = ApplicationState::getVars();
        if (!in_array(POP_STRATUM_WEB, $vars['strata'])) {
            return;
        }

        $moduleprocessor_manager = ModuleProcessorManagerFacade::getInstance();
        $processor = $moduleprocessor_manager->getProcessor($module);

        $datasource = $data_properties[DataloadingConstants::DATASOURCE];

        // Save the results on either the static or mutableonrequest branches
        if ($datasource == \PoP\ComponentModel\Constants\DataSources::IMMUTABLE) {
            $modulejsdata = &$this->immutable_modulejsdata;
        } elseif ($datasource == \PoP\ComponentModel\Constants\DataSources::MUTABLEONMODEL) {
            $modulejsdata = &$this->mutableonmodel_modulejsdata;
        } elseif ($datasource == \PoP\ComponentModel\Constants\DataSources::MUTABLEONREQUEST) {
            $modulejsdata = &$this->mutableonrequest_modulejsdata;
        }

        // Integrate the JS feedback into $modulejsdata
        if (!is_null($modulejsdata)) {

            // Add the feedback into the object
            if ($feedback = $processor->getJsdataFeedbackDatasetmoduletree($module, $props, $data_properties, $dataaccess_checkpoint_validation, $actionexecution_checkpoint_validation, $executed, $dbObjectIDs)) {

                // Advance the position of the array into the current module
                foreach ($module_path as $submodule) {
                    $submoduleOutputName = ModuleUtils::getModuleOutputName($submodule);
                    $modulejsdata[$submoduleOutputName][GD_JS_SUBMODULES] = $modulejsdata[$submoduleOutputName][GD_JS_SUBMODULES] ?? array();
                    $modulejsdata = &$modulejsdata[$submoduleOutputName][GD_JS_SUBMODULES];
                }
                // Merge the JS feedback in
                $modulejsdata = array_merge_recursive(
                    $modulejsdata,
                    $feedback
                );
            }
        }
    }

    public function getRequestMeta()
    {
        $meta = parent::getRequestMeta();

        // Validate that the strata includes the required stratum
        $vars = ApplicationState::getVars();
        if (!in_array(POP_STRATUM_WEB, $vars['strata'])) {
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
        if (in_array(GD_URLPARAM_ACTION_PRELOAD, $vars['actions'])) {
            $meta[POP_JS_DONOTRENDER] = true;
        }

        $meta = HooksAPIFacade::getInstance()->applyFilters(
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
    protected function getEncodedDataObject($data)
    {
        $data = parent::getEncodedDataObject($data);

        // Validate that the strata includes the required stratum
        $vars = ApplicationState::getVars();
        if (!in_array(POP_STRATUM_WEB, $vars['strata'])) {
            return $data;
        }

        // Allow PoP Server-Side Rendering to inject this value
        $data = HooksAPIFacade::getInstance()->applyFilters(
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
        $vars = ApplicationState::getVars();
        $version = $vars['version'];
        $cmswebplatformapi = \PoP\EngineWebPlatform\FunctionAPIFactory::getInstance();

        $script = 'pop';
        if (PoP_WebPlatform_ServerUtils::useBundledResources()) {

            // The bundle application file must be registered under "pop-app"
            $script = 'pop-app';
        }
        // Allow PoP Resource Loader to set its own "first script" instead
        $script = HooksAPIFacade::getInstance()->applyFilters(
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

    // protected function getJsonModuleImmutableSettings(array $module, array &$props) {

    // 	$moduleprocessor_manager = ModuleProcessorManagerFacade::getInstance();

    // 	$json_settings = parent::getJsonModuleImmutableSettings($module, $props);

    // 	// Otherwise, get the dynamic configuration
    // 	$processor = $moduleprocessor_manager->getProcessor($module);

    // 	$json_settings['modules-cbs'] = $processor->getModulesCbs($module, $props);
    // 	$json_settings['modules-paths'] = $processor->getModulesPaths($module, $props);
    // 	$json_settings['js-settings'] = $processor->get_js_settings($module, $props);
    // 	$json_settings['jsmethods'] = array(
    // 		'pagesection' => $processor->getPagesectionJsmethods($module, $props),
    // 		'block' => $processor->get_block_jsmethods($module, $props)
    // 	);
    // 	$json_settings['templates'] = $processor->getTemplates($module, $props);

    // 	return HooksAPIFacade::getInstance()->applyFilters(
    // 		'PoPWebPlatform_Engine:json-module-immutable-settings',
    // 		$json_settings,
    // 		$module,
    // 		$props,
    // 		$processor
    // 	);
    // }

    // protected function getJsonModuleMutableonrequestSettings(array $module, array &$props) {

    // 	$moduleprocessor_manager = ModuleProcessorManagerFacade::getInstance();

    // 	$json_runtimesettings = parent::getJsonModuleMutableonrequestSettings($module, $props);

    // 	// Otherwise, get the dynamic configuration
    // 	$processor = $moduleprocessor_manager->getProcessor($module);

    // 	$json_runtimesettings['js-settings'] = $processor->get_js_runtimesettings($module, $props);

    // 	return $json_runtimesettings;
    // }
}

/**
 * Initialization
 */
new PoPWebPlatform_Engine();

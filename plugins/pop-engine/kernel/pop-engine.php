<?php
namespace PoP\Engine;

class Engine
{
    public $data;
    public $helperCalculations;
    public $model_props;
    public $props;
    protected $nocache_fields;
    protected $moduledata;
    protected $ids_data_fields;
    protected $dbdata;
    protected $backgroundload_urls;
    protected $extra_uris;
    protected $cachedsettings;
    protected $outputData;

    public function __construct()
    {

        // Set myself as the Engine instance in the Factory
        Engine_Factory::setInstance($this);
    }

    // function isCachedSettings() {

    //     return $this->cachedsettings;
    // }

    public function getOutputData()
    {
        return $this->outputData;
    }

    public function addBackgroundUrl($url, $targets)
    {
        $this->backgroundload_urls[$url] = $targets;
    }

    public function getEntryModule()
    {
        $siteconfiguration = Settings\SiteConfigurationProcessorManager_Factory::getInstance()->getProcessor();
        if (!$siteconfiguration) {
            throw new \Exception('There is no Site Configuration. Hence, we can\'t continue.');
        }

        $module = $siteconfiguration->getEntryModule();
        if (!$module) {
            throw new \Exception(sprintf('No entry module for this request (%s)', fullUrl()));
        }

        return $module;
    }

    public function sendEtagHeader()
    {

        // ETag is needed for the Service Workers
        // Also needed to use together with the Control-Cache header, to know when to refetch data from the server: https://developers.google.com/web/fundamentals/performance/optimizing-content-efficiency/http-caching
        if (apply_filters('\PoP\Engine\Engine:outputData:addEtagHeader', true)) {

            // The same page will have different hashs only because of those random elements added each time,
            // such as the unique_id and the current_time. So remove these to generate the hash
            $differentiators = array(
            POP_CONSTANT_UNIQUE_ID,
            POP_CONSTANT_CURRENTTIMESTAMP,
            POP_CONSTANT_RAND,
            POP_CONSTANT_TIME,
            );
            $commoncode = str_replace($differentiators, '', json_encode($this->data));

            // Also replace all those tags with content that, even if it's different, should not alter the output
            // Eg: comments-count. Because adding a comment does not delete the cache, then the comments-count is allowed
            // to be shown stale. So if adding a new comment, there's no need for the user to receive the
            // "This page has been updated, click here to refresh it." notification
            // Because we already got the JSON, then remove entries of the type:
            // "userpostactivity-count":1, (if there are more elements after)
            // and
            // "userpostactivity-count":1
            // Comment Leo 22/10/2017: ?module=settings doesn't have 'nocache-fields'
            if ($this->nocache_fields) {
                $commoncode = preg_replace('/"('.implode('|', $this->nocache_fields).')":[0-9]+,?/', '', $commoncode);
            }

            // Allow plug-ins to replace their own non-needed content (eg: thumbprints, defined in Core)
            $commoncode = apply_filters('\PoP\Engine\Engine:etag_header:commoncode', $commoncode);

            header("ETag: ".wp_hash($commoncode));
        }
    }

    protected function getExtraUris()
    {

        // The extra URIs must be cached! That is because we will change the requested URI in $vars, upon which the hook to inject extra URIs (eg: for page INITIALFRAMES) will stop working
        if (!is_null($this->extra_uris)) {
            return $this->extra_uris;
        }

        $this->extra_uris = array();
        if (Server\Utils::enableExtraurisByParams()) {
            $this->extra_uris = $_REQUEST[GD_URLPARAM_EXTRAURIS] ?? array();
            $this->extra_uris = is_array($this->extra_uris) ? $this->extra_uris : array($this->extra_uris);
        }

        // Enable to add extra URLs in a fixed manner
        $this->extra_uris = apply_filters(
            '\PoP\Engine\Engine:getExtraUris',
            $this->extra_uris
        );

        return $this->extra_uris;
    }

    protected function listExtraUriVars()
    {
        if ($has_extra_uris = !empty($this->getExtraUris())) {
            $model_instance_id = ModelInstanceProcessor_Utils::getModelInstanceId();
            $current_uri = removeDomain(Utils::getCurrentUrl());
        }

        return array($has_extra_uris, $model_instance_id, $current_uri);
    }

    public function generateData()
    {
        do_action('\PoP\Engine\Engine:beginning');

        // Process the request and obtain the results
        $this->data = $this->helperCalculations = array();
        $this->processAndGenerateData();

        // See if there are extra URIs to be processed in this same request
        if ($extra_uris = $this->getExtraUris()) {

            // Combine the response for each extra URI together with the original response, merging all JSON objects together, but under each's URL/model_instance_id

            // To obtain the hierarchy for each URI, we use a hack: change the current URI and create a new WP object, which will process the query_vars and from there obtain the hierarchy
            // First make a backup of the current URI to set it again later
            $current_request_uri = $_SERVER['REQUEST_URI'];

            // Process each extra URI, and merge its results with all others
            foreach ($extra_uris as $uri) {

                // From this hack, we obtain the hierarchy
                $_SERVER['REQUEST_URI'] = $uri;
                
                // Reset $vars so that it gets created anew
                Engine_Vars::reset();

                // Allow functionalities to be reset too. Eg: ActionExecuterBase results
                do_action('\PoP\Engine\Engine:generateData:reset');
                
                // Process the request with the new $vars and merge it with all other results
                // Can't use array_merge_recursive since it creates arrays when the key is the same, which is not what is expected in this case
                $this->processAndGenerateData();
            }

            // Set the previous values back
            $_SERVER['REQUEST_URI'] = $current_request_uri;
            Engine_Vars::reset();
        }

        // Add session/site meta
        $this->addSharedMeta();

        // If any formatter is passed, then format the data accordingly
        $this->formatData();

        // Keep only the data that is needed to be sent, and encode it as JSON
        $this->calculateOutuputData();

        // Send the ETag-header
        $this->sendEtagHeader();
    }

    protected function formatData()
    {
        $formatter = Utils::getDatastructureFormatter();
        $this->data = $formatter->getFormattedData($this->data);
    }

    public function calculateOutuputData()
    {
        $this->outputData = $this->getEncodedDataObject($this->data);
    }

    // Allow PoPFrontend_Engine to override this function
    protected function getEncodedDataObject($data)
    {

        // Comment Leo 14/09/2018: Re-enable here:
        // if (true) {
        //     unset($data['combinedstatedata']);
        // }

        return $data;
    }

    // function triggerOutputHooks() {
        
    //     // Allow extra functionalities. Eg: Save the logged-in user meta information
    //     do_action('\PoP\Engine\Engine:output:end');
    //     do_action('\PoP\Engine\Engine:rendered');
    // }

    // function triggerOutputdataHooks() {
        
    //     // Allow extra functionalities. Eg: Save the logged-in user meta information
    //     do_action('\PoP\Engine\Engine:outputData:end');
    //     do_action('\PoP\Engine\Engine:rendered');
    // }

    // function output() {

    //     // Indicate that this is a json response in the HTTP Header
    //     header('Content-type: application/json');
        
    //     echo $this->encoded_data;

    //     // Allow extra functionalities. Eg: Save the logged-in user meta information
    //     $this->triggerOutputHooks();
    // }

    // function outputData() {

    //     // Indicate that this is a json response in the HTTP Header
    //     header('Content-type: application/json');
        
    //     echo $this->encoded_data;

    //     // Allow extra functionalities. Eg: Save the logged-in user meta information
    //     $this->triggerOutputdataHooks();
    // }

    // function checkRedirect($addoutput) {

    //     $moduleprocessor_manager = ModuleProcessor_Manager_Factory::getInstance();
        
    //     if ($redirect = Settings\SettingsManager_Factory::getInstance()->getRedirectUrl()) {

    //         if ($addoutput) {
                
    //             $redirect = add_query_arg(GD_URLPARAM_OUTPUT, GD_URLPARAM_OUTPUT_JSON, $redirect);

    //             if ($target = $_REQUEST[GD_URLPARAM_TARGET]) {

    //                 $redirect = add_query_arg(GD_URLPARAM_TARGET, $target, $redirect);
    //             }
    //             if ($datastructure = $_REQUEST[GD_URLPARAM_DATASTRUCTURE]) {

    //                 $redirect = add_query_arg(GD_URLPARAM_DATASTRUCTURE, $datastructure, $redirect);
    //             }
    //             if ($mangled = $_REQUEST[GD_URLPARAM_MANGLED]) {

    //                 $redirect = add_query_arg(GD_URLPARAM_MANGLED, $mangled, $redirect);
    //             }
    //             if ($modulefilter = $_REQUEST[GD_URLPARAM_MODULEFILTER]) {

    //                 $redirect = add_query_arg(GD_URLPARAM_MODULEFILTER, $modulefilter, $redirect);
                    
    //                 if ($modulefilter == POP_MODULEFILTER_MODULEPATHS && ($modulepaths = $_REQUEST[GD_URLPARAM_MODULEPATHS])) {

    //                     $redirect = add_query_arg(GD_URLPARAM_MODULEPATHS, $modulepaths, $redirect);
    //                 }
    //                 elseif ($modulefilter == POP_MODULEFILTER_HEADMODULE && ($headmodule = $_REQUEST[GD_URLPARAM_HEADMODULE])) {

    //                     $redirect = add_query_arg(GD_URLPARAM_HEADMODULE, $headmodule, $redirect);
    //                 }
    //             }
    //             if ($actionpath = $_REQUEST[GD_URLPARAM_ACTIONPATH]) {

    //                 $redirect = add_query_arg(GD_URLPARAM_ACTIONPATH, $actionpath, $redirect);
    //             }
    //             if (Server\Utils::enableConfigByParams()) {
                    
    //                 if ($config = $_REQUEST[POP_URLPARAM_CONFIG]) {

    //                     $redirect = add_query_arg(POP_URLPARAM_CONFIG, $config, $redirect);
    //                 }
    //             }
    //         }

    //         $cmsapi = \PoP\CMS\FunctionAPI_Factory::getInstance();
    //         $cmsapi->redirect($redirect); exit;
    //     }
    // }

    // function maybeRedirect() {

    //     $moduleprocessor_manager = ModuleProcessor_Manager_Factory::getInstance();
        
    //     if ($redirect = Settings\SettingsManager_Factory::getInstance()->getRedirectUrl()) {

    //         if ($query = $_SERVER['QUERY_STRING']) {

    //             $redirect .= '?'.$query;
    //         }

    //         $cmsapi = \PoP\CMS\FunctionAPI_Factory::getInstance();
    //         $cmsapi->redirect($redirect); exit;
    //     }
    // }

    public function getModelPropsModuletree($module)
    {
        $cachemanager = CacheManager_Factory::getInstance();
        $moduleprocessor_manager = ModuleProcessor_Manager_Factory::getInstance();

        $processor = $moduleprocessor_manager->getProcessor($module);
        
        // Important: cannot use it if doing POST, because the request may have to be handled by a different block than the one whose data was cached
        // Eg: doing GET on /add-post/ will show the form BLOCK_ADDPOST_CREATE, but doing POST on /add-post/ will bring the action ACTION_ADDPOST_CREATE
        // First check if there's a cache stored
        $useCache = Server\Utils::useCache();
        if ($useCache) {
            $model_props = $cachemanager->getCacheByModelInstance(POP_CACHETYPE_PROPS, true);
        }

        // If there is no cached one, or not using the cache, generate the props and cache it
        if (!$model_props) {
            $model_props = array();
            $processor->initModelPropsModuletree($module, $model_props, array(), array());
            
            if ($useCache) {
                $cachemanager->storeCacheByModelInstance(POP_CACHETYPE_PROPS, $model_props, true);
            }
        }

        return $model_props;
    }

    // Notice that $props is passed by copy, this way the input $model_props and the returned $immutable_plus_request_props are different objects
    public function addRequestPropsModuletree($module, $props)
    {
        $moduleprocessor_manager = ModuleProcessor_Manager_Factory::getInstance();
        $processor = $moduleprocessor_manager->getProcessor($module);
        
        // The input $props is the model_props. We add, on object, the mutableonrequest props, resulting in a "static + mutableonrequest" props object
        $processor->initRequestPropsModuletree($module, $props, array(), array());

        return $props;
    }

    protected function processAndGenerateData()
    {
        $vars = Engine_Vars::getVars();

        // Externalize logic into function so it can be overridden by PoP Frontend Engine
        $dataoutputitems = $vars['dataoutputitems'];

        // From the state we know if to process static/staful content or both
        $datasources = $vars['datasources'];

        // Get the entry module based on the application configuration and the hierarchy
        $module = $this->getEntryModule();

        // Save it to be used by the children class
        // Static props are needed for both static/mutableonrequest operations, so build it always
        $this->model_props = $this->getModelPropsModuletree($module);

        // If only getting static content, then no need to add the mutableonrequest props
        if ($datasources == GD_URLPARAM_DATASOURCES_ONLYMODEL) {
            $this->props = $this->model_props;
        } else {
            $this->props = $this->addRequestPropsModuletree($module, $this->model_props);
        }

        // Allow for extra operations (eg: calculate resources)
        do_action(
            '\PoP\Engine\Engine:helperCalculations',
            array(&$this->helperCalculations),
            $module,
            array(&$this->props)
        );

        // Always send back the requestsettings, which indicates which is the entry module
        $data = array(
        // 'requestsettings' => $this->getRequestSettings($module, $model_props),
        );

        if (in_array(GD_URLPARAM_DATAOUTPUTITEMS_MODULESETTINGS, $dataoutputitems)
            || in_array(GD_URLPARAM_DATAOUTPUTITEMS_DATASETMODULESETTINGS, $dataoutputitems)
        ) {
            $data = array_merge(
                $data,
                $this->getModuleSettings($module, $this->model_props, $this->props)
            );
        }

        // Comment Leo 20/01/2018: we must first initialize all the settings, and only later add the data.
        // That is because calculating the data may need the values from the settings. Eg: for the resourceLoader,
        // calculating $loadingframe_resources needs to know all the Handlebars templates from the sitemapping as to generate file "resources.js",
        // which is done through an action, called through getData()
        // Data = dbobjectids (data-ids) + feedback + database
        if (in_array(GD_URLPARAM_DATAOUTPUTITEMS_MODULEDATA, $dataoutputitems)
            || in_array(GD_URLPARAM_DATAOUTPUTITEMS_DATABASES, $dataoutputitems)
        ) {
            $data = array_merge(
                $data,
                $this->getModuleData($module, $this->model_props, $this->props)
            );

            if (in_array(GD_URLPARAM_DATAOUTPUTITEMS_DATABASES, $dataoutputitems)) {
                $data = array_merge(
                    $data,
                    $this->getDatabases()
                );
            }
        }

        list($has_extra_uris, $model_instance_id, $current_uri) = $this->listExtraUriVars();

        // if (
        //     in_array(GD_URLPARAM_DATAOUTPUTITEMS_MODULESETTINGS, $dataoutputitems) ||
        //     in_array(GD_URLPARAM_DATAOUTPUTITEMS_DATASETMODULESETTINGS, $dataoutputitems) ||
        //     in_array(GD_URLPARAM_DATAOUTPUTITEMS_MODULEDATA, $dataoutputitems)
        // ) {
        if (in_array(GD_URLPARAM_DATAOUTPUTITEMS_META, $dataoutputitems)
        ) {

            // Also add the request, session and site meta.
            // IMPORTANT: Call these methods after doing ->getModuleData, since the background_urls and other info is calculated there and printed here
            if ($requestmeta = $this->getRequestMeta()) {
                $data['requestmeta'] = $has_extra_uris ? array($current_uri => $requestmeta) : $requestmeta;
            }
        }

        // Comment Leo 14/09/2018: Re-enable here:
        // // Combine the statelessdata and mutableonrequestdata objects
        // if ($data['modulesettings']) {

        //     $data['modulesettings']['combinedstate'] = array_merge_recursive(
        //         $data['modulesettings']['immutable'] ?? array()
        //         $data['modulesettings']['mutableonmodel'] ?? array()
        //         $data['modulesettings']['mutableonrequest'] ?? array(),
        //     );
        // }
        // if ($data['moduledata']) {

        //     $data['moduledata']['combinedstate'] = array_merge_recursive(
        //         $data['moduledata']['immutable'] ?? array()
        //         $data['moduledata']['mutableonmodel'] ?? array()
        //         $data['moduledata']['mutableonrequest'] ?? array(),
        //     );
        // }
        // if ($data['datasetmoduledata']) {

        //     $data['datasetmoduledata']['combinedstate'] = array_merge_recursive(
        //         $data['datasetmoduledata']['immutable'] ?? array()
        //         $data['datasetmoduledata']['mutableonmodel'] ?? array()
        //         $data['datasetmoduledata']['mutableonrequest'] ?? array(),
        //     );
        // }

        // Do array_replace_recursive because it may already contain data from doing 'extra-uris'
        $this->data = array_replace_recursive(
            $this->data,
            $data
        );
    }

    protected function addSharedMeta()
    {
        $vars = Engine_Vars::getVars();

        // Externalize logic into function so it can be overridden by PoP Frontend Engine
        $dataoutputitems = $vars['dataoutputitems'];

        // if (
        //     in_array(GD_URLPARAM_DATAOUTPUTITEMS_MODULESETTINGS, $dataoutputitems) ||
        //     in_array(GD_URLPARAM_DATAOUTPUTITEMS_DATASETMODULESETTINGS, $dataoutputitems) ||
        //     in_array(GD_URLPARAM_DATAOUTPUTITEMS_MODULEDATA, $dataoutputitems)
        // ) {
        if (in_array(GD_URLPARAM_DATAOUTPUTITEMS_META, $dataoutputitems)
        ) {

            // Also add the request, session and site meta.
            // IMPORTANT: Call these methods after doing ->getModuleData, since the background_urls and other info is calculated there and printed here
            // If it has extra-uris, pass along this information, so that the client can fetch the setting from under $model_instance_id ("mutableonmodel") and $uri ("mutableonrequest")
            if ($this->getExtraUris()) {
                $this->data['requestmeta'][POP_JS_MULTIPLEURIS] = true;
            }
            if ($sitemeta = $this->getSiteMeta()) {
                $this->data['sitemeta'] = $sitemeta;
            }

            if (in_array(GD_URLPARAM_DATAOUTPUTITEMS_SESSION, $dataoutputitems)) {
                if ($sessionmeta = $this->getSessionMeta()) {
                    $this->data['sessionmeta'] = $sessionmeta;
                }
            }
        }
    }

    public function getModuleSettings($module, $model_props, $props)
    {
        $cachemanager = CacheManager_Factory::getInstance();
        $moduleprocessor_manager = ModuleProcessor_Manager_Factory::getInstance();

        $ret = array();

        $processor = $moduleprocessor_manager->getProcessor($module);
        
        // From the state we know if to process static/staful content or both
        $vars = Engine_Vars::getVars();
        $datasources = $vars['datasources'];
        $dataoutputmode = $vars['dataoutputmode'];
        $dataoutputitems = $vars['dataoutputitems'];

        $add_settings = in_array(GD_URLPARAM_DATAOUTPUTITEMS_MODULESETTINGS, $dataoutputitems);
        $add_datasetsettings = in_array(GD_URLPARAM_DATAOUTPUTITEMS_DATASETMODULESETTINGS, $dataoutputitems);

        // Templates: What modules must be executed after call to loadMore is back with data:
        // CB: list of modules to merge
        $this->cachedsettings = false;

        // First check if there's a cache stored
        $useCache = Server\Utils::useCache();
        if ($useCache) {
            if ($add_settings) {
                $immutable_settings = $cachemanager->getCacheByModelInstance(POP_CACHETYPE_IMMUTABLESETTINGS, true);
                $mutableonmodel_settings = $cachemanager->getCacheByModelInstance(POP_CACHETYPE_STATEFULSETTINGS, true);
            }

            if ($add_datasetsettings) {
                $immutable_datasetsettings = $cachemanager->getCacheByModelInstance(POP_CACHETYPE_IMMUTABLEDATASETSETTINGS, true);
                $mutableonmodel_datasetsettings = $cachemanager->getCacheByModelInstance(POP_CACHETYPE_STATEFULDATASETSETTINGS, true);
            }
        }

        // If there is no cached one, generate the configuration and cache it
        $this->cachedsettings = false;
        if ($immutable_settings || $immutable_datasetsettings) {
            $this->cachedsettings = true;
        } else {
            if ($add_settings) {
                $immutable_settings = $processor->getImmutableSettingsModuletree($module, $model_props);
                $mutableonmodel_settings = $processor->getMutableonmodelSettingsModuletree($module, $model_props);
            }

            if ($add_datasetsettings) {
                $immutable_datasetsettings = $processor->getImmutableSettingsDatasetmoduletree($module, $model_props);
                // $mutableonmodel_datasetsettings = $processor->get_mutableonmodel_settings_datasetmoduletree($module, $model_props);
            }

            if ($useCache) {
                if ($add_settings) {
                    $cachemanager->storeCacheByModelInstance(POP_CACHETYPE_IMMUTABLESETTINGS, $immutable_settings, true);
                    $cachemanager->storeCacheByModelInstance(POP_CACHETYPE_STATEFULSETTINGS, $mutableonmodel_settings, true);
                }

                if ($add_datasetsettings) {
                    $cachemanager->storeCacheByModelInstance(POP_CACHETYPE_IMMUTABLEDATASETSETTINGS, $immutable_datasetsettings, true);
                    // $cachemanager->storeCacheByModelInstance(POP_CACHETYPE_STATEFULDATASETSETTINGS, $mutableonmodel_datasetsettings, true);
                }
            }
        }
        if ($datasources == GD_URLPARAM_DATASOURCES_MODELANDREQUEST) {
            if ($add_settings) {
                $mutableonrequest_settings = $processor->getMutableonrequestSettingsModuletree($module, $props);
            }

            // if ($add_datasetsettings) {
            //     $mutableonrequest_datasetsettings = $processor->get_mutableonrequest_settings_datasetmoduletree($module, $props);
            // }
        }

        // If there are multiple URIs, then the results must be returned under the corresponding $model_instance_id for "mutableonmodel", and $url for "mutableonrequest"
        list($has_extra_uris, $model_instance_id, $current_uri) = $this->listExtraUriVars();

        if ($dataoutputmode == GD_URLPARAM_DATAOUTPUTMODE_SPLITBYSOURCES) {

            // Save the model settings
            if ($add_settings) {
                if ($immutable_settings) {
                    $ret['modulesettings']['immutable'] = $immutable_settings;
                }
                if ($mutableonmodel_settings) {
                    $ret['modulesettings']['mutableonmodel'] = $has_extra_uris ? array($model_instance_id => $mutableonmodel_settings) : $mutableonmodel_settings;
                }
                if ($mutableonrequest_settings) {
                    $ret['modulesettings']['mutableonrequest'] = $has_extra_uris ? array($current_uri => $mutableonrequest_settings) : $mutableonrequest_settings;
                }
            }

            if ($add_datasetsettings) {
                if ($immutable_datasetsettings) {
                    $ret['datasetmodulesettings']['immutable'] = $immutable_datasetsettings;
                }
                // if ($mutableonmodel_datasetsettings) {
                //     $ret['datasetmodulesettings']['mutableonmodel'] = $has_extra_uris ? array($model_instance_id => $mutableonmodel_datasetsettings) : $mutableonmodel_datasetsettings;
                // }
                // if ($mutableonrequest_datasetsettings) {
                //     $ret['datasetmodulesettings']['mutableonrequest'] = $has_extra_uris ? array($current_uri => $mutableonrequest_datasetsettings) : $mutableonrequest_datasetsettings;
                // }
            }
        } elseif ($dataoutputmode == GD_URLPARAM_DATAOUTPUTMODE_COMBINED) {

            // If everything is combined, then it belongs under "mutableonrequest"
            if ($add_settings) {
                if ($combined_settings = array_merge_recursive(
                    $immutable_settings ?? array(),
                    $mutableonmodel_settings ?? array(),
                    $mutableonrequest_settings ?? array()
                )
                ) {
                    $ret['modulesettings'] = $has_extra_uris ? array($current_uri => $combined_settings) : $combined_settings;
                }
            }

            if ($add_datasetsettings) {
                if ($combined_datasetsettings = array_merge_recursive(
                    $immutable_datasetsettings ?? array(),
                    array(), // $mutableonmodel_datasetsettings ?? array(),
                    array()// $mutableonrequest_datasetsettings ?? array()
                )
                ) {
                    $ret['datasetmodulesettings'] = $has_extra_uris ? array($current_uri => $combined_datasetsettings) : $combined_datasetsettings;
                }
            }
        }

        return $ret;
    }

    // function getModuleDatasetsettings($module, $model_props, $props) {

    //     $cachemanager = CacheManager_Factory::getInstance();
    //     $moduleprocessor_manager = ModuleProcessor_Manager_Factory::getInstance();

    //     $ret = array();

    //     $processor = $moduleprocessor_manager->getProcessor($module);
        
    //     // From the state we know if to process static/staful content or both
    //     $vars = Engine_Vars::getVars();
    //     $datasources = $vars['datasources'];
    //     $dataoutputmode = $vars['dataoutputmode'];

    //     // Templates: What modules must be executed after call to loadMore is back with data:
    //     // CB: list of modules to merge
    //     $this->cachedsettings = false;

    //     // First check if there's a cache stored
    //     $useCache = Server\Utils::useCache();
    //     if ($useCache) {
            
    //         $immutable_datasetsettings = $cachemanager->getCacheByModelInstance(POP_CACHETYPE_IMMUTABLEDATASETSETTINGS, true);
    //         $mutableonmodel_datasetsettings = $cachemanager->getCacheByModelInstance(POP_CACHETYPE_STATEFULDATASETSETTINGS, true);
    //     }

    //     // If there is no cached one, generate the configuration and cache it
    //     $this->cachedsettings = false;
    //     if ($immutable_datasetsettings) {

    //         $this->cachedsettings = true;
    //     }
    //     else {

    //         $immutable_datasetsettings = $processor->getImmutableSettingsDatasetmoduletree($module, $model_props);
    //         // $mutableonmodel_datasetsettings = $processor->get_mutableonmodel_settings_datasetmoduletree($module, $model_props);

    //         if ($useCache) {

    //             $cachemanager->storeCacheByModelInstance(POP_CACHETYPE_IMMUTABLEDATASETSETTINGS, $immutable_datasetsettings, true);
    //             // $cachemanager->storeCacheByModelInstance(POP_CACHETYPE_STATEFULDATASETSETTINGS, $mutableonmodel_datasetsettings, true);
    //         }
    //     }
    //     if ($datasources == GD_URLPARAM_DATASOURCES_MODELANDREQUEST) {

    //     //     $mutableonrequest_datasetsettings = $processor->get_mutableonrequest_settings_datasetmoduletree($module, $props);
    //     }

    //     // If there are multiple URIs, then the results must be returned under the corresponding $model_instance_id for "mutableonmodel", and $url for "mutableonrequest"
    //     list($has_extra_uris, $model_instance_id, $current_uri) = $this->listExtraUriVars();

    //     if ($dataoutputmode == GD_URLPARAM_DATAOUTPUTMODE_SPLITBYSOURCES) {

    //         if ($immutable_datasetsettings) {
    //             $ret['datasetmodulesettings']['immutable'] = $immutable_datasetsettings;
    //         }
    //         // if ($mutableonmodel_datasetsettings) {
    //         //     $ret['datasetmodulesettings']['mutableonmodel'] = $has_extra_uris ? array($model_instance_id => $mutableonmodel_datasetsettings) : $mutableonmodel_datasetsettings;
    //         // }
    //         // if ($mutableonrequest_datasetsettings) {
    //         //     $ret['datasetmodulesettings']['mutableonrequest'] = $has_extra_uris ? array($current_uri => $mutableonrequest_datasetsettings) : $mutableonrequest_datasetsettings;
    //         // }
    //     }
    //     elseif ($dataoutputmode == GD_URLPARAM_DATAOUTPUTMODE_COMBINED) {

    //         // If everything is combined, then it belongs under "mutableonrequest"
    //         if ($combined_datasetsettings = array_merge_recursive(
    //             $immutable_datasetsettings ?? array(),
    //             array(),// $mutableonmodel_datasetsettings ?? array(),
    //             array()// $mutableonrequest_datasetsettings ?? array()
    //         )) {
    //             $ret['datasetmodulesettings'] = $has_extra_uris ? array($current_uri => $combined_datasetsettings) : $combined_datasetsettings;
    //         }
    //     }

    //     return $ret;
    // }

    // function getRequestSettings($module, $props) {

    //     return apply_filters(
    //         '\PoP\Engine\Engine:request-settings',
    //         array()
    //     );
    // }

    public function getRequestMeta()
    {
        $meta = array(
        POP_CONSTANT_ENTRYMODULE => $this->getEntryModule(),
        POP_UNIQUEID => POP_CONSTANT_UNIQUE_ID,
        GD_URLPARAM_URL => Utils::getCurrentUrl(),
        'modelinstanceid' => ModelInstanceProcessor_Utils::getModelInstanceId(),
        );
        
        if ($this->backgroundload_urls) {
            $meta[GD_URLPARAM_BACKGROUNDLOADURLS] = $this->backgroundload_urls;
        };

        // Starting from what modules must do the rendering. Allow for empty arrays (eg: modulepaths[]=somewhatevervalue)
        $modulefilter_manager = ModuleFilterManager_Factory::getInstance();
        $not_excluded_module_sets = $modulefilter_manager->getNotExcludedModuleSets();
        if (!is_null($not_excluded_module_sets)) {
            
            // Print the settings id of each module. Then, a module can feed data to another one by sharing the same settings id (eg: POP_MODULE_BLOCK_USERAVATAR_EXECUTEUPDATE and POP_MODULE_BLOCK_USERAVATAR_UPDATE)
            $filteredsettings = array();
            foreach ($not_excluded_module_sets as $modules) {
                $filteredsettings[] = array_map(
                    function ($module) {
                        $moduleprocessor_manager = ModuleProcessor_Manager_Factory::getInstance();
                        return $moduleprocessor_manager->getProcessor($module)->getSettingsId($module);
                    },
                    $modules
                );
            }

            $meta['filteredmodules'] = $filteredsettings;
        }

        // Any errors? Send them back
        if (Utils::$errors) {
            if (count(Utils::$errors) > 1) {
                $meta[GD_URLPARAM_ERROR] = __('Ops, there were some errors:', 'pop-engine').implode('<br/>', Utils::$errors);
            } else {
                $meta[GD_URLPARAM_ERROR] = __('Ops, there was an error: ', 'pop-engine').Utils::$errors[0];
            }
        }

        return apply_filters(
            '\PoP\Engine\Engine:request-meta',
            $meta
        );
    }

    public function getSessionMeta()
    {
        return apply_filters(
            '\PoP\Engine\Engine:session-meta',
            array()
        );
    }

    public function getSiteMeta()
    {
        $meta = array();
        if (Utils::fetchingSite()) {
            $vars = Engine_Vars::getVars();

            // $meta['domain'] = getSiteUrl();
            $meta['sitename'] = getBloginfo('name');

            $meta[GD_DATALOAD_PARAMS] = array();

            // Comment Leo 05/04/2017: Create the params array only in the fetchingSite()
            // Before it was outside, and calling the initial-frames page brought params=[],
            // and this was overriding the params in the topLevelFeedback removing all info there

            // Add the version to the topLevel feedback to be sent in the URL params
            $meta[GD_DATALOAD_PARAMS][GD_URLPARAM_VERSION] = popVersion();
            $meta[GD_DATALOAD_PARAMS][GD_URLPARAM_DATAOUTPUTMODE] = $vars['dataoutputmode'];
            $meta[GD_DATALOAD_PARAMS][GD_URLPARAM_DATABASESOUTPUTMODE] = $vars['dboutputmode'];

            if ($vars['format']) {
                $meta[GD_DATALOAD_PARAMS][GD_URLPARAM_SETTINGSFORMAT] = $vars['format'];
            }
            if ($vars['mangled']) {
                $meta[GD_DATALOAD_PARAMS][GD_URLPARAM_MANGLED] = $vars['mangled'];
            }
            if (Server\Utils::enableConfigByParams() && $vars['config']) {
                $meta[GD_DATALOAD_PARAMS][POP_URLPARAM_CONFIG] = $vars['config'];
            }

            // Tell the front-end: are the results from the cache? Needed for the editor, to initialize it since WP will not execute the code
            if (!is_null($this->cachedsettings)) {
                $meta['cachedsettings'] = $this->cachedsettings;
            };
        }
        return apply_filters(
            '\PoP\Engine\Engine:site-meta',
            $meta
        );
    }

    private function combineIdsDatafields(&$ids_data_fields, $dataloader_name, $ids, $data_fields)
    {
        $ids_data_fields[$dataloader_name] = $ids_data_fields[$dataloader_name] ?? array();
        foreach ($ids as $id) {

            // Make sure to always add the 'id' data-field, since that's the key for the dbobject in the client database
            $ids_data_fields[$dataloader_name][$id] = $ids_data_fields[$dataloader_name][$id] ?? array('id');
            $ids_data_fields[$dataloader_name][$id] = array_unique(
                array_merge(
                    $ids_data_fields[$dataloader_name][$id],
                    $data_fields ?? array()
                )
            );
        }
    }

    private function addDatasetToDatabase(&$database, $database_key, $dataitems)
    {

        // Do not create the database key entry when there are no items, or it produces an error when deep merging the database object in the frontend with that from the response
        if (!$dataitems) {
            return;
        }

        // Save in the database under the corresponding database-key (this way, different dataloaders, like 'list-users' and 'author',
        // can both save their results under database key 'users'
        if (!$database[$database_key]) {
            $database[$database_key] = $dataitems;
        } else {
            // array_merge_recursive doesn't work as expected (it merges 2 hashmap arrays into an array, so then I manually do a foreach instead)
            foreach ($dataitems as $dbobject_id => $dbobject_values) {
                if (!$database[$database_key][$dbobject_id]) {
                    $database[$database_key][$dbobject_id] = array();
                }

                $database[$database_key][$dbobject_id] = array_merge(
                    $database[$database_key][$dbobject_id],
                    $dbobject_values
                );
            }
        }
    }

    protected function getInterreferencedModuleFullpaths($module, &$props)
    {
        $paths = array();
        $this->addInterreferencedModuleFullpaths($paths, array(), $module, $props);
        return $paths;
    }

    private function addInterreferencedModuleFullpaths(&$paths, $module_path, $module, &$props)
    {
        $moduleprocessor_manager = ModuleProcessor_Manager_Factory::getInstance();
        $processor = $moduleprocessor_manager->getProcessor($module);

        $modulefilter_manager = ModuleFilterManager_Factory::getInstance();
        
        // If modulepaths is provided, and we haven't reached the destination module yet, then do not execute the function at this level
        if (!$modulefilter_manager->excludeModule($module, $props)) {
            
            // If the current module loads data, then add its path to the list
            if ($interreferenced_modulepath = $processor->getDataFeedbackInterreferencedModulepath($module, $props)) {
                $referenced_modulepath = ModulePathManager_Utils::stringifyModulePath($interreferenced_modulepath);
                $paths[$referenced_modulepath] = $paths[$referenced_modulepath] ?? array();
                $paths[$referenced_modulepath][] = array_merge(
                    $module_path,
                    array(
                    $module
                    )
                );
            }
        }

        $submodule_path = array_merge(
            $module_path,
            array(
            $module,
            )
        );
        
        // Propagate to its inner modules
        $submodules = $processor->getDescendantModules($module);
        $submodules = $modulefilter_manager->removeExcludedSubmodules($module, $submodules);
        
        // This function must be called always, to register matching modules into requestmeta.filtermodules even when the module has no submodules
        $module_path_manager = ModulePathManager_Factory::getInstance();
        $module_path_manager->prepareForPropagation($module);
        foreach ($submodules as $submodule) {
            $this->addInterreferencedModuleFullpaths($paths, $submodule_path, $submodule, $props[$module][POP_PROPS_MODULES]);
        }
        $module_path_manager->restoreFromPropagation($module);
    }

    protected function getDataloadingModuleFullpaths($module, &$props)
    {
        $paths = array();
        $this->addDataloadingModuleFullpaths($paths, array(), $module, $props);
        return $paths;
    }

    private function addDataloadingModuleFullpaths(&$paths, $module_path, $module, &$props)
    {
        $moduleprocessor_manager = ModuleProcessor_Manager_Factory::getInstance();
        $processor = $moduleprocessor_manager->getProcessor($module);

        $modulefilter_manager = ModuleFilterManager_Factory::getInstance();
        
        // If modulepaths is provided, and we haven't reached the destination module yet, then do not execute the function at this level
        if (!$modulefilter_manager->excludeModule($module, $props)) {
            
            // If the current module loads data, then add its path to the list
            if ($processor->getDataloader($module)) {
                $paths[] = array_merge(
                    $module_path,
                    array(
                    $module
                    )
                );
            }
        }

        $submodule_path = array_merge(
            $module_path,
            array(
            $module,
            )
        );
        
        // Propagate to its inner modules
        $submodules = $processor->getDescendantModules($module);
        $submodules = $modulefilter_manager->removeExcludedSubmodules($module, $submodules);
        
        // This function must be called always, to register matching modules into requestmeta.filtermodules even when the module has no submodules
        $module_path_manager = ModulePathManager_Factory::getInstance();
        $module_path_manager->prepareForPropagation($module);
        foreach ($submodules as $submodule) {
            $this->addDataloadingModuleFullpaths($paths, $submodule_path, $submodule, $props[$module][POP_PROPS_MODULES]);
        }
        $module_path_manager->restoreFromPropagation($module);
    }

    protected function assignValueForModule(&$array, $module_path, $module, $key, $value)
    {
        $moduleprocessor_manager = ModuleProcessor_Manager_Factory::getInstance();

        $array_pointer = &$array;
        foreach ($module_path as $submodule) {

            // Notice that when generating the array for the response, we don't use $module anymore, but $settings_id
            $submodule_settings_id = $moduleprocessor_manager->getProcessor($submodule)->getSettingsId($submodule);

            // If the path doesn't exist, create it
            if (!isset($array_pointer[$submodule_settings_id][GD_JS_MODULES])) {
                $array_pointer[$submodule_settings_id][GD_JS_MODULES] = array();
            }

            // The pointer is the location in the array where the value will be set
            $array_pointer = &$array_pointer[$submodule_settings_id][GD_JS_MODULES];
        }

        $settings_id = $moduleprocessor_manager->getProcessor($module)->getSettingsId($module);
        $array_pointer[$settings_id][$key] = $value;
    }

    protected function validateCheckpoints($checkpoints, $module)
    {
        $checkpointprocessor_manager = CheckpointProcessor_Manager_Factory::getInstance();

        // Iterate through the list of all checkpoints, process all of them, if any produces an error, already return it
        foreach ($checkpoints as $checkpoint) {
            $result = $checkpointprocessor_manager->process($checkpoint, $module);
            if (is_wp_error($result)) {
                return $result;
            }
        }

        return true;
    }

    protected function getModulePathKey($module_path, $module)
    {
        return $module.'-'.implode('.', $module_path);
    }

    // This function is not private, so it can be accessed by the automated emails to regenerate the html for each user
    public function getModuleData($root_module, $root_model_props, $root_props)
    {
        $cachemanager = CacheManager_Factory::getInstance();
        $moduleprocessor_manager = ModuleProcessor_Manager_Factory::getInstance();

        $root_processor = $moduleprocessor_manager->getProcessor($root_module);

        // From the state we know if to process static/staful content or both
        $vars = Engine_Vars::getVars();
        $datasources = $vars['datasources'];
        $dataoutputmode = $vars['dataoutputmode'];
        $dataoutputitems = $vars['dataoutputitems'];
        $add_meta = in_array(GD_URLPARAM_DATAOUTPUTITEMS_META, $dataoutputitems);

        $immutable_moduledata = $mutableonmodel_moduledata = $mutableonrequest_moduledata = array();
        $immutable_datasetmoduledata = $mutableonmodel_datasetmoduledata = $mutableonrequest_datasetmoduledata = array();
        if ($add_meta) {
            $immutable_datasetmodulemeta = $mutableonmodel_datasetmodulemeta = $mutableonrequest_datasetmodulemeta = array();
        }
        $this->dbdata = array();

        // Save all the BACKGROUND_LOAD urls to send back to the browser, to load immediately again (needed to fetch non-cacheable data-fields)
        $this->backgroundload_urls = array();

        // Load under global key (shared by all pagesections / blocks)
        $this->ids_data_fields = array();

        // Allow PoP UserState to add the lazy-loaded userstate data triggers
        do_action(
            '\PoP\Engine\Engine:getModuleData:start',
            $root_module,
            array(&$root_model_props),
            array(&$root_props),
            array(&$this->helperCalculations),
            $this
        );

        $useCache = Server\Utils::useCache();

        // First check if there's a cache stored
        if ($useCache) {
            $immutable_data_properties = $cachemanager->getCacheByModelInstance(POP_CACHETYPE_STATICDATAPROPERTIES, true);
            $mutableonmodel_data_properties = $cachemanager->getCacheByModelInstance(POP_CACHETYPE_STATEFULDATAPROPERTIES, true);
        }

        // If there is no cached one, generate the props and cache it
        if (!$immutable_data_properties) {
            $immutable_data_properties = $root_processor->getImmutableDataPropertiesDatasetmoduletree($root_module, $root_model_props);
            $mutableonmodel_data_properties = $root_processor->getMutableonmodelDataPropertiesDatasetmoduletree($root_module, $root_model_props);
            if ($useCache) {
                $cachemanager->storeCacheByModelInstance(POP_CACHETYPE_STATICDATAPROPERTIES, $immutable_data_properties, true);
                $cachemanager->storeCacheByModelInstance(POP_CACHETYPE_STATEFULDATAPROPERTIES, $mutableonmodel_data_properties, true);
            }
        }

        $model_data_properties = array_merge_recursive(
            $immutable_data_properties,
            $mutableonmodel_data_properties
        );

        if ($datasources == GD_URLPARAM_DATASOURCES_ONLYMODEL) {
            $root_data_properties = $model_data_properties;
        } else {
            $mutableonrequest_data_properties = $root_processor->getMutableonrequestDataPropertiesDatasetmoduletree($root_module, $root_props);
            $root_data_properties = array_merge_recursive(
                $model_data_properties,
                $mutableonrequest_data_properties
            );
        }

        // Get the list of all modules which calculate their data feedback using another module's results
        $interreferenced_modulefullpaths = $this->getInterreferencedModuleFullpaths($root_module, $root_props);

        // Get the list of all modules which load data, as a list of the module path starting from the top element (the entry module)
        $module_fullpaths = $this->getDataloadingModuleFullpaths($root_module, $root_props);

        $module_path_manager = ModulePathManager_Factory::getInstance();

        // The modules below are already included, so tell the filtermanager to not validate if they must be excluded or not
        $modulefilter_manager = ModuleFilterManager_Factory::getInstance();
        $modulefilter_manager->neverExclude(true);
        foreach ($module_fullpaths as $module_path) {

            // The module is the last element in the path.
            // Notice that the module is removed from the path, providing the path to all its properties
            $module = array_pop($module_path);

            // Artificially set the current path on the path manager. It will be needed in getDatasetmeta, which calls getDataloadSource, which needs the current path
            $module_path_manager->setPropagationCurrentPath($module_path);

            // Data Properties: assign by reference, so that changes to this variable are also performed in the original variable
            $data_properties = &$root_data_properties;
            foreach ($module_path as $submodule) {
                $data_properties = &$data_properties[$submodule][GD_JS_MODULES];
            }
            $data_properties = &$data_properties[$module][POP_CONSTANT_DATAPROPERTIES];
            $datasource = $data_properties[GD_DATALOAD_DATASOURCE];

            // If we are only requesting data from the model alone, and this dataloading module depends on mutableonrequest, then skip it
            if ($datasources == GD_URLPARAM_DATASOURCES_ONLYMODEL && $datasource == POP_DATALOAD_DATASOURCE_MUTABLEONREQUEST) {
                continue;
            }

            // Load data if the property Skip Data Load is not true
            $load_data = !$data_properties[GD_DATALOAD_SKIPDATALOAD];

            // ------------------------------------------
            // Checkpoint validation
            // ------------------------------------------
            // Load data if the checkpoint did not fail
            if ($load_data && $checkpoints = $data_properties[GD_DATALOAD_DATAACCESSCHECKPOINTS]) {
                
                // Check if the module fails checkpoint validation. If so, it must not load its data or execute the actionexecuter
                $dataaccess_checkpoint_validation = $this->validateCheckpoints($checkpoints, $module);
                $load_data = !is_wp_error($dataaccess_checkpoint_validation);
            }

            // The $props is directly moving the array to the corresponding path
            $props = &$root_props;
            $model_props = &$root_model_props;
            foreach ($module_path as $submodule) {
                $props = &$props[$submodule][POP_PROPS_MODULES];
                $model_props = &$model_props[$submodule][POP_PROPS_MODULES];
            }

            if (in_array(
                $datasource,
                array(
                POP_DATALOAD_DATASOURCE_IMMUTABLE,
                POP_DATALOAD_DATASOURCE_MUTABLEONMODEL,
                )
            )
            ) {
                $module_props = &$model_props;
            } elseif ($datasource == POP_DATALOAD_DATASOURCE_MUTABLEONREQUEST) {
                $module_props = &$props;
            }

            $processor = $moduleprocessor_manager->getProcessor($module);

            // The module path key is used for storing temporary results for later retrieval
            $module_path_key = $this->getModulePathKey($module_path, $module);

            // If data is not loaded, then an empty array will be saved for the dbobject ids
            $dbobjectids = $dataset_meta = array();
            $executed = null;
            if ($load_data) {

                // ------------------------------------------
                // Action Executers
                // ------------------------------------------
                // Allow to plug-in functionality here (eg: form submission)
                // Execute at the very beginning, so the result of the execution can also be fetched later below
                // (Eg: creation of a new location => retrieving its data / Adding a new comment)
                // Pass data_properties so these can also be modified (eg: set id of newly created Location)
                if ($actionexecuter_name = $processor->getActionexecuter($module)) {
                    if ($processor->executeAction($module, $props)) {

                        // Validate that the actionexecution must be triggered through its own checkpoints
                        $execute = true;
                        if ($actionexecution_checkpoints = $data_properties[GD_DATALOAD_ACTIONEXECUTIONCHECKPOINTS]) {
                            
                            // Check if the module fails checkpoint validation. If so, it must not load its data or execute the actionexecuter
                            $actionexecution_checkpoint_validation = $this->validateCheckpoints($actionexecution_checkpoints, $module);
                            $execute = !is_wp_error($actionexecution_checkpoint_validation);
                        }

                        if ($execute) {
                            $gd_dataload_actionexecution_manager = ActionExecution_Manager_Factory::getInstance();
                            $actionexecuter = $gd_dataload_actionexecution_manager->getActionexecuter($actionexecuter_name);
                            $executed = $actionexecuter->execute($data_properties);
                        }
                    }
                }

                // Allow modules to change their data_properties based on the actionexecution of previous modules.
                $processor->prepareDataPropertiesAfterActionexecution($module, $module_props, $data_properties);

                // Re-calculate $data_load, it may have been changed by `prepareDataPropertiesAfterActionexecution`
                $load_data = !$data_properties[GD_DATALOAD_SKIPDATALOAD];
                if ($load_data) {

                    // ------------------------------------------
                    // Data Properties Query Args: add mutableonrequest data
                    // ------------------------------------------
                    // Execute and get the ids and the meta
                    $dbobjectids = $processor->getDbobjectIds($module, $module_props, $data_properties);

                    // Store the ids under $data under key dataload_name => id
                    $dataloader_name = $processor->getDataloader($module);
                    $data_fields = $data_properties['data-fields'] ?? array();
                    $this->combineIdsDatafields($this->ids_data_fields, $dataloader_name, $dbobjectids, $data_fields);

                    // Add the IDs to the possibly-already produced IDs for this dataloader/pageSection/Block
                    $this->initializeDataloaderEntry($this->dbdata, $dataloader_name, $module_path_key);
                    $this->dbdata[$dataloader_name][$module_path_key]['ids'] = array_merge(
                        $this->dbdata[$dataloader_name][$module_path_key]['ids'],
                        $dbobjectids
                    );

                    // The supplementary dbobject data is independent of the dataloader of the block.
                    // Even if it is STATIC, the extend ids must be loaded. That's why we load the extend now,
                    // Before checking below if the checkpoint failed or if the block content must not be loaded.
                    // Eg: Locations Map for the Create Individual Profile: it allows to pre-select locations,
                    // these ones must be fetched even if the block has a static dataloader
                    // If it has extend, add those ids under its dataloader_name
                    $dataload_extend_settings = $processor->getModelSupplementaryDbobjectdataModuletree($module, $model_props);
                    if ($datasource == POP_DATALOAD_DATASOURCE_MUTABLEONREQUEST) {
                        $dataload_extend_settings = array_merge_recursive(
                            $dataload_extend_settings,
                            $processor->getMutableonrequestSupplementaryDbobjectdataModuletree($module, $props)
                        );
                    }
                    foreach ($dataload_extend_settings as $extend_dataloader_name => $extend_data_properties) {
                        
                         // Get the info for the subcomponent dataloader
                        $extend_data_fields = $extend_data_properties['data-fields'] ? $extend_data_properties['data-fields'] : array();
                        $extend_ids = $extend_data_properties['ids'];
                                
                        $this->combineIdsDatafields($this->ids_data_fields, $extend_dataloader_name, $extend_ids, $extend_data_fields);

                        // This is needed to add the dataloader-extend IDs, for if nobody else creates an entry for this dataloader
                        $this->initializeDataloaderEntry($this->dbdata, $extend_dataloader_name, $module_path_key);
                    }

                    // Keep iterating for its subcomponents
                    $this->integrateSubcomponentDataProperties($this->dbdata, $data_properties, $dataloader_name, $module_path_key);
                }
            }

            // Save the results on either the static or mutableonrequest branches
            if ($datasource == POP_DATALOAD_DATASOURCE_IMMUTABLE) {
                $datasetmoduledata = &$immutable_datasetmoduledata;
                if ($add_meta) {
                    $datasetmodulemeta = &$immutable_datasetmodulemeta;
                }
                $this->moduledata = &$immutable_moduledata;
            } elseif ($datasource == POP_DATALOAD_DATASOURCE_MUTABLEONMODEL) {
                $datasetmoduledata = &$mutableonmodel_datasetmoduledata;
                if ($add_meta) {
                    $datasetmodulemeta = &$mutableonmodel_datasetmodulemeta;
                }
                $this->moduledata = &$mutableonmodel_moduledata;
            } elseif ($datasource == POP_DATALOAD_DATASOURCE_MUTABLEONREQUEST) {
                $datasetmoduledata = &$mutableonrequest_datasetmoduledata;
                if ($add_meta) {
                    $datasetmodulemeta = &$mutableonrequest_datasetmodulemeta;
                }
                $this->moduledata = &$mutableonrequest_moduledata;
            }

            // Integrate the dbobjectids into $datasetmoduledata
            // ALWAYS print the $dbobjectids, even if its an empty array. This to indicate that this is a dataloading module, so the application in the frontend knows if to load a new batch of dbobjectids, or reuse the ones from the previous module when iterating down
            if (!is_null($datasetmoduledata)) {
                $this->assignValueForModule($datasetmoduledata, $module_path, $module, POP_CONSTANT_DBOBJECTIDS, $dbobjectids);
            }

            // Save the meta into $datasetmodulemeta
            if ($add_meta) {
                if (!is_null($datasetmodulemeta)) {
                    if ($dataset_meta = $processor->getDatasetmeta($module, $module_props, $data_properties, $dataaccess_checkpoint_validation, $actionexecution_checkpoint_validation, $executed, $dbobjectids)) {
                        $this->assignValueForModule($datasetmodulemeta, $module_path, $module, POP_CONSTANT_META, $dataset_meta);
                    }
                }
            }

            // Integrate the feedback into $moduledata
            $this->processAndAddModuleData($module_path, $module, $module_props, $data_properties, $dataaccess_checkpoint_validation, $actionexecution_checkpoint_validation, $executed, $dbobjectids);

            // Allow other modules to produce their own feedback using this module's data results
            if ($referencer_modulefullpaths = $interreferenced_modulefullpaths[ModulePathManager_Utils::stringifyModulePath(array_merge($module_path, array($module)))]) {
                foreach ($referencer_modulefullpaths as $referencer_modulepath) {
                    $referencer_module = array_pop($referencer_modulepath);

                    $referencer_props = &$root_props;
                    $referencer_model_props = &$root_model_props;
                    foreach ($referencer_modulepath as $submodule) {
                        $referencer_props = &$referencer_props[$submodule][POP_PROPS_MODULES];
                        $referencer_model_props = &$referencer_model_props[$submodule][POP_PROPS_MODULES];
                    }

                    if (in_array(
                        $datasource,
                        array(
                        POP_DATALOAD_DATASOURCE_IMMUTABLE,
                        POP_DATALOAD_DATASOURCE_MUTABLEONMODEL,
                        )
                    )
                    ) {
                        $referencer_module_props = &$referencer_model_props;
                    } elseif ($datasource == POP_DATALOAD_DATASOURCE_MUTABLEONREQUEST) {
                        $referencer_module_props = &$referencer_props;
                    }
                    $this->processAndAddModuleData($referencer_modulepath, $referencer_module, $referencer_module_props, $data_properties, $dataaccess_checkpoint_validation, $actionexecution_checkpoint_validation, $executed, $dbobjectids);
                }
            }

            // Incorporate the background URLs
            $this->backgroundload_urls = array_merge(
                $this->backgroundload_urls,
                $processor->getBackgroundurlsMergeddatasetmoduletree($module, $module_props, $data_properties, $dataaccess_checkpoint_validation, $actionexecution_checkpoint_validation, $executed, $dbobjectids)
            );

            // Allow PoP UserState to add the lazy-loaded userstate data triggers
            do_action(
                '\PoP\Engine\Engine:getModuleData:dataloading-module',
                $module,
                array(&$module_props),
                array(&$data_properties),
                $dataaccess_checkpoint_validation,
                $actionexecution_checkpoint_validation,
                $executed,
                $dbobjectids,
                array(&$this->helperCalculations),
                $this
            );
        }

        // Reset the filtermanager state and the pathmanager current path
        $modulefilter_manager->neverExclude(false);
        $module_path_manager->setPropagationCurrentPath();

        $ret = array();

        if (in_array(GD_URLPARAM_DATAOUTPUTITEMS_MODULEDATA, $dataoutputitems)) {

            // If there are multiple URIs, then the results must be returned under the corresponding $model_instance_id for "mutableonmodel", and $url for "mutableonrequest"
            list($has_extra_uris, $model_instance_id, $current_uri) = $this->listExtraUriVars();

            if ($dataoutputmode == GD_URLPARAM_DATAOUTPUTMODE_SPLITBYSOURCES) {
                if ($immutable_moduledata) {
                    $ret['moduledata']['immutable'] = $immutable_moduledata;
                }
                if ($mutableonmodel_moduledata) {
                    $ret['moduledata']['mutableonmodel'] = $has_extra_uris ? array($model_instance_id => $mutableonmodel_moduledata) : $mutableonmodel_moduledata;
                }
                if ($mutableonrequest_moduledata) {
                    $ret['moduledata']['mutableonrequest'] = $has_extra_uris ? array($current_uri => $mutableonrequest_moduledata) : $mutableonrequest_moduledata;
                }
                if ($immutable_datasetmoduledata) {
                    $ret['datasetmoduledata']['immutable'] = $immutable_datasetmoduledata;
                }
                if ($mutableonmodel_datasetmoduledata) {
                    $ret['datasetmoduledata']['mutableonmodel'] = $has_extra_uris ? array($model_instance_id => $mutableonmodel_datasetmoduledata) : $mutableonmodel_datasetmoduledata;
                }
                if ($mutableonrequest_datasetmoduledata) {
                    $ret['datasetmoduledata']['mutableonrequest'] = $has_extra_uris ? array($current_uri => $mutableonrequest_datasetmoduledata) : $mutableonrequest_datasetmoduledata;
                }

                if ($add_meta) {
                    if ($immutable_datasetmodulemeta) {
                        $ret['datasetmodulemeta']['immutable'] = $immutable_datasetmodulemeta;
                    }
                    if ($mutableonmodel_datasetmodulemeta) {
                        $ret['datasetmodulemeta']['mutableonmodel'] = $has_extra_uris ? array($model_instance_id => $mutableonmodel_datasetmodulemeta) : $mutableonmodel_datasetmodulemeta;
                    }
                    if ($mutableonrequest_datasetmodulemeta) {
                        $ret['datasetmodulemeta']['mutableonrequest'] = $has_extra_uris ? array($current_uri => $mutableonrequest_datasetmodulemeta) : $mutableonrequest_datasetmodulemeta;
                    }
                }
            } elseif ($dataoutputmode == GD_URLPARAM_DATAOUTPUTMODE_COMBINED) {

                // If everything is combined, then it belongs under "mutableonrequest"
                if ($combined_moduledata = array_merge_recursive(
                    $immutable_moduledata ?? array(),
                    $mutableonmodel_moduledata ?? array(),
                    $mutableonrequest_moduledata ?? array()
                )
                ) {
                    $ret['moduledata'] = $has_extra_uris ? array($current_uri => $combined_moduledata) : $combined_moduledata;
                }
                if ($combined_datasetmoduledata = array_merge_recursive(
                    $immutable_datasetmoduledata ?? array(),
                    $mutableonmodel_datasetmoduledata ?? array(),
                    $mutableonrequest_datasetmoduledata ?? array()
                )
                ) {
                    $ret['datasetmoduledata'] = $has_extra_uris ? array($current_uri => $combined_datasetmoduledata) : $combined_datasetmoduledata;
                }
                if ($add_meta) {
                    if ($combined_datasetmodulemeta = array_merge_recursive(
                        $immutable_datasetmodulemeta ?? array(),
                        $mutableonmodel_datasetmodulemeta ?? array(),
                        $mutableonrequest_datasetmodulemeta ?? array()
                    )
                    ) {
                        $ret['datasetmodulemeta'] = $has_extra_uris ? array($current_uri => $combined_datasetmodulemeta) : $combined_datasetmodulemeta;
                    }
                }
            }
        }

        // Allow PoP UserState to add the lazy-loaded userstate data triggers
        do_action(
            '\PoP\Engine\Engine:getModuleData:end',
            $root_module,
            array(&$root_model_props),
            array(&$root_props),
            array(&$this->helperCalculations),
            $this
        );

        return $ret;
    }

    public function getDatabases()
    {
        $dataloader_manager = Dataloader_Manager_Factory::getInstance();
        $dataquery_manager = DataQuery_Manager_Factory::getInstance();
        
        $vars = Engine_Vars::getVars();
        $dboutputmode = $vars['dboutputmode'];
        $formatter = Utils::getDatastructureFormatter();

        // Save all database elements here, under dataloader
        $databases = array();
        $this->nocache_fields = array();
        $format = $vars['format'];

        // Keep an object with all fetched IDs/fields for each dataloader. Then, we can keep using the same dataloader as subcomponent,
        // but we need to avoid fetching those DB objects that were already fetched in a previous iteration
        $already_loaded_ids_data_fields = array();
        $subcomponent_data_fields = array();

        // Iterate while there are dataloaders with data to be processed
        while (!empty($this->ids_data_fields)) {

            // Move the pointer to the first element, and get it
            reset($this->ids_data_fields);
            $dataloader_name = key($this->ids_data_fields);
            $dataloader_ids_data_fields = $this->ids_data_fields[$dataloader_name];

            // Remove the dataloader element from the array, so it doesn't process it anymore
            // Do it immediately, so that subcomponents can load new IDs for this current dataloader (eg: posts => related)
            unset($this->ids_data_fields[$dataloader_name]);

            // If no ids to execute, then skip
            if (empty($dataloader_ids_data_fields)) {
                continue;
            }

            // Store the loaded IDs/fields in an object, to avoid fetching them again in later iterations on the same dataloader
            $already_loaded_ids_data_fields[$dataloader_name] = $already_loaded_ids_data_fields[$dataloader_name] ?? array();
            $already_loaded_ids_data_fields[$dataloader_name] = array_merge_recursive(
                $already_loaded_ids_data_fields[$dataloader_name],
                $dataloader_ids_data_fields
            );

            $dataloader = $dataloader_manager->get($dataloader_name);
            $database_key = $dataloader->getDatabaseKey();

            // Execute the dataloader for all combined ids
            $resultset = $dataloader->getData($dataloader_ids_data_fields);
            $dataitems = $dataloader->getDataitems($formatter, $resultset, $dataloader_ids_data_fields);

            // Save in the database under the corresponding database-key (this way, different dataloaders, like 'list-users' and 'author',
            // can both save their results under database key 'users'
            // Plugin PoP User Login: Also save those results which depend on the logged-in user. These are treated separately because:
            // 1: They contain personal information, so it must be erased from the front-end as soon as the user logs out
            // 2: These results make the page state-full, so this page is not cacheable
            // By splitting the results into state-full and state-less, we can split all functionality into cacheable and non-cacheable,
            // thus caching most of the website even for logged-in users
            foreach ($dataitems['dbitems'] as $dbname => $dbitems) {
                $this->addDatasetToDatabase($databases[$dbname], $database_key, $dbitems);
            }

            // Keep the list of elements that must be retrieved once again from the server
            if ($dataquery_name = $dataloader->getDataquery()) {
                $dataquery = $dataquery_manager->get($dataquery_name);
                $objectid_fieldname = $dataquery->getObjectidFieldname();
                
                // Force retrieval of data from the server. Eg: recommendpost-count
                $forceserverload_fields = $dataquery->getNocachefields();
                
                // Lazy fields. Eg: comments
                $lazylayouts = $dataquery->getLazylayouts();
                $lazyload_fields = array_keys($lazylayouts);
                
                // Store the intersected fields and the corresponding ids
                $forceserverload = array(
                 'ids' => array(),
                 'fields' => array()
                );
                $lazyload = array(
                 'ids' => array(),
                 'layouts' => array()
                );

                // Compare the fields in the result dbobjectids, with the dataquery's specified list of fields that must always be retrieved from the server
                // (eg: comment-count, since adding a comment doesn't delete the cache)
                foreach ($dataitems['dbobjectids'] as $dataitem_id) {

                       // Get the fields requested to that dataitem, for both the database and user database
                    $dataitem_fields = array_merge(
                        array_keys($dataitems['dataitems'][$dataitem_id] ?? array()),
                        array_keys($dataitems['user-dataitems'][$dataitem_id] ?? array())
                    );

                    // Intersect these with the fields that must be loaded from server
                    // Comment Leo 31/03/2017: do it only if we are not currently in the noncacheable_page
                    // If we are, then we came here loading a backgroundload-url, and we don't need to load it again
                    // Otherwise, it would create an infinite loop, since the fields loaded here are, exactly, those defined in the noncacheable_fields
                    // Eg: https://www.mesym.com/en/loaders/posts/data/?pid[0]=21636&pid[1]=21632&pid[2]=21630&pid[3]=21628&pid[4]=21624&pid[5]=21622&fields[0]=recommendpost-count&fields[1]=recommendpost-count-plus1&fields[2]=userpostactivity-count&format=updatedata
                    if (!Utils::isPage($dataquery->getNoncacheablePage())) {
                        if ($intersect = array_values(array_intersect($dataitem_fields, $forceserverload_fields))) {
                            $forceserverload['ids'][] = $dataitem_id;
                            $forceserverload['fields'] = array_merge(
                                $forceserverload['fields'],
                                $intersect
                            );
                        }
                    }

                    // Intersect these with the lazyload fields
                    if ($intersect = array_values(array_intersect($dataitem_fields, $lazyload_fields))) {
                        $lazyload['ids'][] = $dataitem_id;
                        foreach ($intersect as $field) {
                            
                                            // Get the layout for the current format, if it exists, or the default one if not
                            $lazyload['layouts'][] = $lazylayouts[$field][$format] ?? $lazylayouts[$field]['default'];
                        }
                    }
                }
                if ($forceserverload['ids']) {
                    $forceserverload['fields'] = array_unique($forceserverload['fields']);

                    $url = getPermalink($dataquery->getNoncacheablePage());
                    $url = add_query_arg($objectid_fieldname, $forceserverload['ids'], $url);
                    $url = add_query_arg(GD_URLPARAM_FIELDS, $forceserverload['fields'], $url);
                    $url = add_query_arg(GD_URLPARAM_FORMAT, POP_FORMAT_FIELDS, $url);
                    $this->backgroundload_urls[urldecode($url)] = array(POP_TARGET_MAIN);

                    // Keep the nocache fields to remove those from the code when generating the ETag
                    $this->nocache_fields = array_merge(
                        $this->nocache_fields,
                        $forceserverload['fields']
                    );
                }
                if ($lazyload['ids']) {
                    $lazyload['layouts'] = array_unique($lazyload['layouts']);

                    $url = getPermalink($dataquery->getCacheablePage());
                    $url = add_query_arg($objectid_fieldname, $lazyload['ids'], $url);
                    $url = add_query_arg(GD_URLPARAM_LAYOUTS, $lazyload['layouts'], $url);
                    $url = add_query_arg(GD_URLPARAM_FORMAT, POP_FORMAT_LAYOUTS, $url);
                    $this->backgroundload_urls[urldecode($url)] = array(POP_TARGET_MAIN);
                }
            }


            // Important: query like this: obtain keys first instead of iterating directly on array, because it will keep adding elements
            $dataloader_dbdata = $this->dbdata[$dataloader_name];
            foreach (array_keys($dataloader_dbdata) as $module_path_key) {
                $dataloader_data = &$this->dbdata[$dataloader_name][$module_path_key];

                unset($this->dbdata[$dataloader_name][$module_path_key]);

                // Check if it has subcomponents, and then bring this data
                if ($subcomponents_data_properties = $dataloader_data['subcomponents']) {
                    $dataloader_ids = $dataloader_data['ids'];
                    foreach ($subcomponents_data_properties as $subcomponent_data_field => $subcomponent_dataloder_data_properties) {
                        // $subcomponent_module_path_key = $module_path_key.'.'.$subcomponent_data_field;
                        // $subcomponent_module_path_key = $module_path_key;
                        foreach ($subcomponent_dataloder_data_properties as $subcomponent_dataloader_name => $subcomponent_data_properties) {

                            // If the subcomponent dataloader is not explicitly set in `getDbobjectRelationalSuccessors`, then retrieve it now from the current dataloader's fieldprocessor
                            if ($subcomponent_dataloader_name == POP_CONSTANT_SUBCOMPONENTDATALOADER_DEFAULTFROMFIELD) {
                                $subcomponent_dataloader_name = DataloadUtils::getDefaultDataloaderNameFromSubcomponentDataField($dataloader, $subcomponent_data_field);
                            }

                            // If passing a subcomponent fieldname that doesn't exist to the API, then $subcomponent_dataloader_name will be empty
                            if ($subcomponent_dataloader_name) {
                            
                                 // The array_merge_recursive when there are at least 2 levels will make the data_fields to be duplicated, so remove duplicates now
                                if ($subcomponent_data_fields = array_unique($subcomponent_data_properties['data-fields'] ?? array())) {
                                    $subcomponent_already_loaded_ids_data_fields = array();
                                    if ($already_loaded_ids_data_fields && $already_loaded_ids_data_fields[$subcomponent_dataloader_name]) {
                                        $subcomponent_already_loaded_ids_data_fields = $already_loaded_ids_data_fields[$subcomponent_dataloader_name];
                                    }
                                    foreach ($dataloader_ids as $id) {
                                
                                        // $databases may contain more the 1 DB shipped by pop-engine/ ("primary"). Eg: PoP User Login adds db "userstate"
                                        // Fetch the field_ids from all these DBs
                                        $field_ids = array();
                                        foreach ($databases as $dbname => $database) {
                                            if ($database_field_ids = $database[$database_key][$id][$subcomponent_data_field]) {
                                                $field_ids = array_merge(
                                                    $field_ids,
                                                    is_array($database_field_ids) ? $database_field_ids : array($database_field_ids)
                                                );
                                            }
                                        }
                                        if ($field_ids) {
                                            foreach ($field_ids as $field_id) {

                                                // Do not add again the IDs/Fields already loaded
                                                if ($subcomponent_already_loaded_data_fields = $subcomponent_already_loaded_ids_data_fields[$field_id]) {
                                                    $id_subcomponent_data_fields = array_values(
                                                        array_diff(
                                                            $subcomponent_data_fields,
                                                            $subcomponent_already_loaded_data_fields
                                                        )
                                                    );
                                                } else {
                                                    $id_subcomponent_data_fields = $subcomponent_data_fields;
                                                }

                                                if ($id_subcomponent_data_fields) {
                                                    $this->combineIdsDatafields($this->ids_data_fields, $subcomponent_dataloader_name, array($field_id), $id_subcomponent_data_fields);
                                                    $this->initializeDataloaderEntry($this->dbdata, $subcomponent_dataloader_name, $module_path_key/*$subcomponent_module_path_key*/);
                                                    $this->dbdata[$subcomponent_dataloader_name][$module_path_key/*$subcomponent_module_path_key*/]['ids'][] = $field_id;
                                                    $this->integrateSubcomponentDataProperties($this->dbdata, $subcomponent_data_properties, $subcomponent_dataloader_name, $module_path_key/*$subcomponent_module_path_key*/);
                                                }
                                            }
                                        }
                                    }

                                    if ($this->dbdata[$subcomponent_dataloader_name][$module_path_key/*$subcomponent_module_path_key*/]) {
                                        $this->dbdata[$subcomponent_dataloader_name][$module_path_key/*$subcomponent_module_path_key*/]['ids'] = array_unique($this->dbdata[$subcomponent_dataloader_name][$module_path_key/*$subcomponent_module_path_key*/]['ids']);
                                        $this->dbdata[$subcomponent_dataloader_name][$module_path_key/*$subcomponent_module_path_key*/]['data-fields'] = array_unique($this->dbdata[$subcomponent_dataloader_name][$module_path_key/*$subcomponent_module_path_key*/]['data-fields']);
                                    }
                                }
                            }
                        }
                    }
                }
            }
            // }
        }

        $ret = array();
        
        // Do not add the "database", "userstatedatabase" entries unless there are values in them
        // Otherwise, it messes up integrating the current databases in the frontend with those from the response when deep merging them
        if ($databases) {

            // Combine all the databases or send them separate
            if ($dboutputmode == GD_URLPARAM_DATABASESOUTPUTMODE_SPLITBYDATABASES) {
                $ret['databases'] = $databases;
            } elseif ($dboutputmode == GD_URLPARAM_DATABASESOUTPUTMODE_COMBINED) {
                $combined_databases = array();
                foreach ($databases as $database_name => $database) {
                    $combined_databases = array_merge_recursive(
                        $combined_databases,
                        $database
                    );
                }
                $ret['databases'] = $combined_databases;
            }
        }

        return $ret;
    }

    protected function processAndAddModuleData($module_path, $module, &$props, $data_properties, $dataaccess_checkpoint_validation, $actionexecution_checkpoint_validation, $executed, $dbobjectids)
    {
        $moduleprocessor_manager = ModuleProcessor_Manager_Factory::getInstance();
        $processor = $moduleprocessor_manager->getProcessor($module);
        
        // Integrate the feedback into $moduledata
        if (!is_null($this->moduledata)) {
            $moduledata = &$this->moduledata;

            // Add the feedback into the object
            if ($feedback = $processor->getDataFeedbackDatasetmoduletree($module, $props, $data_properties, $dataaccess_checkpoint_validation, $actionexecution_checkpoint_validation, $executed, $dbobjectids)) {

                // Advance the position of the array into the current module
                foreach ($module_path as $submodule) {
                    $submodule_settings_id = $moduleprocessor_manager->getProcessor($submodule)->getSettingsId($submodule);
                    $moduledata[$submodule_settings_id][GD_JS_MODULES] = $moduledata[$submodule_settings_id][GD_JS_MODULES] ?? array();
                    $moduledata = &$moduledata[$submodule_settings_id][GD_JS_MODULES];
                }
                // Merge the feedback in
                $moduledata = array_merge_recursive(
                    $moduledata,
                    $feedback
                );
            }
        }
    }

    private function initializeDataloaderEntry(&$dbdata, $dataloader_name, $module_path_key)
    {
        if (is_null($dbdata[$dataloader_name][$module_path_key])) {
            $dbdata[$dataloader_name][$module_path_key] = array(
            'ids' => array(),
            'data-fields' => array(),
            'subcomponents' => array(),
            );
        }
    }

    private function integrateSubcomponentDataProperties(&$dbdata, $data_properties, $dataloader_name, $module_path_key)
    {

        // Process the subcomponents
        // If it has subcomponents, bring its data to, after executing getData on the primary dataloader, execute getData also on the subcomponent dataloader
        if ($subcomponents_data_properties = $data_properties['subcomponents']) {
            
            // Merge them into the data
            $dbdata[$dataloader_name][$module_path_key]['subcomponents'] = array_merge_recursive(
                $dbdata[$dataloader_name][$module_path_key]['subcomponents'] ?? array(),
                $subcomponents_data_properties
            );
                
            // foreach ($subcomponents_data_properties as $subcomponent_data_field => $subcomponent_dataloader_data_properties) {
                
            //     $subcomponent_module_path_key = $module_path_key.'.'.$subcomponent_data_field;
            //     foreach ($subcomponent_dataloader_data_properties as $subcomponent_dataloader_name => $subcomponent_data_properties) {
                    
            //         // Get the info for the subcomponent dataloader
            //         $subcomponent_data_fields = $subcomponent_data_properties['data-fields'];
                    
            //         // Add to data (but do not bring the ids yet, this comes as a result of getData on the parent dataloader)
            //         $this->initializeDataloaderEntry($dbdata, $subcomponent_dataloader_name, $subcomponent_module_path_key);
            //         $dbdata[$subcomponent_dataloader_name][$subcomponent_module_path_key]['data-fields'] = array_unique(array_merge(
            //             $dbdata[$subcomponent_dataloader_name][$subcomponent_module_path_key]['data-fields'],
            //             $subcomponent_data_fields ?? array()
            //         ));

            //         // // Recursion: Keep including levels below
            //         // if ($subcomponent_subcomponents = $subcomponent_data_properties['subcomponents']) {
                        
            //         //     $dbdata[$subcomponent_dataloader_name][$subcomponent_module_path_key]['subcomponents'] = array_merge_recursive(
            //         //         $dbdata[$subcomponent_dataloader_name][$subcomponent_module_path_key]['subcomponents'],
            //         //         $subcomponent_subcomponents
            //         //     );
            //         //     $this->integrateSubcomponentDataProperties($dbdata, $subcomponent_data_properties, $subcomponent_dataloader_name, $subcomponent_module_path_key);
            //         // }
            //     }
            // }
        }
    }
}

/**
 * Initialization
 */
new Engine();

<?php

declare(strict_types=1);

namespace PoP\ComponentModel\Engine;

use Exception;
use PoP\ComponentModel\App;
use PoP\ComponentModel\Cache\PersistentCacheInterface;
use PoP\ComponentModel\CheckpointProcessors\CheckpointProcessorManagerInterface;
use PoP\ComponentModel\Component;
use PoP\ComponentModel\ComponentConfiguration;
use PoP\ComponentModel\ComponentInfo;
use PoP\ComponentModel\Configuration\Request;
use PoP\ComponentModel\Constants\Actions;
use PoP\ComponentModel\Constants\DatabasesOutputModes;
use PoP\ComponentModel\Constants\DataLoading;
use PoP\ComponentModel\Constants\DataOutputItems;
use PoP\ComponentModel\Constants\DataOutputModes;
use PoP\ComponentModel\Constants\DataSources;
use PoP\ComponentModel\Constants\DataSourceSelectors;
use PoP\ComponentModel\Constants\Params;
use PoP\ComponentModel\Constants\Props;
use PoP\ComponentModel\Constants\Response;
use PoP\ComponentModel\DataStructure\DataStructureManagerInterface;
use PoP\ComponentModel\EntryModule\EntryModuleManagerInterface;
use PoP\ComponentModel\Environment;
use PoP\ComponentModel\Error\Error;
use PoP\ComponentModel\Feedback\DocumentFeedbackInterface;
use PoP\ComponentModel\Feedback\GeneralFeedbackInterface;
use PoP\ComponentModel\Feedback\Tokens;
use PoP\ComponentModel\HelperServices\DataloadHelperServiceInterface;
use PoP\ComponentModel\HelperServices\RequestHelperServiceInterface;
use PoP\ComponentModel\Info\ApplicationInfoInterface;
use PoP\ComponentModel\Misc\GeneralUtils;
use PoP\ComponentModel\ModelInstance\ModelInstanceInterface;
use PoP\ComponentModel\ModuleFiltering\ModuleFilterManagerInterface;
use PoP\ComponentModel\ModulePath\ModulePathHelpersInterface;
use PoP\ComponentModel\ModulePath\ModulePathManagerInterface;
use PoP\ComponentModel\ModuleProcessors\DataloadingConstants;
use PoP\ComponentModel\ModuleProcessors\ModuleProcessorManagerInterface;
use PoP\ComponentModel\Modules\ModuleHelpersInterface;
use PoP\FieldQuery\FeedbackMessageStoreInterface;
use PoP\ComponentModel\Schema\FieldQueryInterpreterInterface;
use PoP\ComponentModel\TypeResolvers\ObjectType\ObjectTypeResolverInterface;
use PoP\ComponentModel\TypeResolvers\RelationalTypeResolverInterface;
use PoP\ComponentModel\TypeResolvers\UnionType\UnionTypeHelpers;
use PoP\ComponentModel\TypeResolvers\UnionType\UnionTypeResolverInterface;
use PoP\Definitions\Constants\Params as DefinitionsParams;
use PoP\Root\Helpers\Methods;
use PoP\Root\Services\BasicServiceTrait;

class Engine implements EngineInterface
{
    use BasicServiceTrait;

    public const CACHETYPE_IMMUTABLEDATASETSETTINGS = 'static-datasetsettings';
    public const CACHETYPE_STATICDATAPROPERTIES = 'static-data-properties';
    public const CACHETYPE_STATEFULDATAPROPERTIES = 'stateful-data-properties';
    public const CACHETYPE_PROPS = 'props';

    private ?PersistentCacheInterface $persistentCache = null;
    private ?DataStructureManagerInterface $dataStructureManager = null;
    private ?ModelInstanceInterface $modelInstance = null;
    private ?FeedbackMessageStoreInterface $feedbackMessageStore = null;
    private ?ModulePathHelpersInterface $modulePathHelpers = null;
    private ?ModulePathManagerInterface $modulePathManager = null;
    private ?FieldQueryInterpreterInterface $fieldQueryInterpreter = null;
    private ?ModuleFilterManagerInterface $moduleFilterManager = null;
    private ?ModuleProcessorManagerInterface $moduleProcessorManager = null;
    private ?CheckpointProcessorManagerInterface $checkpointProcessorManager = null;
    private ?DataloadHelperServiceInterface $dataloadHelperService = null;
    private ?EntryModuleManagerInterface $entryModuleManager = null;
    private ?RequestHelperServiceInterface $requestHelperService = null;
    private ?ApplicationInfoInterface $applicationInfo = null;
    private ?ModuleHelpersInterface $moduleHelpers = null;

    /**
     * Cannot autowire with "#[Required]" because its calling `getNamespace`
     * on services.yaml produces an exception of PHP properties not initialized
     * in its depended services.
     */
    final public function setPersistentCache(PersistentCacheInterface $persistentCache): void
    {
        $this->persistentCache = $persistentCache;
    }
    final public function getPersistentCache(): PersistentCacheInterface
    {
        return $this->persistentCache ??= $this->instanceManager->getInstance(PersistentCacheInterface::class);
    }
    final public function setDataStructureManager(DataStructureManagerInterface $dataStructureManager): void
    {
        $this->dataStructureManager = $dataStructureManager;
    }
    final protected function getDataStructureManager(): DataStructureManagerInterface
    {
        return $this->dataStructureManager ??= $this->instanceManager->getInstance(DataStructureManagerInterface::class);
    }
    final public function setModelInstance(ModelInstanceInterface $modelInstance): void
    {
        $this->modelInstance = $modelInstance;
    }
    final protected function getModelInstance(): ModelInstanceInterface
    {
        return $this->modelInstance ??= $this->instanceManager->getInstance(ModelInstanceInterface::class);
    }
    final public function setFeedbackMessageStore(FeedbackMessageStoreInterface $feedbackMessageStore): void
    {
        $this->feedbackMessageStore = $feedbackMessageStore;
    }
    final protected function getFeedbackMessageStore(): FeedbackMessageStoreInterface
    {
        return $this->feedbackMessageStore ??= $this->instanceManager->getInstance(FeedbackMessageStoreInterface::class);
    }
    final public function setModulePathHelpers(ModulePathHelpersInterface $modulePathHelpers): void
    {
        $this->modulePathHelpers = $modulePathHelpers;
    }
    final protected function getModulePathHelpers(): ModulePathHelpersInterface
    {
        return $this->modulePathHelpers ??= $this->instanceManager->getInstance(ModulePathHelpersInterface::class);
    }
    final public function setModulePathManager(ModulePathManagerInterface $modulePathManager): void
    {
        $this->modulePathManager = $modulePathManager;
    }
    final protected function getModulePathManager(): ModulePathManagerInterface
    {
        return $this->modulePathManager ??= $this->instanceManager->getInstance(ModulePathManagerInterface::class);
    }
    final public function setFieldQueryInterpreter(FieldQueryInterpreterInterface $fieldQueryInterpreter): void
    {
        $this->fieldQueryInterpreter = $fieldQueryInterpreter;
    }
    final protected function getFieldQueryInterpreter(): FieldQueryInterpreterInterface
    {
        return $this->fieldQueryInterpreter ??= $this->instanceManager->getInstance(FieldQueryInterpreterInterface::class);
    }
    final public function setModuleFilterManager(ModuleFilterManagerInterface $moduleFilterManager): void
    {
        $this->moduleFilterManager = $moduleFilterManager;
    }
    final protected function getModuleFilterManager(): ModuleFilterManagerInterface
    {
        return $this->moduleFilterManager ??= $this->instanceManager->getInstance(ModuleFilterManagerInterface::class);
    }
    final public function setModuleProcessorManager(ModuleProcessorManagerInterface $moduleProcessorManager): void
    {
        $this->moduleProcessorManager = $moduleProcessorManager;
    }
    final protected function getModuleProcessorManager(): ModuleProcessorManagerInterface
    {
        return $this->moduleProcessorManager ??= $this->instanceManager->getInstance(ModuleProcessorManagerInterface::class);
    }
    final public function setCheckpointProcessorManager(CheckpointProcessorManagerInterface $checkpointProcessorManager): void
    {
        $this->checkpointProcessorManager = $checkpointProcessorManager;
    }
    final protected function getCheckpointProcessorManager(): CheckpointProcessorManagerInterface
    {
        return $this->checkpointProcessorManager ??= $this->instanceManager->getInstance(CheckpointProcessorManagerInterface::class);
    }
    final public function setDataloadHelperService(DataloadHelperServiceInterface $dataloadHelperService): void
    {
        $this->dataloadHelperService = $dataloadHelperService;
    }
    final protected function getDataloadHelperService(): DataloadHelperServiceInterface
    {
        return $this->dataloadHelperService ??= $this->instanceManager->getInstance(DataloadHelperServiceInterface::class);
    }
    final public function setEntryModuleManager(EntryModuleManagerInterface $entryModuleManager): void
    {
        $this->entryModuleManager = $entryModuleManager;
    }
    final protected function getEntryModuleManager(): EntryModuleManagerInterface
    {
        return $this->entryModuleManager ??= $this->instanceManager->getInstance(EntryModuleManagerInterface::class);
    }
    final public function setRequestHelperService(RequestHelperServiceInterface $requestHelperService): void
    {
        $this->requestHelperService = $requestHelperService;
    }
    final protected function getRequestHelperService(): RequestHelperServiceInterface
    {
        return $this->requestHelperService ??= $this->instanceManager->getInstance(RequestHelperServiceInterface::class);
    }
    final public function setApplicationInfo(ApplicationInfoInterface $applicationInfo): void
    {
        $this->applicationInfo = $applicationInfo;
    }
    final protected function getApplicationInfo(): ApplicationInfoInterface
    {
        return $this->applicationInfo ??= $this->instanceManager->getInstance(ApplicationInfoInterface::class);
    }
    final public function setModuleHelpers(ModuleHelpersInterface $moduleHelpers): void
    {
        $this->moduleHelpers = $moduleHelpers;
    }
    final protected function getModuleHelpers(): ModuleHelpersInterface
    {
        return $this->moduleHelpers ??= $this->instanceManager->getInstance(ModuleHelpersInterface::class);
    }

    public function getOutputData(): array
    {
        $engineState = App::getEngineState();
        return $engineState->outputData;
    }

    public function addBackgroundUrl(string $url, array $targets): void
    {
        $engineState = App::getEngineState();
        $engineState->backgroundload_urls[$url] = $targets;
    }

    public function getEntryModule(): array
    {
        $engineState = App::getEngineState();

        // Use cached results
        if ($engineState->entryModule !== null) {
            return $engineState->entryModule;
        }

        // Obtain, validate and cache
        $engineState->entryModule = $this->getEntryModuleManager()->getEntryModule();
        if ($engineState->entryModule === null) {
            throw new Exception(
                $this->__('No entry module for this request', 'component-model')
            );
        }

        return $engineState->entryModule;
    }

    /**
     * Maybe produce the ETag header.
     *
     * ETag is needed for the Service Workers.
     *
     * Also needed to use together with the Control-Cache header,
     * to know when to refetch data from the server.
     *
     * @see https://developers.google.com/web/fundamentals/performance/optimizing-content-efficiency/http-caching
     */
    protected function getEtagHeader(): ?string
    {
        $addEtagHeader = App::applyFilters('\PoP\ComponentModel\Engine:outputData:addEtagHeader', true);
        if (!$addEtagHeader) {
            return null;
        }

        $engineState = App::getEngineState();

        /**
         * The same page will have different hashs only because
         * of those random elements added each time, such as the unique_id
         * and the current_time.
         *
         * So remove these to generate the hash.
         */
        /** @var ComponentInfo */
        $componentInfo = App::getComponent(Component::class)->getInfo();
        $differentiators = array(
            $componentInfo->getUniqueID(),
            $componentInfo->getRand(),
            $componentInfo->getTime(),
        );
        $commoncode = str_replace($differentiators, '', json_encode($engineState->data));

        /**
         * Also replace all those tags with content that, even if it's different,
         * should not alter the output.
         *
         * Eg: comments-count. Because adding a comment does not delete the cache,
         * then the comments-count is allowed to be shown stale.
         * So if adding a new comment, there's no need for the user to receive the
         * "This page has been updated, click here to refresh it." notification.
         *
         * Because we already got the JSON, then remove entries of the type:
         * "userpostactivity-count":1, (if there are more elements after)
         * and
         * "userpostactivity-count":1
         *
         * Please notice: ?module=settings doesn't have 'nocache-fields'
         */
        if ($engineState->nocache_fields) {
            $commoncode = preg_replace('/"(' . implode('|', $engineState->nocache_fields) . ')":[0-9]+,?/', '', $commoncode);
        }

        // Allow plug-ins to replace their own non-needed content (eg: thumbprints, defined in Core)
        $commoncode = App::applyFilters(
            '\PoP\ComponentModel\Engine:etag_header:commoncode',
            $commoncode
        );
        return hash('md5', $commoncode);
    }

    public function getExtraRoutes(): array
    {
        $engineState = App::getEngineState();

        // The extra URIs must be cached! That is because we will change the requested URI in $vars, upon which the hook to inject extra URIs (eg: for page INITIALFRAMES) will stop working
        if ($engineState->extra_routes !== null) {
            return $engineState->extra_routes;
        }

        $engineState->extra_routes = [];

        /** Only enable for an HTTP request */
        if (!App::isHTTPRequest()) {
            return $engineState->extra_routes;
        }

        if (Environment::enableExtraRoutesByParams()) {
            $engineState->extra_routes = Request::getExtraRoutes();
        }

        // Enable to add extra URLs in a fixed manner
        $engineState->extra_routes = App::applyFilters(
            '\PoP\ComponentModel\Engine:getExtraRoutes',
            $engineState->extra_routes
        );

        return $engineState->extra_routes;
    }

    public function listExtraRouteVars(): array
    {
        $model_instance_id = $current_uri = null;
        if ($has_extra_routes = $this->getExtraRoutes() !== []) {
            $model_instance_id = $this->getModelInstance()->getModelInstanceId();
            $current_uri = GeneralUtils::removeDomain(
                $this->getRequestHelperService()->getCurrentURL()
            );
        }

        return array($has_extra_routes, $model_instance_id, $current_uri);
    }

    public function generateDataAndPrepareResponse(): void
    {
        $this->generateData();
        $this->prepareResponse();
    }

    /**
     * Get the data, format it into the content, and set it
     * (and the headers) on the Response
     */
    protected function prepareResponse(): void
    {
        $data = $this->getOutputData();
        $dataStructureFormatter = $this->getDataStructureManager()->getDataStructureFormatter();
        $outputContent = $dataStructureFormatter->getOutputContent($data);

        // 3. Prepare the Response
        $response = App::getResponse();
        $response->setContent($outputContent);
        foreach ($this->getHeaders() as $name => $value) {
            $response->headers->set($name, $value);
        }
    }

    /**
     * @return array<string,string>
     */
    protected function getHeaders(): array
    {
        $headers = [];

        // Maybe add the ETag header
        $etagHeader = $this->getEtagHeader();
        if ($etagHeader !== null) {
            $headers['ETag'] = $etagHeader;
        }

        // Add the content type header
        $dataStructureFormatter = $this->getDataStructureManager()->getDataStructureFormatter();
        if ($contentType = $dataStructureFormatter->getContentType()) {
            $headers['Content-Type'] = $contentType;
        }

        return $headers;
    }

    protected function generateData(): void
    {
        // Reset the state
        App::regenerateEngineState();
        App::regenerateFeedbackStore();
        App::regenerateMutationResolutionStore();

        App::doAction('\PoP\ComponentModel\Engine:beginning');

        // Process the request and obtain the results
        $this->processAndGenerateData();

        /**
         * See if there are extra URIs to be processed in this same request.
         *
         * Combine the response for each extra URI together with the original response,
         * merging all JSON objects together, but under each's URL/model_instance_id.
         *
         * To obtain the nature for each URI, we use a hack:
         * change the current URI and create a new WP object,
         * which will process the query_vars and from there obtain the nature.
         */
        if ($extra_routes = $this->getExtraRoutes()) {
            // First make a backup of the current URI to set it again later
            $currentRoute = App::getState('route');

            $appStateManager = App::getAppStateManager();

            // Process each extra URI, and merge its results with all others
            foreach ($extra_routes as $route) {
                // Reset $vars so that it gets created anew
                $appStateManager->override('route', $route);

                // Process the request with the new $vars and merge it with all other results
                $this->processAndGenerateData();
            }

            // Set the previous values back
            $appStateManager->override('route', $currentRoute);
        }

        // Add session/site meta
        $this->addSharedMeta();

        // If any formatter is passed, then format the data accordingly
        $this->formatData();

        // Keep only the data that is needed to be sent, and encode it as JSON
        $this->calculateOutputData();
    }

    protected function formatData(): void
    {
        $engineState = App::getEngineState();
        $dataStructureFormatter = $this->getDataStructureManager()->getDataStructureFormatter();
        $engineState->data = $dataStructureFormatter->getFormattedData($engineState->data);
    }

    public function calculateOutputData(): void
    {
        $engineState = App::getEngineState();
        $engineState->outputData = $this->getEncodedDataObject($engineState->data);
    }

    // Allow PoPWebPlatform_Engine to override this function
    protected function getEncodedDataObject(array $data): array
    {
        // Comment Leo 14/09/2018: Re-enable here:
        // if (true) {
        //     unset($data['combinedstatedata']);
        // }
        return $data;
    }

    public function getModelPropsModuletree(array $module): array
    {
        /** @var ComponentConfiguration */
        $componentConfiguration = App::getComponent(Component::class)->getConfiguration();
        $useCache = $componentConfiguration->useComponentModelCache();
        $processor = $this->getModuleProcessorManager()->getProcessor($module);

        // Important: cannot use it if doing POST, because the request may have to be handled by a different block than the one whose data was cached
        // Eg: doing GET on /add-post/ will show the form BLOCK_ADDPOST_CREATE, but doing POST on /add-post/ will bring the action ACTION_ADDPOST_CREATE
        // First check if there's a cache stored
        $model_props = null;
        if ($useCache) {
            $model_props = $this->getPersistentCache()->getCacheByModelInstance(self::CACHETYPE_PROPS);
        }

        // If there is no cached one, or not using the cache, generate the props and cache it
        if ($model_props === null) {
            $model_props = [];
            $processor->initModelPropsModuletree($module, $model_props, [], []);

            if ($useCache) {
                $this->getPersistentCache()->storeCacheByModelInstance(self::CACHETYPE_PROPS, $model_props);
            }
        }

        return $model_props;
    }

    // Notice that $props is passed by copy, this way the input $model_props and the returned $immutable_plus_request_props are different objects
    public function addRequestPropsModuletree(array $module, array $props): array
    {
        $processor = $this->getModuleProcessorManager()->getProcessor($module);

        // The input $props is the model_props. We add, on object, the mutableonrequest props, resulting in a "static + mutableonrequest" props object
        $processor->initRequestPropsModuletree($module, $props, [], []);

        return $props;
    }

    protected function processAndGenerateData(): void
    {
        // Externalize logic into function so it can be overridden by PoP Web Platform Engine
        $dataoutputitems = App::getState('dataoutputitems');

        // From the state we know if to process static/staful content or both
        $datasourceselector = App::getState('datasourceselector');

        // Get the entry module based on the application configuration and the nature
        $module = $this->getEntryModule();

        $engineState = App::getEngineState();

        // Save it to be used by the children class
        // Static props are needed for both static/mutableonrequest operations, so build it always
        $engineState->model_props = $this->getModelPropsModuletree($module);

        // If only getting static content, then no need to add the mutableonrequest props
        if ($datasourceselector == DataSourceSelectors::ONLYMODEL) {
            $engineState->props = $engineState->model_props;
        } else {
            $engineState->props = $this->addRequestPropsModuletree($module, $engineState->model_props);
        }

        // Allow for extra operations (eg: calculate resources)
        App::doAction(
            '\PoP\ComponentModel\Engine:helperCalculations',
            array(&$engineState->helperCalculations),
            $module,
            array(&$engineState->props)
        );

        $data = [];
        if (in_array(DataOutputItems::DATASET_MODULE_SETTINGS, $dataoutputitems)) {
            $data = array_merge(
                $data,
                $this->getModuleDatasetSettings($module, $engineState->model_props, $engineState->props)
            );
        }

        // Comment Leo 20/01/2018: we must first initialize all the settings, and only later add the data.
        // That is because calculating the data may need the values from the settings. Eg: for the resourceLoader,
        // calculating $loadingframe_resources needs to know all the Handlebars templates from the sitemapping as to generate file "resources.js",
        // which is done through an action, called through getData()
        // Data = dbobjectids (data-ids) + feedback + database
        if (
            in_array(DataOutputItems::MODULE_DATA, $dataoutputitems)
            || in_array(DataOutputItems::DATABASES, $dataoutputitems)
        ) {
            $data = array_merge(
                $data,
                $this->getModuleData($module, $engineState->model_props, $engineState->props)
            );

            if (in_array(DataOutputItems::DATABASES, $dataoutputitems)) {
                $data = array_merge(
                    $data,
                    $this->generateDatabases()
                );
            }
        }

        list($has_extra_routes, $model_instance_id, $current_uri) = $this->listExtraRouteVars();

        if (
            in_array(DataOutputItems::META, $dataoutputitems)
        ) {
            // Also add the request, session and site meta.
            // IMPORTANT: Call these methods after doing ->getModuleData, since the background_urls and other info is calculated there and printed here
            if ($requestmeta = $this->getRequestMeta()) {
                $data['requestmeta'] = $has_extra_routes ? array($current_uri => $requestmeta) : $requestmeta;
            }
        }

        // Comment Leo 14/09/2018: Re-enable here:
        // // Combine the statelessdata and mutableonrequestdata objects
        // if ($data['modulesettings'] ?? null) {

        //     $data['modulesettings']['combinedstate'] = array_merge_recursive(
        //         $data['modulesettings']['immutable'] ?? []
        //         $data['modulesettings']['mutableonmodel'] ?? []
        //         $data['modulesettings']['mutableonrequest'] ?? [],
        //     );
        // }
        // if ($data['moduledata'] ?? null) {

        //     $data['moduledata']['combinedstate'] = array_merge_recursive(
        //         $data['moduledata']['immutable'] ?? []
        //         $data['moduledata']['mutableonmodel'] ?? []
        //         $data['moduledata']['mutableonrequest'] ?? [],
        //     );
        // }
        // if ($data['datasetmoduledata'] ?? null) {

        //     $data['datasetmoduledata']['combinedstate'] = array_merge_recursive(
        //         $data['datasetmoduledata']['immutable'] ?? []
        //         $data['datasetmoduledata']['mutableonmodel'] ?? []
        //         $data['datasetmoduledata']['mutableonrequest'] ?? [],
        //     );
        // }

        // Do array_replace_recursive because it may already contain data from doing 'extra-uris'
        $engineState->data = array_replace_recursive(
            $engineState->data,
            $data
        );
    }

    protected function addSharedMeta(): void
    {
        // Externalize logic into function so it can be overridden by PoP Web Platform Engine
        $dataoutputitems = App::getState('dataoutputitems');

        if (
            in_array(DataOutputItems::META, $dataoutputitems)
        ) {
            $engineState = App::getEngineState();

            // Also add the request, session and site meta.
            // IMPORTANT: Call these methods after doing ->getModuleData, since the background_urls and other info is calculated there and printed here
            // If it has extra-uris, pass along this information, so that the client can fetch the setting from under $model_instance_id ("mutableonmodel") and $uri ("mutableonrequest")
            if ($this->getExtraRoutes()) {
                $engineState->data['requestmeta'][Response::MULTIPLE_ROUTES] = true;
            }
            if ($sitemeta = $this->getSiteMeta()) {
                $engineState->data['sitemeta'] = $sitemeta;
            }

            if (in_array(DataOutputItems::SESSION, $dataoutputitems)) {
                if ($sessionmeta = $this->getSessionMeta()) {
                    $engineState->data['sessionmeta'] = $sessionmeta;
                }
            }
        }
    }

    public function getModuleDatasetSettings(array $module, $model_props, array &$props): array
    {
        $ret = [];
        /** @var ComponentConfiguration */
        $componentConfiguration = App::getComponent(Component::class)->getConfiguration();
        $useCache = $componentConfiguration->useComponentModelCache();
        $processor = $this->getModuleProcessorManager()->getProcessor($module);
        $engineState = App::getEngineState();

        // From the state we know if to process static/staful content or both
        $dataoutputmode = App::getState('dataoutputmode');

        // First check if there's a cache stored
        $immutable_datasetsettings = null;
        if ($useCache) {
            $immutable_datasetsettings = $this->getPersistentCache()->getCacheByModelInstance(self::CACHETYPE_IMMUTABLEDATASETSETTINGS);
        }

        // If there is no cached one, generate the configuration and cache it
        $engineState->cachedsettings = false;
        if ($immutable_datasetsettings !== null) {
            $engineState->cachedsettings = true;
        } else {
            $immutable_datasetsettings = $processor->getImmutableSettingsDatasetmoduletree($module, $model_props);

            if ($useCache) {
                $this->getPersistentCache()->storeCacheByModelInstance(self::CACHETYPE_IMMUTABLEDATASETSETTINGS, $immutable_datasetsettings);
            }
        }

        // If there are multiple URIs, then the results must be returned under the corresponding $model_instance_id for "mutableonmodel", and $url for "mutableonrequest"
        list($has_extra_routes, $model_instance_id, $current_uri) = $this->listExtraRouteVars();

        if ($dataoutputmode == DataOutputModes::SPLITBYSOURCES) {
            if ($immutable_datasetsettings) {
                $ret['datasetmodulesettings']['immutable'] = $immutable_datasetsettings;
            }
        } elseif ($dataoutputmode == DataOutputModes::COMBINED) {
            // If everything is combined, then it belongs under "mutableonrequest"
            if ($combined_datasetsettings = $immutable_datasetsettings) {
                $ret['datasetmodulesettings'] = $has_extra_routes ? array($current_uri => $combined_datasetsettings) : $combined_datasetsettings;
            }
        }

        return $ret;
    }

    public function getRequestMeta(): array
    {
        /** @var ComponentInfo */
        $componentInfo = App::getComponent(Component::class)->getInfo();
        $meta = array(
            Response::ENTRY_MODULE => $this->getEntryModule()[1],
            Response::UNIQUE_ID => $componentInfo->getUniqueID(),
            'modelinstanceid' => $this->getModelInstance()->getModelInstanceId(),
        );

        if (App::isHTTPRequest()) {
            $meta[Response::URL] = $this->getRequestHelperService()->getCurrentURL();
        }

        $engineState = App::getEngineState();
        if ($engineState->backgroundload_urls) {
            $meta[Response::BACKGROUND_LOAD_URLS] = $engineState->backgroundload_urls;
        };

        // Starting from what modules must do the rendering. Allow for empty arrays (eg: modulepaths[]=somewhatevervalue)
        $not_excluded_module_sets = $this->getModuleFilterManager()->getNotExcludedModuleSets();
        if (!is_null($not_excluded_module_sets)) {
            // Print the settings id of each module. Then, a module can feed data to another one by sharing the same settings id (eg: self::MODULE_BLOCK_USERAVATAR_EXECUTEUPDATE and PoP_UserAvatarProcessors_Module_Processor_UserBlocks::MODULE_BLOCK_USERAVATAR_UPDATE)
            $filteredsettings = [];
            foreach ($not_excluded_module_sets as $modules) {
                $filteredsettings[] = array_map(
                    [$this->getModuleHelpers(), 'getModuleOutputName'],
                    $modules
                );
            }
            $meta['filteredmodules'] = $filteredsettings;
        }

        return App::applyFilters(
            '\PoP\ComponentModel\Engine:request-meta',
            $meta
        );
    }

    public function getSessionMeta(): array
    {
        return App::applyFilters(
            '\PoP\ComponentModel\Engine:session-meta',
            []
        );
    }

    /**
     * Function to override by the ConfigurationComponentModel
     */
    protected function addSiteMeta(): bool
    {
        return true;
    }
    public function getSiteMeta(): array
    {
        $meta = [];
        if ($this->addSiteMeta()) {
            $meta[Params::VERSION] = $this->getApplicationInfo()->getVersion();
            $meta[Params::DATAOUTPUTMODE] = App::getState('dataoutputmode');
            $meta[Params::DATABASESOUTPUTMODE] = App::getState('dboutputmode');

            if (App::getState('mangled')) {
                $meta[DefinitionsParams::MANGLED] = App::getState('mangled');
            }

            // Tell the front-end: are the results from the cache? Needed for the editor, to initialize it since WP will not execute the code
            $engineState = App::getEngineState();
            if (!is_null($engineState->cachedsettings)) {
                $meta['cachedsettings'] = $engineState->cachedsettings;
            };
        }
        return App::applyFilters(
            '\PoP\ComponentModel\Engine:site-meta',
            $meta
        );
    }

    private function combineIDsDatafields(
        array &$relationalTypeOutputDBKeyIDsDataFields,
        RelationalTypeResolverInterface $relationalTypeResolver,
        string $relationalTypeOutputDBKey,
        array $ids,
        array $data_fields,
        array $conditional_data_fields = []
    ): void {
        $relationalTypeOutputDBKeyIDsDataFields[$relationalTypeOutputDBKey] ??= [
            'relationalTypeResolver' => $relationalTypeResolver,
            'idsDataFields' => [],
        ];
        foreach ($ids as $id) {
            // Make sure to always add the 'id' data-field, since that's the key for the dbobject in the client database
            $relationalTypeOutputDBKeyIDsDataFields[$relationalTypeOutputDBKey]['idsDataFields'][(string)$id]['direct'] ??= ['id'];
            $relationalTypeOutputDBKeyIDsDataFields[$relationalTypeOutputDBKey]['idsDataFields'][(string)$id]['direct'] = array_values(array_unique(array_merge(
                $relationalTypeOutputDBKeyIDsDataFields[$relationalTypeOutputDBKey]['idsDataFields'][(string)$id]['direct'],
                $data_fields
            )));
            // The conditional data fields have the condition data fields, as key, and the list of conditional data fields to load if the condition one is successful, as value
            $relationalTypeOutputDBKeyIDsDataFields[$relationalTypeOutputDBKey]['idsDataFields'][(string)$id]['conditional'] ??= [];
            foreach ($conditional_data_fields as $conditionDataField => $conditionalDataFields) {
                $relationalTypeOutputDBKeyIDsDataFields[$relationalTypeOutputDBKey]['idsDataFields'][(string)$id]['conditional'][$conditionDataField] = array_merge(
                    $relationalTypeOutputDBKeyIDsDataFields[$relationalTypeOutputDBKey]['idsDataFields'][(string)$id]['conditional'][$conditionDataField] ?? [],
                    $conditionalDataFields
                );
            }
        }
    }

    private function doAddDatasetToDatabase(
        array &$database,
        string $database_key,
        array $dataitems
    ): void {
        // Save in the database under the corresponding database-key (this way, different dataloaders, like 'list-users' and 'author',
        // can both save their results under database key 'users'
        if (!isset($database[$database_key])) {
            $database[$database_key] = $dataitems;
        } else {
            $dbKey = $database_key;
            // array_merge_recursive doesn't work as expected (it merges 2 hashmap arrays into an array, so then I manually do a foreach instead)
            foreach ($dataitems as $id => $dbobject_values) {
                if (!isset($database[$dbKey][(string)$id])) {
                    $database[$dbKey][(string)$id] = [];
                }

                $database[$dbKey][(string)$id] = array_merge(
                    $database[$dbKey][(string)$id],
                    $dbobject_values
                );
            }
        }
    }

    private function addDatasetToDatabase(
        array &$database,
        RelationalTypeResolverInterface $relationalTypeResolver,
        string $dbKey,
        array $dataitems,
        array $objectIDItems,
        bool $addEntryIfError = false
    ): void {
        // Do not create the database key entry when there are no items, or it produces an error when deep merging the database object in the webplatform with that from the response
        if (!$dataitems) {
            return;
        }

        $isUnionTypeResolver = $relationalTypeResolver instanceof UnionTypeResolverInterface;
        if ($isUnionTypeResolver) {
            /** @var UnionTypeResolverInterface $relationalTypeResolver */
            // Get the actual type for each entity, and add the entry there
            $targetObjectTypeResolverNameTypeResolvers = $targetObjectTypeResolverNameDataItems = $targetObjectTypeResolverNameDBKeys = [];
            $noTargetObjectTypeResolverDataItems = [];
            foreach ($dataitems as $objectID => $dataItem) {
                // Obtain the type of the object
                $exists = false;
                if ($object = $objectIDItems[$objectID] ?? null) {
                    $targetObjectTypeResolver = $relationalTypeResolver->getTargetObjectTypeResolver($object);
                    if ($targetObjectTypeResolver !== null) {
                        $exists = true;
                        // The ID will contain the type. Remove it
                        list(
                            $objectDBKey,
                            $objectID
                        ) = UnionTypeHelpers::extractDBObjectTypeAndID($objectID);

                        $targetObjectTypeResolverName = $targetObjectTypeResolver->getNamespacedTypeName();
                        $targetObjectTypeResolverNameTypeResolvers[$targetObjectTypeResolverName] = $targetObjectTypeResolver;
                        $targetObjectTypeResolverNameDBKeys[$targetObjectTypeResolverName] = $objectDBKey;
                        $targetObjectTypeResolverNameDataItems[$targetObjectTypeResolverName][$objectID] = $dataItem;
                    }
                }
                if (!$exists && $addEntryIfError) {
                    // If the UnionTypeResolver doesn't have a type to process the dataItem, show the error under its own ID
                    $noTargetObjectTypeResolverDataItems[$objectID] = $dataItem;
                }
            }
            foreach ($targetObjectTypeResolverNameDataItems as $targetObjectTypeResolverName => $convertedDataItems) {
                $targetObjectTypeResolver = $targetObjectTypeResolverNameTypeResolvers[$targetObjectTypeResolverName];
                $targetObjectTypeDBKey = $targetObjectTypeResolverNameDBKeys[$targetObjectTypeResolverName];
                $this->addDatasetToDatabase($database, $targetObjectTypeResolver, $targetObjectTypeDBKey, $convertedDataItems, $objectIDItems, $addEntryIfError);
            }
            // Add the errors under the UnionTypeResolver key
            if ($noTargetObjectTypeResolverDataItems) {
                $this->doAddDatasetToDatabase($database, $dbKey, $noTargetObjectTypeResolverDataItems);
            }
            return;
        }
        $this->doAddDatasetToDatabase($database, $dbKey, $dataitems);
    }

    protected function getInterreferencedModuleFullpaths(array $module, array &$props): array
    {
        $paths = [];
        $this->addInterreferencedModuleFullpaths($paths, [], $module, $props);
        return $paths;
    }

    private function addInterreferencedModuleFullpaths(
        array &$paths,
        array $module_path,
        array $module,
        array &$props
    ): void {
        $processor = $this->getModuleProcessorManager()->getProcessor($module);
        $moduleFullName = $this->getModuleHelpers()->getModuleFullName($module);

        // If modulepaths is provided, and we haven't reached the destination module yet, then do not execute the function at this level
        if (!$this->getModuleFilterManager()->excludeModule($module, $props)) {
            // If the current module loads data, then add its path to the list
            if ($interreferenced_modulepath = $processor->getDataFeedbackInterreferencedModulepath($module, $props)) {
                $referenced_modulepath = $this->getModulePathHelpers()->stringifyModulePath($interreferenced_modulepath);
                $paths[$referenced_modulepath] = $paths[$referenced_modulepath] ?? [];
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
        $submodules = $processor->getAllSubmodules($module);
        $submodules = $this->getModuleFilterManager()->removeExcludedSubmodules($module, $submodules);

        // This function must be called always, to register matching modules into requestmeta.filtermodules even when the module has no submodules
        $this->getModuleFilterManager()->prepareForPropagation($module, $props);
        foreach ($submodules as $submodule) {
            $this->addInterreferencedModuleFullpaths($paths, $submodule_path, $submodule, $props[$moduleFullName][Props::SUBMODULES]);
        }
        $this->getModuleFilterManager()->restoreFromPropagation($module, $props);
    }

    protected function getDataloadingModuleFullpaths(array $module, array &$props): array
    {
        $paths = [];
        $this->addDataloadingModuleFullpaths($paths, [], $module, $props);
        return $paths;
    }

    private function addDataloadingModuleFullpaths(
        array &$paths,
        array $module_path,
        array $module,
        array &$props
    ): void {
        $processor = $this->getModuleProcessorManager()->getProcessor($module);
        $moduleFullName = $this->getModuleHelpers()->getModuleFullName($module);

        // If modulepaths is provided, and we haven't reached the destination module yet, then do not execute the function at this level
        if (!$this->getModuleFilterManager()->excludeModule($module, $props)) {
            // If the current module loads data, then add its path to the list
            if ($processor->moduleLoadsData($module)) {
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
        $submodules = $processor->getAllSubmodules($module);
        $submodules = $this->getModuleFilterManager()->removeExcludedSubmodules($module, $submodules);

        // This function must be called always, to register matching modules into requestmeta.filtermodules even when the module has no submodules
        $this->getModuleFilterManager()->prepareForPropagation($module, $props);
        foreach ($submodules as $submodule) {
            $this->addDataloadingModuleFullpaths($paths, $submodule_path, $submodule, $props[$moduleFullName][Props::SUBMODULES]);
        }
        $this->getModuleFilterManager()->restoreFromPropagation($module, $props);
    }

    protected function assignValueForModule(
        array &$array,
        array $module_path,
        array $module,
        string $key,
        mixed $value,
    ): void {
        /** @var ComponentInfo */
        $componentInfo = App::getComponent(Component::class)->getInfo();
        $submodulesOutputProperty = $componentInfo->getSubmodulesOutputProperty();
        $array_pointer = &$array;
        foreach ($module_path as $submodule) {
            // Notice that when generating the array for the response, we don't use $module anymore, but $moduleOutputName
            $submoduleOutputName = $this->getModuleHelpers()->getModuleOutputName($submodule);

            // If the path doesn't exist, create it
            if (!isset($array_pointer[$submoduleOutputName][$submodulesOutputProperty])) {
                $array_pointer[$submoduleOutputName][$submodulesOutputProperty] = [];
            }

            // The pointer is the location in the array where the value will be set
            $array_pointer = &$array_pointer[$submoduleOutputName][$submodulesOutputProperty];
        }

        $moduleOutputName = $this->getModuleHelpers()->getModuleOutputName($module);
        $array_pointer[$moduleOutputName][$key] = $value;
    }

    public function validateCheckpoints(array $checkpoints): bool | Error
    {
        // Iterate through the list of all checkpoints, process all of them, if any produces an error, already return it
        foreach ($checkpoints as $checkpoint) {
            $maybeCheckpointError = $this->getCheckpointProcessorManager()->getProcessor($checkpoint)->validateCheckpoint($checkpoint);
            if (GeneralUtils::isError($maybeCheckpointError)) {
                return $maybeCheckpointError;
            }
        }

        return true;
    }

    protected function getModulePathKey(array $module_path, array $module): string
    {
        $moduleFullName = $this->getModuleHelpers()->getModuleFullName($module);
        return $moduleFullName . '-' . implode('.', $module_path);
    }

    // This function is not private, so it can be accessed by the automated emails to regenerate the html for each user
    public function getModuleData(array $root_module, array $root_model_props, array $root_props): array
    {
        /** @var ComponentConfiguration */
        $componentConfiguration = App::getComponent(Component::class)->getConfiguration();
        $useCache = $componentConfiguration->useComponentModelCache();
        $root_processor = $this->getModuleProcessorManager()->getProcessor($root_module);
        $engineState = App::getEngineState();

        // From the state we know if to process static/staful content or both
        $datasourceselector = App::getState('datasourceselector');
        $dataoutputmode = App::getState('dataoutputmode');
        $dataoutputitems = App::getState('dataoutputitems');
        $add_meta = in_array(DataOutputItems::META, $dataoutputitems);

        $immutable_moduledata = $mutableonmodel_moduledata = $mutableonrequest_moduledata = [];
        $immutable_datasetmoduledata = $mutableonmodel_datasetmoduledata = $mutableonrequest_datasetmoduledata = [];
        if ($add_meta) {
            $immutable_datasetmodulemeta = $mutableonmodel_datasetmodulemeta = $mutableonrequest_datasetmodulemeta = [];
        }
        $engineState->dbdata = [];

        // Save all the BACKGROUND_LOAD urls to send back to the browser, to load immediately again (needed to fetch non-cacheable data-fields)
        $engineState->backgroundload_urls = [];

        // Load under global key (shared by all pagesections / blocks)
        $engineState->relationalTypeOutputDBKeyIDsDataFields = [];

        // Allow PoP UserState to add the lazy-loaded userstate data triggers
        App::doAction(
            '\PoP\ComponentModel\Engine:getModuleData:start',
            $root_module,
            array(&$root_model_props),
            array(&$root_props),
            array(&$engineState->helperCalculations),
            $this
        );

        // First check if there's a cache stored
        $immutable_data_properties = $mutableonmodel_data_properties = null;
        if ($useCache) {
            $immutable_data_properties = $this->getPersistentCache()->getCacheByModelInstance(self::CACHETYPE_STATICDATAPROPERTIES);
            $mutableonmodel_data_properties = $this->getPersistentCache()->getCacheByModelInstance(self::CACHETYPE_STATEFULDATAPROPERTIES);
        }

        // If there is no cached one, generate the props and cache it
        if ($immutable_data_properties === null) {
            $immutable_data_properties = $root_processor->getImmutableDataPropertiesDatasetmoduletree($root_module, $root_model_props);
            if ($useCache) {
                $this->getPersistentCache()->storeCacheByModelInstance(self::CACHETYPE_STATICDATAPROPERTIES, $immutable_data_properties);
            }
        }
        if ($mutableonmodel_data_properties === null) {
            $mutableonmodel_data_properties = $root_processor->getMutableonmodelDataPropertiesDatasetmoduletree($root_module, $root_model_props);
            if ($useCache) {
                $this->getPersistentCache()->storeCacheByModelInstance(self::CACHETYPE_STATEFULDATAPROPERTIES, $mutableonmodel_data_properties);
            }
        }

        $model_data_properties = array_merge_recursive(
            $immutable_data_properties,
            $mutableonmodel_data_properties
        );

        if ($datasourceselector == DataSourceSelectors::ONLYMODEL) {
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

        /** @var ComponentInfo */
        $componentInfo = App::getComponent(Component::class)->getInfo();
        $submodulesOutputProperty = $componentInfo->getSubmodulesOutputProperty();

        // The modules below are already included, so tell the filtermanager to not validate if they must be excluded or not
        $this->getModuleFilterManager()->neverExclude(true);
        foreach ($module_fullpaths as $module_path) {
            // The module is the last element in the path.
            // Notice that the module is removed from the path, providing the path to all its properties
            $module = array_pop($module_path);
            $moduleFullName = $this->getModuleHelpers()->getModuleFullName($module);

            // Artificially set the current path on the path manager. It will be needed in getDatasetmeta, which calls getDataloadSource, which needs the current path
            $this->getModulePathManager()->setPropagationCurrentPath($module_path);

            // Data Properties: assign by reference, so that changes to this variable are also performed in the original variable
            $data_properties = &$root_data_properties;
            foreach ($module_path as $submodule) {
                $submoduleFullName = $this->getModuleHelpers()->getModuleFullName($submodule);
                $data_properties = &$data_properties[$submoduleFullName][$submodulesOutputProperty];
            }
            $data_properties = &$data_properties[$moduleFullName][DataLoading::DATA_PROPERTIES];
            $datasource = $data_properties[DataloadingConstants::DATASOURCE] ?? null;

            // If we are only requesting data from the model alone, and this dataloading module depends on mutableonrequest, then skip it
            if ($datasourceselector == DataSourceSelectors::ONLYMODEL && $datasource == DataSources::MUTABLEONREQUEST) {
                continue;
            }

            // Load data if the property Skip Data Load is not true
            $load_data = !isset($data_properties[DataloadingConstants::SKIPDATALOAD]) || !$data_properties[DataloadingConstants::SKIPDATALOAD];

            // ------------------------------------------
            // Checkpoint validation
            // ------------------------------------------
            // Load data if the checkpoint did not fail
            $dataaccess_checkpoint_validation = null;
            if ($load_data && $checkpoints = ($data_properties[DataLoading::DATA_ACCESS_CHECKPOINTS] ?? null)) {
                // Check if the module fails checkpoint validation. If so, it must not load its data or execute the componentMutationResolverBridge
                $dataaccess_checkpoint_validation = $this->validateCheckpoints($checkpoints);
                $load_data = !GeneralUtils::isError($dataaccess_checkpoint_validation);
            }

            // The $props is directly moving the array to the corresponding path
            $props = &$root_props;
            $model_props = &$root_model_props;
            foreach ($module_path as $submodule) {
                $submoduleFullName = $this->getModuleHelpers()->getModuleFullName($submodule);
                $props = &$props[$submoduleFullName][Props::SUBMODULES];
                $model_props = &$model_props[$submoduleFullName][Props::SUBMODULES];
            }

            $module_props = null;
            if (
                in_array(
                    $datasource,
                    array(
                    DataSources::IMMUTABLE,
                    DataSources::MUTABLEONMODEL,
                    )
                )
            ) {
                $module_props = &$model_props;
            } elseif ($datasource == DataSources::MUTABLEONREQUEST) {
                $module_props = &$props;
            }

            $processor = $this->getModuleProcessorManager()->getProcessor($module);

            // The module path key is used for storing temporary results for later retrieval
            $module_path_key = $this->getModulePathKey($module_path, $module);

            // If data is not loaded, then an empty array will be saved for the dbobject ids
            $dataset_meta = $objectIDs = $typeDBObjectIDs = [];
            $mutation_checkpoint_validation = $executed = $dbObjectIDOrIDs = $typeDBObjectIDOrIDs = $relationalTypeOutputDBKey = null;
            if ($load_data) {
                // ------------------------------------------
                // Action Executers
                // ------------------------------------------
                // Allow to plug-in functionality here (eg: form submission)
                // Execute at the very beginning, so the result of the execution can also be fetched later below
                // (Eg: creation of a new location => retrieving its data / Adding a new comment)
                // Pass data_properties so these can also be modified (eg: set id of newly created Location)
                $componentMutationResolverBridge = $processor->getComponentMutationResolverBridge($module);
                if ($componentMutationResolverBridge !== null && $processor->shouldExecuteMutation($module, $props)) {
                    // Validate that the actionexecution must be triggered through its own checkpoints
                    $execute = true;
                    $mutation_checkpoint_validation = null;
                    if ($mutation_checkpoints = $data_properties[DataLoading::ACTION_EXECUTION_CHECKPOINTS] ?? null) {
                        // Check if the module fails checkpoint validation. If so, it must not load its data or execute the componentMutationResolverBridge
                        $mutation_checkpoint_validation = $this->validateCheckpoints($mutation_checkpoints);
                        $execute = !GeneralUtils::isError($mutation_checkpoint_validation);
                    }
                    if ($execute) {
                        $executed = $componentMutationResolverBridge->executeMutation($data_properties);
                    }
                }

                // Allow modules to change their data_properties based on the actionexecution of previous modules.
                $processor->prepareDataPropertiesAfterMutationExecution($module, $module_props, $data_properties);

                // Re-calculate $data_load, it may have been changed by `prepareDataPropertiesAfterMutationExecution`
                $load_data = !isset($data_properties[DataloadingConstants::SKIPDATALOAD]) || !$data_properties[DataloadingConstants::SKIPDATALOAD];
                if ($load_data) {
                    $relationalTypeResolver = $processor->getRelationalTypeResolver($module);
                    $isUnionTypeResolver = $relationalTypeResolver instanceof UnionTypeResolverInterface;
                    $relationalTypeOutputDBKey = $relationalTypeResolver->getTypeOutputDBKey();
                    // ------------------------------------------
                    // Data Properties Query Args: add mutableonrequest data
                    // ------------------------------------------
                    // Execute and get the ids and the meta
                    $dbObjectIDOrIDs = $processor->getObjectIDOrIDs($module, $module_props, $data_properties);
                    // To simplify the logic, deal with arrays only
                    if ($dbObjectIDOrIDs === null) {
                        $dbObjectIDOrIDs = [];
                    }
                    // If the type is union, we must add the type to each object
                    $typeDBObjectIDOrIDs = $isUnionTypeResolver ?
                        $relationalTypeResolver->getQualifiedDBObjectIDOrIDs($dbObjectIDOrIDs)
                        : $dbObjectIDOrIDs;

                    $objectIDs = is_array($dbObjectIDOrIDs) ? $dbObjectIDOrIDs : array($dbObjectIDOrIDs);
                    $typeDBObjectIDs = is_array($typeDBObjectIDOrIDs) ? $typeDBObjectIDOrIDs : array($typeDBObjectIDOrIDs);

                    // Store the ids under $data under key dataload_name => id
                    $data_fields = $data_properties['data-fields'] ?? [];
                    $conditional_data_fields = $data_properties['conditional-data-fields'] ?? [];
                    $this->combineIDsDatafields($engineState->relationalTypeOutputDBKeyIDsDataFields, $relationalTypeResolver, $relationalTypeOutputDBKey, $typeDBObjectIDs, $data_fields, $conditional_data_fields);

                    // Add the IDs to the possibly-already produced IDs for this typeResolver
                    $this->initializeTypeResolverEntry($engineState->dbdata, $relationalTypeOutputDBKey, $module_path_key);
                    $engineState->dbdata[$relationalTypeOutputDBKey][$module_path_key]['ids'] = array_merge(
                        $engineState->dbdata[$relationalTypeOutputDBKey][$module_path_key]['ids'],
                        $typeDBObjectIDs
                    );

                    // The supplementary dbobject data is independent of the typeResolver of the block.
                    // Even if it is STATIC, the extend ids must be loaded. That's why we load the extend now,
                    // Before checking below if the checkpoint failed or if the block content must not be loaded.
                    // Eg: Locations Map for the Create Individual Profile: it allows to pre-select locations,
                    // these ones must be fetched even if the block has a static typeResolver
                    // If it has extend, add those ids under its relationalTypeOutputDBKey
                    $dataload_extend_settings = $processor->getModelSupplementaryDBObjectDataModuletree($module, $model_props);
                    if ($datasource == DataSources::MUTABLEONREQUEST) {
                        $dataload_extend_settings = array_merge_recursive(
                            $dataload_extend_settings,
                            $processor->getMutableonrequestSupplementaryDBObjectDataModuletree($module, $props)
                        );
                    }
                    foreach ($dataload_extend_settings as $extendTypeOutputDBKey => $extend_data_properties) {
                         // Get the info for the subcomponent typeResolver
                        $extend_data_fields = $extend_data_properties['data-fields'] ? $extend_data_properties['data-fields'] : [];
                        $extend_conditional_data_fields = $extend_data_properties['conditional-data-fields'] ? $extend_data_properties['conditional-data-fields'] : [];
                        $extend_ids = $extend_data_properties['ids'];
                        $extend_typeResolver = $extend_data_properties['resolver'];

                        $this->combineIDsDatafields($engineState->relationalTypeOutputDBKeyIDsDataFields, $extend_typeResolver, $extendTypeOutputDBKey, $extend_ids, $extend_data_fields, $extend_conditional_data_fields);

                        // This is needed to add the typeResolver-extend IDs, for if nobody else creates an entry for this typeResolver
                        $this->initializeTypeResolverEntry($engineState->dbdata, $extendTypeOutputDBKey, $module_path_key);
                    }

                    // Keep iterating for its subcomponents
                    $this->integrateSubcomponentDataProperties($engineState->dbdata, $data_properties, $relationalTypeOutputDBKey, $module_path_key);
                }
            }

            // Save the results on either the static or mutableonrequest branches
            $datasetmoduledata = $datasetmodulemeta = null;
            if ($datasource == DataSources::IMMUTABLE) {
                $datasetmoduledata = &$immutable_datasetmoduledata;
                if ($add_meta) {
                    $datasetmodulemeta = &$immutable_datasetmodulemeta;
                }
                $engineState->moduledata = &$immutable_moduledata;
            } elseif ($datasource == DataSources::MUTABLEONMODEL) {
                $datasetmoduledata = &$mutableonmodel_datasetmoduledata;
                if ($add_meta) {
                    $datasetmodulemeta = &$mutableonmodel_datasetmodulemeta;
                }
                $engineState->moduledata = &$mutableonmodel_moduledata;
            } elseif ($datasource == DataSources::MUTABLEONREQUEST) {
                $datasetmoduledata = &$mutableonrequest_datasetmoduledata;
                if ($add_meta) {
                    $datasetmodulemeta = &$mutableonrequest_datasetmodulemeta;
                }
                $engineState->moduledata = &$mutableonrequest_moduledata;
            }

            // Integrate the dbobjectids into $datasetmoduledata
            // ALWAYS print the $dbobjectids, even if its an empty array. This to indicate that this is a dataloading module, so the application in the webplatform knows if to load a new batch of dbobjectids, or reuse the ones from the previous module when iterating down
            if ($datasetmoduledata !== null) {
                $this->assignValueForModule($datasetmoduledata, $module_path, $module, DataLoading::DB_OBJECT_IDS, $typeDBObjectIDOrIDs);
            }

            // Save the meta into $datasetmodulemeta
            if ($add_meta) {
                if (!is_null($datasetmodulemeta)) {
                    if ($dataset_meta = $processor->getDatasetmeta($module, $module_props, $data_properties, $dataaccess_checkpoint_validation, $mutation_checkpoint_validation, $executed, $dbObjectIDOrIDs)) {
                        $this->assignValueForModule($datasetmodulemeta, $module_path, $module, DataLoading::META, $dataset_meta);
                    }
                }
            }

            // Integrate the feedback into $moduledata
            $this->processAndAddModuleData($module_path, $module, $module_props, $data_properties, $dataaccess_checkpoint_validation, $mutation_checkpoint_validation, $executed, $objectIDs);

            // Allow other modules to produce their own feedback using this module's data results
            if ($referencer_modulefullpaths = $interreferenced_modulefullpaths[$this->getModulePathHelpers()->stringifyModulePath(array_merge($module_path, array($module)))] ?? null) {
                foreach ($referencer_modulefullpaths as $referencer_modulepath) {
                    $referencer_module = array_pop($referencer_modulepath);

                    $referencer_props = &$root_props;
                    $referencer_model_props = &$root_model_props;
                    foreach ($referencer_modulepath as $submodule) {
                        $submoduleFullName = $this->getModuleHelpers()->getModuleFullName($submodule);
                        $referencer_props = &$referencer_props[$submoduleFullName][Props::SUBMODULES];
                        $referencer_model_props = &$referencer_model_props[$submoduleFullName][Props::SUBMODULES];
                    }

                    if (
                        in_array(
                            $datasource,
                            array(
                            DataSources::IMMUTABLE,
                            DataSources::MUTABLEONMODEL,
                            )
                        )
                    ) {
                        $referencer_module_props = &$referencer_model_props;
                    } elseif ($datasource == DataSources::MUTABLEONREQUEST) {
                        $referencer_module_props = &$referencer_props;
                    }
                    $this->processAndAddModuleData($referencer_modulepath, $referencer_module, $referencer_module_props, $data_properties, $dataaccess_checkpoint_validation, $mutation_checkpoint_validation, $executed, $objectIDs);
                }
            }

            // Incorporate the background URLs
            $engineState->backgroundload_urls = array_merge(
                $engineState->backgroundload_urls,
                $processor->getBackgroundurlsMergeddatasetmoduletree($module, $module_props, $data_properties, $dataaccess_checkpoint_validation, $mutation_checkpoint_validation, $executed, $objectIDs)
            );

            // Allow PoP UserState to add the lazy-loaded userstate data triggers
            App::doAction(
                '\PoP\ComponentModel\Engine:getModuleData:dataloading-module',
                $module,
                array(&$module_props),
                array(&$data_properties),
                $dataaccess_checkpoint_validation,
                $mutation_checkpoint_validation,
                $executed,
                $dbObjectIDOrIDs,
                array(&$engineState->helperCalculations),
                $this
            );
        }

        // Reset the filtermanager state and the pathmanager current path
        $this->getModuleFilterManager()->neverExclude(false);
        $this->getModulePathManager()->setPropagationCurrentPath();

        $ret = [];

        if (in_array(DataOutputItems::MODULE_DATA, $dataoutputitems)) {
            // If there are multiple URIs, then the results must be returned under the corresponding $model_instance_id for "mutableonmodel", and $url for "mutableonrequest"
            list($has_extra_routes, $model_instance_id, $current_uri) = $this->listExtraRouteVars();

            if ($dataoutputmode == DataOutputModes::SPLITBYSOURCES) {
                /** @phpstan-ignore-next-line */
                if ($immutable_moduledata) {
                    $ret['moduledata']['immutable'] = $immutable_moduledata;
                }
                /** @phpstan-ignore-next-line */
                if ($mutableonmodel_moduledata) {
                    $ret['moduledata']['mutableonmodel'] = $has_extra_routes ? array($model_instance_id => $mutableonmodel_moduledata) : $mutableonmodel_moduledata;
                }
                /** @phpstan-ignore-next-line */
                if ($mutableonrequest_moduledata) {
                    $ret['moduledata']['mutableonrequest'] = $has_extra_routes ? array($current_uri => $mutableonrequest_moduledata) : $mutableonrequest_moduledata;
                }
                /** @phpstan-ignore-next-line */
                if ($immutable_datasetmoduledata) {
                    $ret['datasetmoduledata']['immutable'] = $immutable_datasetmoduledata;
                }
                /** @phpstan-ignore-next-line */
                if ($mutableonmodel_datasetmoduledata) {
                    $ret['datasetmoduledata']['mutableonmodel'] = $has_extra_routes ? array($model_instance_id => $mutableonmodel_datasetmoduledata) : $mutableonmodel_datasetmoduledata;
                }
                /** @phpstan-ignore-next-line */
                if ($mutableonrequest_datasetmoduledata) {
                    $ret['datasetmoduledata']['mutableonrequest'] = $has_extra_routes ? array($current_uri => $mutableonrequest_datasetmoduledata) : $mutableonrequest_datasetmoduledata;
                }

                if ($add_meta) {
                    /** @phpstan-ignore-next-line */
                    if ($immutable_datasetmodulemeta) {
                        $ret['datasetmodulemeta']['immutable'] = $immutable_datasetmodulemeta;
                    }
                    /** @phpstan-ignore-next-line */
                    if ($mutableonmodel_datasetmodulemeta) {
                        $ret['datasetmodulemeta']['mutableonmodel'] = $has_extra_routes ? array($model_instance_id => $mutableonmodel_datasetmodulemeta) : $mutableonmodel_datasetmodulemeta;
                    }
                    /** @phpstan-ignore-next-line */
                    if ($mutableonrequest_datasetmodulemeta) {
                        $ret['datasetmodulemeta']['mutableonrequest'] = $has_extra_routes ? array($current_uri => $mutableonrequest_datasetmodulemeta) : $mutableonrequest_datasetmodulemeta;
                    }
                }
            } elseif ($dataoutputmode == DataOutputModes::COMBINED) {
                // If everything is combined, then it belongs under "mutableonrequest"
                if (
                    $combined_moduledata = array_merge_recursive(
                        $immutable_moduledata,
                        $mutableonmodel_moduledata,
                        $mutableonrequest_moduledata
                    )
                ) {
                    $ret['moduledata'] = $has_extra_routes ? array($current_uri => $combined_moduledata) : $combined_moduledata;
                }
                if (
                    $combined_datasetmoduledata = array_merge_recursive(
                        $immutable_datasetmoduledata,
                        $mutableonmodel_datasetmoduledata,
                        $mutableonrequest_datasetmoduledata
                    )
                ) {
                    $ret['datasetmoduledata'] = $has_extra_routes ? array($current_uri => $combined_datasetmoduledata) : $combined_datasetmoduledata;
                }
                if ($add_meta) {
                    if (
                        $combined_datasetmodulemeta = array_merge_recursive(
                            $immutable_datasetmodulemeta ?? [],
                            $mutableonmodel_datasetmodulemeta ?? [],
                            $mutableonrequest_datasetmodulemeta ?? []
                        )
                    ) {
                        $ret['datasetmodulemeta'] = $has_extra_routes ? array($current_uri => $combined_datasetmodulemeta) : $combined_datasetmodulemeta;
                    }
                }
            }
        }

        // Allow PoP UserState to add the lazy-loaded userstate data triggers
        App::doAction(
            '\PoP\ComponentModel\Engine:getModuleData:end',
            $root_module,
            array(&$root_model_props),
            array(&$root_props),
            array(&$engineState->helperCalculations),
            $this
        );

        return $ret;
    }

    public function moveEntriesUnderDBName(
        array $entries,
        bool $entryHasId,
        RelationalTypeResolverInterface $relationalTypeResolver
    ): array {
        if (!$entries) {
            return [];
        }
        
        // By default place everything under "primary"
        $dbname_entries = [
            'primary' => $entries,
        ];

        // Allow to inject what data fields must be placed under what dbNames
        // Array of key: dbName, values: data-fields
        $dbname_datafields = App::applyFilters(
            'PoP\ComponentModel\Engine:moveEntriesUnderDBName:dbName-dataFields',
            [],
            $relationalTypeResolver
        );
        foreach ($dbname_datafields as $dbname => $data_fields) {
            // Move these data fields under "meta" DB name
            if ($entryHasId) {
                foreach ($dbname_entries['primary'] as $id => $dbObject) {
                    $entry_data_fields_to_move = array_intersect(
                        // If field "id" for this type has been disabled (eg: by ACL),
                        // then $dbObject may be `null`
                        array_keys($dbObject ?? []),
                        $data_fields
                    );
                    foreach ($entry_data_fields_to_move as $data_field) {
                        $dbname_entries[$dbname][$id][$data_field] = $dbname_entries['primary'][$id][$data_field];
                        unset($dbname_entries['primary'][$id][$data_field]);
                    }
                }
                continue;
            }
            $entry_data_fields_to_move = array_intersect(
                array_keys($dbname_entries['primary']),
                $data_fields
            );
            foreach ($entry_data_fields_to_move as $data_field) {
                $dbname_entries[$dbname][$data_field] = $dbname_entries['primary'][$data_field];
                unset($dbname_entries['primary'][$data_field]);
            }
        }
        return $dbname_entries;
    }

    protected function generateDatabases(): array
    {
        $engineState = App::getEngineState();

        // Save all database elements here, under typeResolver
        $databases = $unionDBKeyIDs = $combinedUnionDBKeyIDs = $previousDBItems = $objectErrors = $objectWarnings = $objectDeprecations = $objectNotices = $objectTraces = $schemaErrors = $schemaWarnings = $schemaDeprecations = $schemaNotices = $schemaTraces = [];
        $engineState->nocache_fields = [];

        // Keep an object with all fetched IDs/fields for each typeResolver. Then, we can keep using the same typeResolver as subcomponent,
        // but we need to avoid fetching those DB objects that were already fetched in a previous iteration
        $already_loaded_ids_data_fields = [];

        /**
         * The variables initially come from the AppState, but then they
         * can be modified by directiveResolvers (eg: for @export)
         */
        $variables = App::getState('variables');

        // Initiate a new $messages interchange across directives
        $messages = [];

        // Iterate while there are dataloaders with data to be processed
        while (!empty($engineState->relationalTypeOutputDBKeyIDsDataFields)) {
            // Move the pointer to the first element, and get it
            reset($engineState->relationalTypeOutputDBKeyIDsDataFields);
            $relationalTypeOutputDBKey = key($engineState->relationalTypeOutputDBKeyIDsDataFields);
            $relationalTypeResolver = $engineState->relationalTypeOutputDBKeyIDsDataFields[$relationalTypeOutputDBKey]['relationalTypeResolver'];
            $ids_data_fields = $engineState->relationalTypeOutputDBKeyIDsDataFields[$relationalTypeOutputDBKey]['idsDataFields'];

            // Remove the typeResolver element from the array, so it doesn't process it anymore
            // Do it immediately, so that subcomponents can load new IDs for this current typeResolver (eg: posts => related)
            unset($engineState->relationalTypeOutputDBKeyIDsDataFields[$relationalTypeOutputDBKey]);

            // If no ids to execute, then skip
            if (empty($ids_data_fields)) {
                continue;
            }

            // Store the loaded IDs/fields in an object, to avoid fetching them again in later iterations on the same typeResolver
            $already_loaded_ids_data_fields[$relationalTypeOutputDBKey] = $already_loaded_ids_data_fields[$relationalTypeOutputDBKey] ?? [];
            foreach ($ids_data_fields as $id => $data_fields) {
                $already_loaded_ids_data_fields[$relationalTypeOutputDBKey][(string)$id] = array_merge(
                    $already_loaded_ids_data_fields[$relationalTypeOutputDBKey][(string)$id] ?? [],
                    $data_fields['direct'],
                    array_keys($data_fields['conditional'])
                );
            }

            $database_key = $relationalTypeResolver->getTypeOutputDBKey();

            // Execute the typeResolver for all combined ids
            $iterationDBItems = $iterationObjectErrors = $iterationObjectWarnings = $iterationObjectDeprecations = $iterationObjectNotices = $iterationObjectTraces = $iterationSchemaErrors = $iterationSchemaWarnings = $iterationSchemaDeprecations = $iterationSchemaNotices = $iterationSchemaTraces = [];
            $isUnionTypeResolver = $relationalTypeResolver instanceof UnionTypeResolverInterface;
            $objectIDItems = $relationalTypeResolver->fillObjects(
                $ids_data_fields,
                $combinedUnionDBKeyIDs,
                $previousDBItems,
                $iterationDBItems,
                $variables,
                $messages,
                $iterationObjectErrors,
                $iterationObjectWarnings,
                $iterationObjectDeprecations,
                $iterationObjectNotices,
                $iterationObjectTraces,
                $iterationSchemaErrors,
                $iterationSchemaWarnings,
                $iterationSchemaDeprecations,
                $iterationSchemaNotices,
                $iterationSchemaTraces
            );

            // Save in the database under the corresponding database-key
            // (this way, different dataloaders, like 'list-users' and 'author',
            // can both save their results under database key 'users'
            // Plugin PoP User Login: Also save those results which depend on the logged-in user.
            // These are treated separately because:
            // 1: They contain personal information, so it must be erased from the front-end
            // as soon as the user logs out
            // 2: These results make the page state-full, so this page is not cacheable
            // By splitting the results into state-full and state-less, we can split all functionality
            // into cacheable and non-cacheable,
            // thus caching most of the website even for logged-in users
            /** @phpstan-ignore-next-line */
            if ($iterationDBItems) {
                // Conditional data fields: Store the loaded IDs/fields in an object,
                // to avoid fetching them again in later iterations on the same typeResolver
                // To find out if they were loaded, validate against the DBObject, to see if it has those properties
                foreach ($ids_data_fields as $id => $data_fields) {
                    foreach ($data_fields['conditional'] as $conditionDataField => $conditionalDataFields) {
                        $iterationFields = array_keys($iterationDBItems[(string)$id]);
                        $already_loaded_ids_data_fields[$relationalTypeOutputDBKey][(string)$id] = array_merge(
                            $already_loaded_ids_data_fields[$relationalTypeOutputDBKey][(string)$id] ?? [],
                            Methods::arrayIntersectAssocRecursive(
                                $conditionalDataFields,
                                $iterationFields
                            ) ?? []
                        );
                    }
                }

                // If the type is union, then add the type corresponding to each object on its ID
                $dbItems = $this->moveEntriesUnderDBName($iterationDBItems, true, $relationalTypeResolver);
                foreach ($dbItems as $dbname => $entries) {
                    $databases[$dbname] ??= [];
                    $this->addDatasetToDatabase($databases[$dbname], $relationalTypeResolver, $database_key, $entries, $objectIDItems);

                    // Populate the $previousDBItems, pointing to the newly fetched dbItems (but without the dbname!)
                    // Save the reference to the values, instead of the values, to save memory
                    // Passing $previousDBItems instead of $databases makes it read-only: Directives can only read the values... if they want to modify them,
                    // the modification is done on $previousDBItems, so it carries no risks
                    foreach ($entries as $id => $fieldValues) {
                        foreach ($fieldValues as $field => &$entryFieldValues) {
                            $previousDBItems[$database_key][$id][$field] = &$entryFieldValues;
                        }
                    }
                }
            }
            /** @phpstan-ignore-next-line */
            if ($iterationObjectErrors) {
                $dbNameErrorEntries = $this->moveEntriesUnderDBName($iterationObjectErrors, true, $relationalTypeResolver);
                foreach ($dbNameErrorEntries as $dbname => $entries) {
                    $objectErrors[$dbname] ??= [];
                    $this->addDatasetToDatabase($objectErrors[$dbname], $relationalTypeResolver, $database_key, $entries, $objectIDItems, true);
                }
            }
            $feedbackStoreObjectWarnings = App::getFeedbackStore()->objectFeedbackStore->getObjectWarnings();
            foreach ($feedbackStoreObjectWarnings as $objectWarning) {
                $iterationFeedbackStoreObjectWarnings = [];
                $fields = $objectWarning->getFields();
                $message = $objectWarning->getMessage();
                $extensions = array_merge(
                    $objectWarning->getExtensions(),
                    [
                        'location' => $objectWarning->getLocation()->toArray(),
                    ]
                );
                foreach ($objectWarning->getObjectIDs() as $id) {
                    $iterationFeedbackStoreObjectWarnings[(string)$id][] = [
                        Tokens::PATH => $fields,
                        Tokens::MESSAGE => $message,
                        Tokens::EXTENSIONS => $extensions,
                    ];
                }
                $iterationDBKey = $objectWarning->getRelationalTypeResolver()->getTypeOutputDBKey();
                $dbNameWarningEntries = $this->moveEntriesUnderDBName($iterationFeedbackStoreObjectWarnings, true, $relationalTypeResolver);
                foreach ($dbNameWarningEntries as $dbname => $idEntries) {
                    foreach ($idEntries as $id => $entries) {
                        $objectWarnings[$dbname][$iterationDBKey][$id] = array_merge(
                            $schemaErrors[$dbname][$iterationDBKey][$id] ?? [],
                            $entries
                        );
                    }
                }
            }
            /** @phpstan-ignore-next-line */
            if ($iterationObjectWarnings !== []) {
                $dbNameWarningEntries = $this->moveEntriesUnderDBName($iterationObjectWarnings, true, $relationalTypeResolver);
                foreach ($dbNameWarningEntries as $dbname => $entries) {
                    $objectWarnings[$dbname] ??= [];
                    $this->addDatasetToDatabase($objectWarnings[$dbname], $relationalTypeResolver, $database_key, $entries, $objectIDItems, true);
                }
            }
            $feedbackStoreObjectDeprecations = App::getFeedbackStore()->objectFeedbackStore->getObjectDeprecations();
            foreach ($feedbackStoreObjectDeprecations as $objectDeprecation) {
                $iterationFeedbackStoreObjectDeprecations = [];
                $fields = $objectDeprecation->getFields();
                $message = $objectDeprecation->getMessage();
                $extensions = array_merge(
                    $objectDeprecation->getExtensions(),
                    [
                        'location' => $objectDeprecation->getLocation()->toArray(),
                    ]
                );
                foreach ($objectDeprecation->getObjectIDs() as $id) {
                    $iterationFeedbackStoreObjectDeprecations[(string)$id][] = [
                        Tokens::PATH => $fields,
                        Tokens::MESSAGE => $message,
                        Tokens::EXTENSIONS => $extensions,
                    ];
                }
                $iterationDBKey = $objectDeprecation->getRelationalTypeResolver()->getTypeOutputDBKey();
                $dbNameDeprecationEntries = $this->moveEntriesUnderDBName($iterationFeedbackStoreObjectDeprecations, true, $objectDeprecation->getRelationalTypeResolver());
                foreach ($dbNameDeprecationEntries as $dbname => $idEntries) {
                    foreach ($idEntries as $id => $entries) {
                        $objectDeprecations[$dbname][$iterationDBKey][$id] = array_merge(
                            $schemaErrors[$dbname][$iterationDBKey][$id] ?? [],
                            $entries
                        );
                    }
                }
            }
            /** @phpstan-ignore-next-line */
            if ($iterationObjectDeprecations) {
                $dbNameDeprecationEntries = $this->moveEntriesUnderDBName($iterationObjectDeprecations, true, $relationalTypeResolver);
                foreach ($dbNameDeprecationEntries as $dbname => $entries) {
                    $objectDeprecations[$dbname] ??= [];
                    $this->addDatasetToDatabase($objectDeprecations[$dbname], $relationalTypeResolver, $database_key, $entries, $objectIDItems, true);
                }
            }
            /** @phpstan-ignore-next-line */
            if ($iterationObjectNotices) {
                $dbNameNoticeEntries = $this->moveEntriesUnderDBName($iterationObjectNotices, true, $relationalTypeResolver);
                foreach ($dbNameNoticeEntries as $dbname => $entries) {
                    $objectNotices[$dbname] ??= [];
                    $this->addDatasetToDatabase($objectNotices[$dbname], $relationalTypeResolver, $database_key, $entries, $objectIDItems, true);
                }
            }
            /** @phpstan-ignore-next-line */
            if ($iterationObjectTraces) {
                $dbNameTraceEntries = $this->moveEntriesUnderDBName($iterationObjectTraces, true, $relationalTypeResolver);
                foreach ($dbNameTraceEntries as $dbname => $entries) {
                    $objectTraces[$dbname] ??= [];
                    $this->addDatasetToDatabase($objectTraces[$dbname], $relationalTypeResolver, $database_key, $entries, $objectIDItems, true);
                }
            }

            $feedbackStoreSchemaErrors = App::getFeedbackStore()->schemaFeedbackStore->getSchemaErrors();
            foreach ($feedbackStoreSchemaErrors as $schemaError) {
                $iterationFeedbackStoreSchemaErrors = [];
                $extensions = array_merge(
                    $schemaError->getExtensions(),
                    [
                        'location' => $schemaError->getLocation()->toArray(),
                    ]
                );
                $iterationFeedbackStoreSchemaErrors[] = [
                    Tokens::PATH => $schemaError->getFields(),
                    Tokens::MESSAGE => $schemaError->getMessage(),
                    Tokens::EXTENSIONS => $extensions,
                ];
                $iterationDBKey = $schemaError->getRelationalTypeResolver()->getTypeOutputDBKey();
                $dbNameSchemaErrorEntries = $this->moveEntriesUnderDBName($iterationFeedbackStoreSchemaErrors, false, $relationalTypeResolver);
                foreach ($dbNameSchemaErrorEntries as $dbname => $entries) {
                    $schemaErrors[$dbname][$iterationDBKey] = array_merge(
                        $schemaErrors[$dbname][$iterationDBKey] ?? [],
                        $entries
                    );
                }
            }
            if ($iterationSchemaErrors !== []) {
                $dbNameSchemaErrorEntries = $this->moveEntriesUnderDBName($iterationSchemaErrors, false, $relationalTypeResolver);
                foreach ($dbNameSchemaErrorEntries as $dbname => $entries) {
                    $schemaErrors[$dbname][$database_key] = array_merge(
                        $schemaErrors[$dbname][$database_key] ?? [],
                        $entries
                    );
                }
            }
            $feedbackStoreSchemaWarnings = App::getFeedbackStore()->schemaFeedbackStore->getSchemaWarnings();
            foreach ($feedbackStoreSchemaWarnings as $schemaWarning) {
                $iterationFeedbackStoreSchemaWarnings = [];
                $extensions = array_merge(
                    $schemaWarning->getExtensions(),
                    [
                        'location' => $schemaWarning->getLocation()->toArray(),
                    ]
                );
                $iterationFeedbackStoreSchemaWarnings[] = [
                    Tokens::PATH => $schemaWarning->getFields(),
                    Tokens::MESSAGE => $schemaWarning->getMessage(),
                    Tokens::EXTENSIONS => $extensions,
                ];
                $iterationDBKey = $schemaWarning->getRelationalTypeResolver()->getTypeOutputDBKey();
                $dbNameSchemaWarningEntries = $this->moveEntriesUnderDBName($iterationFeedbackStoreSchemaWarnings, false, $relationalTypeResolver);
                foreach ($dbNameSchemaWarningEntries as $dbname => $entries) {
                    $schemaWarnings[$dbname][$iterationDBKey] = array_merge(
                        $schemaWarnings[$dbname][$iterationDBKey] ?? [],
                        $entries
                    );
                }
            }
            /** @phpstan-ignore-next-line */
            if ($iterationSchemaWarnings !== []) {
                $iterationSchemaWarnings = array_intersect_key($iterationSchemaWarnings, array_unique(array_map('serialize', $iterationSchemaWarnings)));
                $dbNameSchemaWarningEntries = $this->moveEntriesUnderDBName($iterationSchemaWarnings, false, $relationalTypeResolver);
                foreach ($dbNameSchemaWarningEntries as $dbname => $entries) {
                    $schemaWarnings[$dbname][$database_key] = array_merge(
                        $schemaWarnings[$dbname][$database_key] ?? [],
                        $entries
                    );
                }
            }
            /** @phpstan-ignore-next-line */
            if ($iterationSchemaDeprecations) {
                $iterationSchemaDeprecations = array_intersect_key($iterationSchemaDeprecations, array_unique(array_map('serialize', $iterationSchemaDeprecations)));
                $dbNameSchemaDeprecationEntries = $this->moveEntriesUnderDBName($iterationSchemaDeprecations, false, $relationalTypeResolver);
                foreach ($dbNameSchemaDeprecationEntries as $dbname => $entries) {
                    $schemaDeprecations[$dbname][$database_key] = array_merge(
                        $schemaDeprecations[$dbname][$database_key] ?? [],
                        $entries
                    );
                }
            }
            /** @phpstan-ignore-next-line */
            if ($iterationSchemaNotices) {
                $iterationSchemaNotices = array_intersect_key($iterationSchemaNotices, array_unique(array_map('serialize', $iterationSchemaNotices)));
                $dbNameSchemaNoticeEntries = $this->moveEntriesUnderDBName($iterationSchemaNotices, false, $relationalTypeResolver);
                foreach ($dbNameSchemaNoticeEntries as $dbname => $entries) {
                    $schemaNotices[$dbname][$database_key] = array_merge(
                        $schemaNotices[$dbname][$database_key] ?? [],
                        $entries
                    );
                }
            }
            /** @phpstan-ignore-next-line */
            if ($iterationSchemaTraces) {
                $iterationSchemaTraces = array_intersect_key($iterationSchemaTraces, array_unique(array_map('serialize', $iterationSchemaTraces)));
                $dbNameSchemaTraceEntries = $this->moveEntriesUnderDBName($iterationSchemaTraces, false, $relationalTypeResolver);
                foreach ($dbNameSchemaTraceEntries as $dbname => $entries) {
                    $schemaTraces[$dbname][$database_key] = array_merge(
                        $schemaTraces[$dbname][$database_key] ?? [],
                        $entries
                    );
                }
            }

            // Important: query like this: obtain keys first instead of iterating directly on array,
            // because it will keep adding elements
            $typeResolver_dbdata = $engineState->dbdata[$relationalTypeOutputDBKey];
            foreach (array_keys($typeResolver_dbdata) as $module_path_key) {
                $typeResolver_data = &$engineState->dbdata[$relationalTypeOutputDBKey][$module_path_key];

                unset($engineState->dbdata[$relationalTypeOutputDBKey][$module_path_key]);

                // Check if it has subcomponents, and then bring this data
                if ($subcomponents_data_properties = $typeResolver_data['subcomponents']) {
                    $typeResolver_ids = $typeResolver_data['ids'];
                    // The unionTypeResolver doesn't know how to resolver the subcomponents, since the fields
                    // (eg: "authors") are attached to the target typeResolver, not to the unionTypeResolver
                    // Then, iterate through all the target typeResolvers, and have each of them process their data
                    if ($isUnionTypeResolver) {
                        // If the type data resolver is union, the dbKey where the value is stored
                        // is contained in the ID itself, with format dbKey/ID.
                        // We must extract this information: assign the dbKey to $database_key,
                        // and remove the dbKey from the ID.
                        // If the Dataloader failed loading the object, the original ID as int
                        // may have been stored, so cast it always to string
                        $targetObjectIDItems = [];
                        $objectTypeResolver_ids = [];
                        foreach ($typeResolver_ids as $composedID) {
                            list(
                                $database_key,
                                $id
                            ) = UnionTypeHelpers::extractDBObjectTypeAndID((string)$composedID);
                            // It's null if the Dataloader couldn't load the item with the given ID
                            $targetObjectIDItems[$id] = $objectIDItems[$composedID] ?? null;
                            $objectTypeResolver_ids[] = $id;
                        }

                        // If it's a unionTypeResolver, get the typeResolver for each object
                        // to obtain the subcomponent typeResolver
                        /** @var UnionTypeResolverInterface $relationalTypeResolver */
                        $targetObjectTypeResolvers = $relationalTypeResolver->getObjectIDTargetTypeResolvers($objectTypeResolver_ids);
                        $iterationObjectTypeResolverNameDataItems = [];
                        foreach ($objectTypeResolver_ids as $id) {
                            // If there's no resolver, it's an error: the ID can't be processed by anyone
                            if ($targetObjectTypeResolver = $targetObjectTypeResolvers[(string)$id] ?? null) {
                                $objectTypeResolverName = $targetObjectTypeResolver->getNamespacedTypeName();
                                $iterationObjectTypeResolverNameDataItems[$objectTypeResolverName] ??= [
                                    'targetObjectTypeResolver' => $targetObjectTypeResolver,
                                    'objectIDs' => [],
                                ];
                                $iterationObjectTypeResolverNameDataItems[$objectTypeResolverName]['objectIDs'][] = $id;
                            }
                        }
                        foreach ($iterationObjectTypeResolverNameDataItems as $iterationObjectTypeResolverName => $iterationObjectTypeResolverDataItems) {
                            $targetObjectTypeResolver = $iterationObjectTypeResolverDataItems['targetObjectTypeResolver'];
                            $targetObjectIDs = $iterationObjectTypeResolverDataItems['objectIDs'];
                            $this->processSubcomponentData($relationalTypeResolver, $targetObjectTypeResolver, $targetObjectIDs, $module_path_key, $databases, $subcomponents_data_properties, $already_loaded_ids_data_fields, $unionDBKeyIDs, $combinedUnionDBKeyIDs, $targetObjectIDItems);
                        }
                    } else {
                        /** @var ObjectTypeResolverInterface $relationalTypeResolver */
                        $this->processSubcomponentData($relationalTypeResolver, $relationalTypeResolver, $typeResolver_ids, $module_path_key, $databases, $subcomponents_data_properties, $already_loaded_ids_data_fields, $unionDBKeyIDs, $combinedUnionDBKeyIDs, $objectIDItems);
                    }
                }
            }
            // }
            
            /**
             * Regenerate the schema/object FeedbackStore, to reset the
             * state of errors/warnings/logs/etc for the next iteration
             */
            $feedbackStore = App::getFeedbackStore();
            $feedbackStore->regenerateSchemaFeedbackStore();
            $feedbackStore->regenerateObjectFeedbackStore();
        }

        $ret = [];

        // Executing the following query will produce duplicates on SchemaWarnings:
        // ?query=posts(limit:3.5).title,posts(limit:extract(posts(limit:4.5),saraza)).title
        // This is unavoidable, since add schemaWarnings (and, correspondingly, errors and deprecations) in functions
        // `resolveFieldValidationWarningDescriptions` and `resolveValue` from the AbstractObjectTypeResolver
        // Ideally, doing it in `resolveValue` is not needed, since it already went through the validation in `resolveFieldValidationWarningDescriptions`, so it's a duplication
        // However, when having composed fields, the warnings are caught only in `resolveValue`, hence we need to add it there too
        // Then, we will certainly have duplicates. Remove them now
        // Because these are arrays of arrays, we use the method taken from https://stackoverflow.com/a/2561283
        foreach ($schemaErrors as $dbname => &$entries) {
            foreach ($entries as $dbKey => $errors) {
                $entries[$dbKey] = array_intersect_key($errors, array_unique(array_map('serialize', $errors)));
            }
        }

        // Add the feedback (errors, warnings, deprecations) into the output
        $generalFeedbackStore = App::getFeedbackStore()->generalFeedbackStore;
        if ($generalErrors = $generalFeedbackStore->getGeneralErrors()) {
            $ret['generalErrors'] = $this->getGeneralFeedbackEntriesForOutput($generalErrors);
        }
        if ($generalWarnings = $generalFeedbackStore->getGeneralWarnings()) {
            $ret['generalWarnings'] = $this->getGeneralFeedbackEntriesForOutput($generalWarnings);
        }

        $documentFeedbackStore = App::getFeedbackStore()->documentFeedbackStore;

        if ($documentErrors = $this->getFeedbackMessageStore()->getQueryErrors()) {
            $ret['documentErrors'] = $documentErrors;
        }
        if ($documentErrors = $documentFeedbackStore->getDocumentErrors()) {
            $ret['documentErrors'] = array_merge(
                $ret['documentErrors'] ?? [],
                $this->getDocumentFeedbackEntriesForOutput($documentErrors)
            );
        }
        if ($documentWarnings = $this->getFeedbackMessageStore()->getQueryWarnings()) {
            $ret['documentWarnings'] = $documentWarnings;
        }
        if ($documentWarnings = $documentFeedbackStore->getDocumentWarnings()) {
            $ret['documentWarnings'] = array_merge(
                $ret['documentWarnings'] ?? [],
                $this->getDocumentFeedbackEntriesForOutput($documentWarnings)
            );
        }
        $this->maybeCombineAndAddDatabaseEntries($ret, 'objectErrors', $objectErrors);
        $this->maybeCombineAndAddDatabaseEntries($ret, 'objectWarnings', $objectWarnings);
        $this->maybeCombineAndAddDatabaseEntries($ret, 'objectDeprecations', $objectDeprecations);
        $this->maybeCombineAndAddDatabaseEntries($ret, 'objectNotices', $objectNotices);
        $this->maybeCombineAndAddSchemaEntries($ret, 'schemaErrors', $schemaErrors);
        $this->maybeCombineAndAddSchemaEntries($ret, 'schemaWarnings', $schemaWarnings);
        $this->maybeCombineAndAddSchemaEntries($ret, 'schemaDeprecations', $schemaDeprecations);
        $this->maybeCombineAndAddSchemaEntries($ret, 'schemaNotices', $schemaNotices);


        // Execute a hook to process the traces (in advance, we don't do anything with them)
        App::doAction(
            '\PoP\ComponentModel\Engine:traces:schema',
            $schemaTraces
        );
        App::doAction(
            '\PoP\ComponentModel\Engine:traces:db',
            $objectTraces
        );
        if (Environment::showTracesInResponse()) {
            $this->maybeCombineAndAddDatabaseEntries($ret, 'objectTraces', $objectTraces);
            $this->maybeCombineAndAddSchemaEntries($ret, 'schemaTraces', $schemaTraces);
        }

        // Show logs only if both enabled, and passing the action in the URL
        if (Environment::enableShowLogs()) {
            if (in_array(Actions::SHOW_LOGS, App::getState('actions'))) {
                $ret['logEntries'] = $this->getDocumentFeedbackEntriesForOutput($documentFeedbackStore->getDocumentLogs());
            }
        }
        $this->maybeCombineAndAddDatabaseEntries($ret, 'dbData', $databases);
        $this->maybeCombineAndAddDatabaseEntries($ret, 'unionDBKeyIDs', $unionDBKeyIDs);

        return $ret;
    }

    /**
     * @param GeneralFeedbackInterface[] $generalFeedbackEntries
     * @return array<string,mixed>
     */
    protected function getGeneralFeedbackEntriesForOutput(array $generalFeedbackEntries): array
    {
        $output = [];
        foreach ($generalFeedbackEntries as $generalFeedbackEntry) {
            $generalFeedbackEntryExtensions = [];
            if ($code = $generalFeedbackEntry->getCode()) {
                $generalFeedbackEntryExtensions['code'] = $code;
            }
            $output[$generalFeedbackEntry->getMessage()] = $generalFeedbackEntryExtensions;
        }
        return $output;
    }

    /**
     * @param DocumentFeedbackInterface[] $documentFeedbackEntries
     * @return array<array<string,mixed>>
     */
    protected function getDocumentFeedbackEntriesForOutput(array $documentFeedbackEntries): array
    {
        $output = [];
        foreach ($documentFeedbackEntries as $documentFeedbackEntry) {
            $documentFeedbackEntryExtensions = $documentFeedbackEntry->getExtensions();
            if ($code = $documentFeedbackEntry->getCode()) {
                $documentFeedbackEntryExtensions['code'] = $code;
            }
            if ($data = $documentFeedbackEntry->getData()) {
                $documentFeedbackEntryExtensions['data'] = $data;
            }
            $documentFeedbackEntryExtensions['location'] = $documentFeedbackEntry->getLocation()->toArray();
            $output[] = [
                'message' => $documentFeedbackEntry->getMessage(),
                'extensions' => $documentFeedbackEntryExtensions,
            ];
        }
        return $output;
    }

    protected function processSubcomponentData(
        RelationalTypeResolverInterface $relationalTypeResolver,
        ObjectTypeResolverInterface $targetObjectTypeResolver,
        array $typeResolver_ids,
        string $module_path_key,
        array &$databases,
        array &$subcomponents_data_properties,
        array &$already_loaded_ids_data_fields,
        array &$unionDBKeyIDs,
        array &$combinedUnionDBKeyIDs,
        array $objectIDItems,
    ): void {
        $engineState = App::getEngineState();
        $database_key = $targetObjectTypeResolver->getTypeOutputDBKey();
        foreach ($subcomponents_data_properties as $subcomponent_data_field => $subcomponent_data_properties) {
            // Retrieve the subcomponent typeResolver from the current typeResolver
            // Watch out! When dealing with the UnionDataLoader, we attempt to get the subcomponentType for that field twice: first from the UnionTypeResolver and, if it doesn't handle it, only then from the TargetTypeResolver
            // This is for the very specific use of the "self" field: When referencing "self" from a UnionTypeResolver, we don't know what type it's going to be the result, hence we need to add the type to entry "unionDBKeyIDs"
            // However, for the targetObjectTypeResolver, "self" is processed by itself, not by a UnionTypeResolver, hence it would never add the type under entry "unionDBKeyIDs".
            // The UnionTypeResolver should only handle 2 connection fields: "id" and "self"
            $subcomponentTypeResolver = $this->getDataloadHelperService()->getTypeResolverFromSubcomponentDataField($relationalTypeResolver, $subcomponent_data_field);
            if ($subcomponentTypeResolver === null && $relationalTypeResolver != $targetObjectTypeResolver) {
                $subcomponentTypeResolver = $this->getDataloadHelperService()->getTypeResolverFromSubcomponentDataField($targetObjectTypeResolver, $subcomponent_data_field);
            }
            if ($subcomponentTypeResolver !== null) {
                $subcomponentTypeOutputDBKey = $subcomponentTypeResolver->getTypeOutputDBKey();
                // The array_merge_recursive when there are at least 2 levels will make the data_fields to be duplicated, so remove duplicates now
                $subcomponent_data_fields = array_unique($subcomponent_data_properties['data-fields'] ?? []);
                $subcomponent_conditional_data_fields = $subcomponent_data_properties['conditional-data-fields'] ?? [];
                if ($subcomponent_data_fields || $subcomponent_conditional_data_fields) {
                    $subcomponentIsUnionTypeResolver = $subcomponentTypeResolver instanceof UnionTypeResolverInterface;

                    $subcomponent_already_loaded_ids_data_fields = [];
                    if ($already_loaded_ids_data_fields && ($already_loaded_ids_data_fields[$subcomponentTypeOutputDBKey] ?? null)) {
                        $subcomponent_already_loaded_ids_data_fields = $already_loaded_ids_data_fields[$subcomponentTypeOutputDBKey];
                    }
                    $subcomponentIDs = [];
                    foreach ($typeResolver_ids as $id) {
                        $object = $objectIDItems[$id];
                        $subcomponent_data_field_outputkey = $this->getFieldQueryInterpreter()->getUniqueFieldOutputKey($relationalTypeResolver, $subcomponent_data_field, $object);
                        // $databases may contain more the 1 DB shipped by pop-engine/ ("primary"). Eg: PoP User Login adds db "userstate"
                        // Fetch the field_ids from all these DBs
                        foreach ($databases as $dbname => $database) {
                            if ($database_field_ids = $database[$database_key][(string)$id][$subcomponent_data_field_outputkey] ?? null) {
                                $subcomponentIDs[$dbname][$database_key][(string)$id] = array_merge(
                                    $subcomponentIDs[$dbname][$database_key][(string)$id] ?? [],
                                    is_array($database_field_ids) ? $database_field_ids : array($database_field_ids)
                                );
                            }
                        }
                    }
                    // We don't want to store the dbKey/ID inside the relationalID, because that can lead to problems when dealing with the relations in the application (better keep it only to the ID)
                    // So, instead, we store the dbKey/ID values in another object "$unionDBKeyIDs"
                    // Then, whenever it's a union type data resolver, we obtain the values for the relationship under this other object
                    $typedSubcomponentIDs = [];
                    // if ($subcomponentIsUnionTypeResolver) {
                        // Get the types for all of the IDs all at once. Flatten 3 levels: dbname => dbkey => id => ...
                        $allSubcomponentIDs = array_values(array_unique(
                            GeneralUtils::arrayFlatten(GeneralUtils::arrayFlatten(GeneralUtils::arrayFlatten($subcomponentIDs)))
                        ));
                        $qualifiedSubcomponentIDs = $subcomponentTypeResolver->getQualifiedDBObjectIDOrIDs($allSubcomponentIDs);
                        // Create a map, from ID to TypedID
                    for ($i = 0; $i < count($allSubcomponentIDs); $i++) {
                        $typedSubcomponentIDs[$allSubcomponentIDs[$i]] = $qualifiedSubcomponentIDs[$i];
                    }
                    // }

                    $field_ids = [];
                    foreach ($subcomponentIDs as $dbname => $dbkey_id_database_field_ids) {
                        foreach ($dbkey_id_database_field_ids as $database_key => $id_database_field_ids) {
                            foreach ($id_database_field_ids as $id => $database_field_ids) {
                                // Transform the IDs, adding their type
                                // Do it always, for UnionTypeResolvers and non-union ones.
                                // This is because if it's a relational field that comes after a UnionTypeResolver, its dbKey could not be inferred (since it depends from the dbObject, and can't be obtained in the settings, where "dbkeys" is obtained and which doesn't depend on data items)
                                // Eg: /?query=content.comments.id. In this case, "content" is handled by UnionTypeResolver, and "comments" would not be found since its entry can't be added under "datasetmodulesettings.dbkeys", since the module (of class AbstractRelationalFieldQueryDataModuleProcessor) with a UnionTypeResolver can't resolve the 'succeeding-typeResolver' to set to its submodules
                                // Having 'succeeding-typeResolver' being NULL, then it is not able to locate its data
                                $typed_database_field_ids = array_map(
                                    function ($field_id) use ($typedSubcomponentIDs) {
                                        return $typedSubcomponentIDs[$field_id];
                                    },
                                    $database_field_ids
                                );
                                if ($subcomponentIsUnionTypeResolver) {
                                    $database_field_ids = $typed_database_field_ids;
                                }
                                $object = $objectIDItems[$id];
                                $subcomponent_data_field_outputkey = $this->getFieldQueryInterpreter()->getUniqueFieldOutputKey($relationalTypeResolver, $subcomponent_data_field, $object);
                                // Set on the `unionDBKeyIDs` output entry. This could be either an array or a single value. Check from the original entry which case it is
                                $entryIsArray = $databases[$dbname][$database_key][(string)$id][$subcomponent_data_field_outputkey] && is_array($databases[$dbname][$database_key][(string)$id][$subcomponent_data_field_outputkey]);
                                $unionDBKeyIDs[$dbname][$database_key][(string)$id][$subcomponent_data_field_outputkey] = $entryIsArray ? $typed_database_field_ids : $typed_database_field_ids[0];
                                $combinedUnionDBKeyIDs[$database_key][(string)$id][$subcomponent_data_field_outputkey] = $entryIsArray ? $typed_database_field_ids : $typed_database_field_ids[0];

                                // Merge, after adding their type!
                                $field_ids = array_merge(
                                    $field_ids,
                                    $database_field_ids
                                );
                            }
                        }
                    }
                    if ($field_ids) {
                        foreach ($field_ids as $field_id) {
                            // Do not add again the IDs/Fields already loaded
                            if ($subcomponent_already_loaded_data_fields = $subcomponent_already_loaded_ids_data_fields[$field_id] ?? null) {
                                $id_subcomponent_data_fields = array_values(
                                    array_diff(
                                        $subcomponent_data_fields,
                                        $subcomponent_already_loaded_data_fields
                                    )
                                );
                                $id_subcomponent_conditional_data_fields = [];
                                foreach ($subcomponent_conditional_data_fields as $conditionField => $conditionalFields) {
                                    $id_subcomponent_conditional_data_fields[$conditionField] = Methods::arrayDiffRecursive(
                                        $conditionalFields,
                                        $subcomponent_already_loaded_data_fields
                                    );
                                }
                            } else {
                                $id_subcomponent_data_fields = $subcomponent_data_fields;
                                $id_subcomponent_conditional_data_fields = $subcomponent_conditional_data_fields;
                            }
                            // Important: do ALWAYS execute the lines below, even if $id_subcomponent_data_fields is empty
                            // That is because we can load additional data for an object that was already loaded in a previous iteration
                            // Eg: /api/?query=posts(id:1).author.posts.comments.post.author.posts.title
                            // In this case, property "title" at the end would not be fetched otherwise (that post was already loaded at the beginning)
                            // if ($id_subcomponent_data_fields) {
                            $this->combineIDsDatafields($engineState->relationalTypeOutputDBKeyIDsDataFields, $subcomponentTypeResolver, $subcomponentTypeOutputDBKey, array($field_id), $id_subcomponent_data_fields, $id_subcomponent_conditional_data_fields);
                            // }
                        }
                        $this->initializeTypeResolverEntry($engineState->dbdata, $subcomponentTypeOutputDBKey, $module_path_key);
                        $engineState->dbdata[$subcomponentTypeOutputDBKey][$module_path_key]['ids'] = array_merge(
                            $engineState->dbdata[$subcomponentTypeOutputDBKey][$module_path_key]['ids'] ?? [],
                            $field_ids
                        );
                        $this->integrateSubcomponentDataProperties($engineState->dbdata, $subcomponent_data_properties, $subcomponentTypeOutputDBKey, $module_path_key);
                    }

                    if ($engineState->dbdata[$subcomponentTypeOutputDBKey][$module_path_key] ?? null) {
                        $engineState->dbdata[$subcomponentTypeOutputDBKey][$module_path_key]['ids'] = array_unique($engineState->dbdata[$subcomponentTypeOutputDBKey][$module_path_key]['ids']);
                        $engineState->dbdata[$subcomponentTypeOutputDBKey][$module_path_key]['data-fields'] = array_unique($engineState->dbdata[$subcomponentTypeOutputDBKey][$module_path_key]['data-fields']);
                    }
                }
            }
        }
    }

    protected function maybeCombineAndAddDatabaseEntries(array &$ret, string $name, array $entries): void
    {
        // Do not add the "database", "userstatedatabase" entries unless there are values in them
        // Otherwise, it messes up integrating the current databases in the webplatform with those from the response when deep merging them
        if ($entries === []) {
            return;
        }

        $dboutputmode = App::getState('dboutputmode');

        // Combine all the databases or send them separate
        if ($dboutputmode == DatabasesOutputModes::SPLITBYDATABASES) {
            $ret[$name] = $entries;
            return;
        }

        if ($dboutputmode == DatabasesOutputModes::COMBINED) {
            // Filter to make sure there are entries
            if ($entries = array_filter($entries)) {
                $combined_databases = [];
                foreach ($entries as $database_name => $database) {
                    // Combine them on an ID by ID basis, because doing [2 => [...], 3 => [...]]), which is wrong
                    foreach ($database as $database_key => $dbItems) {
                        foreach ($dbItems as $dbobject_id => $dbobject_values) {
                            $combined_databases[$database_key][(string)$dbobject_id] = array_merge(
                                $combined_databases[$database_key][(string)$dbobject_id] ?? [],
                                // If field "id" for this type has been disabled (eg: by ACL),
                                // then $dbObject may be `null`
                                $dbobject_values ?? []
                            );
                        }
                    }
                }
                $ret[$name] = $combined_databases;
            }
        }
    }

    protected function maybeCombineAndAddSchemaEntries(array &$ret, string $name, array $entries): void
    {
        if ($entries === []) {
            return;
        }

        $dboutputmode = App::getState('dboutputmode');

        // Combine all the databases or send them separate
        if ($dboutputmode == DatabasesOutputModes::SPLITBYDATABASES) {
            $ret[$name] = $entries;
            return;
        }

        if ($dboutputmode == DatabasesOutputModes::COMBINED) {
            // Filter to make sure there are entries
            if ($entries = array_filter($entries)) {
                $combined_databases = [];
                foreach ($entries as $database_name => $database) {
                    $combined_databases = array_merge_recursive(
                        $combined_databases,
                        $database
                    );
                }
                $ret[$name] = $combined_databases;
            }
        }
    }

    protected function processAndAddModuleData(
        array $module_path,
        array $module,
        array &$props,
        array $data_properties,
        $dataaccess_checkpoint_validation,
        $mutation_checkpoint_validation,
        $executed,
        $objectIDs
    ): void {
        $processor = $this->getModuleProcessorManager()->getProcessor($module);
        $engineState = App::getEngineState();

        // Integrate the feedback into $moduledata
        if ($engineState->moduledata !== null) {
            $moduledata = &$engineState->moduledata;

            // Add the feedback into the object
            if ($feedback = $processor->getDataFeedbackDatasetmoduletree($module, $props, $data_properties, $dataaccess_checkpoint_validation, $mutation_checkpoint_validation, $executed, $objectIDs)) {
                /** @var ComponentInfo */
                $componentInfo = App::getComponent(Component::class)->getInfo();
                $submodulesOutputProperty = $componentInfo->getSubmodulesOutputProperty();

                // Advance the position of the array into the current module
                foreach ($module_path as $submodule) {
                    $submoduleOutputName = $this->getModuleHelpers()->getModuleOutputName($submodule);
                    $moduledata[$submoduleOutputName][$submodulesOutputProperty] = $moduledata[$submoduleOutputName][$submodulesOutputProperty] ?? [];
                    $moduledata = &$moduledata[$submoduleOutputName][$submodulesOutputProperty];
                }
                // Merge the feedback in
                $moduledata = array_merge_recursive(
                    $moduledata,
                    $feedback
                );
            }
        }
    }

    private function initializeTypeResolverEntry(
        array &$dbdata,
        string $relationalTypeOutputDBKey,
        string $module_path_key
    ): void {
        if (!isset($dbdata[$relationalTypeOutputDBKey][$module_path_key])) {
            $dbdata[$relationalTypeOutputDBKey][$module_path_key] = array(
                'ids' => [],
                'data-fields' => [],
                'subcomponents' => [],
            );
        }
    }

    private function integrateSubcomponentDataProperties(
        array &$dbdata,
        array $data_properties,
        string $relationalTypeOutputDBKey,
        string $module_path_key
    ): void {
        // Process the subcomponents
        // If it has subcomponents, bring its data to, after executing getData on the primary typeResolver, execute getData also on the subcomponent typeResolver
        if ($subcomponents_data_properties = $data_properties['subcomponents'] ?? null) {
            // Merge them into the data
            $dbdata[$relationalTypeOutputDBKey][$module_path_key]['subcomponents'] = array_merge_recursive(
                $dbdata[$relationalTypeOutputDBKey][$module_path_key]['subcomponents'] ?? [],
                $subcomponents_data_properties
            );
        }
    }
}

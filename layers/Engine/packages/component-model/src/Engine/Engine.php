<?php

declare(strict_types=1);

namespace PoP\ComponentModel\Engine;

use PoP\ComponentModel\App;
use PoP\ComponentModel\Cache\PersistentCacheInterface;
use PoP\ComponentModel\Checkpoints\CheckpointInterface;
use PoP\ComponentModel\Component\Component;
use PoP\ComponentModel\ComponentFiltering\ComponentFilterManagerInterface;
use PoP\ComponentModel\ComponentHelpers\ComponentHelpersInterface;
use PoP\ComponentModel\ComponentPath\ComponentPathHelpersInterface;
use PoP\ComponentModel\ComponentPath\ComponentPathManagerInterface;
use PoP\ComponentModel\ComponentProcessors\ComponentProcessorManagerInterface;
use PoP\ComponentModel\ComponentProcessors\DataloadingConstants;
use PoP\ComponentModel\Configuration\Request;
use PoP\ComponentModel\Constants\DatabasesOutputModes;
use PoP\ComponentModel\Constants\DataLoading;
use PoP\ComponentModel\Constants\DataOutputItems;
use PoP\ComponentModel\Constants\DataOutputModes;
use PoP\ComponentModel\Constants\DataProperties;
use PoP\ComponentModel\Constants\DataSources;
use PoP\ComponentModel\Constants\DataSourceSelectors;
use PoP\ComponentModel\Constants\Params;
use PoP\ComponentModel\Constants\Props;
use PoP\ComponentModel\Constants\Response;
use PoP\ComponentModel\DataStructure\DataStructureManagerInterface;
use PoP\ComponentModel\Engine\EngineIterationFieldSet;
use PoP\ComponentModel\EntryComponent\EntryComponentManagerInterface;
use PoP\ComponentModel\Environment;
use PoP\ComponentModel\Feedback\EngineIterationFeedbackStore;
use PoP\ComponentModel\Feedback\FeedbackCategories;
use PoP\ComponentModel\Feedback\GeneralFeedbackInterface;
use PoP\ComponentModel\Feedback\ObjectResolutionFeedbackInterface;
use PoP\ComponentModel\Feedback\ObjectResolutionFeedbackStore;
use PoP\ComponentModel\Feedback\SchemaFeedbackInterface;
use PoP\ComponentModel\Feedback\SchemaFeedbackStore;
use PoP\ComponentModel\Feedback\Tokens;
use PoP\ComponentModel\GraphQLEngine\Model\ComponentModelSpec\ComponentFieldNodeInterface;
use PoP\ComponentModel\HelperServices\DataloadHelperServiceInterface;
use PoP\ComponentModel\HelperServices\RequestHelperServiceInterface;
use PoP\ComponentModel\Info\ApplicationInfoInterface;
use PoP\ComponentModel\Misc\GeneralUtils;
use PoP\ComponentModel\ModelInstance\ModelInstanceInterface;
use PoP\ComponentModel\Module;
use PoP\ComponentModel\ModuleConfiguration;
use PoP\ComponentModel\ModuleInfo;
use PoP\ComponentModel\Schema\FieldQueryInterpreterInterface;
use PoP\ComponentModel\TypeResolvers\ObjectType\ObjectTypeResolverInterface;
use PoP\ComponentModel\TypeResolvers\RelationalTypeResolverInterface;
use PoP\ComponentModel\TypeResolvers\UnionType\UnionTypeHelpers;
use PoP\ComponentModel\TypeResolvers\UnionType\UnionTypeResolverInterface;
use PoP\Definitions\Constants\Params as DefinitionsParams;
use PoP\FieldQuery\FeedbackMessageStoreInterface;
use PoP\GraphQLParser\Spec\Parser\Ast\AstInterface;
use PoP\GraphQLParser\Spec\Parser\Ast\FieldInterface;
use PoP\Root\Exception\ImpossibleToHappenException;
use PoP\Root\Feedback\FeedbackItemResolution;
use PoP\Root\Services\BasicServiceTrait;
use SplObjectStorage;

class Engine implements EngineInterface
{
    use BasicServiceTrait;

    public const HOOK_DBNAME_TO_FIELDNAMES = __CLASS__ . ':dbName-to-fieldNames';

    public const PRIMARY_DBNAME = 'primary';

    public final const CACHETYPE_IMMUTABLEDATASETSETTINGS = 'static-datasetsettings';
    public final const CACHETYPE_STATICDATAPROPERTIES = 'static-data-properties';
    public final const CACHETYPE_STATEFULDATAPROPERTIES = 'stateful-data-properties';
    public final const CACHETYPE_PROPS = 'props';

    protected final const DATA_PROP_RELATIONAL_TYPE_RESOLVER = 'relationalTypeResolver';
    protected final const DATA_PROP_ID_FIELD_SET = 'idFieldSet';

    /**
     * @var array<string,string[]>|null
     */
    protected ?array $dbNameFieldNames = null;

    private ?PersistentCacheInterface $persistentCache = null;
    private ?DataStructureManagerInterface $dataStructureManager = null;
    private ?ModelInstanceInterface $modelInstance = null;
    private ?FeedbackMessageStoreInterface $feedbackMessageStore = null;
    private ?ComponentPathHelpersInterface $componentPathHelpers = null;
    private ?ComponentPathManagerInterface $componentPathManager = null;
    private ?FieldQueryInterpreterInterface $fieldQueryInterpreter = null;
    private ?ComponentFilterManagerInterface $componentFilterManager = null;
    private ?ComponentProcessorManagerInterface $componentProcessorManager = null;
    private ?DataloadHelperServiceInterface $dataloadHelperService = null;
    private ?EntryComponentManagerInterface $entryComponentManager = null;
    private ?RequestHelperServiceInterface $requestHelperService = null;
    private ?ApplicationInfoInterface $applicationInfo = null;
    private ?ComponentHelpersInterface $componentHelpers = null;

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
    final public function setComponentPathHelpers(ComponentPathHelpersInterface $componentPathHelpers): void
    {
        $this->componentPathHelpers = $componentPathHelpers;
    }
    final protected function getComponentPathHelpers(): ComponentPathHelpersInterface
    {
        return $this->componentPathHelpers ??= $this->instanceManager->getInstance(ComponentPathHelpersInterface::class);
    }
    final public function setComponentPathManager(ComponentPathManagerInterface $componentPathManager): void
    {
        $this->componentPathManager = $componentPathManager;
    }
    final protected function getComponentPathManager(): ComponentPathManagerInterface
    {
        return $this->componentPathManager ??= $this->instanceManager->getInstance(ComponentPathManagerInterface::class);
    }
    final public function setFieldQueryInterpreter(FieldQueryInterpreterInterface $fieldQueryInterpreter): void
    {
        $this->fieldQueryInterpreter = $fieldQueryInterpreter;
    }
    final protected function getFieldQueryInterpreter(): FieldQueryInterpreterInterface
    {
        return $this->fieldQueryInterpreter ??= $this->instanceManager->getInstance(FieldQueryInterpreterInterface::class);
    }
    final public function setComponentFilterManager(ComponentFilterManagerInterface $componentFilterManager): void
    {
        $this->componentFilterManager = $componentFilterManager;
    }
    final protected function getComponentFilterManager(): ComponentFilterManagerInterface
    {
        return $this->componentFilterManager ??= $this->instanceManager->getInstance(ComponentFilterManagerInterface::class);
    }
    final public function setComponentProcessorManager(ComponentProcessorManagerInterface $componentProcessorManager): void
    {
        $this->componentProcessorManager = $componentProcessorManager;
    }
    final protected function getComponentProcessorManager(): ComponentProcessorManagerInterface
    {
        return $this->componentProcessorManager ??= $this->instanceManager->getInstance(ComponentProcessorManagerInterface::class);
    }
    final public function setDataloadHelperService(DataloadHelperServiceInterface $dataloadHelperService): void
    {
        $this->dataloadHelperService = $dataloadHelperService;
    }
    final protected function getDataloadHelperService(): DataloadHelperServiceInterface
    {
        return $this->dataloadHelperService ??= $this->instanceManager->getInstance(DataloadHelperServiceInterface::class);
    }
    final public function setEntryComponentManager(EntryComponentManagerInterface $entryComponentManager): void
    {
        $this->entryComponentManager = $entryComponentManager;
    }
    final protected function getEntryComponentManager(): EntryComponentManagerInterface
    {
        return $this->entryComponentManager ??= $this->instanceManager->getInstance(EntryComponentManagerInterface::class);
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
    final public function setComponentHelpers(ComponentHelpersInterface $componentHelpers): void
    {
        $this->componentHelpers = $componentHelpers;
    }
    final protected function getComponentHelpers(): ComponentHelpersInterface
    {
        return $this->componentHelpers ??= $this->instanceManager->getInstance(ComponentHelpersInterface::class);
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

    public function getEntryComponent(): Component
    {
        $engineState = App::getEngineState();

        // Use cached results
        if ($engineState->entryComponent !== null) {
            return $engineState->entryComponent;
        }

        // Obtain, validate and cache
        $engineState->entryComponent = $this->getEntryComponentManager()->getEntryComponent();
        if ($engineState->entryComponent === null) {
            throw new ImpossibleToHappenException(
                $this->__('No entry component for this request', 'component-model')
            );
        }

        return $engineState->entryComponent;
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
        /** @var ModuleInfo */
        $moduleInfo = App::getModule(Module::class)->getInfo();
        $differentiators = array(
            $moduleInfo->getUniqueID(),
            $moduleInfo->getRand(),
            $moduleInfo->getTime(),
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
         * Please notice: ?component=settings doesn't have 'nocache-fields'
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
        App::regenerateTracingStore();
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

        /**
         * @todo Remove this temporary code to remove feedback state
         */
        $this->getFeedbackMessageStore()->clearAll();
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

    public function getModelPropsComponentTree(Component $component): array
    {
        /** @var ModuleConfiguration */
        $moduleConfiguration = App::getModule(Module::class)->getConfiguration();
        $useCache = $moduleConfiguration->useComponentModelCache();
        $processor = $this->getComponentProcessorManager()->getComponentProcessor($component);

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
            $processor->initModelPropsComponentTree($component, $model_props, [], []);

            if ($useCache) {
                $this->getPersistentCache()->storeCacheByModelInstance(self::CACHETYPE_PROPS, $model_props);
            }
        }

        return $model_props;
    }

    // Notice that $props is passed by copy, this way the input $model_props and the returned $immutable_plus_request_props are different objects
    public function addRequestPropsComponentTree(Component $component, array $props): array
    {
        $processor = $this->getComponentProcessorManager()->getComponentProcessor($component);

        // The input $props is the model_props. We add, on object, the mutableonrequest props, resulting in a "static + mutableonrequest" props object
        $processor->initRequestPropsComponentTree($component, $props, [], []);

        return $props;
    }

    protected function processAndGenerateData(): void
    {
        // Externalize logic into function so it can be overridden by PoP Web Platform Engine
        $dataoutputitems = App::getState('dataoutputitems');

        // From the state we know if to process static/staful content or both
        $datasourceselector = App::getState('datasourceselector');

        // Get the entry component based on the application configuration and the nature
        $component = $this->getEntryComponent();

        $engineState = App::getEngineState();

        // Save it to be used by the children class
        // Static props are needed for both static/mutableonrequest operations, so build it always
        $engineState->model_props = $this->getModelPropsComponentTree($component);

        // If only getting static content, then no need to add the mutableonrequest props
        if ($datasourceselector == DataSourceSelectors::ONLYMODEL) {
            $engineState->props = $engineState->model_props;
        } else {
            $engineState->props = $this->addRequestPropsComponentTree($component, $engineState->model_props);
        }

        // Allow for extra operations (eg: calculate resources)
        App::doAction(
            '\PoP\ComponentModel\Engine:helperCalculations',
            array(&$engineState->helperCalculations),
            $component,
            array(&$engineState->props)
        );

        $data = [];
        if (in_array(DataOutputItems::DATASET_COMPONENT_SETTINGS, $dataoutputitems)) {
            $data = array_merge(
                $data,
                $this->getComponentDatasetSettings($component, $engineState->model_props, $engineState->props)
            );
        }

        // Comment Leo 20/01/2018: we must first initialize all the settings, and only later add the data.
        // That is because calculating the data may need the values from the settings. Eg: for the resourceLoader,
        // calculating $loadingframe_resources needs to know all the Handlebars templates from the sitemapping as to generate file "resources.js",
        // which is done through an action, called through getData()
        // Data = objectIDs (data-ids) + feedback + database
        if (
            in_array(DataOutputItems::COMPONENT_DATA, $dataoutputitems)
            || in_array(DataOutputItems::DATABASES, $dataoutputitems)
        ) {
            $data = array_merge(
                $data,
                $this->getComponentData($component, $engineState->model_props, $engineState->props)
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
            // IMPORTANT: Call these methods after doing ->getComponentData, since the background_urls and other info is calculated there and printed here
            if ($requestmeta = $this->getRequestMeta()) {
                $data['requestmeta'] = $has_extra_routes ? array($current_uri => $requestmeta) : $requestmeta;
            }
        }

        // Comment Leo 14/09/2018: Re-enable here:
        // // Combine the statelessdata and mutableonrequestdata objects
        // if ($data['componentsettings'] ?? null) {

        //     $data['componentsettings']['combinedstate'] = array_merge_recursive(
        //         $data['componentsettings']['immutable'] ?? []
        //         $data['componentsettings']['mutableonmodel'] ?? []
        //         $data['componentsettings']['mutableonrequest'] ?? [],
        //     );
        // }
        // if ($data['componentdata'] ?? null) {

        //     $data['componentdata']['combinedstate'] = array_merge_recursive(
        //         $data['componentdata']['immutable'] ?? []
        //         $data['componentdata']['mutableonmodel'] ?? []
        //         $data['componentdata']['mutableonrequest'] ?? [],
        //     );
        // }
        // if ($data['datasetcomponentdata'] ?? null) {

        //     $data['datasetcomponentdata']['combinedstate'] = array_merge_recursive(
        //         $data['datasetcomponentdata']['immutable'] ?? []
        //         $data['datasetcomponentdata']['mutableonmodel'] ?? []
        //         $data['datasetcomponentdata']['mutableonrequest'] ?? [],
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
            // IMPORTANT: Call these methods after doing ->getComponentData, since the background_urls and other info is calculated there and printed here
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

    public function getComponentDatasetSettings(Component $component, $model_props, array &$props): array
    {
        $ret = [];
        /** @var ModuleConfiguration */
        $moduleConfiguration = App::getModule(Module::class)->getConfiguration();
        $useCache = $moduleConfiguration->useComponentModelCache();
        $processor = $this->getComponentProcessorManager()->getComponentProcessor($component);
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
            $immutable_datasetsettings = $processor->getImmutableSettingsDatasetcomponentTree($component, $model_props);

            if ($useCache) {
                $this->getPersistentCache()->storeCacheByModelInstance(self::CACHETYPE_IMMUTABLEDATASETSETTINGS, $immutable_datasetsettings);
            }
        }

        // If there are multiple URIs, then the results must be returned under the corresponding $model_instance_id for "mutableonmodel", and $url for "mutableonrequest"
        list($has_extra_routes, $model_instance_id, $current_uri) = $this->listExtraRouteVars();

        if ($dataoutputmode == DataOutputModes::SPLITBYSOURCES) {
            if ($immutable_datasetsettings) {
                $ret['datasetcomponentsettings']['immutable'] = $immutable_datasetsettings;
            }
        } elseif ($dataoutputmode == DataOutputModes::COMBINED) {
            // If everything is combined, then it belongs under "mutableonrequest"
            if ($combined_datasetsettings = $immutable_datasetsettings) {
                $ret['datasetcomponentsettings'] = $has_extra_routes ? array($current_uri => $combined_datasetsettings) : $combined_datasetsettings;
            }
        }

        return $ret;
    }

    public function getRequestMeta(): array
    {
        /** @var ModuleInfo */
        $moduleInfo = App::getModule(Module::class)->getInfo();
        $entryComponent = $this->getEntryComponent();
        $meta = array(
            Response::ENTRY_COMPONENT => $entryComponent->name,
            Response::UNIQUE_ID => $moduleInfo->getUniqueID(),
            'modelinstanceid' => $this->getModelInstance()->getModelInstanceId(),
        );

        if (App::isHTTPRequest()) {
            $meta[Response::URL] = $this->getRequestHelperService()->getCurrentURL();
        }

        $engineState = App::getEngineState();
        if ($engineState->backgroundload_urls) {
            $meta[Response::BACKGROUND_LOAD_URLS] = $engineState->backgroundload_urls;
        };

        // Starting from what components must do the rendering. Allow for empty arrays (eg: componentPaths[]=somewhatevervalue)
        $not_excluded_component_sets = $this->getComponentFilterManager()->getNotExcludedComponentSets();
        if (!is_null($not_excluded_component_sets)) {
            // Print the settings id of each component. Then, a component can feed data to another one by sharing the same settings id (eg: self::COMPONENT_BLOCK_USERAVATAR_EXECUTEUPDATE and PoP_UserAvatarProcessors_Module_Processor_UserBlocks::COMPONENT_BLOCK_USERAVATAR_UPDATE)
            $filteredsettings = [];
            foreach ($not_excluded_component_sets as $components) {
                $filteredsettings[] = array_map(
                    $this->getComponentHelpers()->getComponentOutputName(...),
                    $components
                );
            }
            $meta['filteredcomponents'] = $filteredsettings;
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

    /**
     * @param array<string,array{relationalTypeResolver: RelationalTypeResolverInterface, idFieldSet: array<string|int,EngineIterationFieldSet>}> $relationalTypeOutputKeyIDFieldSets
     * @param array<string|int> $ids
     * @param ComponentFieldNodeInterface[] $directComponentFieldNodes
     * @param SplObjectStorage<ComponentFieldNodeInterface,ComponentFieldNodeInterface[]> $conditionalComponentFieldNodesSplObjectStorage
     */
    private function combineIDsDatafields(
        array &$relationalTypeOutputKeyIDFieldSets,
        RelationalTypeResolverInterface $relationalTypeResolver,
        string $relationalTypeOutputKey,
        array $ids,
        array $directComponentFieldNodes,
        SplObjectStorage $conditionalComponentFieldNodesSplObjectStorage
    ): void {
        $relationalTypeOutputKeyIDFieldSets[$relationalTypeOutputKey] ??= [
            self::DATA_PROP_RELATIONAL_TYPE_RESOLVER => $relationalTypeResolver,
            self::DATA_PROP_ID_FIELD_SET => [],
        ];
        foreach ($ids as $id) {
            /** @var EngineIterationFieldSet */
            $engineIterationFieldSet = $relationalTypeOutputKeyIDFieldSets[$relationalTypeOutputKey][self::DATA_PROP_ID_FIELD_SET][$id]
                ?? new EngineIterationFieldSet(
                    array_map(
                        fn (ComponentFieldNodeInterface $componentFieldNode) => $componentFieldNode->getField(),
                        $this->getDBObjectMandatoryFields()
                    )
                );

            // Add the 'direct' fields
            $engineIterationFieldSet->addFields(
                array_map(
                    fn (ComponentFieldNodeInterface $componentFieldNode) => $componentFieldNode->getField(),
                    $directComponentFieldNodes
                )
            );

            /**
             * Add the 'conditional' fields, as an array with this format:
             *
             *   Key: condition field
             *   Value: the list of conditional fields to load
             *          if the condition one is successful (eg: if it's `true`)
             */
            foreach ($conditionalComponentFieldNodesSplObjectStorage as $conditionComponentFieldNode) {
                /** @var ComponentFieldNodeInterface $conditionComponentFieldNode */
                $conditionalFields = $conditionalComponentFieldNodesSplObjectStorage[$conditionComponentFieldNode];
                /** @var ComponentFieldNodeInterface[] $conditionalFields */
                $conditionField = $conditionComponentFieldNode->getField();
                $conditionalComponentFieldNodes = [];
                foreach ($conditionalFields as $conditionalField) {
                    /** @var ComponentFieldNodeInterface $conditionalField */
                    $conditionalComponentFieldNodes[] = $conditionalField;
                }
                $engineIterationFieldSet->conditionalFields[$conditionField] = array_merge(
                    $engineIterationFieldSet->conditionalFields[$conditionField] ??= [],
                    array_map(
                        fn (ComponentFieldNodeInterface $componentFieldNode) => $componentFieldNode->getField(),
                        $conditionalComponentFieldNodes
                    )
                );
            }
            $relationalTypeOutputKeyIDFieldSets[$relationalTypeOutputKey][self::DATA_PROP_ID_FIELD_SET][$id] = $engineIterationFieldSet;
        }
    }

    /**
     * If any field must be retrieved always (eg: the object ID
     * must always be displayed in the client) then add it here.
     *
     * @return ComponentFieldNodeInterface[]
     */
    protected function getDBObjectMandatoryFields(): array
    {
        return [];
    }

    /**
     * @param array<string,array<string|int,SplObjectStorage<FieldInterface,mixed>>> $database
     * @param array<string|int,SplObjectStorage<FieldInterface,mixed>> $dataItems
     */
    private function doAddDatasetToDatabase(
        array &$database,
        string $typeOutputKey,
        array $dataItems
    ): void {
        /**
         * Save in the database under the corresponding database-key.
         * This way, different dataloaders, like 'list-users' and 'author',
         * can both save their results under database key 'users'
         */
        if (!isset($database[$typeOutputKey])) {
            $database[$typeOutputKey] = $dataItems;
            return;
        }

        foreach ($dataItems as $id => $dbobject_values) {
            /** @var SplObjectStorage<FieldInterface,mixed> */
            $dbIDFieldValues = $database[$typeOutputKey][$id] ?? new SplObjectStorage();
            $dbIDFieldValues->addAll($dbobject_values);
            $database[$typeOutputKey][$id] = $dbIDFieldValues;
        }
    }

    /**
     * @param array<string,array<string|int,SplObjectStorage<FieldInterface,mixed>>> $database
     * @param array<string|int,SplObjectStorage<FieldInterface,mixed>> $dataItems
     */
    private function addDatasetToDatabase(
        array &$database,
        RelationalTypeResolverInterface $relationalTypeResolver,
        string $typeOutputKey,
        array $dataItems,
        array $idObjects,
        bool $addEntryIfError = false,
    ): void {
        // Do not create the database key entry when there are no items, or it produces an error when deep merging the database object in the webplatform with that from the response
        if (!$dataItems) {
            return;
        }

        $isUnionTypeResolver = $relationalTypeResolver instanceof UnionTypeResolverInterface;
        if ($isUnionTypeResolver) {
            /** @var UnionTypeResolverInterface $relationalTypeResolver */
            // Get the actual type for each entity, and add the entry there
            $targetObjectTypeResolverNameTypeResolvers = $targetObjectTypeResolverNameDataItems = $targetObjectTypeResolverNameTypeOutputKeys = [];
            $noTargetObjectTypeResolverDataItems = [];
            foreach ($dataItems as $objectID => $dataItem) {
                // Obtain the type of the object
                $exists = false;
                if ($object = $idObjects[$objectID] ?? null) {
                    $targetObjectTypeResolver = $relationalTypeResolver->getTargetObjectTypeResolver($object);
                    if ($targetObjectTypeResolver !== null) {
                        $exists = true;
                        // The ID will contain the type. Remove it
                        list(
                            $objectTypeOutputKey,
                            $objectID
                        ) = UnionTypeHelpers::extractObjectTypeAndID($objectID);

                        $targetObjectTypeResolverName = $targetObjectTypeResolver->getNamespacedTypeName();
                        $targetObjectTypeResolverNameTypeResolvers[$targetObjectTypeResolverName] = $targetObjectTypeResolver;
                        $targetObjectTypeResolverNameTypeOutputKeys[$targetObjectTypeResolverName] = $objectTypeOutputKey;
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
                $targetObjectTypeOutputKey = $targetObjectTypeResolverNameTypeOutputKeys[$targetObjectTypeResolverName];
                $this->addDatasetToDatabase($database, $targetObjectTypeResolver, $targetObjectTypeOutputKey, $convertedDataItems, $idObjects, $addEntryIfError);
            }
            // Add the errors under the UnionTypeResolver key
            if ($noTargetObjectTypeResolverDataItems) {
                $this->doAddDatasetToDatabase($database, $typeOutputKey, $noTargetObjectTypeResolverDataItems);
            }
            return;
        }
        $this->doAddDatasetToDatabase($database, $typeOutputKey, $dataItems);
    }

    protected function getInterreferencedComponentFullPaths(Component $component, array &$props): array
    {
        $paths = [];
        $this->addInterreferencedComponentFullPaths($paths, [], $component, $props);
        return $paths;
    }

    private function addInterreferencedComponentFullPaths(
        array &$paths,
        array $component_path,
        Component $component,
        array &$props
    ): void {
        $processor = $this->getComponentProcessorManager()->getComponentProcessor($component);
        $componentFullName = $this->getComponentHelpers()->getComponentFullName($component);

        // If componentPaths is provided, and we haven't reached the destination component yet, then do not execute the function at this level
        if (!$this->getComponentFilterManager()->excludeSubcomponent($component, $props)) {
            // If the current component loads data, then add its path to the list
            if ($interreferenced_componentPath = $processor->getDataFeedbackInterreferencedComponentPath($component, $props)) {
                $referenced_componentPath = $this->getComponentPathHelpers()->stringifyComponentPath($interreferenced_componentPath);
                $paths[$referenced_componentPath] = $paths[$referenced_componentPath] ?? [];
                $paths[$referenced_componentPath][] = array_merge(
                    $component_path,
                    array(
                        $component
                    )
                );
            }
        }

        $subcomponent_path = array_merge(
            $component_path,
            array(
                $component,
            )
        );

        // Propagate to its inner components
        $subcomponents = $processor->getAllSubcomponents($component);
        $subcomponents = $this->getComponentFilterManager()->removeExcludedSubcomponents($component, $subcomponents);

        // This function must be called always, to register matching components into requestmeta.filtercomponents even when the component has no subcomponents
        $this->getComponentFilterManager()->prepareForPropagation($component, $props);
        foreach ($subcomponents as $subcomponent) {
            $this->addInterreferencedComponentFullPaths($paths, $subcomponent_path, $subcomponent, $props[$componentFullName][Props::SUBCOMPONENTS]);
        }
        $this->getComponentFilterManager()->restoreFromPropagation($component, $props);
    }

    /**
     * @return array<Component[]>
     */
    protected function getDataloadingComponentFullPaths(Component $component, array &$props): array
    {
        $paths = [];
        $this->addDataloadingComponentFullPaths($paths, [], $component, $props);
        return $paths;
    }

    /**
     * @param array<Component[]> $paths
     * @param Component[] $component_path
     */
    private function addDataloadingComponentFullPaths(
        array &$paths,
        array $component_path,
        Component $component,
        array &$props
    ): void {
        $processor = $this->getComponentProcessorManager()->getComponentProcessor($component);
        $componentFullName = $this->getComponentHelpers()->getComponentFullName($component);

        // If componentPaths is provided, and we haven't reached the destination component yet, then do not execute the function at this level
        if (!$this->getComponentFilterManager()->excludeSubcomponent($component, $props)) {
            // If the current component loads data, then add its path to the list
            if ($processor->doesComponentLoadData($component)) {
                $paths[] = array_merge(
                    $component_path,
                    array(
                        $component
                    )
                );
            }
        }

        $subcomponent_path = array_merge(
            $component_path,
            array(
                $component,
            )
        );

        // Propagate to its inner components
        $subcomponents = $processor->getAllSubcomponents($component);
        $subcomponents = $this->getComponentFilterManager()->removeExcludedSubcomponents($component, $subcomponents);

        // This function must be called always, to register matching components into requestmeta.filtercomponents even when the component has no subcomponents
        $this->getComponentFilterManager()->prepareForPropagation($component, $props);
        foreach ($subcomponents as $subcomponent) {
            $this->addDataloadingComponentFullPaths($paths, $subcomponent_path, $subcomponent, $props[$componentFullName][Props::SUBCOMPONENTS]);
        }
        $this->getComponentFilterManager()->restoreFromPropagation($component, $props);
    }

    protected function assignValueForComponent(
        array &$array,
        array $component_path,
        Component $component,
        string $key,
        mixed $value,
    ): void {
        /** @var ModuleInfo */
        $moduleInfo = App::getModule(Module::class)->getInfo();
        $subcomponentsOutputProperty = $moduleInfo->getSubcomponentsOutputProperty();
        $array_pointer = &$array;
        foreach ($component_path as $subcomponent) {
            // Notice that when generating the array for the response, we don't use $component anymore, but $componentOutputName
            $subcomponentOutputName = $this->getComponentHelpers()->getComponentOutputName($subcomponent);

            // If the path doesn't exist, create it
            if (!isset($array_pointer[$subcomponentOutputName][$subcomponentsOutputProperty])) {
                $array_pointer[$subcomponentOutputName][$subcomponentsOutputProperty] = [];
            }

            // The pointer is the location in the array where the value will be set
            $array_pointer = &$array_pointer[$subcomponentOutputName][$subcomponentsOutputProperty];
        }

        $componentOutputName = $this->getComponentHelpers()->getComponentOutputName($component);
        $array_pointer[$componentOutputName][$key] = $value;
    }

    /**
     * @param CheckpointInterface[] $checkpoints
     */
    public function validateCheckpoints(array $checkpoints): ?FeedbackItemResolution
    {
        /**
         * Iterate through the list of all checkpoints, process all of them,
         * if any produces an error, already return it
         */
        foreach ($checkpoints as $checkpoint) {
            $feedbackItemResolution = $checkpoint->validateCheckpoint();
            if ($feedbackItemResolution !== null) {
                return $feedbackItemResolution;
            }
        }

        return null;
    }

    protected function getComponentPathKey(array $component_path, Component $component): string
    {
        $componentFullName = $this->getComponentHelpers()->getComponentFullName($component);
        return $componentFullName . '-' . implode('.', $component_path);
    }

    // This function is not private, so it can be accessed by the automated emails to regenerate the html for each user
    public function getComponentData(Component $root_component, array $root_model_props, array $root_props): array
    {
        /** @var ModuleConfiguration */
        $moduleConfiguration = App::getModule(Module::class)->getConfiguration();
        $useCache = $moduleConfiguration->useComponentModelCache();
        $root_processor = $this->getComponentProcessorManager()->getComponentProcessor($root_component);
        $engineState = App::getEngineState();

        // From the state we know if to process static/staful content or both
        $datasourceselector = App::getState('datasourceselector');
        $dataoutputmode = App::getState('dataoutputmode');
        $dataoutputitems = App::getState('dataoutputitems');
        $add_meta = in_array(DataOutputItems::META, $dataoutputitems);

        $immutable_componentdata = $mutableonmodel_componentdata = $mutableonrequest_componentdata = [];
        $immutable_datasetcomponentdata = $mutableonmodel_datasetcomponentdata = $mutableonrequest_datasetcomponentdata = [];
        if ($add_meta) {
            $immutable_datasetcomponentmeta = $mutableonmodel_datasetcomponentmeta = $mutableonrequest_datasetcomponentmeta = [];
        }
        $engineState->dbdata = [];

        // Save all the BACKGROUND_LOAD urls to send back to the browser, to load immediately again (needed to fetch non-cacheable data-fields)
        $engineState->backgroundload_urls = [];

        // Load under global key (shared by all pagesections / blocks)
        $engineState->relationalTypeOutputKeyIDFieldSets = [];

        // Allow PoP UserState to add the lazy-loaded userstate data triggers
        App::doAction(
            '\PoP\ComponentModel\Engine:getComponentData:start',
            $root_component,
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
            $immutable_data_properties = $root_processor->getImmutableDataPropertiesDatasetcomponentTree($root_component, $root_model_props);
            if ($useCache) {
                $this->getPersistentCache()->storeCacheByModelInstance(self::CACHETYPE_STATICDATAPROPERTIES, $immutable_data_properties);
            }
        }
        if ($mutableonmodel_data_properties === null) {
            $mutableonmodel_data_properties = $root_processor->getMutableonmodelDataPropertiesDatasetcomponentTree($root_component, $root_model_props);
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
            $mutableonrequest_data_properties = $root_processor->getMutableonrequestDataPropertiesDatasetcomponentTree($root_component, $root_props);
            $root_data_properties = array_merge_recursive(
                $model_data_properties,
                $mutableonrequest_data_properties
            );
        }

        // Get the list of all components which calculate their data feedback using another component's results
        $interreferenced_componentfullpaths = $this->getInterreferencedComponentFullPaths($root_component, $root_props);

        // Get the list of all components which load data, as a list of the component path starting from the top element (the entry component)
        $component_fullpaths = $this->getDataloadingComponentFullPaths($root_component, $root_props);

        /** @var ModuleInfo */
        $moduleInfo = App::getModule(Module::class)->getInfo();
        $subcomponentsOutputProperty = $moduleInfo->getSubcomponentsOutputProperty();

        // The components below are already included, so tell the filtermanager to not validate if they must be excluded or not
        $this->getComponentFilterManager()->setNeverExclude(true);
        foreach ($component_fullpaths as $component_path) {
            // The component is the last element in the path.
            // Notice that the component is removed from the path, providing the path to all its properties
            $component = array_pop($component_path);
            $componentFullName = $this->getComponentHelpers()->getComponentFullName($component);

            // Artificially set the current path on the path manager. It will be needed in getDatasetmeta, which calls getDataloadSource, which needs the current path
            $this->getComponentPathManager()->setPropagationCurrentPath($component_path);

            // Data Properties: assign by reference, so that changes to this variable are also performed in the original variable
            $data_properties = &$root_data_properties;
            foreach ($component_path as $subcomponent) {
                $subcomponentFullName = $this->getComponentHelpers()->getComponentFullName($subcomponent);
                $data_properties = &$data_properties[$subcomponentFullName][$subcomponentsOutputProperty];
            }
            $data_properties = &$data_properties[$componentFullName][DataLoading::DATA_PROPERTIES];
            $datasource = $data_properties[DataloadingConstants::DATASOURCE] ?? null;

            // If we are only requesting data from the model alone, and this dataloading component depends on mutableonrequest, then skip it
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
                // Check if the component fails checkpoint validation. If so, it must not load its data or execute the componentMutationResolverBridge
                $dataaccess_checkpoint_validation = $this->validateCheckpoints($checkpoints);
                $load_data = $dataaccess_checkpoint_validation !== null;
            }

            // The $props is directly moving the array to the corresponding path
            $props = &$root_props;
            $model_props = &$root_model_props;
            foreach ($component_path as $subcomponent) {
                $subcomponentFullName = $this->getComponentHelpers()->getComponentFullName($subcomponent);
                $props = &$props[$subcomponentFullName][Props::SUBCOMPONENTS];
                $model_props = &$model_props[$subcomponentFullName][Props::SUBCOMPONENTS];
            }

            $component_props = null;
            if (
                in_array(
                    $datasource,
                    array(
                    DataSources::IMMUTABLE,
                    DataSources::MUTABLEONMODEL,
                    )
                )
            ) {
                $component_props = &$model_props;
            } elseif ($datasource == DataSources::MUTABLEONREQUEST) {
                $component_props = &$props;
            }

            $processor = $this->getComponentProcessorManager()->getComponentProcessor($component);

            // The component path key is used for storing temporary results for later retrieval
            $component_path_key = $this->getComponentPathKey($component_path, $component);

            // If data is not loaded, then an empty array will be saved for the dbobject ids
            $dataset_meta = $objectIDs = $typeDBObjectIDs = [];
            $mutation_checkpoint_validation = $executed = $objectIDOrIDs = $typeDBObjectIDOrIDs = $relationalTypeOutputKey = null;
            if ($load_data) {
                // ------------------------------------------
                // Action Executers
                // ------------------------------------------
                // Allow to plug-in functionality here (eg: form submission)
                // Execute at the very beginning, so the result of the execution can also be fetched later below
                // (Eg: creation of a new location => retrieving its data / Adding a new comment)
                // Pass data_properties so these can also be modified (eg: set id of newly created Location)
                $componentMutationResolverBridge = $processor->getComponentMutationResolverBridge($component);
                if ($componentMutationResolverBridge !== null && $processor->shouldExecuteMutation($component, $props)) {
                    // Validate that the actionexecution must be triggered through its own checkpoints
                    $execute = true;
                    $mutation_checkpoint_validation = null;
                    if ($mutation_checkpoints = $data_properties[DataLoading::ACTION_EXECUTION_CHECKPOINTS] ?? null) {
                        // Check if the component fails checkpoint validation. If so, it must not load its data or execute the componentMutationResolverBridge
                        $mutation_checkpoint_validation = $this->validateCheckpoints($mutation_checkpoints);
                        $execute = $mutation_checkpoint_validation !== null;
                    }
                    if ($execute) {
                        $executed = $componentMutationResolverBridge->executeMutation($data_properties);
                    }
                }

                // Allow components to change their data_properties based on the actionexecution of previous components.
                $processor->prepareDataPropertiesAfterMutationExecution($component, $component_props, $data_properties);

                // Re-calculate $data_load, it may have been changed by `prepareDataPropertiesAfterMutationExecution`
                $load_data = !isset($data_properties[DataloadingConstants::SKIPDATALOAD]) || !$data_properties[DataloadingConstants::SKIPDATALOAD];
                if ($load_data) {
                    $relationalTypeResolver = $processor->getRelationalTypeResolver($component);
                    $isUnionTypeResolver = $relationalTypeResolver instanceof UnionTypeResolverInterface;
                    $relationalTypeOutputKey = $relationalTypeResolver->getTypeOutputKey();
                    // ------------------------------------------
                    // Data Properties Query Args: add mutableonrequest data
                    // ------------------------------------------
                    // Execute and get the ids and the meta
                    $objectIDOrIDs = $processor->getObjectIDOrIDs($component, $component_props, $data_properties);
                    // To simplify the logic, deal with arrays only
                    if ($objectIDOrIDs === null) {
                        $objectIDOrIDs = [];
                    }
                    // If the type is union, we must add the type to each object
                    $typeDBObjectIDOrIDs = $isUnionTypeResolver ?
                        $relationalTypeResolver->getQualifiedDBObjectIDOrIDs($objectIDOrIDs)
                        : $objectIDOrIDs;

                    $objectIDs = is_array($objectIDOrIDs) ? $objectIDOrIDs : array($objectIDOrIDs);
                    $typeDBObjectIDs = is_array($typeDBObjectIDOrIDs) ? $typeDBObjectIDOrIDs : array($typeDBObjectIDOrIDs);

                    // Store the ids under $data under key dataload_name => id
                    $directComponentFieldNodes = $data_properties[DataProperties::DIRECT_COMPONENT_FIELD_NODES] ?? [];
                    $conditionalComponentFieldNodesSplObjectStorage = $data_properties[DataProperties::CONDITIONAL_COMPONENT_FIELD_NODES] ?? new SplObjectStorage();
                    $this->combineIDsDatafields($engineState->relationalTypeOutputKeyIDFieldSets, $relationalTypeResolver, $relationalTypeOutputKey, $typeDBObjectIDs, $directComponentFieldNodes, $conditionalComponentFieldNodesSplObjectStorage);

                    // Add the IDs to the possibly-already produced IDs for this typeResolver
                    $this->initializeTypeResolverEntry($engineState->dbdata, $relationalTypeOutputKey, $component_path_key);
                    $engineState->dbdata[$relationalTypeOutputKey][$component_path_key][DataProperties::IDS] = array_merge(
                        $engineState->dbdata[$relationalTypeOutputKey][$component_path_key][DataProperties::IDS],
                        $typeDBObjectIDs
                    );

                    // The supplementary dbobject data is independent of the typeResolver of the block.
                    // Even if it is STATIC, the extend ids must be loaded. That's why we load the extend now,
                    // Before checking below if the checkpoint failed or if the block content must not be loaded.
                    // Eg: Locations Map for the Create Individual Profile: it allows to pre-select locations,
                    // these ones must be fetched even if the block has a static typeResolver
                    // If it has extend, add those ids under its relationalTypeOutputKey
                    $dataload_extend_settings = $processor->getModelSupplementaryDBObjectDataComponentTree($component, $model_props);
                    if ($datasource == DataSources::MUTABLEONREQUEST) {
                        $dataload_extend_settings = array_merge_recursive(
                            $dataload_extend_settings,
                            $processor->getMutableonrequestSupplementaryDBObjectDataComponentTree($component, $props)
                        );
                    }
                    foreach ($dataload_extend_settings as $extendTypeOutputKey => $extend_data_properties) {
                        // Get the info for the subcomponent typeResolver
                        $extend_data_fields = $extend_data_properties[DataProperties::DIRECT_COMPONENT_FIELD_NODES] ?? [];
                        $extend_conditional_data_fields = $extend_data_properties[DataProperties::CONDITIONAL_COMPONENT_FIELD_NODES] ?? new SplObjectStorage();
                        $extend_ids = $extend_data_properties[DataProperties::IDS];
                        $extend_typeResolver = $extend_data_properties[DataProperties::RESOLVER];

                        $this->combineIDsDatafields($engineState->relationalTypeOutputKeyIDFieldSets, $extend_typeResolver, $extendTypeOutputKey, $extend_ids, $extend_data_fields, $extend_conditional_data_fields);

                        // This is needed to add the typeResolver-extend IDs, for if nobody else creates an entry for this typeResolver
                        $this->initializeTypeResolverEntry($engineState->dbdata, $extendTypeOutputKey, $component_path_key);
                    }

                    // Keep iterating for its subcomponents
                    $this->integrateSubcomponentDataProperties($engineState->dbdata, $data_properties, $relationalTypeOutputKey, $component_path_key);
                }
            }

            // Save the results on either the static or mutableonrequest branches
            $datasetcomponentdata = $datasetcomponentmeta = null;
            if ($datasource == DataSources::IMMUTABLE) {
                $datasetcomponentdata = &$immutable_datasetcomponentdata;
                if ($add_meta) {
                    $datasetcomponentmeta = &$immutable_datasetcomponentmeta;
                }
                $engineState->componentdata = &$immutable_componentdata;
            } elseif ($datasource == DataSources::MUTABLEONMODEL) {
                $datasetcomponentdata = &$mutableonmodel_datasetcomponentdata;
                if ($add_meta) {
                    $datasetcomponentmeta = &$mutableonmodel_datasetcomponentmeta;
                }
                $engineState->componentdata = &$mutableonmodel_componentdata;
            } elseif ($datasource == DataSources::MUTABLEONREQUEST) {
                $datasetcomponentdata = &$mutableonrequest_datasetcomponentdata;
                if ($add_meta) {
                    $datasetcomponentmeta = &$mutableonrequest_datasetcomponentmeta;
                }
                $engineState->componentdata = &$mutableonrequest_componentdata;
            }

            // Integrate the objectIDs into $datasetcomponentdata
            // ALWAYS print the $objectIDs, even if its an empty array. This to indicate that this is a dataloading component, so the application in the webplatform knows if to load a new batch of objectIDs, or reuse the ones from the previous component when iterating down
            if ($datasetcomponentdata !== null) {
                $this->assignValueForComponent($datasetcomponentdata, $component_path, $component, DataLoading::DB_OBJECT_IDS, $typeDBObjectIDOrIDs);
            }

            // Save the meta into $datasetcomponentmeta
            if ($add_meta) {
                if (!is_null($datasetcomponentmeta)) {
                    if ($dataset_meta = $processor->getDatasetmeta($component, $component_props, $data_properties, $dataaccess_checkpoint_validation, $mutation_checkpoint_validation, $executed, $objectIDOrIDs)) {
                        $this->assignValueForComponent($datasetcomponentmeta, $component_path, $component, DataLoading::META, $dataset_meta);
                    }
                }
            }

            // Integrate the feedback into $componentdata
            $this->processAndAddComponentData($component_path, $component, $component_props, $data_properties, $dataaccess_checkpoint_validation, $mutation_checkpoint_validation, $executed, $objectIDs);

            // Allow other components to produce their own feedback using this component's data results
            if ($referencer_componentfullpaths = $interreferenced_componentfullpaths[$this->getComponentPathHelpers()->stringifyComponentPath(array_merge($component_path, array($component)))] ?? null) {
                foreach ($referencer_componentfullpaths as $referencer_componentPath) {
                    $referencer_component = array_pop($referencer_componentPath);

                    $referencer_props = &$root_props;
                    $referencer_model_props = &$root_model_props;
                    foreach ($referencer_componentPath as $subcomponent) {
                        $subcomponentFullName = $this->getComponentHelpers()->getComponentFullName($subcomponent);
                        $referencer_props = &$referencer_props[$subcomponentFullName][Props::SUBCOMPONENTS];
                        $referencer_model_props = &$referencer_model_props[$subcomponentFullName][Props::SUBCOMPONENTS];
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
                        $referencer_component_props = &$referencer_model_props;
                    } elseif ($datasource == DataSources::MUTABLEONREQUEST) {
                        $referencer_component_props = &$referencer_props;
                    }
                    $this->processAndAddComponentData($referencer_componentPath, $referencer_component, $referencer_component_props, $data_properties, $dataaccess_checkpoint_validation, $mutation_checkpoint_validation, $executed, $objectIDs);
                }
            }

            // Incorporate the background URLs
            $engineState->backgroundload_urls = array_merge(
                $engineState->backgroundload_urls,
                $processor->getBackgroundurlsMergeddatasetcomponentTree($component, $component_props, $data_properties, $dataaccess_checkpoint_validation, $mutation_checkpoint_validation, $executed, $objectIDs)
            );

            // Allow PoP UserState to add the lazy-loaded userstate data triggers
            App::doAction(
                '\PoP\ComponentModel\Engine:getComponentData:dataloading-component',
                $component,
                array(&$component_props),
                array(&$data_properties),
                $dataaccess_checkpoint_validation,
                $mutation_checkpoint_validation,
                $executed,
                $objectIDOrIDs,
                array(&$engineState->helperCalculations),
                $this
            );
        }

        // Reset the filtermanager state and the pathmanager current path
        $this->getComponentFilterManager()->setNeverExclude(false);
        $this->getComponentPathManager()->setPropagationCurrentPath();

        $ret = [];

        if (in_array(DataOutputItems::COMPONENT_DATA, $dataoutputitems)) {
            // If there are multiple URIs, then the results must be returned under the corresponding $model_instance_id for "mutableonmodel", and $url for "mutableonrequest"
            list($has_extra_routes, $model_instance_id, $current_uri) = $this->listExtraRouteVars();

            if ($dataoutputmode == DataOutputModes::SPLITBYSOURCES) {
                /** @phpstan-ignore-next-line */
                if ($immutable_componentdata) {
                    $ret['componentdata']['immutable'] = $immutable_componentdata;
                }
                /** @phpstan-ignore-next-line */
                if ($mutableonmodel_componentdata) {
                    $ret['componentdata']['mutableonmodel'] = $has_extra_routes ? array($model_instance_id => $mutableonmodel_componentdata) : $mutableonmodel_componentdata;
                }
                /** @phpstan-ignore-next-line */
                if ($mutableonrequest_componentdata) {
                    $ret['componentdata']['mutableonrequest'] = $has_extra_routes ? array($current_uri => $mutableonrequest_componentdata) : $mutableonrequest_componentdata;
                }
                /** @phpstan-ignore-next-line */
                if ($immutable_datasetcomponentdata) {
                    $ret['datasetcomponentdata']['immutable'] = $immutable_datasetcomponentdata;
                }
                /** @phpstan-ignore-next-line */
                if ($mutableonmodel_datasetcomponentdata) {
                    $ret['datasetcomponentdata']['mutableonmodel'] = $has_extra_routes ? array($model_instance_id => $mutableonmodel_datasetcomponentdata) : $mutableonmodel_datasetcomponentdata;
                }
                /** @phpstan-ignore-next-line */
                if ($mutableonrequest_datasetcomponentdata) {
                    $ret['datasetcomponentdata']['mutableonrequest'] = $has_extra_routes ? array($current_uri => $mutableonrequest_datasetcomponentdata) : $mutableonrequest_datasetcomponentdata;
                }

                if ($add_meta) {
                    /** @phpstan-ignore-next-line */
                    if ($immutable_datasetcomponentmeta) {
                        $ret['datasetcomponentmeta']['immutable'] = $immutable_datasetcomponentmeta;
                    }
                    /** @phpstan-ignore-next-line */
                    if ($mutableonmodel_datasetcomponentmeta) {
                        $ret['datasetcomponentmeta']['mutableonmodel'] = $has_extra_routes ? array($model_instance_id => $mutableonmodel_datasetcomponentmeta) : $mutableonmodel_datasetcomponentmeta;
                    }
                    /** @phpstan-ignore-next-line */
                    if ($mutableonrequest_datasetcomponentmeta) {
                        $ret['datasetcomponentmeta']['mutableonrequest'] = $has_extra_routes ? array($current_uri => $mutableonrequest_datasetcomponentmeta) : $mutableonrequest_datasetcomponentmeta;
                    }
                }
            } elseif ($dataoutputmode == DataOutputModes::COMBINED) {
                // If everything is combined, then it belongs under "mutableonrequest"
                if (
                    $combined_componentdata = array_merge_recursive(
                        $immutable_componentdata,
                        $mutableonmodel_componentdata,
                        $mutableonrequest_componentdata
                    )
                ) {
                    $ret['componentdata'] = $has_extra_routes ? array($current_uri => $combined_componentdata) : $combined_componentdata;
                }
                if (
                    $combined_datasetcomponentdata = array_merge_recursive(
                        $immutable_datasetcomponentdata,
                        $mutableonmodel_datasetcomponentdata,
                        $mutableonrequest_datasetcomponentdata
                    )
                ) {
                    $ret['datasetcomponentdata'] = $has_extra_routes ? array($current_uri => $combined_datasetcomponentdata) : $combined_datasetcomponentdata;
                }
                if ($add_meta) {
                    if (
                        $combined_datasetcomponentmeta = array_merge_recursive(
                            $immutable_datasetcomponentmeta ?? [],
                            $mutableonmodel_datasetcomponentmeta ?? [],
                            $mutableonrequest_datasetcomponentmeta ?? []
                        )
                    ) {
                        $ret['datasetcomponentmeta'] = $has_extra_routes ? array($current_uri => $combined_datasetcomponentmeta) : $combined_datasetcomponentmeta;
                    }
                }
            }
        }

        // Allow PoP UserState to add the lazy-loaded userstate data triggers
        App::doAction(
            '\PoP\ComponentModel\Engine:getComponentData:end',
            $root_component,
            array(&$root_model_props),
            array(&$root_props),
            array(&$engineState->helperCalculations),
            $this
        );

        return $ret;
    }

    /**
     * Allow to inject what data fields must be placed under what dbNames
     *
     * @return array<string,string[]> Array of key: dbName, values: field names
     */
    public function getDBNameFieldNames(
        RelationalTypeResolverInterface $relationalTypeResolver
    ): array {
        if ($this->dbNameFieldNames === null) {
            $this->dbNameFieldNames = App::applyFilters(
                self::HOOK_DBNAME_TO_FIELDNAMES,
                [],
                $relationalTypeResolver
            );
        }
        return $this->dbNameFieldNames;
    }

    /**
     * Place all entries under dbName "primary"
     *
     * @param SplObjectStorage<FieldInterface,mixed>|array<string|int,SplObjectStorage<FieldInterface,mixed>> $entries
     * @return array<string,SplObjectStorage<FieldInterface,mixed>>|array<string,array<string|int,SplObjectStorage<FieldInterface,mixed>>>
     */
    protected function getEntriesUnderPrimaryDBName(
        array|SplObjectStorage $entries,
    ): array {
        return [
            self::PRIMARY_DBNAME => $entries,
        ];
    }

    /**
     * @param array<string|int,SplObjectStorage<FieldInterface,mixed>> $entries
     * @return array<string,array<string|int,SplObjectStorage<FieldInterface,mixed>>>
     */
    public function moveEntriesWithIDUnderDBName(
        array $entries,
        RelationalTypeResolverInterface $relationalTypeResolver
    ): array {
        if (!$entries) {
            return [];
        }

        /** @var array<string,array<string|int,SplObjectStorage<FieldInterface,mixed>>> */
        $dbname_entries = $this->getEntriesUnderPrimaryDBName($entries);
        $dbNameToFieldNames = $this->getDBNameFieldNames($relationalTypeResolver);
        foreach ($dbname_entries[self::PRIMARY_DBNAME] as $id => $fieldValues) {
            $fields = iterator_to_array($fieldValues);
            foreach ($dbNameToFieldNames as $dbName => $fieldNames) {
                $fields_to_move = array_filter(
                    $fields,
                    fn (FieldInterface $field) => in_array($field->getName(), $fieldNames),
                );
                $dbname_entries[$dbName][$id] ??= new SplObjectStorage();
                foreach ($fields_to_move as $field) {
                    $dbname_entries[$dbName][$id][$field] = $dbname_entries[self::PRIMARY_DBNAME][$id][$field];
                    $dbname_entries[self::PRIMARY_DBNAME][$id]->detach($field);
                }
            }
        }
        return $dbname_entries;
    }

    /**
     * @param SplObjectStorage<FieldInterface,mixed> $entries
     * @return array<string,SplObjectStorage<FieldInterface,mixed>>
     */
    public function moveEntriesWithoutIDUnderDBName(
        SplObjectStorage $entries,
        RelationalTypeResolverInterface $relationalTypeResolver
    ): array {
        if ($entries->count() === 0) {
            return [];
        }

        // By default place everything under "primary"
        /** @var array<string,SplObjectStorage<FieldInterface,mixed>> */
        $dbname_entries = $this->getEntriesUnderPrimaryDBName($entries);
        $dbNameToFieldNames = $this->getDBNameFieldNames($relationalTypeResolver);
        $fields = iterator_to_array($entries);
        foreach ($dbNameToFieldNames as $dbName => $fieldNames) {
            // Move these data fields under "meta" DB name
            $fields_to_move = array_filter(
                $fields,
                fn (FieldInterface $field) => in_array($field->getName(), $fieldNames),
            );
            foreach ($fields_to_move as $field) {
                $dbname_entries[$dbName] ??= new SplObjectStorage();
                $dbname_entries[$dbName][$field] = $dbname_entries[self::PRIMARY_DBNAME][$field];
                $dbname_entries[self::PRIMARY_DBNAME]->detach($field);
            }
        }
        return $dbname_entries;
    }

    protected function generateDatabases(): array
    {
        $engineState = App::getEngineState();

        // Save all database elements here, under typeResolver
        /** @var array<string,array<string,array<string|int,SplObjectStorage<FieldInterface,mixed>>>> */
        $databases = [];
        /** @var array<string,array<string,array<string|int,SplObjectStorage<FieldInterface,array<string|int>>>>> */
        $unionTypeOutputKeyIDs = [];
        /** @var array<string,array<string|int,SplObjectStorage<FieldInterface,array<string|int>>>> */
        $combinedUnionTypeOutputKeyIDs = [];

        /** @var array<string,array<string|int,SplObjectStorage<FieldInterface,mixed>>> */
        $previouslyResolvedIDFieldValues = [];
        $objectFeedbackEntries = $schemaFeedbackEntries = [
            FeedbackCategories::ERROR => [],
            FeedbackCategories::WARNING => [],
            FeedbackCategories::DEPRECATION => [],
            FeedbackCategories::NOTICE => [],
            FeedbackCategories::SUGGESTION => [],
            FeedbackCategories::LOG => [],
        ];
        $engineState->nocache_fields = [];

        // Keep an object with all fetched IDs/fields for each typeResolver. Then, we can keep using the same typeResolver as subcomponent,
        // but we need to avoid fetching those DB objects that were already fetched in a previous iteration
        $already_loaded_id_fields = [];

        /**
         * The variables initially come from the AppState, but then they
         * can be modified by directiveResolvers (eg: for @export)
         */
        $variables = App::getState('variables');

        // Initiate a new $messages interchange across directives
        $messages = [];

        // Iterate while there are dataloaders with data to be processed
        while (!empty($engineState->relationalTypeOutputKeyIDFieldSets)) {
            // Move the pointer to the first element, and get it
            reset($engineState->relationalTypeOutputKeyIDFieldSets);
            $relationalTypeOutputKey = key($engineState->relationalTypeOutputKeyIDFieldSets);
            /** @var RelationalTypeResolverInterface */
            $relationalTypeResolver = $engineState->relationalTypeOutputKeyIDFieldSets[$relationalTypeOutputKey][self::DATA_PROP_RELATIONAL_TYPE_RESOLVER];
            /** @var array<string|int,EngineIterationFieldSet> */
            $idFieldSet = $engineState->relationalTypeOutputKeyIDFieldSets[$relationalTypeOutputKey][self::DATA_PROP_ID_FIELD_SET];

            // Remove the typeResolver element from the array, so it doesn't process it anymore
            // Do it immediately, so that subcomponents can load new IDs for this current typeResolver (eg: posts => related)
            unset($engineState->relationalTypeOutputKeyIDFieldSets[$relationalTypeOutputKey]);

            // If no ids to execute, then skip
            if (empty($idFieldSet)) {
                continue;
            }

            // Store the loaded IDs/fields in an object, to avoid fetching them again in later iterations on the same typeResolver
            $already_loaded_id_fields[$relationalTypeOutputKey] ??= [];
            foreach ($idFieldSet as $id => $fieldSet) {
                $already_loaded_id_fields[$relationalTypeOutputKey][$id] = array_merge(
                    $already_loaded_id_fields[$relationalTypeOutputKey][$id] ?? [],
                    $fieldSet->fields,
                    // Conditional items must also be in direct, so no need to check to cache them
                    // iterator_to_array($fieldSet->conditionalFields)
                );
            }

            $typeOutputKey = $relationalTypeResolver->getTypeOutputKey();
            $engineIterationFeedbackStore = new EngineIterationFeedbackStore();

            // Execute the typeResolver for all combined ids
            /** @var array<string|int,SplObjectStorage<FieldInterface,mixed>> */
            $iterationResolvedIDFieldValues = [];
            $isUnionTypeResolver = $relationalTypeResolver instanceof UnionTypeResolverInterface;
            $idObjects = $relationalTypeResolver->fillObjects(
                $idFieldSet,
                $combinedUnionTypeOutputKeyIDs,
                $previouslyResolvedIDFieldValues,
                $iterationResolvedIDFieldValues,
                $variables,
                $messages,
                $engineIterationFeedbackStore,
            );

            /**
             * Save in the database under the corresponding database-key
             * (this way, different dataloaders, like 'list-users' and 'author',
             * can both save their results under database key 'users'.
             *
             * Plugin PoP User Login: Also save those results which depend
             * on the logged-in user. These are treated separately because:
             *
             *   1: They contain personal information, so it must be erased
             *      from the front-end as soon as the user logs out
             *   2: These results make the page state-full,
             *      so this page is not cacheable
             *
             * By splitting the results into state-full and state-less,
             * we can split all functionality into cacheable and non-cacheable,
             * thus caching most of the website even for logged-in users
             */
            if ($iterationResolvedIDFieldValues) {
                /**
                 * Conditional data fields: Store the loaded IDs/fields in an object,
                 * to avoid fetching them again in later iterations on the same typeResolver
                 * To find out if they were loaded, validate against the DBObject,
                 * to see if it has those properties
                 */
                foreach ($idFieldSet as $id => $fieldSet) {
                    // If it failed to load the item, it will be null
                    $iterationResolvedFieldValues = $iterationResolvedIDFieldValues[$id];
                    if ($iterationResolvedFieldValues === null) {
                        continue;
                    }
                    /** @var FieldInterface[] */
                    $resolvedDirectFields = iterator_to_array($iterationResolvedFieldValues);
                    $already_loaded_id_fields[$relationalTypeOutputKey][$id] = array_merge(
                        $already_loaded_id_fields[$relationalTypeOutputKey][$id] ?? [],
                        $resolvedDirectFields
                    );
                }

                // If the type is union, then add the type corresponding to each object on its ID
                $resolvedIDFieldValues = $this->moveEntriesWithIDUnderDBName($iterationResolvedIDFieldValues, $relationalTypeResolver);
                foreach ($resolvedIDFieldValues as $dbName => $entries) {
                    $databases[$dbName] ??= [];
                    $this->addDatasetToDatabase($databases[$dbName], $relationalTypeResolver, $typeOutputKey, $entries, $idObjects);

                    /**
                     * Populate the $previouslyResolvedIDFieldValues, pointing to the newly
                     * fetched resolvedIDFieldValues (but without the dbName!)
                     *
                     * Passing $previouslyResolvedIDFieldValues instead of $databases
                     * makes it read-only: Directives can only read the values...
                     * if they want to modify them, the modification is done on
                     * $previouslyResolvedIDFieldValues, so it carries no risks
                     */
                    foreach ($entries as $id => $fieldValues) {
                        /** @var SplObjectStorage<FieldInterface,mixed> */
                        $previouslyResolvedFieldValues = $previouslyResolvedIDFieldValues[$typeOutputKey][$id] ?? new SplObjectStorage();
                        $previouslyResolvedFieldValues->addAll($fieldValues);
                        $previouslyResolvedIDFieldValues[$typeOutputKey][$id] = $previouslyResolvedFieldValues;
                    }
                }
            }

            /**
             * Transfer the feedback entries from the FeedbackStore
             * to temporary variables for processing.
             */
            $this->transferFeedback(
                $idObjects,
                $engineIterationFeedbackStore,
                $objectFeedbackEntries,
                $schemaFeedbackEntries,
            );

            // Important: query like this: obtain keys first instead of iterating directly on array,
            // because it will keep adding elements
            $typeResolver_dbdata = $engineState->dbdata[$relationalTypeOutputKey];
            foreach (array_keys($typeResolver_dbdata) as $component_path_key) {
                $typeResolver_data = &$engineState->dbdata[$relationalTypeOutputKey][$component_path_key];

                unset($engineState->dbdata[$relationalTypeOutputKey][$component_path_key]);

                // Check if it has subcomponents, and then bring this data
                if ($subcomponents_data_properties = $typeResolver_data[DataProperties::SUBCOMPONENTS]) {
                    $typeResolverIDs = $typeResolver_data[DataProperties::IDS];
                    // The unionTypeResolver doesn't know how to resolver the subcomponents, since the fields
                    // (eg: "authors") are attached to the target typeResolver, not to the unionTypeResolver
                    // Then, iterate through all the target typeResolvers, and have each of them process their data
                    if ($isUnionTypeResolver) {
                        // If the type data resolver is union, the typeOutputKey where the value is stored
                        // is contained in the ID itself, with format typeOutputKey/ID.
                        // We must extract this information: assign the typeOutputKey to $typeOutputKey,
                        // and remove the typeOutputKey from the ID.
                        // If the Dataloader failed loading the object, the original ID as int
                        // may have been stored, so cast it always to string
                        $targetObjectIDItems = [];
                        $objectTypeResolver_ids = [];
                        foreach ($typeResolverIDs as $composedID) {
                            list(
                                $typeOutputKey,
                                $id
                            ) = UnionTypeHelpers::extractObjectTypeAndID((string)$composedID);
                            // It's null if the Dataloader couldn't load the item with the given ID
                            $targetObjectIDItems[$id] = $idObjects[$composedID] ?? null;
                            $objectTypeResolver_ids[] = $id;
                        }

                        // If it's a unionTypeResolver, get the typeResolver for each object
                        // to obtain the subcomponent typeResolver
                        /** @var UnionTypeResolverInterface $relationalTypeResolver */
                        $targetObjectTypeResolvers = $relationalTypeResolver->getObjectIDTargetTypeResolvers($objectTypeResolver_ids);
                        $iterationObjectTypeResolverNameDataItems = [];
                        foreach ($objectTypeResolver_ids as $id) {
                            // If there's no resolver, it's an error: the ID can't be processed by anyone
                            if ($targetObjectTypeResolver = $targetObjectTypeResolvers[$id] ?? null) {
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
                            $this->processSubcomponentData($relationalTypeResolver, $targetObjectTypeResolver, $targetObjectIDs, $component_path_key, $databases, $subcomponents_data_properties, $already_loaded_id_fields, $unionTypeOutputKeyIDs, $combinedUnionTypeOutputKeyIDs, $targetObjectIDItems);
                        }
                    } else {
                        /** @var ObjectTypeResolverInterface $relationalTypeResolver */
                        $this->processSubcomponentData($relationalTypeResolver, $relationalTypeResolver, $typeResolverIDs, $component_path_key, $databases, $subcomponents_data_properties, $already_loaded_id_fields, $unionTypeOutputKeyIDs, $combinedUnionTypeOutputKeyIDs, $idObjects);
                    }
                }
            }
            // }

            /**
             * Regenerate the SchemaFeedbackStore, to reset the
             * state of errors/warnings/logs/etc for the next iteration
             */
            App::getFeedbackStore()->regenerateSchemaFeedbackStore();
        }

        // Print data into the output
        $ret = [];
        $this->maybeCombineAndAddDatabaseEntries($ret, 'databases', $databases);
        $this->maybeCombineAndAddDatabaseEntries($ret, 'unionTypeOutputKeyIDs', $unionTypeOutputKeyIDs);

        // Add the feedback (errors, warnings, deprecations, notices, etc) into the output
        $this->combineAndAddFeedbackEntries($ret, $objectFeedbackEntries, $schemaFeedbackEntries);

        return $ret;
    }

    /**
     * Add the feedback (errors, warnings, deprecations, notices, etc)
     * into the output.
     */
    protected function combineAndAddFeedbackEntries(
        array &$ret,
        array $objectFeedbackEntries,
        array $schemaFeedbackEntries,
    ): void {
        $ret[Response::GENERAL_FEEDBACK] = [];
        $ret[Response::OBJECT_FEEDBACK] = [];
        $ret[Response::SCHEMA_FEEDBACK] = [];

        /** @var ModuleConfiguration */
        $moduleConfiguration = App::getModule(Module::class)->getConfiguration();
        $enabledFeedbackCategoryExtensions = $moduleConfiguration->getEnabledFeedbackCategoryExtensions();
        $sendFeedbackWarnings = in_array(FeedbackCategories::WARNING, $enabledFeedbackCategoryExtensions);
        $sendFeedbackDeprecations = in_array(FeedbackCategories::DEPRECATION, $enabledFeedbackCategoryExtensions);
        $sendFeedbackNotices = in_array(FeedbackCategories::NOTICE, $enabledFeedbackCategoryExtensions);
        $sendFeedbackSuggestions = in_array(FeedbackCategories::SUGGESTION, $enabledFeedbackCategoryExtensions);
        $sendFeedbackLogs = in_array(FeedbackCategories::LOG, $enabledFeedbackCategoryExtensions);

        // Errors
        $generalFeedbackStore = App::getFeedbackStore()->generalFeedbackStore;
        if ($generalErrors = $generalFeedbackStore->getErrors()) {
            $ret[Response::GENERAL_FEEDBACK][FeedbackCategories::ERROR] = $this->getGeneralFeedbackEntriesForOutput($generalErrors);
        }
        // @todo Remove alongside FeedbackMessageStore!
        if ($queryErrors = $this->getFeedbackMessageStore()->getQueryErrors()) {
            $queryDocumentErrors = [];
            foreach ($queryErrors as $message => $extensions) {
                $queryDocumentError =  [
                    Tokens::MESSAGE => $message,
                ];
                if ($locations = $extensions['locations'] ?? null) {
                    $queryDocumentError[Tokens::LOCATIONS] = $locations;
                    unset($extensions['locations']);
                }
                if ($extensions !== []) {
                    $queryDocumentError[Tokens::EXTENSIONS] = $extensions;
                }
                $queryDocumentErrors[] = $queryDocumentError;
            }
        }
        $this->maybeCombineAndAddDatabaseEntries($ret[Response::OBJECT_FEEDBACK], FeedbackCategories::ERROR, $objectFeedbackEntries[FeedbackCategories::ERROR]);
        $this->maybeCombineAndAddSchemaEntries($ret[Response::SCHEMA_FEEDBACK], FeedbackCategories::ERROR, $schemaFeedbackEntries[FeedbackCategories::ERROR]);

        // Warnings
        if ($sendFeedbackWarnings) {
            if ($generalWarnings = $generalFeedbackStore->getWarnings()) {
                $ret[Response::GENERAL_FEEDBACK][FeedbackCategories::WARNING] = $this->getGeneralFeedbackEntriesForOutput($generalWarnings);
            }
            $this->maybeCombineAndAddDatabaseEntries($ret[Response::OBJECT_FEEDBACK], FeedbackCategories::WARNING, $objectFeedbackEntries[FeedbackCategories::WARNING]);
            $this->maybeCombineAndAddSchemaEntries($ret[Response::SCHEMA_FEEDBACK], FeedbackCategories::WARNING, $schemaFeedbackEntries[FeedbackCategories::WARNING]);
        }

        // Deprecations
        if ($sendFeedbackDeprecations) {
            if ($generalDeprecations = $generalFeedbackStore->getDeprecations()) {
                $ret[Response::GENERAL_FEEDBACK][FeedbackCategories::DEPRECATION] = $this->getGeneralFeedbackEntriesForOutput($generalDeprecations);
            }
            $this->maybeCombineAndAddDatabaseEntries($ret[Response::OBJECT_FEEDBACK], FeedbackCategories::DEPRECATION, $objectFeedbackEntries[FeedbackCategories::DEPRECATION]);
            $this->maybeCombineAndAddSchemaEntries($ret[Response::SCHEMA_FEEDBACK], FeedbackCategories::DEPRECATION, $schemaFeedbackEntries[FeedbackCategories::DEPRECATION]);
        }

        // Notices
        if ($sendFeedbackNotices) {
            if ($generalNotices = $generalFeedbackStore->getNotices()) {
                $ret[Response::GENERAL_FEEDBACK][FeedbackCategories::NOTICE] = $this->getGeneralFeedbackEntriesForOutput($generalNotices);
            }
            $this->maybeCombineAndAddDatabaseEntries($ret[Response::OBJECT_FEEDBACK], FeedbackCategories::NOTICE, $objectFeedbackEntries[FeedbackCategories::NOTICE]);
            $this->maybeCombineAndAddSchemaEntries($ret[Response::SCHEMA_FEEDBACK], FeedbackCategories::NOTICE, $schemaFeedbackEntries[FeedbackCategories::NOTICE]);
        }

        // Suggestions
        if ($sendFeedbackSuggestions) {
            if ($generalSuggestions = $generalFeedbackStore->getSuggestions()) {
                $ret[Response::GENERAL_FEEDBACK][FeedbackCategories::SUGGESTION] = $this->getGeneralFeedbackEntriesForOutput($generalSuggestions);
            }
            $this->maybeCombineAndAddDatabaseEntries($ret[Response::OBJECT_FEEDBACK], FeedbackCategories::SUGGESTION, $objectFeedbackEntries[FeedbackCategories::SUGGESTION]);
            $this->maybeCombineAndAddSchemaEntries($ret[Response::SCHEMA_FEEDBACK], FeedbackCategories::SUGGESTION, $schemaFeedbackEntries[FeedbackCategories::SUGGESTION]);
        }

        // Logs
        if ($sendFeedbackLogs) {
            if ($generalLogs = $generalFeedbackStore->getLogs()) {
                $ret[Response::GENERAL_FEEDBACK][FeedbackCategories::LOG] = $this->getGeneralFeedbackEntriesForOutput($generalLogs);
            }
            $this->maybeCombineAndAddDatabaseEntries($ret[Response::OBJECT_FEEDBACK], FeedbackCategories::LOG, $objectFeedbackEntries[FeedbackCategories::LOG]);
            $this->maybeCombineAndAddSchemaEntries($ret[Response::SCHEMA_FEEDBACK], FeedbackCategories::LOG, $schemaFeedbackEntries[FeedbackCategories::LOG]);
        }
    }

    /**
     * @param SplObjectStorage<RelationalTypeResolverInterface,array<string|int,SplObjectStorage<FieldInterface,mixed>>> $iterationEntries
     * @param array<string,array<string,SplObjectStorage<FieldInterface,mixed>>> $destination
     */
    protected function addObjectEntriesToDestinationArray(
        SplObjectStorage $iterationEntries,
        array &$destination,
        array $idObjects,
    ): void {
        if ($iterationEntries->count() === 0) {
            return;
        }

        /** @var RelationalTypeResolverInterface $iterationRelationalTypeResolver */
        foreach ($iterationEntries as $iterationRelationalTypeResolver) {
            $typeOutputKey = $iterationRelationalTypeResolver->getTypeOutputKey();
            $entries = $iterationEntries[$iterationRelationalTypeResolver];
            $dbNameEntries = $this->moveEntriesWithIDUnderDBName($entries, $iterationRelationalTypeResolver);
            foreach ($dbNameEntries as $dbName => $entries) {
                $destination[$dbName] ??= [];
                $this->addDatasetToDatabase($destination[$dbName], $iterationRelationalTypeResolver, $typeOutputKey, $entries, $idObjects, true);
            }
        }
    }

    /**
     * @param SplObjectStorage<RelationalTypeResolverInterface,SplObjectStorage<FieldInterface,mixed>> $iterationEntries
     * @param array<string,array<string,SplObjectStorage<FieldInterface,mixed>>> $destination
     */
    protected function addSchemaEntriesToDestinationArray(
        SplObjectStorage $iterationEntries,
        array &$destination,
    ): void {
        if ($iterationEntries->count() === 0) {
            return;
        }
        /** @var RelationalTypeResolverInterface $iterationRelationalTypeResolver */
        foreach ($iterationEntries as $iterationRelationalTypeResolver) {
            $typeOutputKey = $iterationRelationalTypeResolver->getTypeOutputKey();
            $entries = $iterationEntries[$iterationRelationalTypeResolver];
            $dbNameEntries = $this->moveEntriesWithoutIDUnderDBName($entries, $iterationRelationalTypeResolver);
            foreach ($dbNameEntries as $dbName => $entries) {
                /** @var SplObjectStorage<FieldInterface,mixed> */
                $destinationSplObjectStorage = $destination[$dbName][$typeOutputKey] ?? new SplObjectStorage();
                $destinationSplObjectStorage->addAll($entries);
                $destination[$dbName][$typeOutputKey] = $destinationSplObjectStorage;
            }
        }
    }

    private function transferFeedback(
        array $idObjects,
        EngineIterationFeedbackStore $engineIterationFeedbackStore,
        array &$objectFeedbackEntries,
        array &$schemaFeedbackEntries,
    ): void {
        $this->transferObjectFeedback(
            $idObjects,
            $engineIterationFeedbackStore->objectFeedbackStore,
            $objectFeedbackEntries,
        );
        $this->transferSchemaFeedback(
            $engineIterationFeedbackStore->schemaFeedbackStore,
            $schemaFeedbackEntries,
        );

        /**
         * The SchemaFeedbackStore is processed also within each iteration.
         * It processes the information here, and at the end of the loop
         * it will regenerated a new instance for the next iteration.
         */
        $this->transferSchemaFeedback(
            App::getFeedbackStore()->schemaFeedbackStore,
            $schemaFeedbackEntries,
        );
    }

    private function transferObjectFeedback(
        array $idObjects,
        ObjectResolutionFeedbackStore $objectFeedbackStore,
        array &$objectFeedbackEntries,
    ): void {
        /** @var SplObjectStorage<RelationalTypeResolverInterface,array<int|string,SplObjectStorage<FieldInterface,mixed>>> */
        $iterationObjectErrors = new SplObjectStorage();
        foreach ($objectFeedbackStore->getErrors() as $objectFeedbackError) {
            $this->transferObjectFeedbackEntries(
                $objectFeedbackError,
                $iterationObjectErrors,
            );
        }
        $this->addObjectEntriesToDestinationArray(
            $iterationObjectErrors,
            $objectFeedbackEntries[FeedbackCategories::ERROR],
            $idObjects
        );

        /** @var SplObjectStorage<RelationalTypeResolverInterface,array<int|string,SplObjectStorage<FieldInterface,mixed>>> */
        $iterationObjectWarnings = new SplObjectStorage();
        foreach ($objectFeedbackStore->getWarnings() as $objectFeedbackWarning) {
            $this->transferObjectFeedbackEntries(
                $objectFeedbackWarning,
                $iterationObjectWarnings,
            );
        }
        $this->addObjectEntriesToDestinationArray(
            $iterationObjectWarnings,
            $objectFeedbackEntries[FeedbackCategories::WARNING],
            $idObjects
        );

        /** @var SplObjectStorage<RelationalTypeResolverInterface,array<int|string,SplObjectStorage<FieldInterface,mixed>>> */
        $iterationObjectDeprecations = new SplObjectStorage();
        foreach ($objectFeedbackStore->getDeprecations() as $objectFeedbackDeprecation) {
            $this->transferObjectFeedbackEntries(
                $objectFeedbackDeprecation,
                $iterationObjectDeprecations,
            );
        }
        $this->addObjectEntriesToDestinationArray(
            $iterationObjectDeprecations,
            $objectFeedbackEntries[FeedbackCategories::DEPRECATION],
            $idObjects
        );

        /** @var SplObjectStorage<RelationalTypeResolverInterface,array<int|string,SplObjectStorage<FieldInterface,mixed>>> */
        $iterationObjectNotices = new SplObjectStorage();
        foreach ($objectFeedbackStore->getNotices() as $objectFeedbackNotice) {
            $this->transferObjectFeedbackEntries(
                $objectFeedbackNotice,
                $iterationObjectNotices,
            );
        }
        $this->addObjectEntriesToDestinationArray(
            $iterationObjectNotices,
            $objectFeedbackEntries[FeedbackCategories::NOTICE],
            $idObjects
        );

        /** @var SplObjectStorage<RelationalTypeResolverInterface,array<int|string,SplObjectStorage<FieldInterface,mixed>>> */
        $iterationObjectSuggestions = new SplObjectStorage();
        foreach ($objectFeedbackStore->getSuggestions() as $objectFeedbackSuggestion) {
            $this->transferObjectFeedbackEntries(
                $objectFeedbackSuggestion,
                $iterationObjectSuggestions,
            );
        }
        $this->addObjectEntriesToDestinationArray(
            $iterationObjectSuggestions,
            $objectFeedbackEntries[FeedbackCategories::SUGGESTION],
            $idObjects
        );

        /** @var SplObjectStorage<RelationalTypeResolverInterface,array<int|string,SplObjectStorage<FieldInterface,mixed>>> */
        $iterationObjectLogs = new SplObjectStorage();
        foreach ($objectFeedbackStore->getLogs() as $objectFeedbackLog) {
            $this->transferObjectFeedbackEntries(
                $objectFeedbackLog,
                $iterationObjectLogs,
            );
        }
        $this->addObjectEntriesToDestinationArray(
            $iterationObjectLogs,
            $objectFeedbackEntries[FeedbackCategories::LOG],
            $idObjects
        );
    }

    private function transferObjectFeedbackEntries(
        ObjectResolutionFeedbackInterface $objectFeedback,
        SplObjectStorage $iterationObjectFeedbackEntries
    ): void {
        $entry = $this->getObjectOrSchemaFeedbackEntries($objectFeedback);
        $objectFeedbackEntries = $iterationObjectFeedbackEntries[$objectFeedback->getRelationalTypeResolver()] ?? [];
        foreach ($objectFeedback->getIDFieldSet() as $id => $fieldSet) {
            foreach ($fieldSet->fields as $field) {
                $objectFeedbackEntriesStorage = $objectFeedbackEntries[$id] ?? new SplObjectStorage();
                $fieldObjectFeedbackEntries = $objectFeedbackEntries[$id][$field] ?? [];
                $fieldObjectFeedbackEntries[] = $entry;
                $objectFeedbackEntriesStorage[$field] = $fieldObjectFeedbackEntries;
                $objectFeedbackEntries[$id] = $objectFeedbackEntriesStorage;
            }
        }
        $iterationObjectFeedbackEntries[$objectFeedback->getRelationalTypeResolver()] = $objectFeedbackEntries;
    }

    private function transferSchemaFeedback(
        SchemaFeedbackStore $schemaFeedbackStore,
        array &$schemaFeedbackEntries,
    ): void {
        /** @var SplObjectStorage<RelationalTypeResolverInterface,SplObjectStorage<FieldInterface,mixed>> */
        $iterationSchemaErrors = new SplObjectStorage();
        foreach ($schemaFeedbackStore->getErrors() as $schemaFeedbackError) {
            $this->transferSchemaFeedbackEntries(
                $schemaFeedbackError,
                $iterationSchemaErrors,
            );
        }
        $this->addSchemaEntriesToDestinationArray(
            $iterationSchemaErrors,
            $schemaFeedbackEntries[FeedbackCategories::ERROR],
        );

        /** @var SplObjectStorage<RelationalTypeResolverInterface,SplObjectStorage<FieldInterface,mixed>> */
        $iterationSchemaWarnings = new SplObjectStorage();
        foreach ($schemaFeedbackStore->getWarnings() as $schemaFeedbackWarning) {
            $this->transferSchemaFeedbackEntries(
                $schemaFeedbackWarning,
                $iterationSchemaWarnings,
            );
        }
        $this->addSchemaEntriesToDestinationArray(
            $iterationSchemaWarnings,
            $schemaFeedbackEntries[FeedbackCategories::WARNING],
        );

        /** @var SplObjectStorage<RelationalTypeResolverInterface,SplObjectStorage<FieldInterface,mixed>> */
        $iterationSchemaDeprecations = new SplObjectStorage();
        foreach ($schemaFeedbackStore->getDeprecations() as $schemaFeedbackDeprecation) {
            $this->transferSchemaFeedbackEntries(
                $schemaFeedbackDeprecation,
                $iterationSchemaDeprecations,
            );
        }
        $this->addSchemaEntriesToDestinationArray(
            $iterationSchemaDeprecations,
            $schemaFeedbackEntries[FeedbackCategories::DEPRECATION],
        );

        /** @var SplObjectStorage<RelationalTypeResolverInterface,SplObjectStorage<FieldInterface,mixed>> */
        $iterationSchemaNotices = new SplObjectStorage();
        foreach ($schemaFeedbackStore->getNotices() as $schemaFeedbackNotice) {
            $this->transferSchemaFeedbackEntries(
                $schemaFeedbackNotice,
                $iterationSchemaNotices,
            );
        }
        $this->addSchemaEntriesToDestinationArray(
            $iterationSchemaNotices,
            $schemaFeedbackEntries[FeedbackCategories::NOTICE],
        );

        /** @var SplObjectStorage<RelationalTypeResolverInterface,SplObjectStorage<FieldInterface,mixed>> */
        $iterationSchemaSuggestions = new SplObjectStorage();
        foreach ($schemaFeedbackStore->getSuggestions() as $schemaFeedbackSuggestion) {
            $this->transferSchemaFeedbackEntries(
                $schemaFeedbackSuggestion,
                $iterationSchemaSuggestions,
            );
        }
        $this->addSchemaEntriesToDestinationArray(
            $iterationSchemaSuggestions,
            $schemaFeedbackEntries[FeedbackCategories::SUGGESTION],
        );

        /** @var SplObjectStorage<RelationalTypeResolverInterface,SplObjectStorage<FieldInterface,mixed>> */
        $iterationSchemaLogs = new SplObjectStorage();
        foreach ($schemaFeedbackStore->getLogs() as $schemaFeedbackLog) {
            $this->transferSchemaFeedbackEntries(
                $schemaFeedbackLog,
                $iterationSchemaLogs,
            );
        }
        $this->addSchemaEntriesToDestinationArray(
            $iterationSchemaLogs,
            $schemaFeedbackEntries[FeedbackCategories::LOG],
        );
    }

    private function transferSchemaFeedbackEntries(
        SchemaFeedbackInterface $schemaFeedback,
        SplObjectStorage $iterationSchemaFeedbackEntries
    ): void {
        $entry = $this->getObjectOrSchemaFeedbackEntries($schemaFeedback);
        $schemaFeedbackEntries = $iterationSchemaFeedbackEntries[$schemaFeedback->getRelationalTypeResolver()] ?? new SplObjectStorage();
        foreach ($schemaFeedback->getFields() as $field) {
            $fieldSchemaFeedbackEntries = $schemaFeedbackEntries[$field] ?? [];
            $fieldSchemaFeedbackEntries[] = $entry;
            $schemaFeedbackEntries[$field] = $fieldSchemaFeedbackEntries;
        }
        $iterationSchemaFeedbackEntries[$schemaFeedback->getRelationalTypeResolver()] = $schemaFeedbackEntries;
    }

    /**
     * @return array<string,mixed>
     */
    private function getObjectOrSchemaFeedbackEntries(
        ObjectResolutionFeedbackInterface | SchemaFeedbackInterface $objectOrSchemaFeedback,
    ): array {
        $feedbackItemResolution = $objectOrSchemaFeedback->getFeedbackItemResolution();
        $message = $objectOrSchemaFeedback->getFeedbackItemResolution()->getMessage();
        $specifiedByURL = $feedbackItemResolution->getSpecifiedByURL();
        $astNode = $objectOrSchemaFeedback->getAstNode();
        $location = $astNode->getLocation()->toArray();
        /**
         * Re-create the path to the AST node
         *
         * @var string[]
         */
        $astNodePath = [];
        /**
         * Closest Field to where the feedback was produced:
         * either the AST node directly if that's the Field,
         * its parent for an Argument, or nothing if a Directive.
         *
         * @var FieldInterface|null
         */
        $field = null;
        /** @var SplObjectStorage<AstInterface,AstInterface> */
        $documentASTNodeAncestors = App::getState('document-ast-node-ancestors');
        while ($astNode !== null) {
            $astNodePath[] = $astNode->asASTNodeString();
            if ($field !== null && $astNode instanceof FieldInterface) {
                $field = $astNode;
            }
            // Move to the ancestor AST node
            $astNode = $documentASTNodeAncestors[$astNode] ?? null;
        }
        $entry = [
            Tokens::MESSAGE => $message,
            Tokens::LOCATIONS => [$location],
            Tokens::EXTENSIONS => array_merge(
                $objectOrSchemaFeedback->getExtensions(),
                [
                    'code' => $feedbackItemResolution->getNamespacedCode(),
                ],
                $specifiedByURL !== null ? [
                    'specifiedBy' => $specifiedByURL,
                ] : []
            ),
            Tokens::PATH => $astNodePath,
        ];
        if ($field !== null) {
            $entry[Tokens::FIELD] = $field->asASTNodeString();
        }
        return $entry;
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
            if ($code = $generalFeedbackEntry->getFeedbackItemResolution()->getNamespacedCode()) {
                $generalFeedbackEntryExtensions['code'] = $code;
            }
            $output[$generalFeedbackEntry->getFeedbackItemResolution()->getMessage()] = $generalFeedbackEntryExtensions;
        }
        return $output;
    }

    /**
     * @param array<string,array<string,array<string|int,SplObjectStorage<FieldInterface,mixed>>>> $databases
     * @param array<string,array<string,array<string|int,SplObjectStorage<FieldInterface,array<string|int>>>>> $unionTypeOutputKeyIDs
     * @param array<string,array<string|int,SplObjectStorage<FieldInterface,array<string|int>>>> $combinedUnionTypeOutputKeyIDs
     */
    protected function processSubcomponentData(
        RelationalTypeResolverInterface $relationalTypeResolver,
        ObjectTypeResolverInterface $targetObjectTypeResolver,
        array $typeResolverIDs,
        string $component_path_key,
        array &$databases,
        SplObjectStorage $subcomponents_data_properties,
        array &$already_loaded_id_fields,
        array &$unionTypeOutputKeyIDs,
        array &$combinedUnionTypeOutputKeyIDs,
        array $idObjects,
    ): void {
        $engineState = App::getEngineState();
        $targetTypeOutputKey = $targetObjectTypeResolver->getTypeOutputKey();
        foreach ($subcomponents_data_properties as $componentFieldNode) {
            /** @var ComponentFieldNodeInterface $componentFieldNode */
            $subcomponent_data_properties = $subcomponents_data_properties[$componentFieldNode];
            /** @var array<string,mixed> $subcomponent_data_properties */
            // Retrieve the subcomponent typeResolver from the current typeResolver
            // Watch out! When dealing with the UnionDataLoader, we attempt to get the subcomponentType for that field twice: first from the UnionTypeResolver and, if it doesn't handle it, only then from the TargetTypeResolver
            // This is for the very specific use of the "self" field: When referencing "self" from a UnionTypeResolver, we don't know what type it's going to be the result, hence we need to add the type to entry "unionTypeOutputKeyIDs"
            // However, for the targetObjectTypeResolver, "self" is processed by itself, not by a UnionTypeResolver, hence it would never add the type under entry "unionTypeOutputKeyIDs".
            // The UnionTypeResolver should only handle 2 connection fields: "id" and "self"
            $subcomponentTypeResolver = $this->getDataloadHelperService()->getTypeResolverFromSubcomponentField($relationalTypeResolver, $componentFieldNode->getField());
            if ($subcomponentTypeResolver === null && $relationalTypeResolver !== $targetObjectTypeResolver) {
                $subcomponentTypeResolver = $this->getDataloadHelperService()->getTypeResolverFromSubcomponentField($targetObjectTypeResolver, $componentFieldNode->getField());
            }
            if ($subcomponentTypeResolver === null) {
                continue;
            }
            $subcomponentTypeOutputKey = $subcomponentTypeResolver->getTypeOutputKey();
            // The array_merge_recursive when there are at least 2 levels will make the data_fields to be duplicated, so remove duplicates now
            $subcomponent_direct_fields = array_unique($subcomponent_data_properties[DataProperties::DIRECT_COMPONENT_FIELD_NODES] ?? []);
            /** @var SplObjectStorage<ComponentFieldNodeInterface,ComponentFieldNodeInterface[]> */
            $subcomponent_conditional_fields_storage = $subcomponent_data_properties[DataProperties::CONDITIONAL_COMPONENT_FIELD_NODES] ?? new SplObjectStorage();
            if ($subcomponent_direct_fields || $subcomponent_conditional_fields_storage->count() > 0) {
                $subcomponentIsUnionTypeResolver = $subcomponentTypeResolver instanceof UnionTypeResolverInterface;

                $subcomponent_already_loaded_id_fields = [];
                if ($already_loaded_id_fields && ($already_loaded_id_fields[$subcomponentTypeOutputKey] ?? null)) {
                    $subcomponent_already_loaded_id_fields = $already_loaded_id_fields[$subcomponentTypeOutputKey];
                }
                $subcomponentIDs = [];
                foreach ($typeResolverIDs as $id) {
                    // $databases may contain more the 1 DB shipped by pop-engine/ ("primary"). Eg: PoP User Login adds db "userstate"
                    // Fetch the field_ids from all these DBs
                    foreach ($databases as $dbName => $database) {
                        $database_field_ids = $database[$targetTypeOutputKey][$id][$componentFieldNode->getField()] ?? null;
                        if (!$database_field_ids) {
                            continue;
                        }
                        $subcomponentIDs[$dbName][$targetTypeOutputKey][$id] = array_merge(
                            $subcomponentIDs[$dbName][$targetTypeOutputKey][$id] ?? [],
                            is_array($database_field_ids) ? $database_field_ids : array($database_field_ids)
                        );
                    }
                }
                /**
                 * We don't want to store the typeOutputKey/ID inside the relationalID,
                 * because that can lead to problems when dealing with the relations
                 * in the application (better keep it only to the ID).
                 * So, instead, we store the typeOutputKey/ID values in another object
                 * "$unionTypeOutputKeyIDs".
                 * Then, whenever it's a union type data resolver, we obtain the values
                 * for the relationship under this other object.
                 */
                $typedSubcomponentIDs = [];
                /**
                 * Get the types for all of the IDs all at once.
                 * Flatten 3 levels: dbName => typeOutputKey => id => ...
                 */
                $allSubcomponentIDs = array_values(array_unique(
                    GeneralUtils::arrayFlatten(GeneralUtils::arrayFlatten(GeneralUtils::arrayFlatten($subcomponentIDs)))
                ));
                $qualifiedSubcomponentIDs = $subcomponentTypeResolver->getQualifiedDBObjectIDOrIDs($allSubcomponentIDs);
                // Create a map, from ID to TypedID
                for ($i = 0; $i < count($allSubcomponentIDs); $i++) {
                    $typedSubcomponentIDs[$allSubcomponentIDs[$i]] = $qualifiedSubcomponentIDs[$i];
                }

                /** @var array<string|int> */
                $field_ids = [];
                foreach ($subcomponentIDs as $dbName => $typeOutputKey_id_database_field_ids) {
                    foreach ($typeOutputKey_id_database_field_ids as $typeOutputKey => $id_database_field_ids) {
                        foreach ($id_database_field_ids as $id => $database_field_ids) {
                            // Transform the IDs, adding their type
                            // Do it always, for UnionTypeResolvers and non-union ones.
                            // This is because if it's a relational field that comes after a UnionTypeResolver, its typeOutputKey could not be inferred (since it depends from the resolvedObject, and can't be obtained in the settings, where "outputKeys" is obtained and which doesn't depend on data items)
                            // Eg: /?query=content.comments.id. In this case, "content" is handled by UnionTypeResolver, and "comments" would not be found since its entry can't be added under "datasetcomponentsettings.outputKeys", since the component (of class AbstractRelationalFieldQueryDataComponentProcessor) with a UnionTypeResolver can't resolve the 'succeeding-typeResolver' to set to its subcomponents
                            // Having 'succeeding-typeResolver' being NULL, then it is not able to locate its data
                            $typed_database_field_ids = array_map(
                                fn (string|int $field_id) => $typedSubcomponentIDs[$field_id],
                                $database_field_ids
                            );
                            if ($subcomponentIsUnionTypeResolver) {
                                $database_field_ids = $typed_database_field_ids;
                            }
                            // Set on the `unionTypeOutputKeyIDs` output entry. This could be either an array or a single value. Check from the original entry which case it is
                            $entryIsArray = $databases[$dbName][$typeOutputKey][$id]->contains($componentFieldNode->getField()) && is_array($databases[$dbName][$typeOutputKey][$id][$componentFieldNode->getField()]);
                            $unionTypeOutputKeyIDs[$dbName][$typeOutputKey][$id] ??= new SplObjectStorage();
                            $unionTypeOutputKeyIDs[$dbName][$typeOutputKey][$id][$componentFieldNode->getField()] = $entryIsArray ? $typed_database_field_ids : $typed_database_field_ids[0];
                            $combinedUnionTypeOutputKeyIDs[$typeOutputKey][$id] ??= new SplObjectStorage();
                            $combinedUnionTypeOutputKeyIDs[$typeOutputKey][$id][$componentFieldNode->getField()] = $entryIsArray ? $typed_database_field_ids : $typed_database_field_ids[0];

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
                        if ($subcomponent_already_loaded_data_fields = $subcomponent_already_loaded_id_fields[$field_id] ?? null) {
                            $id_subcomponent_direct_fields = array_values(
                                array_filter(
                                    $subcomponent_direct_fields,
                                    fn (ComponentFieldNodeInterface $componentFieldNode) => !in_array($componentFieldNode->getField(), $subcomponent_already_loaded_data_fields)
                                )
                            );
                            $id_subcomponent_conditional_fields_storage = new SplObjectStorage();
                            foreach ($subcomponent_conditional_fields_storage as $conditionComponentFieldNode) {
                                /** @var ComponentFieldNodeInterface $conditionComponentFieldNode */
                                $conditionComponentFieldNodes = $subcomponent_conditional_fields_storage[$conditionComponentFieldNode];
                                /** @var ComponentFieldNodeInterface[] $conditionComponentFieldNodes */
                                $id_subcomponent_conditional_fields_storage[$conditionComponentFieldNode] ??= [];
                                $id_subcomponent_conditional_data_fields_storage = $id_subcomponent_conditional_fields_storage[$conditionComponentFieldNode];
                                foreach ($conditionComponentFieldNodes as $componentFieldNode) {
                                    /** @var ComponentFieldNodeInterface $componentFieldNode */
                                    if (in_array($componentFieldNode->getField(), $subcomponent_already_loaded_data_fields)) {
                                        continue;
                                    }
                                    $id_subcomponent_conditional_data_fields_storage[] = $componentFieldNode;
                                }
                                $id_subcomponent_conditional_fields_storage[$conditionComponentFieldNode] = $id_subcomponent_conditional_data_fields_storage;
                            }
                        } else {
                            $id_subcomponent_direct_fields = $subcomponent_direct_fields;
                            $id_subcomponent_conditional_fields_storage = $subcomponent_conditional_fields_storage;
                        }
                        // Important: do ALWAYS execute the lines below, even if $id_subcomponent_direct_fields is empty
                        // That is because we can load additional data for an object that was already loaded in a previous iteration
                        // Eg: /api/?query=posts(id:1).author.posts.comments.post.author.posts.title
                        // In this case, property "title" at the end would not be fetched otherwise (that post was already loaded at the beginning)
                        $this->combineIDsDatafields($engineState->relationalTypeOutputKeyIDFieldSets, $subcomponentTypeResolver, $subcomponentTypeOutputKey, array($field_id), $id_subcomponent_direct_fields, $id_subcomponent_conditional_fields_storage);
                    }
                    $this->initializeTypeResolverEntry($engineState->dbdata, $subcomponentTypeOutputKey, $component_path_key);
                    $engineState->dbdata[$subcomponentTypeOutputKey][$component_path_key][DataProperties::IDS] = array_merge(
                        $engineState->dbdata[$subcomponentTypeOutputKey][$component_path_key][DataProperties::IDS] ?? [],
                        $field_ids
                    );
                    $this->integrateSubcomponentDataProperties($engineState->dbdata, $subcomponent_data_properties, $subcomponentTypeOutputKey, $component_path_key);
                }

                if ($engineState->dbdata[$subcomponentTypeOutputKey][$component_path_key] ?? null) {
                    $engineState->dbdata[$subcomponentTypeOutputKey][$component_path_key][DataProperties::IDS] = array_unique($engineState->dbdata[$subcomponentTypeOutputKey][$component_path_key][DataProperties::IDS]);
                    $engineState->dbdata[$subcomponentTypeOutputKey][$component_path_key][DataProperties::DIRECT_COMPONENT_FIELD_NODES] = array_unique($engineState->dbdata[$subcomponentTypeOutputKey][$component_path_key][DataProperties::DIRECT_COMPONENT_FIELD_NODES]);
                }
            }
        }
    }

    /**
     * @param array<string,array<string,array<string,array<string|int,SplObjectStorage<FieldInterface,mixed>>>>> $ret
     * @param array<string,array<string,array<string|int,SplObjectStorage<FieldInterface,mixed>>>> $entries
     */
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
                /** @var array<string,array<string|int,SplObjectStorage<FieldInterface,mixed>>> */
                $combined_databases = [];
                foreach ($entries as $database_name => $database) {
                    // Combine them on an ID by ID basis, because doing [2 => [...], 3 => [...]]), which is wrong
                    foreach ($database as $typeOutputKey => $resolvedIDFieldValues) {
                        foreach ($resolvedIDFieldValues as $dbobject_id => $fieldValues) {
                            /** @var SplObjectStorage<FieldInterface,mixed> */
                            $combinedDatabasesSplObjectStorage = $combined_databases[$typeOutputKey][$dbobject_id] ?? new SplObjectStorage();
                            $combinedDatabasesSplObjectStorage->addAll($fieldValues);
                            $combined_databases[$typeOutputKey][$dbobject_id] = $combinedDatabasesSplObjectStorage;
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

    protected function processAndAddComponentData(
        array $component_path,
        Component $component,
        array &$props,
        array $data_properties,
        ?FeedbackItemResolution $dataaccess_checkpoint_validation,
        $mutation_checkpoint_validation,
        $executed,
        $objectIDs
    ): void {
        $processor = $this->getComponentProcessorManager()->getComponentProcessor($component);
        $engineState = App::getEngineState();

        // Integrate the feedback into $componentdata
        if ($engineState->componentdata !== null) {
            $componentdata = &$engineState->componentdata;

            // Add the feedback into the object
            if ($feedback = $processor->getDataFeedbackDatasetcomponentTree($component, $props, $data_properties, $dataaccess_checkpoint_validation, $mutation_checkpoint_validation, $executed, $objectIDs)) {
                /** @var ModuleInfo */
                $moduleInfo = App::getModule(Module::class)->getInfo();
                $subcomponentsOutputProperty = $moduleInfo->getSubcomponentsOutputProperty();

                // Advance the position of the array into the current component
                foreach ($component_path as $subcomponent) {
                    $subcomponentOutputName = $this->getComponentHelpers()->getComponentOutputName($subcomponent);
                    $componentdata[$subcomponentOutputName][$subcomponentsOutputProperty] = $componentdata[$subcomponentOutputName][$subcomponentsOutputProperty] ?? [];
                    $componentdata = &$componentdata[$subcomponentOutputName][$subcomponentsOutputProperty];
                }
                // Merge the feedback in
                $componentdata = array_merge_recursive(
                    $componentdata,
                    $feedback
                );
            }
        }
    }

    private function initializeTypeResolverEntry(
        array &$dbdata,
        string $relationalTypeOutputKey,
        string $component_path_key
    ): void {
        if (!isset($dbdata[$relationalTypeOutputKey][$component_path_key])) {
            $dbdata[$relationalTypeOutputKey][$component_path_key] = array(
                DataProperties::IDS => [],
                DataProperties::DIRECT_COMPONENT_FIELD_NODES => [],
                DataProperties::SUBCOMPONENTS => new SplObjectStorage(),
            );
        }
    }

    private function integrateSubcomponentDataProperties(
        array &$dbdata,
        array $data_properties,
        string $relationalTypeOutputKey,
        string $component_path_key
    ): void {
        // Process the subcomponents
        // If it has subcomponents, bring its data to, after executing getData on the primary typeResolver, execute getData also on the subcomponent typeResolver
        if ($subcomponents_data_properties = $data_properties[DataProperties::SUBCOMPONENTS] ?? null) {
            /** @var SplObjectStorage<ComponentFieldNodeInterface,array<string,mixed>> $subcomponents_data_properties */
            $dbdata[$relationalTypeOutputKey][$component_path_key][DataProperties::SUBCOMPONENTS] ??= new SplObjectStorage();
            /** @var SplObjectStorage<ComponentFieldNodeInterface,array<string,mixed>> */
            $dbDataSubcomponentsSplObjectStorage = $dbdata[$relationalTypeOutputKey][$component_path_key][DataProperties::SUBCOMPONENTS];
            /**
             * Merge them into the data.
             * Watch out! Can't do `array_merge_recursive` because:
             *
             *   - SplObjectStorage items (under 'conditional-component-field-nodes') are all deleted!
             *   - 'direct-component-field-nodes' items are duplicated
             *
             * So then iterate the 3 entries, and merge them individually
             */
            foreach ($subcomponents_data_properties as $componentFieldNode) {
                /** @var ComponentFieldNodeInterface $componentFieldNode */
                $componentFieldNodeData = $subcomponents_data_properties[$componentFieldNode];
                $dbDataSubcomponentsSplObjectStorage[$componentFieldNode] ??= [];
                $dbDataSubcomponentsFieldSplObjectStorage = $dbDataSubcomponentsSplObjectStorage[$componentFieldNode];
                if (isset($componentFieldNodeData[DataProperties::DIRECT_COMPONENT_FIELD_NODES])) {
                    $dbDataSubcomponentsFieldSplObjectStorage[DataProperties::DIRECT_COMPONENT_FIELD_NODES] = array_values(array_unique(array_merge(
                        $dbDataSubcomponentsFieldSplObjectStorage[DataProperties::DIRECT_COMPONENT_FIELD_NODES] ?? [],
                        $componentFieldNodeData[DataProperties::DIRECT_COMPONENT_FIELD_NODES]
                    )));
                }
                if (isset($componentFieldNodeData[DataProperties::CONDITIONAL_COMPONENT_FIELD_NODES])) {
                    $dbDataSubcomponentsFieldSplObjectStorage[DataProperties::CONDITIONAL_COMPONENT_FIELD_NODES] ??= new SplObjectStorage();
                    /** @var SplObjectStorage<ComponentFieldNodeInterface,ComponentFieldNodeInterface[]> */
                    $componentFieldNodeConditionalFieldsSplObjectStorage = $componentFieldNodeData[DataProperties::CONDITIONAL_COMPONENT_FIELD_NODES];
                    foreach ($componentFieldNodeConditionalFieldsSplObjectStorage as $conditionField) {
                        /** @var ComponentFieldNodeInterface $conditionField */
                        $conditionalComponentFieldNodes = $componentFieldNodeConditionalFieldsSplObjectStorage[$conditionField];
                        /** @var ComponentFieldNodeInterface[] $conditionalComponentFieldNodes */
                        $dbDataSubcomponentsFieldSplObjectStorage[DataProperties::CONDITIONAL_COMPONENT_FIELD_NODES][$conditionField] = array_merge(
                            $dbDataSubcomponentsFieldSplObjectStorage[DataProperties::CONDITIONAL_COMPONENT_FIELD_NODES][$conditionField] ?? [],
                            $conditionalComponentFieldNodes
                        );
                    }
                }
                if (isset($componentFieldNodeData[DataProperties::SUBCOMPONENTS])) {
                    $dbDataSubcomponentsFieldSplObjectStorage[DataProperties::SUBCOMPONENTS] ??= new SplObjectStorage();
                    /** @var SplObjectStorage<ComponentFieldNodeInterface,array<string,mixed>> */
                    $componentFieldNodeDataSubcomponents = $componentFieldNodeData[DataProperties::SUBCOMPONENTS];
                    $dbDataSubcomponentsFieldSplObjectStorage[DataProperties::SUBCOMPONENTS]->addAll($componentFieldNodeDataSubcomponents);
                }
                $dbDataSubcomponentsSplObjectStorage[$componentFieldNode] = $dbDataSubcomponentsFieldSplObjectStorage;
            }
            $dbdata[$relationalTypeOutputKey][$component_path_key][DataProperties::SUBCOMPONENTS] = $dbDataSubcomponentsSplObjectStorage;
        }
    }
}

<?php

declare(strict_types=1);

namespace PoP\API\Hooks;

use PoP\API\ComponentConfiguration;
use PoP\API\Constants\Actions;
use PoP\API\Facades\FieldQueryConvertorFacade;
use PoP\API\PersistedQueries\PersistedQueryUtils;
use PoP\API\Response\Schemes as APISchemes;
use PoP\API\Schema\QueryInputs;
use PoP\BasicService\AbstractHookSet;
use PoP\ComponentModel\Constants\DatabasesOutputModes;
use PoP\ComponentModel\Constants\DataOutputItems;
use PoP\ComponentModel\Constants\DataOutputModes;
use PoP\ComponentModel\Constants\Outputs;
use PoP\ComponentModel\Error\ErrorServiceInterface;
use PoP\ComponentModel\ModelInstance\ModelInstance;
use PoP\ComponentModel\Schema\FeedbackMessageStoreInterface;
use PoP\ComponentModel\State\ApplicationState;
use PoP\GraphQLParser\Execution\RequestInterface;
use PoP\GraphQLParser\Parser\ParserInterface;
use PoP\GraphQLParser\Validator\RequestValidator\RequestValidatorInterface;

class VarsHookSet extends AbstractHookSet
{
    private ?ErrorServiceInterface $errorService = null;
    private ?FeedbackMessageStoreInterface $feedbackMessageStore = null;
    private ?ParserInterface $parser = null;
    private ?RequestInterface $request = null;
    private ?RequestValidatorInterface $requestValidator = null;

    final public function setErrorService(ErrorServiceInterface $errorService): void
    {
        $this->errorService = $errorService;
    }
    final protected function getErrorService(): ErrorServiceInterface
    {
        return $this->errorService ??= $this->instanceManager->getInstance(ErrorServiceInterface::class);
    }
    final public function setFeedbackMessageStore(FeedbackMessageStoreInterface $feedbackMessageStore): void
    {
        $this->feedbackMessageStore = $feedbackMessageStore;
    }
    final protected function getFeedbackMessageStore(): FeedbackMessageStoreInterface
    {
        return $this->feedbackMessageStore ??= $this->instanceManager->getInstance(FeedbackMessageStoreInterface::class);
    }
    final public function setParser(ParserInterface $parser): void
    {
        $this->parser = $parser;
    }
    final protected function getParser(): ParserInterface
    {
        return $this->parser ??= $this->instanceManager->getInstance(ParserInterface::class);
    }
    final public function setRequest(RequestInterface $request): void
    {
        $this->request = $request;
    }
    final protected function getRequest(): RequestInterface
    {
        return $this->request ??= $this->instanceManager->getInstance(RequestInterface::class);
    }
    final public function setRequestValidator(RequestValidatorInterface $requestValidator): void
    {
        $this->requestValidator = $requestValidator;
    }
    final protected function getRequestValidator(): RequestValidatorInterface
    {
        return $this->requestValidator ??= $this->instanceManager->getInstance(RequestValidatorInterface::class);
    }

    protected function init(): void
    {
        // Execute early, since others (eg: SPA) will be based on these updated values
        $this->getHooksAPI()->addAction(
            'ApplicationState:addVars',
            array($this, 'addVars'),
            5,
            1
        );
        // Add functions as hooks, so we allow PoP_Application to set the 'routing-state' first
        $this->getHooksAPI()->addAction(
            'ApplicationState:addVars',
            array($this, 'addURLParamVars'),
            10,
            1
        );
        $this->getHooksAPI()->addFilter(
            ModelInstance::HOOK_COMPONENTS_RESULT,
            array($this, 'getModelInstanceComponentsFromVars')
        );
    }

    /**
     * Override values for the API mode!
     * Whenever doing ?scheme=api, the specific configuration below must be set in the vars
     * @param array<array> $vars_in_array
     */
    public function addVars(array $vars_in_array): void
    {
        [&$vars] = $vars_in_array;
        if (isset($vars['scheme']) && $vars['scheme'] == APISchemes::API) {
            // For the API, the response is always JSON
            $vars['output'] = Outputs::JSON;

            // Fetch datasetmodulesettings: needed to obtain the dbKeyPath to know where to find the database entries
            $vars['dataoutputitems'] = [
                DataOutputItems::DATASET_MODULE_SETTINGS,
                DataOutputItems::MODULE_DATA,
                DataOutputItems::DATABASES,
            ];

            // dataoutputmode => Combined: there is no need to split the sources, then already combined them
            $vars['dataoutputmode'] = DataOutputModes::COMBINED;

            // dboutputmode => Combined: needed since we don't know under what database does the dbKeyPath point to. Then simply integrate all of them
            // Also, needed for REST/GraphQL APIs since all their data comes bundled all together
            $vars['dboutputmode'] = DatabasesOutputModes::COMBINED;

            // Do not print the entry module
            $vars['actions'][] = Actions::REMOVE_ENTRYMODULE_FROM_OUTPUT;

            // Enable mutations?
            $vars['are-mutations-enabled'] = ComponentConfiguration::enableMutations();

            // Entry to indicate if the query has errors (eg: some GraphQL variable not submitted)
            $vars['does-api-query-have-errors'] = false;
        }
    }

    public function addURLParamVars(array $vars_in_array): void
    {
        // Allow WP API to set the "routing-state" first
        // Each page is an independent configuration
        [&$vars] = $vars_in_array;
        if (isset($vars['scheme']) && $vars['scheme'] == APISchemes::API) {
            $this->addFieldsToVars($vars);
        }
    }

    private function addFieldsToVars(array &$vars): void
    {
        if (isset($_REQUEST[QueryInputs::QUERY])) {
            /**
             * Until GraphQL over HTTP is ready,
             * this is a standard GraphQL query.
             *
             * @see https://github.com/graphql/graphql-over-http
             */
            $query = $_REQUEST[QueryInputs::QUERY];

            // Validate the URL param is not passed as an array
            if (is_array($query)) {
                $errorMessage = sprintf(
                    $this->getTranslationAPI()->__('The GraphQL query must be a string, but received \'%s\'', 'api'),
                    $this->getErrorService()->jsonEncodeArrayOrStdClassValue($query)
                );
                $this->getFeedbackMessageStore()->addQueryError($errorMessage);
                return;
            }

            // If the query starts with "!", then it is the query name to a persisted query
            $query = PersistedQueryUtils::maybeGetPersistedQuery($query);

            // Set the query in $vars
            $this->parseGraphQLQueryAndAddToVars($vars, $query);
        }
    }

    /**
     * This function is public so it can be invoked from
     * GraphQLByPoP\GraphQLRequest\Hooks\VarsHookSet.
     *
     * The GraphQL query must be parsed into the AST, which has 2 outputs:
     *
     * 1. The actual requested query
     * 2. The executable query, created by doing transformations on the requested query
     *
     * For instance, when doing query batching, fields in the executable query
     * will be prepended with "self" to have the operations be executed in strict order.
     *
     * The executable query is the one needed to load data, so it's saved under "query".
     * The requested query is used to display the data, for instance for GraphQL.
     * It's saved under "requested-query" in $vars, and it's optional: if empty,
     * requested = executable => the executable query from $vars['query'] can be used
     */
    public function parseGraphQLQueryAndAddToVars(array &$vars, string $query): void
    {
        $parsedData = $this->getParser()->parse($query);

        // Take the existing variables from $vars, so they must be set in advance
        $variables = $vars['variables'] ?? [];
        
        // If some variable hasn't been submitted, it will throw an Exception
        // Let it bubble up
        /** @var RequestInterface */
        $request = $this->getRequest()->process($parsedData, $variables);

        // If the validation fails, it will throw an exception
        $this->getRequestValidator()->validate($request);

        $vars['query'] = $request;
        if (false) {
            $vars['requested-query'] = $request;
        }
        // $fieldQueryConvertor = FieldQueryConvertorFacade::getInstance();
        // $fieldQuerySet = $fieldQueryConvertor->convertAPIQuery($query);
        // if ($fieldQuerySet->areRequestedAndExecutableFieldQueriesDifferent()) {
        // if ($fieldQuerySet->areRequestedAndExecutableFieldQueriesDifferent()) {
        //     $vars['requested-query'] = $fieldQuerySet->getRequestedFieldQuery();
        // }
    }

    public function getModelInstanceComponentsFromVars($components)
    {
        // Allow WP API to set the "routing-state" first
        // Each page is an independent configuration
        $vars = ApplicationState::getVars();
        if (isset($vars['scheme']) && $vars['scheme'] == APISchemes::API) {
            $this->addFieldsToComponents($components);
        }

        // Namespaces change the configuration
        $components[] = $this->getTranslationAPI()->__('namespaced:', 'pop-engine') . ($vars['namespace-types-and-interfaces'] ?? false);

        return $components;
    }

    private function addFieldsToComponents(&$components): void
    {
        $vars = ApplicationState::getVars();
        if ($fields = $vars['query'] ?? null) {
            // Serialize instead of implode, because $fields can contain $key => $value
            $components[] = $this->getTranslationAPI()->__('fields:', 'pop-engine') . serialize($fields);
        }
    }
}

<?php

declare(strict_types=1);

namespace GraphQLByPoP\GraphQLRequest\Hooks;

use GraphQLByPoP\GraphQLQuery\Facades\GraphQLQueryConvertorFacade;
use GraphQLByPoP\GraphQLQuery\Schema\OperationTypes;
use GraphQLByPoP\GraphQLRequest\ComponentConfiguration;
use GraphQLByPoP\GraphQLRequest\Execution\QueryExecutionHelpers;
use GraphQLByPoP\GraphQLRequest\Facades\GraphQLPersistedQueryManagerFacade;
use PoP\API\Response\Schemes as APISchemes;
use PoP\API\Schema\QueryInputs;
use PoP\API\State\ApplicationStateUtils;
use PoP\ComponentModel\CheckpointProcessors\MutationCheckpointProcessor;
use PoP\ComponentModel\Facades\Instances\InstanceManagerFacade;
use PoP\ComponentModel\Facades\Schema\FeedbackMessageStoreFacade;
use PoP\ComponentModel\State\ApplicationState;
use PoP\GraphQLAPI\DataStructureFormatters\GraphQLDataStructureFormatter;
use PoP\Hooks\AbstractHookSet;
use PoP\Translation\Facades\TranslationAPIFacade;

class VarsHooks extends AbstractHookSet
{
    protected function init()
    {
        // Priority 20: execute after the same code in API, as to remove $vars['query]
        $this->hooksAPI->addAction(
            'ApplicationState:addVars',
            array($this, 'addVars'),
            20,
            1
        );

        // Change the error message when mutations are not supported
        $this->hooksAPI->addFilter(
            MutationCheckpointProcessor::HOOK_MUTATIONS_NOT_SUPPORTED_ERROR_MSG,
            array($this, 'getMutationsNotSupportedErrorMessage'),
            10,
            1
        );
    }

    /**
     * Override the error message when executing a query through standard GraphQL
     * @param array<array> $vars_in_array
     */
    public function getMutationsNotSupportedErrorMessage($errorMessage): string
    {
        $vars = ApplicationState::getVars();
        if ($vars['standard-graphql']) {
            $translationAPI = TranslationAPIFacade::getInstance();
            return sprintf(
                $translationAPI->__('Use the operation type \'%s\' to execute mutations', 'graphql-request'),
                OperationTypes::MUTATION
            );
        }
        return $errorMessage;
    }

    /**
     * @param array<array> $vars_in_array
     */
    public function addVars(array $vars_in_array): void
    {
        [&$vars] = $vars_in_array;

        // Set always. It will be overriden below
        $vars['standard-graphql'] = false;

        $instanceManager = InstanceManagerFacade::getInstance();
        /** @var GraphQLDataStructureFormatter */
        $graphQLDataStructureFormatter = $instanceManager->getInstance(GraphQLDataStructureFormatter::class);
        if ($vars['scheme'] == APISchemes::API && $vars['datastructure'] == $graphQLDataStructureFormatter->getName()) {
            $this->processURLParamVars($vars);
        }
    }

    public function setStandardGraphQLVars(array &$vars): void
    {
        // Add a flag indicating that we are doing standard GraphQL
        $vars['standard-graphql'] = true;
    }

    /**
     * @param array<string, mixed> $vars
     */
    protected function processURLParamVars(array &$vars): void
    {
        $disablePoPQuery = isset($_REQUEST[QueryInputs::QUERY]) && ComponentConfiguration::disableGraphQLAPIForPoP();
        if ($disablePoPQuery) {
            // Remove the query set by package API
            unset($vars['query']);
        }
        // If the "query" param is set, this case is already handled in API package
        // Unless it is a persisted query for GraphQL, then deal with it here
        $graphQLPersistedQueryManager = GraphQLPersistedQueryManagerFacade::getInstance();
        $isGraphQLPersistedQuery = isset($_REQUEST[QueryInputs::QUERY]) && $graphQLPersistedQueryManager->isPersistedQuery($_REQUEST[QueryInputs::QUERY]);
        if (
            !isset($_REQUEST[QueryInputs::QUERY])
            || ComponentConfiguration::disableGraphQLAPIForPoP()
            || $isGraphQLPersistedQuery
        ) {
            // Add a flag indicating that we are doing standard GraphQL
            // Do it already, so that even if there is no query, the error doesn't have "extensions"
            $this->setStandardGraphQLVars($vars);
            // The GraphQL query is either a persisted query passed through URL param via GET
            // (such as ?query=!introspectionQuery) or, if not, it's passed through the
            // body via POST, as standard behavior
            if ($isGraphQLPersistedQuery) {
                $graphQLQuery = $variables = $operationName = null;
                // Get the query name, and extract the query from the PersistedQueryManager
                $query = $_REQUEST[QueryInputs::QUERY] ?? '';
                $queryName = $graphQLPersistedQueryManager->getPersistedQueryName($query);
                if ($graphQLPersistedQueryManager->hasPersistedQuery($queryName)) {
                    $graphQLQuery = $graphQLPersistedQueryManager->getPersistedQuery($queryName);
                }
            } else {
                list(
                    $graphQLQuery,
                    $variables,
                    $operationName
                ) = QueryExecutionHelpers::extractRequestedGraphQLQueryPayload();
            }
            // Process the query, or show an error if empty
            if ($graphQLQuery) {
                // Maybe override the variables, getting them from the GraphQL dictionary
                if ($variables) {
                    $vars['variables'] = $variables;
                }
                $this->addGraphQLQueryToVars($vars, $graphQLQuery, $operationName);
            } elseif ($disablePoPQuery || !$isGraphQLPersistedQuery) {
                // If the persisted query does not exist, no need to show an error,
                // since the "field ... does not exist" already takes care of it,
                // here for GraphQL and also for PoP.
                // Show error only for the other cases
                $translationAPI = TranslationAPIFacade::getInstance();
                $feedbackMessageStore = FeedbackMessageStoreFacade::getInstance();
                $errorMessage = $disablePoPQuery ?
                    $translationAPI->__('No query was provided. (The body has no query, and the query provided as a URL param is ignored because of configuration)', 'graphql-request')
                    : $translationAPI->__('The query in the body is empty', 'graphql-request');
                $feedbackMessageStore->addQueryError($errorMessage);
            }
        }
    }

    /**
     * Function is public so it can be invoked from the WordPress plugin
     *
     * @return void
     */
    public function addGraphQLQueryToVars(array &$vars, string $graphQLQuery, ?string $operationName = null)
    {
        // Take the existing variables from $vars, so they must be set in advance
        $variables = $vars['variables'] ?? [];
        // Convert from GraphQL syntax to Field Query syntax
        $graphQLQueryConvertor = GraphQLQueryConvertorFacade::getInstance();
        list(
            $operationType,
            $fieldQuery
        ) = $graphQLQueryConvertor->convertFromGraphQLToFieldQuery(
            $graphQLQuery,
            $variables,
            ComponentConfiguration::enableMultipleQueryExecution(),
            $operationName
        );

        // Set the operation type and, based on it, if mutations are supported
        $vars['graphql-operation-type'] = $operationType;
        $vars['are-mutations-enabled'] = $operationType == OperationTypes::MUTATION;

        // Set the query in $vars
        ApplicationStateUtils::maybeConvertQueryAndAddToVars($vars, $fieldQuery);

        // Do not include the fieldArgs and directives when outputting the field
        $vars['only-fieldname-as-outputkey'] = true;
    }
}

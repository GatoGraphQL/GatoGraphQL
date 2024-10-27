<?php

declare(strict_types=1);

namespace PoPAPI\API\State;

use PoPAPI\API\Configuration\EngineRequest;
use PoPAPI\API\Constants\Actions;
use PoPAPI\API\Module;
use PoPAPI\API\ModuleConfiguration;
use PoPAPI\API\PersistedQueries\PersistedQueryManagerInterface;
use PoPAPI\API\QueryParsing\GraphQLParserHelperServiceInterface;
use PoPAPI\API\Response\Schemes as APISchemes;
use PoP\ComponentModel\App;
use PoP\ComponentModel\Constants\DataOutputItems;
use PoP\ComponentModel\Constants\DataOutputModes;
use PoP\ComponentModel\Constants\DatabasesOutputModes;
use PoP\ComponentModel\Constants\Outputs;
use PoP\ComponentModel\Feedback\DocumentFeedback;
use PoP\ComponentModel\Feedback\FeedbackItemResolution;
use PoP\ComponentModel\Feedback\QueryFeedback;
use PoP\GraphQLParser\Exception\AbstractASTNodeException;
use PoP\GraphQLParser\Exception\AbstractQueryException;
use PoP\GraphQLParser\Exception\Parser\AbstractASTNodeParserException;
use PoP\GraphQLParser\Exception\Parser\AbstractParserException;
use PoP\Root\State\AbstractAppStateProvider;

class AppStateProvider extends AbstractAppStateProvider
{
    private ?GraphQLParserHelperServiceInterface $graphQLParserHelperService = null;
    private ?PersistedQueryManagerInterface $persistedQueryManager = null;

    final protected function getGraphQLParserHelperService(): GraphQLParserHelperServiceInterface
    {
        if ($this->graphQLParserHelperService === null) {
            /** @var GraphQLParserHelperServiceInterface */
            $graphQLParserHelperService = $this->instanceManager->getInstance(GraphQLParserHelperServiceInterface::class);
            $this->graphQLParserHelperService = $graphQLParserHelperService;
        }
        return $this->graphQLParserHelperService;
    }
    final protected function getPersistedQueryManager(): PersistedQueryManagerInterface
    {
        if ($this->persistedQueryManager === null) {
            /** @var PersistedQueryManagerInterface */
            $persistedQueryManager = $this->instanceManager->getInstance(PersistedQueryManagerInterface::class);
            $this->persistedQueryManager = $persistedQueryManager;
        }
        return $this->persistedQueryManager;
    }

    /**
     * @param array<string,mixed> $state
     */
    public function initialize(array &$state): void
    {
        $state['executable-document-ast'] = null;
        $state['does-api-query-have-errors'] = null;

        /**
         * This is not needed as they are already initialized at
         * the ComponentModel level.
         *
         * @see layers/Engine/packages/component-model/src/State/AppStateProvider.php
         */
        // $state['document-ast-node-ancestors'] = null;
        // $state['document-object-resolved-field-value-referenced-fields'] = [];

        /**
         * Passing the query via URL param? Eg: ?query={ posts { id } }
         */
        $state['query'] = EngineRequest::getQuery();

        /**
         * If not passing the query, and passing ?persistedQuery=someName
         * (eg: "introspectionQuery"), then retrieve the query from
         * the PersistedQueryManager service
         */
        if (empty($state['query'])) {
            /** @var ModuleConfiguration */
            $moduleConfiguration = App::getModule(Module::class)->getConfiguration();
            $persistedQuery = EngineRequest::getPersistedQuery();
            if (
                $moduleConfiguration->enablePassingPersistedQueryNameViaURLParam()
                && !empty($persistedQuery)
                && $this->getPersistedQueryManager()->hasPersistedQuery($persistedQuery)
            ) {
                $state['query'] = $this->getPersistedQueryManager()->getPersistedQuery($persistedQuery);
            }
        }

        /**
         * Passing the operationName via URL param? Eg: ?operationName=One.
         *
         * This is needed when using Multiple Query Execution
         * in a Persisted Query, to indicate which operation to execute.
         */
        $state['operation-name'] = EngineRequest::getOperationName();
    }

    /**
     * @param array<string,mixed> $state
     */
    public function consolidate(array &$state): void
    {
        if ($state['scheme'] !== APISchemes::API) {
            return;
        }

        // For the API, the response is always JSON
        $state['output'] = Outputs::JSON;

        // Fetch datasetcomponentsettings: needed to obtain the typeOutputKeyPath to know where to find the database entries
        $state['dataoutputitems'] = [
            DataOutputItems::DATASET_COMPONENT_SETTINGS,
            DataOutputItems::COMPONENT_DATA,
            DataOutputItems::DATABASES,
        ];

        // dataoutputmode => Combined: there is no need to split the sources, then already combined them
        $state['dataoutputmode'] = DataOutputModes::COMBINED;

        // dboutputmode => Combined: needed since we don't know under what database does the typeOutputKeyPath point to. Then simply integrate all of them
        // Also, needed for REST/GraphQL APIs since all their data comes bundled all together
        $state['dboutputmode'] = DatabasesOutputModes::COMBINED;

        // Do not print the entry component
        $state['actions'][] = Actions::REMOVE_ENTRYCOMPONENT_FROM_OUTPUT;

        // Entry to indicate if the query has errors (eg: some GraphQL variable not submitted)
        $state['does-api-query-have-errors'] = false;
    }

    /**
     * @param array<string,mixed> $state
     */
    public function execute(array &$state): void
    {
        if ($state['scheme'] !== APISchemes::API) {
            return;
        }

        $query = $state['query'];
        if ($query === null) {
            return;
        }

        $variableValues = $state['variables'];
        $operationName = $state['operation-name'];

        $executableDocument = null;
        try {
            $graphQLQueryParsingPayload = $this->getGraphQLParserHelperService()->parseGraphQLQuery(
                $query,
                $variableValues,
                $operationName
            );
            $executableDocument = $graphQLQueryParsingPayload->executableDocument;
            $state['document-object-resolved-field-value-referenced-fields'] = $graphQLQueryParsingPayload->objectResolvedFieldValueReferencedFields;
        } catch (AbstractASTNodeException | AbstractASTNodeParserException | AbstractQueryException $exception) {
            // AbstractQueryException is needed to avoid `@export(as: $someVar)`
            App::getFeedbackStore()->documentFeedbackStore->addError(
                new QueryFeedback(
                    FeedbackItemResolution::fromUpstreamFeedbackItemResolution($exception->getFeedbackItemResolution()),
                    $exception->getAstNode(),
                )
            );
        } catch (AbstractParserException $parserException) {
            App::getFeedbackStore()->documentFeedbackStore->addError(
                new DocumentFeedback(
                    FeedbackItemResolution::fromUpstreamFeedbackItemResolution($parserException->getFeedbackItemResolution()),
                    $parserException->getLocation(),
                )
            );
        }

        if ($executableDocument !== null) {
            /**
             * Calculate now, as it's useful also if the validation
             * of the ExecutableDocument has errors.
             */
            $state['document-ast-node-ancestors'] = $executableDocument->getDocument()->getASTNodeAncestors();

            try {
                $executableDocument->validateAndInitialize();
            } catch (AbstractASTNodeException $astNodeException) {
                $executableDocument = null;
                App::getFeedbackStore()->documentFeedbackStore->addError(
                    new QueryFeedback(
                        FeedbackItemResolution::fromUpstreamFeedbackItemResolution($astNodeException->getFeedbackItemResolution()),
                        $astNodeException->getAstNode(),
                    )
                );
            } catch (AbstractQueryException $queryException) {
                $executableDocument = null;
                App::getFeedbackStore()->documentFeedbackStore->addError(
                    new QueryFeedback(
                        FeedbackItemResolution::fromUpstreamFeedbackItemResolution($queryException->getFeedbackItemResolution()),
                        $queryException->getAstNode(),
                    )
                );
            }
        }

        $state['executable-document-ast'] = $executableDocument;
        if ($executableDocument === null) {
            $state['does-api-query-have-errors'] = true;
        }
    }
}

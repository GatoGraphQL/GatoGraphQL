<?php

declare(strict_types=1);

namespace PoPAPI\API\State;

use PoP\ComponentModel\App;
use PoP\ComponentModel\Component as ComponentModelComponent;
use PoP\ComponentModel\ComponentConfiguration as ComponentModelComponentConfiguration;
use PoP\ComponentModel\Constants\DatabasesOutputModes;
use PoP\ComponentModel\Constants\DataOutputItems;
use PoP\ComponentModel\Constants\DataOutputModes;
use PoP\ComponentModel\Constants\Outputs;
use PoP\GraphQLParser\Exception\Parser\InvalidRequestException;
use PoP\GraphQLParser\Exception\Parser\SyntaxErrorException;
use PoP\GraphQLParser\ExtendedSpec\Execution\ExecutableDocument;
use PoP\GraphQLParser\ExtendedSpec\Parser\ParserInterface;
use PoP\GraphQLParser\Spec\Execution\Context;
use PoP\Root\State\AbstractAppStateProvider;
use PoPAPI\API\Configuration\EngineRequest;
use PoPAPI\API\Constants\Actions;
use PoPAPI\API\Facades\FieldQueryConvertorFacade;
use PoPAPI\API\PersistedQueries\PersistedQueryUtils;
use PoPAPI\API\Response\Schemes as APISchemes;

class AppStateProvider extends AbstractAppStateProvider
{
    private ?ParserInterface $parser = null;

    final public function setParser(ParserInterface $parser): void
    {
        $this->parser = $parser;
    }
    final protected function getParser(): ParserInterface
    {
        return $this->parser ??= $this->instanceManager->getInstance(ParserInterface::class);
    }

    public function initialize(array &$state): void
    {
        $state['executable-document-ast'] = null;
        $state['executable-query'] = null;
        $state['requested-query'] = null;
        $state['does-api-query-have-errors'] = null;

        // Passing the query via URL param?
        /** @var ComponentModelComponentConfiguration */
        $componentModelComponentConfiguration = App::getComponent(ComponentModelComponent::class)->getConfiguration();
        $enableModifyingEngineBehaviorViaRequest = $componentModelComponentConfiguration->enableModifyingEngineBehaviorViaRequest();
        $state['query'] = EngineRequest::getQuery($enableModifyingEngineBehaviorViaRequest);
    }

    public function consolidate(array &$state): void
    {
        if ($state['scheme'] !== APISchemes::API) {
            return;
        }

        // For the API, the response is always JSON
        $state['output'] = Outputs::JSON;

        // Fetch datasetmodulesettings: needed to obtain the dbKeyPath to know where to find the database entries
        $state['dataoutputitems'] = [
            DataOutputItems::DATASET_MODULE_SETTINGS,
            DataOutputItems::MODULE_DATA,
            DataOutputItems::DATABASES,
        ];

        // dataoutputmode => Combined: there is no need to split the sources, then already combined them
        $state['dataoutputmode'] = DataOutputModes::COMBINED;

        // dboutputmode => Combined: needed since we don't know under what database does the dbKeyPath point to. Then simply integrate all of them
        // Also, needed for REST/GraphQL APIs since all their data comes bundled all together
        $state['dboutputmode'] = DatabasesOutputModes::COMBINED;

        // Do not print the entry module
        $state['actions'][] = Actions::REMOVE_ENTRYMODULE_FROM_OUTPUT;

        // Entry to indicate if the query has errors (eg: some GraphQL variable not submitted)
        $state['does-api-query-have-errors'] = false;
    }

    /**
     * The query must be converted to array, which has 2 outputs:
     *
     *   1. The actual requested query
     *   2. The executable query, created by doing transformations on the requested query
     *
     * For instance, when doing query batching, fields in the executable query
     * will be prepended with "self" to have the operations be executed in stric order.
     *
     * The executable query is the one needed to load data, so it's saved under "query".
     * The requested query is used to display the data, for instance for GraphQL.
     * It's saved under "requested-query" in $state, and it's optional: if empty,
     * requested = executable => the executable query from $state['query'] can be used
     */
    public function compute(array &$state): void
    {
        if ($state['scheme'] !== APISchemes::API) {
            return;
        }

        $query = $state['query'];
        if ($query === null || trim($query) === '') {
            /**
             * No need to return any error here, that will be done
             * when processing the query in the Engine
             */
            return;
        }

        $variableValues = $state['variables'];
        $operationName = $state['graphql-operation-name'];

        try {
            $executableDocument = $this->parseGraphQLQuery(
                $query,
                $variableValues,
                $operationName
            );
            $state['executable-document-ast'] = $executableDocument;
        } catch (SyntaxErrorException | InvalidRequestException $e) {
            // @todo Show GraphQL error in client
            // ...
            $state['does-api-query-have-errors'] = true;
        }

        // @todo Remove all code below!!!

        // If the query starts with "!", then it is the query name to a persisted query
        $fieldQuery = $state['field-query'];
        $fieldQuery = PersistedQueryUtils::maybeGetPersistedQuery($fieldQuery);

        // Parse the query from string into the format needed to work with it
        $fieldQueryConvertor = FieldQueryConvertorFacade::getInstance();
        $fieldQuerySet = $fieldQueryConvertor->convertAPIQuery($fieldQuery);
        $state['executable-query'] = $fieldQuerySet->getExecutableFieldQuery();
        if ($fieldQuerySet->areRequestedAndExecutableFieldQueriesDifferent()) {
            $state['requested-query'] = $fieldQuerySet->getRequestedFieldQuery();
        }
    }

    /**
     * @throws SyntaxErrorException
     * @throws InvalidRequestException
     */
    protected function parseGraphQLQuery(
        string $query,
        array $variableValues,
        ?string $operationName,
    ): ExecutableDocument {
        $document = $this->getParser()->parse($query)->setAncestorsInAST();
        $executableDocument = (
            new ExecutableDocument(
                $document,
                new Context($operationName, $variableValues)
            )
        )->validateAndInitialize();
        return $executableDocument;
    }
}

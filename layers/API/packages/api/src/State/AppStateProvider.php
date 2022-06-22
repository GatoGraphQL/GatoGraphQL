<?php

declare(strict_types=1);

namespace PoPAPI\API\State;

use PoP\ComponentModel\App;
use PoP\ComponentModel\Module as ComponentModelModule;
use PoP\ComponentModel\ModuleConfiguration as ComponentModelModuleConfiguration;
use PoP\ComponentModel\Constants\DatabasesOutputModes;
use PoP\ComponentModel\Constants\DataOutputItems;
use PoP\ComponentModel\Constants\DataOutputModes;
use PoP\ComponentModel\Constants\Outputs;
use PoP\ComponentModel\ExtendedSpec\Execution\ExecutableDocument;
use PoP\GraphQLParser\Exception\Parser\InvalidRequestException;
use PoP\GraphQLParser\Exception\Parser\SyntaxErrorException;
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
        $state['does-api-query-have-errors'] = null;

        // Passing the query via URL param?
        /** @var ComponentModelModuleConfiguration */
        $componentModelModuleConfiguration = App::getModule(ComponentModelModule::class)->getConfiguration();
        $enableModifyingEngineBehaviorViaRequest = $componentModelModuleConfiguration->enableModifyingEngineBehaviorViaRequest();
        $state['query'] = EngineRequest::getQuery($enableModifyingEngineBehaviorViaRequest);
    }

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
        /** @var ExecutableDocument */
        $executableDocument = (
            new ExecutableDocument(
                $document,
                new Context($operationName, $variableValues)
            )
        )->validateAndInitialize();
        return $executableDocument;
    }
}

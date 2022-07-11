<?php

declare(strict_types=1);

namespace PoPAPI\API\State;

use PoP\ComponentModel\App;
use PoP\ComponentModel\Constants\DatabasesOutputModes;
use PoP\ComponentModel\Constants\DataOutputItems;
use PoP\ComponentModel\Constants\DataOutputModes;
use PoP\ComponentModel\Constants\Outputs;
use PoP\ComponentModel\Module as ComponentModelModule;
use PoP\ComponentModel\ModuleConfiguration as ComponentModelModuleConfiguration;
use PoP\GraphQLParser\Exception\Parser\InvalidRequestException;
use PoP\GraphQLParser\Exception\Parser\SyntaxErrorException;
use PoPAPI\API\StaticHelpers\GraphQLParserHelpers;
use PoP\Root\State\AbstractAppStateProvider;
use PoPAPI\API\Configuration\EngineRequest;
use PoPAPI\API\Constants\Actions;
use PoPAPI\API\Response\Schemes as APISchemes;

class AppStateProvider extends AbstractAppStateProvider
{
    public function initialize(array &$state): void
    {
        $state['executable-document-ast'] = null;
        $state['document-ast-node-ancestors'] = null;
        $state['does-api-query-have-errors'] = null;

        // Passing the query via URL param?
        /** @var ComponentModelModuleConfiguration */
        $componentModelModuleConfiguration = App::getModule(ComponentModelModule::class)->getConfiguration();
        $enableModifyingEngineBehaviorViaRequest = $componentModelModuleConfiguration->enableModifyingEngineBehaviorViaRequest();
        $state['query'] = EngineRequest::getQuery($enableModifyingEngineBehaviorViaRequest);
        $state['operation-name'] = EngineRequest::getOperationName($enableModifyingEngineBehaviorViaRequest);
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

    public function execute(array &$state): void
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
        $operationName = $state['operation-name'];

        try {
            $executableDocument = GraphQLParserHelpers::parseGraphQLQuery(
                $query,
                $variableValues,
                $operationName
            );
            $state['executable-document-ast'] = $executableDocument;
            $state['document-ast-node-ancestors'] = $executableDocument->getDocument()->getASTNodeAncestors();
        } catch (SyntaxErrorException | InvalidRequestException $e) {
            // @todo Show GraphQL error in client
            // ...
            $state['does-api-query-have-errors'] = true;
        }
    }
}

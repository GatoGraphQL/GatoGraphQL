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
use PoP\Root\State\AbstractAppStateProvider;
use PoPAPI\API\Component as APIComponent;
use PoPAPI\API\ComponentConfiguration as APIComponentConfiguration;
use PoPAPI\API\Configuration\EngineRequest;
use PoPAPI\API\Constants\Actions;
use PoPAPI\API\Facades\FieldQueryConvertorFacade;
use PoPAPI\API\PersistedQueries\PersistedQueryUtils;
use PoPAPI\API\Response\Schemes as APISchemes;

class AppStateProvider extends AbstractAppStateProvider
{
    public function initialize(array &$state): void
    {
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

        // Enable mutations?
        /** @var APIComponentConfiguration */
        $apiComponentConfiguration = App::getComponent(APIComponent::class)->getConfiguration();
        $state['are-mutations-enabled'] = $apiComponentConfiguration->enableMutations();

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

        // If the query starts with "!", then it is the query name to a persisted query
        $query = PersistedQueryUtils::maybeGetPersistedQuery($query);

        // Parse the query from string into the format needed to work with it
        $fieldQueryConvertor = FieldQueryConvertorFacade::getInstance();
        $fieldQuerySet = $fieldQueryConvertor->convertAPIQuery($query);
        $state['executable-query'] = $fieldQuerySet->getExecutableFieldQuery();
        if ($fieldQuerySet->areRequestedAndExecutableFieldQueriesDifferent()) {
            $state['requested-query'] = $fieldQuerySet->getRequestedFieldQuery();
        }
    }
}

<?php

declare(strict_types=1);

namespace PoP\API\State;

use PoP\API\Component;
use PoP\API\ComponentConfiguration;
use PoP\API\Configuration\Request;
use PoP\API\Constants\Actions;
use PoP\API\Facades\FieldQueryConvertorFacade;
use PoP\API\PersistedQueries\PersistedQueryUtils;
use PoP\API\Response\Schemes as APISchemes;
use PoP\ComponentModel\Constants\DatabasesOutputModes;
use PoP\ComponentModel\Constants\DataOutputItems;
use PoP\ComponentModel\Constants\DataOutputModes;
use PoP\ComponentModel\Constants\Outputs;
use PoP\ComponentModel\Schema\FeedbackMessageStoreInterface;
use PoP\Root\App;
use PoP\Root\State\AbstractAppStateProvider;

class AppStateProvider extends AbstractAppStateProvider
{
    private ?FeedbackMessageStoreInterface $feedbackMessageStore = null;
    
    final public function setFeedbackMessageStore(FeedbackMessageStoreInterface $feedbackMessageStore): void
    {
        $this->feedbackMessageStore = $feedbackMessageStore;
    }
    final protected function getFeedbackMessageStore(): FeedbackMessageStoreInterface
    {
        return $this->feedbackMessageStore ??= $this->instanceManager->getInstance(FeedbackMessageStoreInterface::class);
    }

    public function initialize(array &$state): void
    {
        $state['query'] = null;
        $state['executable-query'] = null;
        $state['requested-query'] = null;
        $state['does-api-query-have-errors'] = null;
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
        /** @var ComponentConfiguration */
        $componentConfiguration = App::getComponent(Component::class)->getConfiguration();
        $state['are-mutations-enabled'] = $componentConfiguration->enableMutations();

        // Entry to indicate if the query has errors (eg: some GraphQL variable not submitted)
        $state['does-api-query-have-errors'] = false;

        // Passing the query via URL param?
        $state['query'] = Request::getQuery();
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
    public function augment(array &$state): void
    {
        if ($state['scheme'] !== APISchemes::API) {
            return;
        }

        $query = $state['query'];
        if (empty($query)) {
            $this->getFeedbackMessageStore()->addQueryError($this->__('The query in the body is empty', 'api'));
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

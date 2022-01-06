<?php

declare(strict_types=1);

namespace PoP\API\Hooks;

use PoP\Root\App;
use PoP\API\Component;
use PoP\API\ComponentConfiguration;
use PoP\API\Constants\Actions;
use PoP\API\PersistedQueries\PersistedQueryUtils;
use PoP\API\Response\Schemes as APISchemes;
use PoP\API\Schema\QueryInputs;
use PoP\API\State\ApplicationStateUtils;
use PoP\ComponentModel\Constants\DatabasesOutputModes;
use PoP\ComponentModel\Constants\DataOutputItems;
use PoP\ComponentModel\Constants\DataOutputModes;
use PoP\ComponentModel\Constants\Outputs;
use PoP\ComponentModel\ModelInstance\ModelInstance;
use PoP\ComponentModel\State\ApplicationState;
use PoP\BasicService\AbstractHookSet;

class VarsHookSet extends AbstractHookSet
{
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
            /** @var ComponentConfiguration */
            $componentConfiguration = App::getComponent(Component::class)->getConfiguration();
            $vars['are-mutations-enabled'] = $componentConfiguration->enableMutations();

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
            $query = $_REQUEST[QueryInputs::QUERY];

            // If the query starts with "!", then it is the query name to a persisted query
            $query = PersistedQueryUtils::maybeGetPersistedQuery($query);

            // Set the query in $vars
            ApplicationStateUtils::maybeConvertQueryAndAddToVars($vars, $query);
        }
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
        $components[] = $this->__('namespaced:', 'pop-engine') . ($vars['namespace-types-and-interfaces'] ?? false);

        return $components;
    }

    private function addFieldsToComponents(&$components): void
    {
        $vars = ApplicationState::getVars();
        if ($fields = $vars['query'] ?? null) {
            // Serialize instead of implode, because $fields can contain $key => $value
            $components[] = $this->__('fields:', 'pop-engine') . serialize($fields);
        }
    }
}

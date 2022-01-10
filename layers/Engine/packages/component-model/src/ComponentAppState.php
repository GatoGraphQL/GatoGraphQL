<?php

declare(strict_types=1);

namespace PoP\ComponentModel\Component;

use PoP\ComponentModel\Component;
use PoP\ComponentModel\ComponentConfiguration;
use PoP\ComponentModel\Configuration\Request;
use PoP\ComponentModel\Constants\DatabasesOutputModes;
use PoP\ComponentModel\Constants\DataOutputItems;
use PoP\ComponentModel\Constants\DataOutputModes;
use PoP\ComponentModel\Constants\DataSourceSelectors;
use PoP\ComponentModel\Constants\Params;
use PoP\ComponentModel\Facades\ModuleFiltering\ModuleFilterManagerFacade;
use PoP\ComponentModel\Facades\Schema\FieldQueryInterpreterFacade;
use PoP\ComponentModel\Tokens\Param;
use PoP\Hooks\Facades\HooksAPIFacade;
use PoP\Root\App;
use PoP\Root\Component\AbstractComponentAppState;
use PoP\Routing\Facades\RoutingManagerFacade;
use PoP\Routing\RouteNatures;

class ComponentAppState extends AbstractComponentAppState
{
    /**
     * Have the Component set its own state, accessible for all Components in the App
     *
     * @param array<string,mixed> $state
     */
    public function initialize(array &$state): void
    {
        // Target/Module default values (for either empty, or if the user is playing around with the url)
        $datasources = strtolower($_REQUEST[Params::DATA_SOURCE] ?? '');
        $alldatasources = array(
            DataSourceSelectors::ONLYMODEL,
            DataSourceSelectors::MODELANDREQUEST,
        );
        if (!in_array($datasources, $alldatasources)) {
            $datasources = DataSourceSelectors::MODELANDREQUEST;
        }

        $dataoutputmode = strtolower($_REQUEST[Params::DATAOUTPUTMODE] ?? '');
        $dataoutputmodes = array(
            DataOutputModes::SPLITBYSOURCES,
            DataOutputModes::COMBINED,
        );
        if (!in_array($dataoutputmode, $dataoutputmodes)) {
            $dataoutputmode = DataOutputModes::SPLITBYSOURCES;
        }

        $dboutputmode = strtolower($_REQUEST[Params::DATABASESOUTPUTMODE] ?? '');
        $dboutputmodes = array(
            DatabasesOutputModes::SPLITBYDATABASES,
            DatabasesOutputModes::COMBINED,
        );
        if (!in_array($dboutputmode, $dboutputmodes)) {
            $dboutputmode = DatabasesOutputModes::SPLITBYDATABASES;
        }

        $dataoutputitems = $_REQUEST[Params::DATA_OUTPUT_ITEMS] ?? [];
        if ($dataoutputitems) {
            if (!is_array($dataoutputitems)) {
                $dataoutputitems = explode(Param::VALUE_SEPARATOR, strtolower($dataoutputitems));
            } else {
                $dataoutputitems = array_map('strtolower', $dataoutputitems);
            }
        }

        $hooksAPI = HooksAPIFacade::getInstance();
        $alldataoutputitems = (array) $hooksAPI->applyFilters(
            'ApplicationState:dataoutputitems',
            array(
                DataOutputItems::META,
                DataOutputItems::DATASET_MODULE_SETTINGS,
                DataOutputItems::MODULE_DATA,
                DataOutputItems::DATABASES,
                DataOutputItems::SESSION,
            )
        );
        $dataoutputitems = array_intersect(
            $dataoutputitems,
            $alldataoutputitems
        );
        if (!$dataoutputitems) {
            $dataoutputitems = $hooksAPI->applyFilters(
                'ApplicationState:default-dataoutputitems',
                array(
                    DataOutputItems::META,
                    DataOutputItems::DATASET_MODULE_SETTINGS,
                    DataOutputItems::MODULE_DATA,
                    DataOutputItems::DATABASES,
                    DataOutputItems::SESSION,
                )
            );
        }

        $modulefilter_manager = ModuleFilterManagerFacade::getInstance();
        $modulefilter = $modulefilter_manager->getSelectedModuleFilterName();

        // By default, get the variables from the request
        $fieldQueryInterpreter = FieldQueryInterpreterFacade::getInstance();
        $variables = $fieldQueryInterpreter->getVariablesFromRequest();

        /** @var ComponentConfiguration */
        $componentConfiguration = App::getComponent(Component::class)->getConfiguration();
        $routingManager = RoutingManagerFacade::getInstance();
        $state = array_merge(
            $state,
            [
                'nature' => $routingManager->getCurrentNature(),
                'route' => $routingManager->getCurrentRoute(),
                'output' => Request::getOutput(),
                'modulefilter' => $modulefilter,
                'actionpath' => $_REQUEST[Params::ACTION_PATH] ?? '',
                'dataoutputitems' => $dataoutputitems,
                'datasources' => $datasources,
                'datastructure' => Request::getDataStructure(),
                'dataoutputmode' => $dataoutputmode,
                'dboutputmode' => $dboutputmode,
                'mangled' => Request::getMangledValue(),
                'actions' => Request::getActions(),
                'scheme' => Request::getScheme(),
                'variables' => $variables,
                'only-fieldname-as-outputkey' => false,
                'namespace-types-and-interfaces' => $componentConfiguration->mustNamespaceTypes(),
                'version-constraint' => Request::getVersionConstraint(),
                'field-version-constraints' => Request::getVersionConstraintsForFields(),
                'directive-version-constraints' => Request::getVersionConstraintsForDirectives(),
                // By default, mutations are always enabled. Can be changed for the API
                'are-mutations-enabled' => true,
            ]
        );

        // Set the routing state (eg: PoP Queried Object can add its information)
        $state['routing-state'] = [];
    }

    /**
     * Once all properties by all Components have been set,
     * have this second pass consolidate the state
     *
     * @param array<string,mixed> $state
     */
    public function augment(array &$state): void
    {
        $nature = $state['nature'];
        $state['routing-state']['is-standard'] = $nature == RouteNatures::STANDARD;
        $state['routing-state']['is-home'] = $nature == RouteNatures::HOME;
        $state['routing-state']['is-404'] = $nature == RouteNatures::NOTFOUND;
    }
}

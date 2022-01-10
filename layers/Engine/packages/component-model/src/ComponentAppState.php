<?php

declare(strict_types=1);

namespace PoP\ComponentModel\Component;

use PoP\ComponentModel\Component;
use PoP\ComponentModel\ComponentConfiguration;
use PoP\ComponentModel\Configuration\Request;
use PoP\ComponentModel\Constants\Params;
use PoP\ComponentModel\Facades\ModuleFiltering\ModuleFilterManagerFacade;
use PoP\ComponentModel\Facades\Schema\FieldQueryInterpreterFacade;
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
                'dataoutputitems' => Request::getDataOutputItems(),
                'datasources' => Request::getDataSourceSelector(),
                'datastructure' => Request::getDataStructure(),
                'dataoutputmode' => Request::getDataOutputMode(),
                'dboutputmode' => Request::getDBOutputMode(),
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

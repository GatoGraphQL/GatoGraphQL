<?php

declare(strict_types=1);

namespace PoP\ComponentModel;

use PoP\ComponentModel\Component;
use PoP\ComponentModel\ComponentConfiguration;
use PoP\ComponentModel\Configuration\Request;
use PoP\ComponentModel\Facades\ModuleFiltering\ModuleFilterManagerFacade;
use PoP\ComponentModel\Facades\Schema\FieldQueryInterpreterFacade;
use PoP\Root\App;
use PoP\Root\Component\AbstractComponentAppState;
use PoP\Routing\Facades\RoutingManagerFacade;
use PoP\Routing\RouteNatures;

class ComponentAppState extends AbstractComponentAppState
{
    public function initialize(array &$state): void
    {
        $routingManager = RoutingManagerFacade::getInstance();
        $modulefilter_manager = ModuleFilterManagerFacade::getInstance();
        $fieldQueryInterpreter = FieldQueryInterpreterFacade::getInstance();
        /** @var ComponentConfiguration */
        $componentConfiguration = App::getComponent(Component::class)->getConfiguration();

        $state['nature'] = $routingManager->getCurrentNature();
        $state['route'] = $routingManager->getCurrentRoute();
        $state['output'] = Request::getOutput();
        $state['modulefilter'] = $modulefilter_manager->getSelectedModuleFilterName();
        $state['actionpath'] = Request::getActionPath();
        $state['dataoutputitems'] = Request::getDataOutputItems();
        $state['datasources'] = Request::getDataSourceSelector();
        $state['datastructure'] = Request::getDataStructure();
        $state['dataoutputmode'] = Request::getDataOutputMode();
        $state['dboutputmode'] = Request::getDBOutputMode();
        $state['mangled'] = Request::getMangledValue();
        $state['actions'] = Request::getActions();
        $state['scheme'] = Request::getScheme();
        
        // By default, get the variables from the request
        $state['variables'] = $fieldQueryInterpreter->getVariablesFromRequest();
        $state['only-fieldname-as-outputkey'] = false;
        $state['namespace-types-and-interfaces'] = $componentConfiguration->mustNamespaceTypes();
        $state['version-constraint'] = Request::getVersionConstraint();
        $state['field-version-constraints'] = Request::getVersionConstraintsForFields();
        $state['directive-version-constraints'] = Request::getVersionConstraintsForDirectives();
        
        // By default, mutations are always enabled. Can be changed for the API
        $state['are-mutations-enabled'] = true;

        // Set the routing state (eg: PoP Queried Object can add its information)
        $state['routing-state'] = [];
    }

    public function augment(array &$state): void
    {
        $nature = $state['nature'];
        $state['routing-state']['is-standard'] = $nature === RouteNatures::STANDARD;
        $state['routing-state']['is-home'] = $nature === RouteNatures::HOME;
        $state['routing-state']['is-404'] = $nature === RouteNatures::NOTFOUND;
    }
}

<?php

declare(strict_types=1);

namespace PoP\ComponentModel\State;

use PoP\ComponentModel\Component;
use PoP\ComponentModel\ComponentConfiguration;
use PoP\ComponentModel\Configuration\Request;
use PoP\ComponentModel\Facades\ModuleFiltering\ModuleFilterManagerFacade;
use PoP\ComponentModel\Facades\Schema\FieldQueryInterpreterFacade;
use PoP\Root\App;
use PoP\Root\State\AbstractAppStateProvider;
use PoP\Routing\Facades\RoutingManagerFacade;
use PoP\Routing\RouteNatures;

class AppStateProvider extends AbstractAppStateProvider
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
        $state['modulefilter'] = $modulefilter_manager->getSelectedModuleFilterName();
        $state['variables'] = $fieldQueryInterpreter->getVariablesFromRequest();
        $state['namespace-types-and-interfaces'] = $componentConfiguration->mustNamespaceTypes();
        $state['only-fieldname-as-outputkey'] = false;
        
        $state['output'] = Request::getOutput();
        $state['actionpath'] = Request::getActionPath();
        $state['dataoutputitems'] = Request::getDataOutputItems();
        $state['datasources'] = Request::getDataSourceSelector();
        $state['datastructure'] = Request::getDataStructure();
        $state['dataoutputmode'] = Request::getDataOutputMode();
        $state['dboutputmode'] = Request::getDBOutputMode();
        $state['mangled'] = Request::getMangledValue();
        $state['actions'] = Request::getActions();
        $state['scheme'] = Request::getScheme();
        $state['version-constraint'] = Request::getVersionConstraint();
        $state['field-version-constraints'] = Request::getVersionConstraintsForFields();
        $state['directive-version-constraints'] = Request::getVersionConstraintsForDirectives();

        // By default, mutations are always enabled. Can be changed for the API
        $state['are-mutations-enabled'] = true;

        // Set the routing state (eg: PoP Queried Object can add its information)
        $state['routing'] = [];
    }

    public function augment(array &$state): void
    {
        $nature = $state['nature'];
        $state['routing']['is-standard'] = $nature === RouteNatures::STANDARD;
        $state['routing']['is-home'] = $nature === RouteNatures::HOME;
        $state['routing']['is-404'] = $nature === RouteNatures::NOTFOUND;
    }
}

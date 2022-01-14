<?php

declare(strict_types=1);

namespace PoP\ComponentModel\State;

use PoP\ComponentModel\Component;
use PoP\ComponentModel\ComponentConfiguration;
use PoP\ComponentModel\Configuration\EngineRequest;
use PoP\ComponentModel\Configuration\Request;
use PoP\ComponentModel\ModuleFiltering\ModuleFilterManagerInterface;
use PoP\ComponentModel\Schema\FieldQueryInterpreterInterface;
use PoP\Definitions\Configuration\Request as DefinitionsRequest;
use PoP\Root\App;
use PoP\Root\State\AbstractAppStateProvider;
use PoP\Routing\RouteNatures;
use PoP\Routing\RoutingManagerInterface;

class AppStateProvider extends AbstractAppStateProvider
{
    private ?FieldQueryInterpreterInterface $fieldQueryInterpreter = null;
    private ?ModuleFilterManagerInterface $moduleFilterManager = null;
    private ?RoutingManagerInterface $routingManager = null;

    final public function setFieldQueryInterpreter(FieldQueryInterpreterInterface $fieldQueryInterpreter): void
    {
        $this->fieldQueryInterpreter = $fieldQueryInterpreter;
    }
    final protected function getFieldQueryInterpreter(): FieldQueryInterpreterInterface
    {
        return $this->fieldQueryInterpreter ??= $this->instanceManager->getInstance(FieldQueryInterpreterInterface::class);
    }
    final public function setModuleFilterManager(ModuleFilterManagerInterface $moduleFilterManager): void
    {
        $this->moduleFilterManager = $moduleFilterManager;
    }
    final protected function getModuleFilterManager(): ModuleFilterManagerInterface
    {
        return $this->moduleFilterManager ??= $this->instanceManager->getInstance(ModuleFilterManagerInterface::class);
    }
    final public function setRoutingManager(RoutingManagerInterface $routingManager): void
    {
        $this->routingManager = $routingManager;
    }
    final protected function getRoutingManager(): RoutingManagerInterface
    {
        return $this->routingManager ??= $this->instanceManager->getInstance(RoutingManagerInterface::class);
    }
    
    public function initialize(array &$state): void
    {
        /** @var ComponentConfiguration */
        $componentConfiguration = App::getComponent(Component::class)->getConfiguration();
        $state['namespace-types-and-interfaces'] = $componentConfiguration->mustNamespaceTypes();

        $state['nature'] = $this->getRoutingManager()->getCurrentNature();
        $state['route'] = $this->getRoutingManager()->getCurrentRoute();
        $state['modulefilter'] = $this->getModuleFilterManager()->getSelectedModuleFilterName();
        $state['variables'] = $this->getFieldQueryInterpreter()->getVariablesFromRequest();
        $state['only-fieldname-as-outputkey'] = false;
        $state['are-mutations-enabled'] = true;

        $state['mangled'] = DefinitionsRequest::getMangledValue();
        $state['actionpath'] = Request::getActionPath();
        $state['actions'] = Request::getActions();
        $state['version-constraint'] = Request::getVersionConstraint();
        $state['field-version-constraints'] = Request::getVersionConstraintsForFields();
        $state['directive-version-constraints'] = Request::getVersionConstraintsForDirectives();

        $enableModifyingEngineBehaviorViaRequestParams = $componentConfiguration->enableModifyingEngineBehaviorViaRequestParams();
        $state['output'] = EngineRequest::getOutput($enableModifyingEngineBehaviorViaRequestParams);
        $state['dataoutputitems'] = EngineRequest::getDataOutputItems($enableModifyingEngineBehaviorViaRequestParams);
        $state['datasourceselector'] = EngineRequest::getDataSourceSelector($enableModifyingEngineBehaviorViaRequestParams);
        $state['datastructure'] = EngineRequest::getDataStructure($enableModifyingEngineBehaviorViaRequestParams);
        $state['dataoutputmode'] = EngineRequest::getDataOutputMode($enableModifyingEngineBehaviorViaRequestParams);
        $state['dboutputmode'] = EngineRequest::getDBOutputMode($enableModifyingEngineBehaviorViaRequestParams);
        $state['scheme'] = EngineRequest::getScheme($enableModifyingEngineBehaviorViaRequestParams);

        // Set the routing state under a unified entry
        $state['routing'] = [];
    }

    public function augment(array &$state): void
    {
        $nature = $state['nature'];
        $state['routing']['is-standard'] = $nature === RouteNatures::GENERIC;
        $state['routing']['is-home'] = $nature === RouteNatures::HOME;
        $state['routing']['is-404'] = $nature === RouteNatures::NOTFOUND;
    }
}

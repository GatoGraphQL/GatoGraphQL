<?php

declare(strict_types=1);

namespace PoP\ComponentModel\State;

use PoP\ComponentModel\Module;
use PoP\ComponentModel\ModuleConfiguration;
use PoP\ComponentModel\Configuration\EngineRequest;
use PoP\ComponentModel\Configuration\Request;
use PoP\ComponentModel\ModuleFiltering\ModuleFilterManagerInterface;
use PoP\ComponentModel\Schema\FieldQueryInterpreterInterface;
use PoP\Definitions\Configuration\Request as DefinitionsRequest;
use PoP\Definitions\Constants\ParamValues;
use PoP\Root\App;
use PoP\Root\Module as RootModule;
use PoP\Root\ModuleConfiguration as RootModuleConfiguration;
use PoP\Root\State\AbstractAppStateProvider;

class AppStateProvider extends AbstractAppStateProvider
{
    private ?FieldQueryInterpreterInterface $fieldQueryInterpreter = null;
    private ?ModuleFilterManagerInterface $moduleFilterManager = null;

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

    public function initialize(array &$state): void
    {
        /** @var ModuleConfiguration */
        $moduleConfiguration = App::getComponent(Module::class)->getConfiguration();
        $state['namespace-types-and-interfaces'] = $moduleConfiguration->mustNamespaceTypes();
        $state['are-mutations-enabled'] = $moduleConfiguration->enableMutations();

        $state['only-fieldname-as-outputkey'] = false;

        $state['modulefilter'] = $this->getModuleFilterManager()->getSelectedModuleFilterName();
        $state['variables'] = $this->getFieldQueryInterpreter()->getVariablesFromRequest();

        /** @var RootModuleConfiguration */
        $rootModuleConfiguration = App::getComponent(RootModule::class)->getConfiguration();
        if ($rootModuleConfiguration->enablePassingStateViaRequest()) {
            $state['mangled'] = DefinitionsRequest::getMangledValue();
            $state['actionpath'] = Request::getActionPath();
            $state['actions'] = Request::getActions();
            $state['version-constraint'] = Request::getVersionConstraint();
            $state['field-version-constraints'] = Request::getVersionConstraintsForFields();
            $state['directive-version-constraints'] = Request::getVersionConstraintsForDirectives();
        } else {
            $state['mangled'] = ParamValues::MANGLED_NONE;
            $state['actionpath'] = null;
            $state['actions'] = [];
            $state['version-constraint'] = null;
            $state['field-version-constraints'] = null;
            $state['directive-version-constraints'] = null;
        }

        $enableModifyingEngineBehaviorViaRequest = $moduleConfiguration->enableModifyingEngineBehaviorViaRequest();
        $state['output'] = EngineRequest::getOutput($enableModifyingEngineBehaviorViaRequest);
        $state['dataoutputitems'] = EngineRequest::getDataOutputItems($enableModifyingEngineBehaviorViaRequest);
        $state['datasourceselector'] = EngineRequest::getDataSourceSelector($enableModifyingEngineBehaviorViaRequest);
        $state['datastructure'] = EngineRequest::getDataStructure($enableModifyingEngineBehaviorViaRequest);
        $state['dataoutputmode'] = EngineRequest::getDataOutputMode($enableModifyingEngineBehaviorViaRequest);
        $state['dboutputmode'] = EngineRequest::getDBOutputMode($enableModifyingEngineBehaviorViaRequest);
        $state['scheme'] = EngineRequest::getScheme($enableModifyingEngineBehaviorViaRequest);
    }
}

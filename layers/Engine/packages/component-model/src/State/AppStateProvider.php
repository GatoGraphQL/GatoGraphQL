<?php

declare(strict_types=1);

namespace PoP\ComponentModel\State;

use PoP\ComponentModel\ComponentFiltering\ComponentFilterManagerInterface;
use PoP\ComponentModel\Configuration\EngineRequest;
use PoP\ComponentModel\Configuration\Request;
use PoP\ComponentModel\Engine\EngineInterface;
use PoP\ComponentModel\Module;
use PoP\ComponentModel\ModuleConfiguration;
use PoP\ComponentModel\Variables\VariableManagerInterface;
use PoP\Definitions\Configuration\Request as DefinitionsRequest;
use PoP\Definitions\Constants\ParamValues;
use PoP\Root\App;
use PoP\Root\Module as RootModule;
use PoP\Root\ModuleConfiguration as RootModuleConfiguration;
use PoP\Root\State\AbstractAppStateProvider;

class AppStateProvider extends AbstractAppStateProvider
{
    private ?VariableManagerInterface $fieldQueryInterpreter = null;
    private ?ComponentFilterManagerInterface $componentFilterManager = null;
    private ?EngineInterface $engine = null;

    final public function setVariableManager(VariableManagerInterface $fieldQueryInterpreter): void
    {
        $this->fieldQueryInterpreter = $fieldQueryInterpreter;
    }
    final protected function getVariableManager(): VariableManagerInterface
    {
        return $this->fieldQueryInterpreter ??= $this->instanceManager->getInstance(VariableManagerInterface::class);
    }
    final public function setComponentFilterManager(ComponentFilterManagerInterface $componentFilterManager): void
    {
        $this->componentFilterManager = $componentFilterManager;
    }
    final protected function getComponentFilterManager(): ComponentFilterManagerInterface
    {
        return $this->componentFilterManager ??= $this->instanceManager->getInstance(ComponentFilterManagerInterface::class);
    }
    final public function setEngine(EngineInterface $engine): void
    {
        $this->engine = $engine;
    }
    final protected function getEngine(): EngineInterface
    {
        return $this->engine ??= $this->instanceManager->getInstance(EngineInterface::class);
    }

    public function initialize(array &$state): void
    {
        $state['componentFilter'] = $this->getComponentFilterManager()->getSelectedComponentFilterName();
        $state['variables'] = $this->getVariableManager()->getVariablesFromRequest();

        /**
         * Dynamic variables are those generated on runtime
         * when resolving the GraphQL query, eg: via @export
         */
        $state['document-dynamic-variables'] = [];

        /** @var RootModuleConfiguration */
        $rootModuleConfiguration = App::getModule(RootModule::class)->getConfiguration();
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

        /** @var ModuleConfiguration */
        $moduleConfiguration = App::getModule(Module::class)->getConfiguration();
        $enableModifyingEngineBehaviorViaRequest = $moduleConfiguration->enableModifyingEngineBehaviorViaRequest();
        $state['output'] = EngineRequest::getOutput($enableModifyingEngineBehaviorViaRequest);
        $state['dataoutputitems'] = EngineRequest::getDataOutputItems($enableModifyingEngineBehaviorViaRequest);
        $state['datasourceselector'] = EngineRequest::getDataSourceSelector($enableModifyingEngineBehaviorViaRequest);
        $state['datastructure'] = EngineRequest::getDataStructure($enableModifyingEngineBehaviorViaRequest);
        $state['dataoutputmode'] = EngineRequest::getDataOutputMode($enableModifyingEngineBehaviorViaRequest);
        $state['dboutputmode'] = EngineRequest::getDBOutputMode($enableModifyingEngineBehaviorViaRequest);
        $state['scheme'] = EngineRequest::getScheme($enableModifyingEngineBehaviorViaRequest);
    }

    /**
     * Must initialize the Engine state before parsing the GraphQL query in:
     *
     * @see layers/API/packages/api/src/State/AppStateProvider.php
     *
     * Otherwise, if there's an error (eg: empty query), it throws
     * an exception when adding it to the FeedbackStore.     *
     *
     * Call ModuleConfiguration only after hooks from
     * SchemaConfigurationExecuter have been initialized.
     * That's why these are called on `execute` and not `initialize`.
     */
    public function execute(array &$state): void
    {
        /** @var ModuleConfiguration */
        $moduleConfiguration = App::getModule(Module::class)->getConfiguration();
        $state['namespace-types-and-interfaces'] = $moduleConfiguration->mustNamespaceTypes();
        $state['are-mutations-enabled'] = $moduleConfiguration->enableMutations();

        $this->getEngine()->initializeState();
    }
}

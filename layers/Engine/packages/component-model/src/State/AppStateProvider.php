<?php

declare(strict_types=1);

namespace PoP\ComponentModel\State;

use PoP\ComponentModel\App;
use PoP\ComponentModel\ComponentFiltering\ComponentFilterManagerInterface;
use PoP\ComponentModel\Configuration\EngineRequest;
use PoP\ComponentModel\Configuration\Request;
use PoP\ComponentModel\Engine\EngineInterface;
use PoP\ComponentModel\Module;
use PoP\ComponentModel\ModuleConfiguration;
use PoP\ComponentModel\Variables\VariableManagerInterface;
use PoP\Definitions\Configuration\Request as DefinitionsRequest;
use PoP\Definitions\Constants\ParamValues;
use PoP\GraphQLParser\Spec\Parser\Ast\AstInterface;
use PoP\GraphQLParser\Spec\Parser\Ast\FieldInterface;
use PoP\Root\Module as RootModule;
use PoP\Root\ModuleConfiguration as RootModuleConfiguration;
use PoP\Root\State\AbstractAppStateProvider;
use SplObjectStorage;

class AppStateProvider extends AbstractAppStateProvider
{
    private ?VariableManagerInterface $fieldQueryInterpreter = null;
    private ?ComponentFilterManagerInterface $componentFilterManager = null;
    private ?EngineInterface $engine = null;

    final protected function getVariableManager(): VariableManagerInterface
    {
        if ($this->fieldQueryInterpreter === null) {
            /** @var VariableManagerInterface */
            $fieldQueryInterpreter = $this->instanceManager->getInstance(VariableManagerInterface::class);
            $this->fieldQueryInterpreter = $fieldQueryInterpreter;
        }
        return $this->fieldQueryInterpreter;
    }
    final protected function getComponentFilterManager(): ComponentFilterManagerInterface
    {
        if ($this->componentFilterManager === null) {
            /** @var ComponentFilterManagerInterface */
            $componentFilterManager = $this->instanceManager->getInstance(ComponentFilterManagerInterface::class);
            $this->componentFilterManager = $componentFilterManager;
        }
        return $this->componentFilterManager;
    }
    final protected function getEngine(): EngineInterface
    {
        if ($this->engine === null) {
            /** @var EngineInterface */
            $engine = $this->instanceManager->getInstance(EngineInterface::class);
            $this->engine = $engine;
        }
        return $this->engine;
    }

    /**
     * @param array<string,mixed> $state
     */
    public function initialize(array &$state): void
    {
        // For Serialization
        /** @var SplObjectStorage<FieldInterface,int|null> */
        $fieldTypeModifiersForSerialization = new SplObjectStorage();
        $state['field-type-modifiers-for-serialization'] = $fieldTypeModifiersForSerialization;

        // For Validating if the Directive supports only certain types
        $state['field-type-resolver-for-supported-directive-resolution'] = null;

        // Show a warning when providing a duplicate variable name to `@export` or similar
        $state['show-warnings-on-exporting-duplicate-dynamic-variable-name'] = true;

        $state['componentFilter'] = $this->getComponentFilterManager()->getSelectedComponentFilterName();
        $state['variables'] = $this->getVariableManager()->getVariablesFromRequest();

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

        /**
         * These one will be filled at the API level, but they are
         * created at this level since they are referenced at the
         * Component Model too.
         *
         * These initialization objects will not be actually ever referenced,
         * as the API level will replace these objects, but instantiate them
         * anyway so that the logic has no flaws (eg: PHPStan validation.)
         *
         * @see layers/API/packages/api/src/State/AppStateProvider.php
         *
         * @var SplObjectStorage<AstInterface,AstInterface>
         */
        $documentASTNodeAncestors = new SplObjectStorage();
        $state['document-ast-node-ancestors'] = $documentASTNodeAncestors;
        /** @var FieldInterface[] */
        $documentObjectResolvedFieldValueReferencedFields = [];
        $state['document-object-resolved-field-value-referenced-fields'] = $documentObjectResolvedFieldValueReferencedFields;
    }

    /**
     * Must initialize the Engine state before parsing the GraphQL query in:
     *
     * @see layers/API/packages/api/src/State/AppStateProvider.php
     *
     * Otherwise, if there's an error (eg: empty query), it throws
     * an exception when adding it to the FeedbackStore.
     *
     * As such, method `generateDataAndPrepareResponse` in Engine will
     * receive variable `$areFeedbackAndTracingStoresAlreadyCreated`
     * as `true`, and it will not initialize these stores again:
     *
     * @see layers/Engine/packages/component-model/src/Engine/Engine.php
     *
     * -------------------------------------------------------
     *
     * Call ModuleConfiguration only after hooks from
     * SchemaConfigurationExecuter have been initialized.
     * That's why these are called on `execute` and not `initialize`.
     * @param array<string,mixed> $state
     */
    public function execute(array &$state): void
    {
        /** @var ModuleConfiguration */
        $moduleConfiguration = App::getModule(Module::class)->getConfiguration();
        $state['namespace-types-and-interfaces'] = $moduleConfiguration->mustNamespaceTypes();

        // Initialize stores to catch initial errors in the GraphQL document
        App::generateAndStackFeedbackStore();
        App::generateAndStackTracingStore();
    }
}

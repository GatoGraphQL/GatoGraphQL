<?php

declare(strict_types=1);

namespace GraphQLByPoP\GraphQLServer\Hooks;

use PoP\Root\Managers\ComponentManager;
use GraphQLByPoP\GraphQLServer\Component;
use GraphQLByPoP\GraphQLServer\ComponentConfiguration;
use GraphQLByPoP\GraphQLServer\Configuration\Request;
use PoP\API\Response\Schemes as APISchemes;
use PoP\ComponentModel\ModelInstance\ModelInstance;
use PoP\ComponentModel\State\ApplicationState;
use PoP\GraphQLAPI\DataStructureFormatters\GraphQLDataStructureFormatter;
use PoP\BasicService\AbstractHookSet;

class VarsHookSet extends AbstractHookSet
{
    private ?GraphQLDataStructureFormatter $graphQLDataStructureFormatter = null;

    final public function setGraphQLDataStructureFormatter(GraphQLDataStructureFormatter $graphQLDataStructureFormatter): void
    {
        $this->graphQLDataStructureFormatter = $graphQLDataStructureFormatter;
    }
    final protected function getGraphQLDataStructureFormatter(): GraphQLDataStructureFormatter
    {
        return $this->graphQLDataStructureFormatter ??= $this->instanceManager->getInstance(GraphQLDataStructureFormatter::class);
    }

    protected function init(): void
    {
        $this->getHooksAPI()->addAction(
            'ApplicationState:addVars',
            array($this, 'addVars'),
            10,
            1
        );
        $this->getHooksAPI()->addAction(
            'augmentVarsProperties',
            [$this, 'augmentVarsProperties'],
            10,
            1
        );
        $this->getHooksAPI()->addFilter(
            ModelInstance::HOOK_COMPONENTS_RESULT,
            array($this, 'getModelInstanceComponentsFromVars')
        );
    }

    /**
     * Check if to use nested mutations from the GraphQL server config
     * @param array<array> $vars_in_array
     */
    public function augmentVarsProperties(array $vars_in_array): void
    {
        /** @var ComponentConfiguration */
        $componentConfiguration = ComponentManager::getComponent(Component::class)->getConfiguration();

        // The PQL always has nested mutations enabled. Only the for the standard GraphQL server
        [&$vars] = $vars_in_array;
        $vars['nested-mutations-enabled'] = $vars['standard-graphql'] ?
            $componentConfiguration->enableNestedMutations()
            : true;
        // Check if the value has been defined by configuration. If so, use it.
        // Otherwise, use the defaults:
        // By default, Standard GraphQL has introspection enabled, and PQL is not
        $enableGraphQLIntrospection = $componentConfiguration->enableGraphQLIntrospection();
        $vars['graphql-introspection-enabled'] = $enableGraphQLIntrospection !== null ?
            $enableGraphQLIntrospection
            : $vars['standard-graphql'];
    }

    /**
     * @param array<array> $vars_in_array
     */
    public function addVars(array $vars_in_array): void
    {
        [&$vars] = $vars_in_array;
        if ($vars['scheme'] == APISchemes::API && $vars['datastructure'] == $this->getGraphQLDataStructureFormatter()->getName()) {
            $vars['edit-schema'] = Request::editSchema();
        }
    }

    public function getModelInstanceComponentsFromVars($components)
    {
        $vars = ApplicationState::getVars();
        if (isset($vars['edit-schema'])) {
            $components[] = $this->getTranslationAPI()->__('edit schema:', 'graphql-server') . $vars['edit-schema'];
        }
        if ($graphQLOperationType = $vars['graphql-operation-type'] ?? null) {
            $components[] = $this->getTranslationAPI()->__('GraphQL operation type:', 'graphql-server') . $graphQLOperationType;
        }
        $components[] = $this->getTranslationAPI()->__('enable nested mutations:', 'graphql-server') . $vars['nested-mutations-enabled'];
        $components[] = $this->getTranslationAPI()->__('enable GraphQL introspection:', 'graphql-server') . $vars['graphql-introspection-enabled'];

        return $components;
    }
}

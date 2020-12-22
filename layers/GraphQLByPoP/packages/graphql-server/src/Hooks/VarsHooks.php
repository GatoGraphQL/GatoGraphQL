<?php

declare(strict_types=1);

namespace GraphQLByPoP\GraphQLServer\Hooks;

use GraphQLByPoP\GraphQLServer\ComponentConfiguration;
use GraphQLByPoP\GraphQLServer\Configuration\Request;
use PoP\Hooks\AbstractHookSet;
use PoP\ComponentModel\State\ApplicationState;
use PoP\Translation\Facades\TranslationAPIFacade;
use PoP\ComponentModel\ModelInstance\ModelInstance;
use PoP\GraphQLAPI\DataStructureFormatters\GraphQLDataStructureFormatter;
use PoP\API\Response\Schemes as APISchemes;

class VarsHooks extends AbstractHookSet
{
    protected function init()
    {
        $this->hooksAPI->addAction(
            'ApplicationState:addVars',
            array($this, 'addVars'),
            10,
            1
        );
        $this->hooksAPI->addAction(
            'augmentVarsProperties',
            [$this, 'augmentVarsProperties'],
            10,
            1
        );
        $this->hooksAPI->addFilter(
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
        // The PQL always has nested mutations enabled. Only the for the standard GraphQL server
        [&$vars] = $vars_in_array;
        $vars['nested-mutations-enabled'] = $vars['standard-graphql'] ?
            ComponentConfiguration::enableNestedMutations()
            : true;
        // Check if the value has been defined by configuration. If so, use it.
        // Otherwise, use the defaults:
        // By default, Standard GraphQL has introspection enabled, and PQL is not
        $enableGraphQLIntrospection = ComponentConfiguration::enableGraphQLIntrospection();
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
        if ($vars['scheme'] == APISchemes::API && $vars['datastructure'] == GraphQLDataStructureFormatter::getName()) {
            $vars['edit-schema'] = Request::editSchema();
        }
    }

    public function getModelInstanceComponentsFromVars($components)
    {
        $translationAPI = TranslationAPIFacade::getInstance();
        $vars = ApplicationState::getVars();
        if (isset($vars['edit-schema'])) {
            $components[] = $translationAPI->__('edit schema:', 'graphql-server') . $vars['edit-schema'];
        }
        if ($graphQLOperationType = $vars['graphql-operation-type'] ?? null) {
            $components[] = $translationAPI->__('GraphQL operation type:', 'graphql-server') . $graphQLOperationType;
        }
        $components[] = $translationAPI->__('enable nested mutations:', 'graphql-server') . $vars['nested-mutations-enabled'];
        $components[] = $translationAPI->__('enable GraphQL introspection:', 'graphql-server') . $vars['graphql-introspection-enabled'];

        return $components;
    }
}

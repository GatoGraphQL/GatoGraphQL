<?php

declare(strict_types=1);

namespace GraphQLByPoP\GraphQLServer\Hooks;

use PoP\Root\App;
use PoP\ComponentModel\ModelInstance\ModelInstance;
use PoP\Root\Hooks\AbstractHookSet;

class VarsHookSet extends AbstractHookSet
{
    protected function init(): void
    {
        App::addFilter(
            ModelInstance::HOOK_COMPONENTS_RESULT,
            $this->getModelInstanceComponentsFromAppState(...)
        );
    }

    public function getModelInstanceComponentsFromAppState($components)
    {
        $components[] = $this->__('edit schema:', 'graphql-server') . App::getState('edit-schema');
        if ($graphQLOperationType = App::getState('graphql-operation-type')) {
            $components[] = $this->__('GraphQL operation type:', 'graphql-server') . $graphQLOperationType;
        }
        $components[] = $this->__('enable nested mutations:', 'graphql-server') . App::getState('nested-mutations-enabled');
        $components[] = $this->__('enable GraphQL introspection:', 'graphql-server') . App::getState('graphql-introspection-enabled');

        return $components;
    }
}

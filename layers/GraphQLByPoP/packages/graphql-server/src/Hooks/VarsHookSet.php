<?php

declare(strict_types=1);

namespace GraphQLByPoP\GraphQLServer\Hooks;

use PoP\ComponentModel\ModelInstance\ModelInstance;
use PoP\ComponentModel\State\ApplicationState;
use PoP\BasicService\AbstractHookSet;

class VarsHookSet extends AbstractHookSet
{
    protected function init(): void
    {
        $this->getHooksAPI()->addFilter(
            ModelInstance::HOOK_COMPONENTS_RESULT,
            array($this, 'getModelInstanceComponentsFromVars')
        );
    }

    public function getModelInstanceComponentsFromVars($components)
    {
        if (isset(\PoP\Root\App::getState('edit-schema'))) {
            $components[] = $this->__('edit schema:', 'graphql-server') . \PoP\Root\App::getState('edit-schema');
        }
        if ($graphQLOperationType = \PoP\Root\App::getState('graphql-operation-type')) {
            $components[] = $this->__('GraphQL operation type:', 'graphql-server') . $graphQLOperationType;
        }
        $components[] = $this->__('enable nested mutations:', 'graphql-server') . \PoP\Root\App::getState('nested-mutations-enabled');
        $components[] = $this->__('enable GraphQL introspection:', 'graphql-server') . \PoP\Root\App::getState('graphql-introspection-enabled');

        return $components;
    }
}

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
        $vars = ApplicationState::getVars();
        if (isset($vars['edit-schema'])) {
            $components[] = $this->__('edit schema:', 'graphql-server') . $vars['edit-schema'];
        }
        if ($graphQLOperationType = $vars['graphql-operation-type'] ?? null) {
            $components[] = $this->__('GraphQL operation type:', 'graphql-server') . $graphQLOperationType;
        }
        $components[] = $this->__('enable nested mutations:', 'graphql-server') . $vars['nested-mutations-enabled'];
        $components[] = $this->__('enable GraphQL introspection:', 'graphql-server') . $vars['graphql-introspection-enabled'];

        return $components;
    }
}

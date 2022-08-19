<?php

declare(strict_types=1);

namespace GraphQLByPoP\GraphQLServer\Hooks;

use GraphQLByPoP\GraphQLServer\Module;
use GraphQLByPoP\GraphQLServer\ModuleConfiguration;
use PoP\ComponentModel\ModelInstance\ModelInstance;
use PoP\Root\App;
use PoP\Root\Hooks\AbstractHookSet;

class VarsHookSet extends AbstractHookSet
{
    protected function init(): void
    {
        App::addFilter(
            ModelInstance::HOOK_ELEMENTS_RESULT,
            $this->getModelInstanceElementsFromAppState(...)
        );
    }

    /**
     * @return string[]
     * @param string[] $elements
     */
    public function getModelInstanceElementsFromAppState(array $elements): array
    {
        $elements[] = $this->__('edit schema:', 'graphql-server') . App::getState('edit-schema');
        if ($graphQLOperationType = App::getState('graphql-operation-type')) {
            $elements[] = $this->__('GraphQL operation type:', 'graphql-server') . $graphQLOperationType;
        }
        /** @var ModuleConfiguration */
        $moduleConfiguration = App::getModule(Module::class)->getConfiguration();
        $elements[] = $this->__('enable nested mutations:', 'graphql-server') . $moduleConfiguration->enableNestedMutations();
        $elements[] = $this->__('enable GraphQL introspection:', 'graphql-server') . App::getState('graphql-introspection-enabled');

        return $elements;
    }
}

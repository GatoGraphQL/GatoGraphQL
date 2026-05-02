<?php

declare(strict_types=1);

namespace PoPCMSSchema\CustomPostMutations\ConditionalOnModule\Users\SchemaHooks;

use PoP\ComponentModel\Component\Component;
use PoP\Root\App;
use PoP\Root\Hooks\AbstractHookSet;
use PoPCMSSchema\Users\ConditionalOnModule\CustomPosts\SchemaHooks\FilterInputHookSet as UserCustomPostFilterInputHookSet;

abstract class AbstractRemoveAuthorFilterInputHookSet extends AbstractHookSet
{
    private ?UserCustomPostFilterInputHookSet $userCustomPostFilterInputHookSet = null;

    final protected function getUserCustomPostFilterInputHookSet(): UserCustomPostFilterInputHookSet
    {
        if ($this->userCustomPostFilterInputHookSet === null) {
            /** @var UserCustomPostFilterInputHookSet */
            $userCustomPostFilterInputHookSet = $this->instanceManager->getInstance(UserCustomPostFilterInputHookSet::class);
            $this->userCustomPostFilterInputHookSet = $userCustomPostFilterInputHookSet;
        }
        return $this->userCustomPostFilterInputHookSet;
    }

    protected function init(): void
    {
        App::addFilter(
            $this->getHookNameToRemoveFilterInput(),
            $this->getFilterInputComponents(...)
        );
    }

    abstract protected function getHookNameToRemoveFilterInput(): string;

    /**
     * Remove author fieldArgs from field "myCustomPosts"
     *
     * @param Component[] $filterInputComponents
     * @return Component[]
     */
    public function getFilterInputComponents(array $filterInputComponents): array
    {
        $components = $this->getUserCustomPostFilterInputHookSet()->getAuthorFilterInputComponents();
        if ($components === []) {
            return $filterInputComponents;
        }
        /**
         * `Component` is a `final readonly` value object: equality is by
         * property value, not identity. Build a value-key set of components
         * to remove, then filter `$filterInputComponents` in one O(N+M)
         * pass instead of N × `array_search` (each O(M)) plus N `array_splice`.
         *
         * @var array<string,true>
         */
        $removeComponentKeys = [];
        foreach ($components as $component) {
            $removeComponentKeys[$component->processorClass . '|' . $component->name . '|' . serialize($component->atts)] = true;
        }
        return array_values(array_filter(
            $filterInputComponents,
            static fn (Component $c): bool => !isset($removeComponentKeys[$c->processorClass . '|' . $c->name . '|' . serialize($c->atts)])
        ));
    }
}

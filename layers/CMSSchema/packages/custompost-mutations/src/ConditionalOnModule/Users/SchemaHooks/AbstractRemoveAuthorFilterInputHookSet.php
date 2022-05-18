<?php

declare(strict_types=1);

namespace PoPCMSSchema\CustomPostMutations\ConditionalOnModule\Users\SchemaHooks;

use PoP\Root\App;
use PoP\Root\Hooks\AbstractHookSet;
use PoPCMSSchema\Users\ConditionalOnModule\CustomPosts\SchemaHooks\FilterInputHookSet as UserCustomPostFilterInputHookSet;

abstract class AbstractRemoveAuthorFilterInputHookSet extends AbstractHookSet
{
    private ?UserCustomPostFilterInputHookSet $userCustomPostFilterInputHookSet = null;

    final public function setUserCustomPostFilterInputHookSet(UserCustomPostFilterInputHookSet $userCustomPostFilterInputHookSet): void
    {
        $this->userCustomPostFilterInputHookSet = $userCustomPostFilterInputHookSet;
    }
    final protected function getUserCustomPostFilterInputHookSet(): UserCustomPostFilterInputHookSet
    {
        return $this->userCustomPostFilterInputHookSet ??= $this->instanceManager->getInstance(UserCustomPostFilterInputHookSet::class);
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
     */
    public function getFilterInputComponents(array $filterInputModules): array
    {
        $components = $this->getUserCustomPostFilterInputHookSet()->getAuthorFilterInputComponents();
        foreach ($components as $component) {
            $pos = array_search($component, $filterInputModules);
            if ($pos !== false) {
                array_splice($filterInputModules, $pos, 1);
            }
        }
        return $filterInputModules;
    }
}

<?php

declare(strict_types=1);

namespace PoPSchema\CustomPostMutations\ConditionalOnComponent\Users\SchemaHooks;

use PoP\Root\App;
use PoP\Root\Hooks\AbstractHookSet;
use PoPSchema\Users\ConditionalOnComponent\CustomPosts\SchemaHooks\FilterInputHookSet as UserCustomPostFilterInputHookSet;

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
        App::getHookManager()->addFilter(
            $this->getHookNameToRemoveFilterInput(),
            [$this, 'getFilterInputModules']
        );
    }

    abstract protected function getHookNameToRemoveFilterInput(): string;

    /**
     * Remove author fieldArgs from field "myCustomPosts"
     */
    public function getFilterInputModules(array $filterInputModules): array
    {
        $modules = $this->getUserCustomPostFilterInputHookSet()->getAuthorFilterInputModules();
        foreach ($modules as $module) {
            $pos = array_search($module, $filterInputModules);
            if ($pos !== false) {
                array_splice($filterInputModules, $pos, 1);
            }
        }
        return $filterInputModules;
    }
}

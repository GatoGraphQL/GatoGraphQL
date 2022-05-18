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
            $this->getFilterInputComponentVariations(...)
        );
    }

    abstract protected function getHookNameToRemoveFilterInput(): string;

    /**
     * Remove author fieldArgs from field "myCustomPosts"
     */
    public function getFilterInputComponentVariations(array $filterInputModules): array
    {
        $componentVariations = $this->getUserCustomPostFilterInputHookSet()->getAuthorFilterInputComponentVariations();
        foreach ($componentVariations as $componentVariation) {
            $pos = array_search($componentVariation, $filterInputModules);
            if ($pos !== false) {
                array_splice($filterInputModules, $pos, 1);
            }
        }
        return $filterInputModules;
    }
}

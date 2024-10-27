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
        foreach ($components as $component) {
            $pos = array_search($component, $filterInputComponents);
            if ($pos === false) {
                continue;
            }
            /** @var int $pos */
            array_splice($filterInputComponents, $pos, 1);
        }
        return $filterInputComponents;
    }
}

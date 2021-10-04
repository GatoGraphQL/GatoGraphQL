<?php

declare(strict_types=1);

namespace PoPSchema\CustomPostMutations\ConditionalOnComponent\Users\SchemaHooks;

use PoP\Hooks\AbstractHookSet;
use PoPSchema\Users\ConditionalOnComponent\CustomPosts\SchemaHooks\FilterInputHookSet as UserCustomPostFilterInputHookSet;
use Symfony\Contracts\Service\Attribute\Required;

abstract class AbstractRemoveAuthorFilterInputHookSet extends AbstractHookSet
{
    protected UserCustomPostFilterInputHookSet $userCustomPostFilterInputHookSet;

    #[Required]
    final public function autowireAbstractRemoveAuthorFilterInputHookSet(
        UserCustomPostFilterInputHookSet $userCustomPostFilterInputHookSet,
    ): void {
        $this->userCustomPostFilterInputHookSet = $userCustomPostFilterInputHookSet;
    }

    protected function init(): void
    {
        $this->hooksAPI->addFilter(
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
        $modules = $this->userCustomPostFilterInputHookSet->getAuthorFilterInputModules();
        foreach ($modules as $module) {
            $pos = array_search($module, $filterInputModules);
            if ($pos !== false) {
                array_splice($filterInputModules, $pos, 1);
            }
        }
        return $filterInputModules;
    }
}

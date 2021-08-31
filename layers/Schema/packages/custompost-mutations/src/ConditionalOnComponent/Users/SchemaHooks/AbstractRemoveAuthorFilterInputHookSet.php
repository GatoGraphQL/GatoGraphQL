<?php

declare(strict_types=1);

namespace PoPSchema\CustomPostMutations\ConditionalOnComponent\Users\SchemaHooks;

use PoP\ComponentModel\Instances\InstanceManagerInterface;
use PoP\Hooks\AbstractHookSet;
use PoP\Hooks\HooksAPIInterface;
use PoP\Translation\TranslationAPIInterface;
use PoPSchema\Users\ConditionalOnComponent\CustomPosts\SchemaHooks\FilterInputHookSet as UserCustomPostFilterInputHookSet;

abstract class AbstractRemoveAuthorFilterInputHookSet extends AbstractHookSet
{
    public function __construct(
        HooksAPIInterface $hooksAPI,
        TranslationAPIInterface $translationAPI,
        InstanceManagerInterface $instanceManager,
        protected UserCustomPostFilterInputHookSet $userCustomPostFilterInputHookSet,
    ) {
        parent::__construct(
            $hooksAPI,
            $translationAPI,
            $instanceManager,
        );
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

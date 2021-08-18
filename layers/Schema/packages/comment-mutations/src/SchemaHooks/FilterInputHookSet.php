<?php

declare(strict_types=1);

namespace PoPSchema\CommentMutations\SchemaHooks;

use PoP\ComponentModel\Instances\InstanceManagerInterface;
use PoP\Hooks\AbstractHookSet;
use PoP\Hooks\HooksAPIInterface;
use PoP\Translation\TranslationAPIInterface;
use PoPSchema\CommentMutations\ModuleProcessors\CommentFilterInputContainerModuleProcessor;
use PoPSchema\Comments\ConditionalOnComponent\Users\SchemaHooks\FilterInputHookSet as UserCustomPostFilterInputHookSet;

class FilterInputHookSet extends AbstractHookSet
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
            CommentFilterInputContainerModuleProcessor::HOOK_FILTER_INPUTS,
            [$this, 'getFilterInputModules']
        );
    }

    /**
     * Remove author fieldArgs from field "myComments"
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

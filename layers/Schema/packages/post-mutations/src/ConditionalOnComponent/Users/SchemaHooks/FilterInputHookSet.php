<?php

declare(strict_types=1);

namespace PoPSchema\PostMutations\ConditionalOnComponent\Users\SchemaHooks;

use PoP\Hooks\AbstractHookSet;
use PoPSchema\PostMutations\ModuleProcessors\PostMutationFilterInputContainerModuleProcessor;
use PoPSchema\Users\ConditionalOnComponent\CustomPosts\ModuleProcessors\FormInputs\FilterInputModuleProcessor;

class FilterInputHookSet extends AbstractHookSet
{

    protected function init(): void
    {
        $this->hooksAPI->addFilter(
            PostMutationFilterInputContainerModuleProcessor::HOOK_FILTER_INPUTS,
            [$this, 'getFilterInputModules']
        );
    }

    /**
     * Remove argument "author-ids" from field "myPosts"
     */
    public function getFilterInputModules(array $filterInputModules): array
    {
        $module = [
            FilterInputModuleProcessor::class,
            FilterInputModuleProcessor::MODULE_FILTERINPUT_AUTHOR_IDS
        ];
        $pos = array_search($module, $filterInputModules);
        if ($pos !== false) {
            array_splice($filterInputModules, $pos, 1);
        }
        return $filterInputModules;
    }
}

<?php

declare(strict_types=1);

namespace PoPSchema\PostMutations\ConditionalOnComponent\Users\SchemaHooks;

use PoPSchema\CustomPostMutations\ConditionalOnComponent\Users\SchemaHooks\AbstractRemoveAuthorFilterInputHookSet;
use PoPSchema\PostMutations\ModuleProcessors\PostMutationFilterInputContainerModuleProcessor;

class RemoveAuthorFilterInputHookSet extends AbstractRemoveAuthorFilterInputHookSet
{
    protected function getHookNameToRemoveFilterInput(): string
    {
        return PostMutationFilterInputContainerModuleProcessor::HOOK_FILTER_INPUTS;
    }
}

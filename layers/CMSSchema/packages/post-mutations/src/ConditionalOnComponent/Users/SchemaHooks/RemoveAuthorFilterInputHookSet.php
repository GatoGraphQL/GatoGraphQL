<?php

declare(strict_types=1);

namespace PoPCMSSchema\PostMutations\ConditionalOnComponent\Users\SchemaHooks;

use PoPCMSSchema\CustomPostMutations\ConditionalOnComponent\Users\SchemaHooks\AbstractRemoveAuthorFilterInputHookSet;
use PoPCMSSchema\PostMutations\ModuleProcessors\PostMutationFilterInputContainerModuleProcessor;

class RemoveAuthorFilterInputHookSet extends AbstractRemoveAuthorFilterInputHookSet
{
    protected function getHookNameToRemoveFilterInput(): string
    {
        return PostMutationFilterInputContainerModuleProcessor::HOOK_FILTER_INPUTS;
    }
}

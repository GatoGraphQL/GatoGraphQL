<?php

declare(strict_types=1);

namespace PoPCMSSchema\PostMutations\ConditionalOnModule\Users\SchemaHooks;

use PoPCMSSchema\CustomPostMutations\ConditionalOnModule\Users\SchemaHooks\AbstractRemoveAuthorFilterInputHookSet;
use PoPCMSSchema\PostMutations\ModuleProcessors\PostMutationFilterInputContainerModuleProcessor;

class RemoveAuthorFilterInputHookSet extends AbstractRemoveAuthorFilterInputHookSet
{
    protected function getHookNameToRemoveFilterInput(): string
    {
        return PostMutationFilterInputContainerModuleProcessor::HOOK_FILTER_INPUTS;
    }
}

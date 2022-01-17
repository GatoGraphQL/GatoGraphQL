<?php

declare(strict_types=1);

namespace PoPSchema\CustomPostMutations\ConditionalOnComponent\Users\SchemaHooks;

use PoPSchema\CustomPostMutations\ModuleProcessors\CustomPostMutationFilterInputContainerModuleProcessor;

class RemoveAuthorFilterInputHookSet extends AbstractRemoveAuthorFilterInputHookSet
{
    protected function getHookNameToRemoveFilterInput(): string
    {
        return CustomPostMutationFilterInputContainerModuleProcessor::HOOK_FILTER_INPUTS;
    }
}

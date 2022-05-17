<?php

declare(strict_types=1);

namespace PoPCMSSchema\CustomPostMutations\ConditionalOnModule\Users\SchemaHooks;

use PoPCMSSchema\CustomPostMutations\ModuleProcessors\CustomPostMutationFilterInputContainerModuleProcessor;

class RemoveAuthorFilterInputHookSet extends AbstractRemoveAuthorFilterInputHookSet
{
    protected function getHookNameToRemoveFilterInput(): string
    {
        return CustomPostMutationFilterInputContainerModuleProcessor::HOOK_FILTER_INPUTS;
    }
}

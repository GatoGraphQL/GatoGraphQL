<?php

declare(strict_types=1);

namespace PoPCMSSchema\CommentMutations\SchemaHooks;

use PoPCMSSchema\CommentMutations\ModuleProcessors\CommentFilterInputContainerModuleProcessor;
use PoPCMSSchema\CustomPostMutations\ConditionalOnComponent\Users\SchemaHooks\AbstractRemoveAuthorFilterInputHookSet;

class RemoveAuthorFilterInputHookSet extends AbstractRemoveAuthorFilterInputHookSet
{
    protected function getHookNameToRemoveFilterInput(): string
    {
        return CommentFilterInputContainerModuleProcessor::HOOK_FILTER_INPUTS;
    }
}

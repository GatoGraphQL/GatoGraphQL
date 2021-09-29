<?php

declare(strict_types=1);

namespace PoPSchema\CommentMutations\SchemaHooks;

use PoPSchema\CommentMutations\ModuleProcessors\CommentFilterInputContainerModuleProcessor;
use PoPSchema\CustomPostMutations\ConditionalOnComponent\Users\SchemaHooks\AbstractRemoveAuthorFilterInputHookSet;

class RemoveAuthorFilterInputHookSet extends AbstractRemoveAuthorFilterInputHookSet
{
    protected function getHookNameToRemoveFilterInput(): string
    {
        return CommentFilterInputContainerModuleProcessor::HOOK_FILTER_INPUTS;
    }
}

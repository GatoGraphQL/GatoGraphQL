<?php

declare(strict_types=1);

namespace PoPSchema\CommentMutations\SchemaHooks;

use PoPSchema\CustomPostMutations\ConditionalOnComponent\Users\SchemaHooks\AbstractRemoveAuthorFilterInputHookSet;
use PoPSchema\CommentMutations\ModuleProcessors\CommentFilterInputContainerModuleProcessor;

class RemoveAuthorFilterInputHookSet extends AbstractRemoveAuthorFilterInputHookSet
{
    protected function getHookNameToRemoveFilterInput(): string
    {
        return CommentFilterInputContainerModuleProcessor::HOOK_FILTER_INPUTS;
    }
}

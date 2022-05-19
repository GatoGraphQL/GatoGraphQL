<?php

declare(strict_types=1);

namespace PoPCMSSchema\CommentMutations\SchemaHooks;

use PoPCMSSchema\CommentMutations\ComponentProcessors\CommentFilterInputContainerComponentProcessor;
use PoPCMSSchema\CustomPostMutations\ConditionalOnModule\Users\SchemaHooks\AbstractRemoveAuthorFilterInputHookSet;

class RemoveAuthorFilterInputHookSet extends AbstractRemoveAuthorFilterInputHookSet
{
    protected function getHookNameToRemoveFilterInput(): string
    {
        return CommentFilterInputContainerComponentProcessor::HOOK_FILTER_INPUTS;
    }
}

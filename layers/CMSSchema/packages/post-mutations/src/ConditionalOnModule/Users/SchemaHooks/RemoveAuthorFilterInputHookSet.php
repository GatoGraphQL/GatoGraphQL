<?php

declare(strict_types=1);

namespace PoPCMSSchema\PostMutations\ConditionalOnModule\Users\SchemaHooks;

use PoPCMSSchema\CustomPostMutations\ConditionalOnModule\Users\SchemaHooks\AbstractRemoveAuthorFilterInputHookSet;
use PoPCMSSchema\PostMutations\ComponentProcessors\PostMutationFilterInputContainerComponentProcessor;

class RemoveAuthorFilterInputHookSet extends AbstractRemoveAuthorFilterInputHookSet
{
    protected function getHookNameToRemoveFilterInput(): string
    {
        return PostMutationFilterInputContainerComponentProcessor::HOOK_FILTER_INPUTS;
    }
}

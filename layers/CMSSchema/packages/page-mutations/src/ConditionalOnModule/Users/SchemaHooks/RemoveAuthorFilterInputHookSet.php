<?php

declare(strict_types=1);

namespace PoPCMSSchema\PageMutations\ConditionalOnModule\Users\SchemaHooks;

use PoPCMSSchema\CustomPostMutations\ConditionalOnModule\Users\SchemaHooks\AbstractRemoveAuthorFilterInputHookSet;
use PoPCMSSchema\PageMutations\ComponentProcessors\PageMutationFilterInputContainerComponentProcessor;

class RemoveAuthorFilterInputHookSet extends AbstractRemoveAuthorFilterInputHookSet
{
    protected function getHookNameToRemoveFilterInput(): string
    {
        return PageMutationFilterInputContainerComponentProcessor::HOOK_FILTER_INPUTS;
    }
}

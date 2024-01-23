<?php

declare(strict_types=1);

namespace PoPCMSSchema\MediaMutations\SchemaHooks;

use PoPCMSSchema\CustomPostMutations\ConditionalOnModule\Users\SchemaHooks\AbstractRemoveAuthorFilterInputHookSet;
use PoPCMSSchema\MediaMutations\ComponentProcessors\MyMediaFilterInputContainerComponentProcessor;

class RemoveAuthorFilterInputHookSet extends AbstractRemoveAuthorFilterInputHookSet
{
    protected function getHookNameToRemoveFilterInput(): string
    {
        return MyMediaFilterInputContainerComponentProcessor::HOOK_FILTER_INPUTS;
    }
}

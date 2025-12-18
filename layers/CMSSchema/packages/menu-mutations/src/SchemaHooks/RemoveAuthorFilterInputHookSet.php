<?php

declare(strict_types=1);

namespace PoPCMSSchema\MenuMutations\SchemaHooks;

use PoPCMSSchema\CustomPostMutations\ConditionalOnModule\Users\SchemaHooks\AbstractRemoveAuthorFilterInputHookSet;
use PoPCMSSchema\MenuMutations\ComponentProcessors\MyMenuFilterInputContainerComponentProcessor;

class RemoveAuthorFilterInputHookSet extends AbstractRemoveAuthorFilterInputHookSet
{
    protected function getHookNameToRemoveFilterInput(): string
    {
        return MyMenuFilterInputContainerComponentProcessor::HOOK_FILTER_INPUTS;
    }
}

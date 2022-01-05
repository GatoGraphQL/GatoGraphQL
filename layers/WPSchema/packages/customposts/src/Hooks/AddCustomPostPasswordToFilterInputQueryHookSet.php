<?php

declare(strict_types=1);

namespace PoPWPSchema\CustomPosts\Hooks;

use PoPSchema\CustomPostsWP\TypeAPIs\CustomPostTypeAPI;

class AddCustomPostPasswordToFilterInputQueryHookSet extends AbstractAddCustomPostPasswordToFilterInputQueryHookSet
{
    protected function getHookName(): string
    {
        return CustomPostTypeAPI::HOOK_QUERY;
    }
}

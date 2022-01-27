<?php

declare(strict_types=1);

namespace PoPWPSchema\Posts\Hooks;

use PoPCMSSchema\PostsWP\TypeAPIs\PostTypeAPI;
use PoPWPSchema\CustomPosts\Hooks\AbstractAddCustomPostPasswordToFilterInputQueryHookSet;

class AddPostPasswordToFilterInputQueryHookSet extends AbstractAddCustomPostPasswordToFilterInputQueryHookSet
{
    protected function getHookName(): string
    {
        return PostTypeAPI::HOOK_QUERY;
    }
}

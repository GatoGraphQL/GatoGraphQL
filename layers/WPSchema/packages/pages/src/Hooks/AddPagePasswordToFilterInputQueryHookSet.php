<?php

declare(strict_types=1);

namespace PoPWPSchema\Pages\Hooks;

use PoPCMSSchema\PagesWP\TypeAPIs\PageTypeAPI;
use PoPWPSchema\CustomPosts\Hooks\AbstractAddCustomPostPasswordToFilterInputQueryHookSet;

class AddPagePasswordToFilterInputQueryHookSet extends AbstractAddCustomPostPasswordToFilterInputQueryHookSet
{
    protected function getHookName(): string
    {
        return PageTypeAPI::HOOK_QUERY;
    }
}

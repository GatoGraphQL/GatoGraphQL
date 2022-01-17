<?php

declare(strict_types=1);

namespace PoPWPSchema\CustomPostMeta\Hooks;

use PoPCMSSchema\CustomPostsWP\TypeAPIs\AbstractCustomPostTypeAPI;
use PoPWPSchema\Meta\Hooks\AbstractMetaOrderByQueryHookSet;

class CustomPostMetaOrderByQueryHookSet extends AbstractMetaOrderByQueryHookSet
{
    protected function getHookName(): string
    {
        return AbstractCustomPostTypeAPI::HOOK_ORDERBY_QUERY_ARG_VALUE;
    }
}

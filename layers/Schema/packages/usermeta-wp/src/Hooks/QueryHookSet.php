<?php

declare(strict_types=1);

namespace PoPSchema\UserMetaWP\Hooks;

use PoP\Hooks\AbstractHookSet;
use PoPSchema\MetaQueryWP\Helpers\MetaQueryHelpers;

class QueryHookSet extends AbstractHookSet
{
    protected function init(): void
    {
        $this->hooksAPI->addAction(
            'CMSAPI:users:query',
            [MetaQueryHelpers::class, 'convertMetaQuery']
        );
    }
}

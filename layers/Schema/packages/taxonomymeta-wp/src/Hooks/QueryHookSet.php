<?php

declare(strict_types=1);

namespace PoPSchema\TaxonomyMetaWP\Hooks;

use PoP\Hooks\AbstractHookSet;

class QueryHookSet extends AbstractHookSet
{
    protected function init(): void
    {
        $this->hooksAPI->addAction(
            'CMSAPI:taxonomies:query',
            [MetaQueryHelpers::class, 'convertMetaQuery']
        );
    }
}

<?php

declare(strict_types=1);

namespace PoPSchema\TaxonomyMetaWP\Hooks;

use PoP\BasicService\AbstractHookSet;
use PoPSchema\MetaQueryWP\Helpers\MetaQueryHelpers;
use PoPSchema\TaxonomiesWP\TypeAPIs\TaxonomyTypeAPI;

class QueryHookSet extends AbstractHookSet
{
    protected function init(): void
    {
        $this->getHooksAPI()->addFilter(
            TaxonomyTypeAPI::HOOK_QUERY,
            [MetaQueryHelpers::class, 'convertMetaQuery']
        );
    }
}

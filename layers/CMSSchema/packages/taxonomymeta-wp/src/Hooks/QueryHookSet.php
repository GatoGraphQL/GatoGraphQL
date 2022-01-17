<?php

declare(strict_types=1);

namespace PoPCMSSchema\TaxonomyMetaWP\Hooks;

use PoP\Root\App;
use PoP\Root\Hooks\AbstractHookSet;
use PoPCMSSchema\MetaQueryWP\Helpers\MetaQueryHelpers;
use PoPCMSSchema\TaxonomiesWP\TypeAPIs\TaxonomyTypeAPI;

class QueryHookSet extends AbstractHookSet
{
    protected function init(): void
    {
        App::addFilter(
            TaxonomyTypeAPI::HOOK_QUERY,
            [MetaQueryHelpers::class, 'convertMetaQuery']
        );
    }
}

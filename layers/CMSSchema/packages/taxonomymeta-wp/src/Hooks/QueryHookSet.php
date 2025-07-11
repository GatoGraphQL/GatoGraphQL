<?php

declare(strict_types=1);

namespace PoPCMSSchema\TaxonomyMetaWP\Hooks;

use PoP\Root\App;
use PoP\Root\Hooks\AbstractHookSet;
use PoPCMSSchema\MetaQueryWP\Helpers\MetaQueryHelpers;
use PoPCMSSchema\TaxonomiesWP\TypeAPIs\AbstractTaxonomyOrTaxonomyTermTypeAPI;

class QueryHookSet extends AbstractHookSet
{
    protected function init(): void
    {
        App::addFilter(
            AbstractTaxonomyOrTaxonomyTermTypeAPI::HOOK_QUERY,
            MetaQueryHelpers::convertMetaQuery(...)
        );
    }
}

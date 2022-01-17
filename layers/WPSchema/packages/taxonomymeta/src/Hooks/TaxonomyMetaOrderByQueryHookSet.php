<?php

declare(strict_types=1);

namespace PoPWPSchema\TaxonomyMeta\Hooks;

use PoPCMSSchema\TaxonomiesWP\TypeAPIs\TaxonomyTypeAPI;
use PoPWPSchema\Meta\Hooks\AbstractMetaOrderByQueryHookSet;

class TaxonomyMetaOrderByQueryHookSet extends AbstractMetaOrderByQueryHookSet
{
    protected function getHookName(): string
    {
        return TaxonomyTypeAPI::HOOK_ORDERBY_QUERY_ARG_VALUE;
    }
}

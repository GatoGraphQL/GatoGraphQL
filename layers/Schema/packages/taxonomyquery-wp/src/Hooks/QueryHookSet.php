<?php

declare(strict_types=1);

namespace PoPSchema\TaxonomyQueryWP\Hooks;

use PoP\Root\App;
use PoP\Root\Hooks\AbstractHookSet;
use PoPSchema\CustomPostsWP\TypeAPIs\AbstractCustomPostTypeAPI;
use PoPSchema\TaxonomyQueryWP\Helpers\TaxonomyQueryHelpers;

class QueryHookSet extends AbstractHookSet
{
    protected function init(): void
    {
        App::addFilter(
            AbstractCustomPostTypeAPI::HOOK_QUERY,
            [TaxonomyQueryHelpers::class, 'convertTaxonomyQuery']
        );
    }
}

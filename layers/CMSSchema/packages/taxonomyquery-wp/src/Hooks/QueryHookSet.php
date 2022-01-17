<?php

declare(strict_types=1);

namespace PoPCMSSchema\TaxonomyQueryWP\Hooks;

use PoP\Root\App;
use PoP\Root\Hooks\AbstractHookSet;
use PoPCMSSchema\CustomPostsWP\TypeAPIs\AbstractCustomPostTypeAPI;
use PoPCMSSchema\TaxonomyQueryWP\Helpers\TaxonomyQueryHelpers;

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

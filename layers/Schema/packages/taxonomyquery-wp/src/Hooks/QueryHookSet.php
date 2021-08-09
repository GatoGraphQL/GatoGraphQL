<?php

declare(strict_types=1);

namespace PoPSchema\TaxonomyQueryWP\Hooks;

use PoP\Hooks\AbstractHookSet;
use PoPSchema\CustomPostsWP\TypeAPIs\CustomPostTypeAPI;
use PoPSchema\TaxonomyQueryWP\Helpers\TaxonomyQueryHelpers;

class QueryHookSet extends AbstractHookSet
{
    protected function init(): void
    {
        $this->hooksAPI->addFilter(
            CustomPostTypeAPI::HOOK_QUERY,
            [TaxonomyQueryHelpers::class, 'convertTaxonomyQuery']
        );
    }
}

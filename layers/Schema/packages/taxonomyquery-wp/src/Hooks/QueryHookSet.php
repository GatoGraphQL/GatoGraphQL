<?php

declare(strict_types=1);

namespace PoPSchema\TaxonomyQueryWP\Hooks;

use PoP\Hooks\AbstractHookSet;
use PoPSchema\TaxonomyQueryWP\Helpers\TaxonomyQueryHelpers;

class QueryHookSet extends AbstractHookSet
{
    protected function init(): void
    {
        $this->hooksAPI->addAction(
            'CMSAPI:customposts:query',
            [TaxonomyQueryHelpers::class, 'convertTaxonomyQuery']
        );
    }
}

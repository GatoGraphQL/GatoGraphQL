<?php

declare(strict_types=1);

namespace PoPSchema\PagesWP\LooseContracts;

use PoP\Root\App;
use PoP\LooseContracts\AbstractLooseContractResolutionSet;

class LooseContractResolutionSet extends AbstractLooseContractResolutionSet
{
    protected function resolveContracts(): void
    {
        // Filters.
        App::addFilter('the_title', function ($post_title, $post_id) {
            $post_type = get_post_type($post_id);
            if ($post_type == 'page') {
                return App::applyFilters('popcms:page:title', $post_title, $post_id);
            }
            return $post_title;
        }, 10, 2);

        $this->getLooseContractManager()->implementHooks([
            'popcms:page:title',
            'popcms:page:content',
        ]);

        $this->getNameResolver()->implementNames([
            'popcms:dbcolumn:orderby:pages:date' => 'date',
        ]);
    }
}

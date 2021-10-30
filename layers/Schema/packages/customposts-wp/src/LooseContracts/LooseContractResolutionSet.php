<?php

declare(strict_types=1);

namespace PoPSchema\CustomPostsWP\LooseContracts;

use PoP\LooseContracts\AbstractLooseContractResolutionSet;

class LooseContractResolutionSet extends AbstractLooseContractResolutionSet
{
    protected function resolveContracts(): void
    {
        // Filters.
        $this->getHooksAPI()->addFilter('the_title', function ($post_title, $post_id) {
            return $this->getHooksAPI()->applyFilters('popcms:post:title', $post_title, $post_id);
        }, 10, 2);
        $this->getHooksAPI()->addFilter('excerpt_more', function ($text) {
            return $this->getHooksAPI()->applyFilters('popcms:excerptMore', $text);
        }, 10, 1);

        $this->getLooseContractManager()->implementHooks([
            'popcms:post:title',
            'popcms:excerptMore',
        ]);

        $this->getNameResolver()->implementNames([
            'popcms:dbcolumn:orderby:customposts:date' => 'date',
            'popcms:dbcolumn:orderby:customposts:modified' => 'modified',
            'popcms:dbcolumn:orderby:customposts:id' => 'ID',
        ]);
    }
}

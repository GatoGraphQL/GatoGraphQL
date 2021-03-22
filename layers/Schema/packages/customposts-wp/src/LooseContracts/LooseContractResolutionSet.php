<?php

declare(strict_types=1);

namespace PoPSchema\CustomPostsWP\LooseContracts;

use PoP\LooseContracts\AbstractLooseContractResolutionSet;

class LooseContractResolutionSet extends AbstractLooseContractResolutionSet
{
    protected function resolveContracts(): void
    {
        // Filters.
        $this->hooksAPI->addFilter('the_title', function ($post_title, $post_id) {
            return $this->hooksAPI->applyFilters('popcms:post:title', $post_title, $post_id);
        }, 10, 2);
        $this->hooksAPI->addFilter('excerpt_more', function ($text) {
            return $this->hooksAPI->applyFilters('popcms:excerptMore', $text);
        }, 10, 1);

        $this->looseContractManager->implementHooks([
            'popcms:post:title',
            'popcms:excerptMore',
        ]);

        $this->nameResolver->implementNames([
            'popcms:dbcolumn:orderby:customposts:date' => 'date',
            'popcms:dbcolumn:orderby:customposts:modified' => 'modified',
            'popcms:dbcolumn:orderby:customposts:id' => 'ID',
        ]);
    }
}

<?php

declare(strict_types=1);

namespace PoPSchema\CustomPostsWP\LooseContracts;

use PoP\LooseContracts\AbstractLooseContractResolutionSet;

class LooseContractResolutionSet extends AbstractLooseContractResolutionSet
{
    protected function resolveContracts(): void
    {
        // Filters.
        \PoP\Root\App::getHookManager()->addFilter('the_title', function ($post_title, $post_id) {
            return \PoP\Root\App::getHookManager()->applyFilters('popcms:post:title', $post_title, $post_id);
        }, 10, 2);
        \PoP\Root\App::getHookManager()->addFilter('excerpt_more', function ($text) {
            return \PoP\Root\App::getHookManager()->applyFilters('popcms:excerptMore', $text);
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

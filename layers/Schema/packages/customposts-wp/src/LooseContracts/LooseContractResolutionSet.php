<?php

declare(strict_types=1);

namespace PoPSchema\CustomPostsWP\LooseContracts;

use PoP\Root\App;
use PoP\LooseContracts\AbstractLooseContractResolutionSet;

class LooseContractResolutionSet extends AbstractLooseContractResolutionSet
{
    protected function resolveContracts(): void
    {
        // Filters.
        App::getHookManager()->addFilter('the_title', function ($post_title, $post_id) {
            return App::getHookManager()->applyFilters('popcms:post:title', $post_title, $post_id);
        }, 10, 2);
        App::getHookManager()->addFilter('excerpt_more', function ($text) {
            return App::getHookManager()->applyFilters('popcms:excerptMore', $text);
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

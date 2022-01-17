<?php

declare(strict_types=1);

namespace PoPSchema\PostCategoriesWP\Hooks;

use PoPSchema\CustomPostCategoriesWP\Hooks\AbstractCustomPostCategoryQueryHookSet;

class PostCategoryQueryHookSet extends AbstractCustomPostCategoryQueryHookSet
{
    protected function getCategoryTaxonomy(): string
    {
        return 'category';
    }
}

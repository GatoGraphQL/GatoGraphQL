<?php

declare(strict_types=1);

namespace PoPCMSSchema\PostCategoriesWP\Hooks;

use PoPCMSSchema\CustomPostCategoriesWP\Hooks\AbstractCustomPostCategoryQueryHookSet;

class PostCategoryQueryHookSet extends AbstractCustomPostCategoryQueryHookSet
{
    protected function getCategoryTaxonomy(): string
    {
        return 'category';
    }
}

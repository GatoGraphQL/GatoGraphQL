<?php

declare(strict_types=1);

namespace PoPCMSSchema\PostCategoriesWP\TypeAPIs;

use PoPCMSSchema\CategoriesWP\TypeAPIs\AbstractCategoryTypeAPI;
use PoPCMSSchema\PostCategories\TypeAPIs\PostCategoryTypeAPIInterface;

/**
 * Methods to interact with the Type, to be implemented by the underlying CMS
 */
class PostCategoryTypeAPI extends AbstractCategoryTypeAPI implements PostCategoryTypeAPIInterface
{
    /**
     * The taxonomy name representing a post category ("category")
     */
    public function getPostCategoryTaxonomyName(): string
    {
        return 'category';
    }

    protected function getCategoryTaxonomyName(): string
    {
        return $this->getPostCategoryTaxonomyName();
    }
}

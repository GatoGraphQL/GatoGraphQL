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
     * Indicates if the passed object is of type Category
     */
    public function isInstanceOfPostCategoryType(object $object): bool
    {
        return $this->isInstanceOfPostCategoryType($object) && $object->name = $this->getPostCategoryTaxonomyName();
    }

    /**
     * The taxonomy name representing a post category ("category")
     */
    public function getPostCategoryTaxonomyName(): string
    {
        return 'category';
    }

    public function getCategoryTaxonomyName(): string
    {
        return $this->getPostCategoryTaxonomyName();
    }

    protected function getCategoryBaseOption(): string
    {
        return 'category_base';
    }
}

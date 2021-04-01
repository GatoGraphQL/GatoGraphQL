<?php

declare(strict_types=1);

namespace PoPSchema\PostCategories\TypeAPIs;

use PoPSchema\Categories\TypeAPIs\CategoryTypeAPIInterface;

/**
 * Methods to interact with the Type, to be implemented by the underlying CMS
 */
interface PostCategoryTypeAPIInterface extends CategoryTypeAPIInterface
{
    /**
     * Indicates if the passed object is of type PostCategory
     */
    public function isInstanceOfPostCategoryType(object $object): bool;

    /**
     * The taxonomy name representing a post category ("category")
     */
    public function getPostCategoryTaxonomyName(): string;
}

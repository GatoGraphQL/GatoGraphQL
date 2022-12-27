<?php

declare(strict_types=1);

namespace PoPCMSSchema\PostCategories\TypeAPIs;

use PoPCMSSchema\Categories\TypeAPIs\CategoryListTypeAPIInterface;
use PoPCMSSchema\Categories\TypeAPIs\CategoryTypeAPIInterface;

/**
 * Methods to interact with the Type, to be implemented by the underlying CMS
 */
interface PostCategoryTypeAPIInterface extends CategoryTypeAPIInterface, CategoryListTypeAPIInterface
{
    /**
     * The taxonomy name representing a post category ("category")
     */
    public function getPostCategoryTaxonomyName(): string;
}

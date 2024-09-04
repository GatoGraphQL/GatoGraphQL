<?php

declare(strict_types=1);

namespace PoPCMSSchema\PostCategories\TypeAPIs;

use PoPCMSSchema\Categories\TypeAPIs\CategoryTypeAPIInterface;

/**
 * Methods to interact with the Type, to be implemented by the underlying CMS
 */
interface PostCategoryTypeAPIInterface extends CategoryTypeAPIInterface
{
    /**
     * The taxonomy name representing a post category ("category")
     */
    public function getPostCategoryTaxonomyName(): string;
    /**
     * Return all the category taxonomies registered for the "post"
     * custom post type, that have also been selected as "queryable"
     * in the plugin settings.
     *
     * @return string[]
     */
    public function getRegisteredPostCategoryTaxonomyNames(): array;
}

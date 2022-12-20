<?php

declare(strict_types=1);

namespace EverythingElse\PoPCMSSchema\Categories\TypeAPIs;

use PoPCMSSchema\Taxonomies\TypeAPIs\TaxonomyTypeAPIInterface;

/**
 * Methods to interact with the Type, to be implemented by the underlying CMS
 */
interface CategoryTypeAPIInterface extends TaxonomyTypeAPIInterface
{
    public function getCategoryBase(): string;
    public function hasCategory($catObjectOrID, $post_id);
    public function getCategoryPath($category_id);
    /**
     * @param mixed[] $categories
     */
    public function setPostCategories(string|int $post_id, array $categories, bool $append = false);
}

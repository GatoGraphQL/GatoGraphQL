<?php

declare(strict_types=1);

namespace EverythingElse\PoPCMSSchema\Categories\TypeAPIs;

use PoPCMSSchema\Categories\TypeAPIs\CategoryTypeAPIInterface as UpstreamCategoryTypeAPIInterface;

interface CategoryTypeAPIInterface extends UpstreamCategoryTypeAPIInterface
{
    public function getCategoryBase(): string;
    public function hasCategory($catObjectOrID, $post_id);
    public function getCategoryPath($category_id);
    /**
     * @param mixed[] $categories
     */
    public function setPostCategories(string|int $post_id, array $categories, bool $append = false);
}

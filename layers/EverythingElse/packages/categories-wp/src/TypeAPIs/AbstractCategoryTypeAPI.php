<?php

declare(strict_types=1);

namespace PoPSchema\CategoriesWP\TypeAPIs;

use PoPSchema\Categories\TypeAPIs\CategoryTypeAPIInterface;
use PoPSchema\TaxonomiesWP\TypeAPIs\TaxonomyTypeAPI;

/**
 * Methods to interact with the Type, to be implemented by the underlying CMS
 */
abstract class AbstractCategoryTypeAPI extends TaxonomyTypeAPI implements CategoryTypeAPIInterface
{
    public function hasCategory($cat_id, $post_id)
    {
        return has_category($cat_id, $post_id);
    }
}

<?php

declare(strict_types=1);

namespace PoPSchema\CategoriesWP\TypeAPIs;

use WP_Taxonomy;
use PoPSchema\Categories\TypeAPIs\CategoryTypeAPIInterface;
use PoPSchema\TaxonomiesWP\TypeAPIs\TaxonomyTypeAPI;

/**
 * Methods to interact with the Type, to be implemented by the underlying CMS
 */
class CategoryTypeAPI extends TaxonomyTypeAPI implements CategoryTypeAPIInterface
{
    /**
     * Indicates if the passed object is of type Category
     *
     * @param [type] $object
     */
    public function isInstanceOfCategoryType($object): bool
    {
        return ($object instanceof WP_Taxonomy) && $object->hierarchical == true;
    }
}

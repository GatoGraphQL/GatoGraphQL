<?php

declare(strict_types=1);

namespace PoPSchema\Categories\TypeAPIs;

use PoPSchema\Taxonomies\TypeAPIs\TaxonomyTypeAPIInterface;

/**
 * Methods to interact with the Type, to be implemented by the underlying CMS
 */
interface CategoryTypeAPIInterface extends TaxonomyTypeAPIInterface
{
    /**
     * Indicates if the passed object is of type Category
     *
     * @param [type] $object
     * @return boolean
     */
    public function isInstanceOfCategoryType($object): bool;
}

<?php

declare(strict_types=1);

namespace PoPSchema\Tags\TypeAPIs;

use PoPSchema\Taxonomies\TypeAPIs\TaxonomyTypeAPIInterface;

/**
 * Methods to interact with the Type, to be implemented by the underlying CMS
 */
interface TagTypeAPIInterface extends TaxonomyTypeAPIInterface
{
    /**
     * Indicates if the passed object is of type Tag
     *
     * @param [type] $object
     * @return boolean
     */
    public function isInstanceOfTagType($object): bool;
}

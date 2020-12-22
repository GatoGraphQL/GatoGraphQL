<?php

declare(strict_types=1);

namespace PoPSchema\TagsWP\TypeAPIs;

use WP_Taxonomy;
use PoPSchema\Tags\TypeAPIs\TagTypeAPIInterface;
use PoPSchema\TaxonomiesWP\TypeAPIs\TaxonomyTypeAPI;

/**
 * Methods to interact with the Type, to be implemented by the underlying CMS
 */
class TagTypeAPI extends TaxonomyTypeAPI implements TagTypeAPIInterface
{
    /**
     * Indicates if the passed object is of type Tag
     *
     * @param [type] $object
     * @return boolean
     */
    public function isInstanceOfTagType($object): bool
    {
        return ($object instanceof WP_Taxonomy) && $object->hierarchical == false;
    }
}

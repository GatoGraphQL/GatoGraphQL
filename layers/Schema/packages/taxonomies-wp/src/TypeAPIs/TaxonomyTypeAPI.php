<?php

declare(strict_types=1);

namespace PoPSchema\TaxonomiesWP\TypeAPIs;

use PoPSchema\Taxonomies\TypeAPIs\TaxonomyTypeAPIInterface;

/**
 * Methods to interact with the Type, to be implemented by the underlying CMS
 */
class TaxonomyTypeAPI implements TaxonomyTypeAPIInterface
{
    protected function getTermObjectAndID($termObjectOrID): array
    {
        return TaxonomyTypeAPIHelpers::getTermObjectAndID($termObjectOrID);
    }
    /**
     * Retrieves the taxonomy name of the object ("post_tag", "category", etc)
     *
     * @param [type] $object
     * @return string
     */
    public function getTermTaxonomyName($termObjectOrID): string
    {
        list(
            $termObject,
            $termObjectID,
        ) = $this->getTermObjectAndID($termObjectOrID);
        return $termObject->taxonomy;
    }
}

<?php

declare(strict_types=1);

namespace PoPSchema\TaxonomiesWP\TypeAPIs;

use PoP\ComponentModel\TypeDataResolvers\InjectedFilterDataloadingModuleTypeDataResolverTrait;
use PoPSchema\Taxonomies\TypeAPIs\TaxonomyTypeAPIInterface;

/**
 * Methods to interact with the Type, to be implemented by the underlying CMS
 */
class TaxonomyTypeAPI implements TaxonomyTypeAPIInterface
{
    use InjectedFilterDataloadingModuleTypeDataResolverTrait;

    protected function getTermObjectAndID(string | int | object $termObjectOrID): array
    {
        if (is_object($termObjectOrID)) {
            $termObject = $termObjectOrID;
            $termObjectID = $termObject->ID;
        } else {
            $termObjectID = $termObjectOrID;
            $termObject = \get_term($termObjectID);
        }
        return [
            $termObject,
            $termObjectID,
        ];
    }
    /**
     * Retrieves the taxonomy name of the object ("post_tag", "category", etc)
     */
    public function getTermTaxonomyName(string | int | object $termObjectOrID): string
    {
        list(
            $termObject,
            $termObjectID,
        ) = $this->getTermObjectAndID($termObjectOrID);
        return $termObject->taxonomy;
    }
}

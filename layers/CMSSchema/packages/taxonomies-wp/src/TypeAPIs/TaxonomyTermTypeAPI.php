<?php

declare(strict_types=1);

namespace PoPCMSSchema\TaxonomiesWP\TypeAPIs;

use PoPCMSSchema\Taxonomies\TypeAPIs\TaxonomyTermTypeAPIInterface;
use PoP\Root\Services\BasicServiceTrait;
use WP_Term;

class TaxonomyTermTypeAPI implements TaxonomyTermTypeAPIInterface
{
    use BasicServiceTrait;
    use TaxonomyTermTypeAPITrait;

    /**
     * Retrieves the taxonomy name of the object ("post_tag", "category", etc)
     */
    public function getTermTaxonomyName(string|int|object $termObjectOrID): ?string
    {
        if (is_object($termObjectOrID)) {
            /** @var WP_Term */
            $termObject = $termObjectOrID;
            return $termObject->taxonomy;
        }
        $termObjectID = $termObjectOrID;
        $termObject = $this->getTerm($termObjectID);
        if ($termObject === null) {
            return null;
        }
        return $termObject->taxonomy;
    }
}

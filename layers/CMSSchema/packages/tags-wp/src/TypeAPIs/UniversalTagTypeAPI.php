<?php

declare(strict_types=1);

namespace PoPCMSSchema\TagsWP\TypeAPIs;

use PoPCMSSchema\Tags\TypeAPIs\UniversalTagTypeAPIInterface;
use PoPCMSSchema\TaxonomiesWP\TypeAPIs\AbstractTaxonomyTypeAPI;
use WP_Term;

class UniversalTagTypeAPI extends AbstractTaxonomyTypeAPI implements UniversalTagTypeAPIInterface
{
    protected function isHierarchical(): bool
    {
        return false;
    }

    public function getTagName(string|int|object $tagObjectOrID): ?string
    {
        /** @var string|int|WP_Term $tagObjectOrID */
        return $this->getTaxonomyTermName($tagObjectOrID);
    }

    public function getTagURL(string|int|object $tagObjectOrID): ?string
    {
        /** @var string|int|WP_Term $tagObjectOrID */
        return $this->getTaxonomyTermURL($tagObjectOrID);
    }

    public function getTagURLPath(string|int|object $tagObjectOrID): ?string
    {
        /** @var string|int|WP_Term $tagObjectOrID */
        return $this->getTaxonomyTermURLPath($tagObjectOrID);
    }

    public function getTagSlug(string|int|object $tagObjectOrID): ?string
    {
        /** @var string|int|WP_Term $tagObjectOrID */
        return $this->getTaxonomyTermSlug($tagObjectOrID);
    }

    public function getTagDescription(string|int|object $tagObjectOrID): ?string
    {
        /** @var string|int|WP_Term $tagObjectOrID */
        return $this->getTaxonomyTermDescription($tagObjectOrID);
    }

    public function getTagItemCount(string|int|object $tagObjectOrID): ?int
    {
        /** @var string|int|WP_Term $tagObjectOrID */
        return $this->getTaxonomyTermItemCount($tagObjectOrID);
    }
}

<?php

declare(strict_types=1);

namespace PoPCMSSchema\CategoriesWP\TypeAPIs;

use PoPCMSSchema\Categories\TypeAPIs\UniversalCategoryTypeAPIInterface;
use PoPCMSSchema\TaxonomiesWP\TypeAPIs\AbstractTaxonomyTypeAPI;
use WP_Term;

class UniversalCategoryTypeAPI extends AbstractTaxonomyTypeAPI implements UniversalCategoryTypeAPIInterface
{
    protected function isHierarchical(): bool
    {
        return true;
    }

    public function getCategoryURL(string|int|object $catObjectOrID): ?string
    {
        /** @var string|int|WP_Term $catObjectOrID */
        return $this->getTaxonomyTermURL($catObjectOrID);
    }

    public function getCategoryURLPath(string|int|object $catObjectOrID): ?string
    {
        /** @var string|int|WP_Term $catObjectOrID */
        return $this->getTaxonomyTermURLPath($catObjectOrID);
    }

    public function getCategorySlug(string|int|object $catObjectOrID): ?string
    {
        /** @var string|int|WP_Term $catObjectOrID */
        return $this->getTaxonomyTermSlug($catObjectOrID);
    }

    public function getCategorySlugPath(string|int|object $catObjectOrID): ?string
    {
        /** @var string|int|WP_Term $catObjectOrID */
        return $this->getTaxonomyTermSlugPath($catObjectOrID, '');
    }

    public function getCategoryName(string|int|object $catObjectOrID): ?string
    {
        /** @var string|int|WP_Term $catObjectOrID */
        return $this->getTaxonomyTermName($catObjectOrID);
    }

    public function getCategoryParentID(string|int|object $catObjectOrID): string|int|null
    {
        /** @var string|int|WP_Term $catObjectOrID */
        return $this->getTaxonomyTermParentID($catObjectOrID);
    }

    public function getCategoryDescription(string|int|object $catObjectOrID): ?string
    {
        /** @var string|int|WP_Term $catObjectOrID */
        return $this->getTaxonomyTermDescription($catObjectOrID);
    }

    public function getCategoryItemCount(string|int|object $catObjectOrID): ?int
    {
        /** @var string|int|WP_Term $catObjectOrID */
        return $this->getTaxonomyTermItemCount($catObjectOrID);
    }
}

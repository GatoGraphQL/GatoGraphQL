<?php

declare(strict_types=1);

namespace PoPCMSSchema\CategoriesWP\StandaloneTypeAPIs;

use PoPCMSSchema\CategoriesWP\TypeAPIs\AbstractCategoryTypeAPI;

final class InjectableTaxonomyCategoryTypeAPI extends AbstractCategoryTypeAPI
{
    public function __construct(
        protected string $catTaxonomy,
    ) {
    }

    /**
     * @return string[]
     */
    protected function getCategoryTaxonomyNames(): array
    {
        return [
            $this->catTaxonomy,
        ];
    }
}

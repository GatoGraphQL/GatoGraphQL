<?php

declare(strict_types=1);

namespace PoPCMSSchema\CategoriesWP\TypeAPIs;

use PoPCMSSchema\CategoriesWP\StandaloneTypeAPIs\InjectableTaxonomyCategoryTypeAPI;
use PoPCMSSchema\Categories\TypeAPIs\InjectableTaxonomyCategoryListTypeAPIInterface;

class InjectableTaxonomyCategoryListTypeAPI implements InjectableTaxonomyCategoryListTypeAPIInterface
{
    /**
     * @var array<string,InjectableTaxonomyCategoryTypeAPI>
     */
    private array $injectableTaxonomyCategoryTypeAPIs = [];

    protected function getInjectableTaxonomyCategoryTypeAPI(string $catTaxonomy): InjectableTaxonomyCategoryTypeAPI
    {
        if (!isset($this->injectableTaxonomyCategoryTypeAPIs[$catTaxonomy])) {
            $this->injectableTaxonomyCategoryTypeAPIs[$catTaxonomy] = new InjectableTaxonomyCategoryTypeAPI($catTaxonomy);
        }
        return $this->injectableTaxonomyCategoryTypeAPIs[$catTaxonomy];
    }

    /**
     * @return array<string|int>|object[]
     * @param array<string,mixed> $query
     * @param array<string,mixed> $options
     */
    public function getTaxonomyCategories(
        string $catTaxonomy,
        array $query,
        array $options = [],
    ): array {
        return $this->getInjectableTaxonomyCategoryTypeAPI($catTaxonomy)->getCategories(
            $query,
            $options,
        );
    }

    /**
     * @param array<string,mixed> $query
     * @param array<string,mixed> $options
     */
    public function getTaxonomyCategoryCount(
        string $catTaxonomy,
        array $query,
        array $options = [],
    ): int {
        return $this->getInjectableTaxonomyCategoryTypeAPI($catTaxonomy)->getCategoryCount(
            $query,
            $options,
        );
    }
}

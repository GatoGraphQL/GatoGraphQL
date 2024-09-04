<?php

declare(strict_types=1);

namespace PoPCMSSchema\TagsWP\TypeAPIs;

use PoPCMSSchema\TagsWP\StandaloneTypeAPIs\InjectableTaxonomyTagTypeAPI;
use PoPCMSSchema\Tags\TypeAPIs\InjectableTaxonomyTagListTypeAPIInterface;

class InjectableTaxonomyTagListTypeAPI implements InjectableTaxonomyTagListTypeAPIInterface
{
    /**
     * @var array<string,InjectableTaxonomyTagTypeAPI>
     */
    private array $injectableTaxonomyTagTypeAPIs = [];

    protected function getInjectableTaxonomyTagTypeAPI(string $catTaxonomy): InjectableTaxonomyTagTypeAPI
    {
        if (!isset($this->injectableTaxonomyTagTypeAPIs[$catTaxonomy])) {
            $this->injectableTaxonomyTagTypeAPIs[$catTaxonomy] = new InjectableTaxonomyTagTypeAPI($catTaxonomy);
        }
        return $this->injectableTaxonomyTagTypeAPIs[$catTaxonomy];
    }

    /**
     * @return array<string|int>|object[]
     * @param array<string,mixed> $query
     * @param array<string,mixed> $options
     */
    public function getTaxonomyTags(
        string $catTaxonomy,
        array $query,
        array $options = [],
    ): array {
        return $this->getInjectableTaxonomyTagTypeAPI($catTaxonomy)->getTags(
            $query,
            $options,
        );
    }

    /**
     * @param array<string,mixed> $query
     * @param array<string,mixed> $options
     */
    public function getTaxonomyTagCount(
        string $catTaxonomy,
        array $query,
        array $options = [],
    ): int {
        return $this->getInjectableTaxonomyTagTypeAPI($catTaxonomy)->getTagCount(
            $query,
            $options,
        );
    }
}

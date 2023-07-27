<?php

declare(strict_types=1);

namespace PoPCMSSchema\Categories\TypeResolvers\InputObjectType;

use stdClass;

abstract class AbstractFixedTaxonomyFilterCustomPostsByCategoriesInputObjectTypeResolver extends AbstractFilterCustomPostsByCategoriesInputObjectTypeResolver
{
    protected function addCategoryTaxonomyFilterInput(): bool
    {
        return false;
    }

    abstract protected function getCategoryTaxonomyName(): string;

    /**
     * @param array<string,mixed> $query
     * @param stdClass|stdClass[]|array<stdClass[]> $inputValue
     */
    public function integrateInputValueToFilteringQueryArgs(array &$query, stdClass|array $inputValue): void
    {
        parent::integrateInputValueToFilteringQueryArgs($query, $inputValue);

        $query['category-taxonomy'] = $this->getCategoryTaxonomyName();
    }
}

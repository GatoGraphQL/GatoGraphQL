<?php

declare(strict_types=1);

namespace PHPUnitForGraphQLAPI\GraphQLAPI\Integration;

trait QueryableCategoryTaxonomiesFixtureEndpointWebserverRequestTestTrait
{
    protected function getFixtureFolder(): string
    {
        return __DIR__ . '/fixture-queryable-categories';
    }

    protected function getIncludedCategoryTaxonomiesNewValue(): mixed
    {
        $dataName = $this->getDataName();
        if (str_ends_with($dataName, ':1')) {
            return [
                'category',
            ];
        }

        return [
            'dummy-category',
        ];
    }

    abstract protected function getDataName(): string;
}

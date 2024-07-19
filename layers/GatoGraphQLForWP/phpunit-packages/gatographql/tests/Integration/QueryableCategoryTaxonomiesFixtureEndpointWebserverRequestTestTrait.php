<?php

declare(strict_types=1);

namespace PHPUnitForGatoGraphQL\GatoGraphQL\Integration;

trait QueryableCategoryTaxonomiesFixtureEndpointWebserverRequestTestTrait
{
    protected static function getFixtureFolder(): string
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
            'dummy-category-two',
        ];
    }

    abstract protected function getDataName(): string;
}

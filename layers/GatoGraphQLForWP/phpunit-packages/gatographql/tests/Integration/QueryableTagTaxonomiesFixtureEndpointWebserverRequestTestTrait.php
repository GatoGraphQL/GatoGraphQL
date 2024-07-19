<?php

declare(strict_types=1);

namespace PHPUnitForGatoGraphQL\GatoGraphQL\Integration;

trait QueryableTagTaxonomiesFixtureEndpointWebserverRequestTestTrait
{
    protected static function getFixtureFolder(): string
    {
        return __DIR__ . '/fixture-queryable-tags';
    }

    protected function getIncludedTagTaxonomiesNewValue(): mixed
    {
        $value = [
            'post_format',
        ];

        $dataName = $this->getDataName();
        if (str_ends_with($dataName, ':1')) {
            $value[] = 'post_tag';
        } else {
            $value[] = 'dummy-tag';
            $value[] = 'dummy-tag-two';
        }

        /**
         * Sort them as to store the entries in same way as via the UI,
         * then tests won't fail whether data was added via PHPUnit test or
         * via user interface
         */
        sort($value);

        return $value;
    }

    abstract protected function getDataName(): string;
}

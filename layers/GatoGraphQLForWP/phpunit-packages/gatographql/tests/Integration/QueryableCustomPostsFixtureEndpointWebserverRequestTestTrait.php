<?php

declare(strict_types=1);

namespace PHPUnitForGatoGraphQL\GatoGraphQL\Integration;

trait QueryableCustomPostsFixtureEndpointWebserverRequestTestTrait
{
    protected static function getFixtureFolder(): string
    {
        return __DIR__ . '/fixture-queryable-customposts';
    }

    /**
     * @return string[]
     */
    protected function getIncludedCustomPostTypesNewValue(): array
    {
        $value = [
            'post',
            'attachment',
            'nav_menu_item',
            'custom_css',
            'revision',
        ];

        $dataName = $this->getDataName();
        if (str_ends_with($dataName, ':1')) {
            $value[] = 'page';
        } elseif (str_ends_with($dataName, ':2')) {
            $value[] = 'page';
            $value[] = 'dummy-cpt';
            $value[] = 'dummy-cpt-two';
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

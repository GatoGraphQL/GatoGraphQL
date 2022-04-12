<?php

declare(strict_types=1);

namespace PHPUnitForGraphQLAPI\WPFakerSchema;

use GraphQLAPI\WPFakerSchema\State\MockDataStore;
use PoP\ComponentModel\App\AbstractComponentModelAppProxy;

/**
 * Keep all state in the application stored and accessible
 * through this class, so that regenerating this class
 * provides a new state.
 *
 * Needed for PHPUnit.
 */
class App extends AbstractComponentModelAppProxy implements AppInterface
{
    protected static MockDataStore $mockDataStore;

    public static function initializeMockDataStore(
        ?MockDataStore $mockDataStore = null,
    ): void {
        self::$mockDataStore = $mockDataStore ?? static::createMockDataStore();
    }

    protected static function createMockDataStore(): MockDataStore
    {
        return new MockDataStore(
            static::getDefaultMockDataFiles(),
            static::getDefaultMockDataOptions(),
        );
    }

    /**
     * The file providing the fixed dataset. It can be either:
     *
     * - a PHP file with the array containing the data
     * - an XML WordPress data export file
     *
     * In the 1st case, the data is retrieved directly from the PHP file.
     * In the 2nd case, the file is parsed via `WPDataParser`.
     *
     * @return string[]
     */
    protected static function getDefaultMockDataFiles(): array
    {
        return [
            dirname(__DIR__) . '/resources/fixed-dataset.wordpress.php',
        ];
    }

    /**
     * return array<string,mixed>
     */
    protected static function getDefaultMockDataOptions(): array
    {
        return [];
    }
}

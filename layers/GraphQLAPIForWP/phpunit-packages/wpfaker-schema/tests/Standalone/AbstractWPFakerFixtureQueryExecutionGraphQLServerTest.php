<?php

declare(strict_types=1);

namespace PHPUnitForGraphQLAPI\WPFakerSchema\Standalone;

use Brain\Faker\Providers;
use Faker\Generator;
use GraphQLByPoP\GraphQLServer\Standalone\AbstractFixtureQueryExecutionGraphQLServerTestCase;
use Mockery;
use PHPUnitForGraphQLAPI\WPFakerSchema\DataParsing\WordPressDataParser;
use PHPUnitForGraphQLAPI\WPFakerSchema\MockFunctions\WordPressMockFunctionContainer;
use PHPUnitForGraphQLAPI\WPFakerSchema\Seed\FakerWordPressDataSeeder;

use function Brain\faker;
use function Brain\fakerReset;
use function Brain\Monkey\Functions\expect;
use function Brain\Monkey\Functions\stubEscapeFunctions;

abstract class AbstractWPFakerFixtureQueryExecutionGraphQLServerTest extends AbstractFixtureQueryExecutionGraphQLServerTestCase
{
    protected static Generator $faker;
    protected static Providers $wpFaker;

    public static function setUpBeforeClass(): void
    {
        // Execute in this order!
        static::setUpFaker();
        parent::setUpBeforeClass();
    }

    /**
     * Extend "Brain Monkey setup for WordPress" with "Brain Faker" capabilities.
     *
     * @see https://github.com/leoloso/BrainFaker#tests-setup
     */
    protected static function setUpFaker(): void
    {
        self::$faker = faker();
        // @phpstan-ignore-next-line
        self::$wpFaker = self::$faker->wp();

        // 1. Mock WordPress functions
        static::mockFunctions();

        // 2. Mock WordPress entity data
        $data = [];
        $wordPressDataParser = static::getWordPressDataParser();
        foreach (static::getWordPressExportDataFiles() as $file) {
            $data = $wordPressDataParser->mergeDataFromFile($data, $file);
        }
        static::getFakerWordPressDataSeeder()->seedWordPressDataIntoFaker(
            self::$wpFaker,
            $data,
            static::getSeedDataOptions(),
        );
    }

    public static function tearDownAfterClass(): void
    {
        fakerReset();
        parent::tearDownAfterClass();
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
    protected static function getWordPressExportDataFiles(): array
    {
        return [
            dirname(__DIR__, 2) . '/resources/fixed-dataset.wordpress.php',
        ];
    }

    /**
     * return array<string,mixed>
     */
    protected static function getSeedDataOptions(): array
    {
        return [];
    }

    protected static function getFakerWordPressDataSeeder(): FakerWordPressDataSeeder
    {
        return new FakerWordPressDataSeeder();
    }

    protected static function getWordPressDataParser(): WordPressDataParser
    {
        return new WordPressDataParser();
    }

    /**
     * Mock needed WordPress functions
     */
    protected static function mockFunctions(): void
    {
        // Stub `esc_sql`
        stubEscapeFunctions();

        expect('get_option')
            ->with('date_format', Mockery::any())
            ->andReturn('Y-m-d');

        $wpMockFunctionContainer = new WordPressMockFunctionContainer();

        expect('mysql2date')
            ->andReturnUsing($wpMockFunctionContainer->mySQL2Date(...));
    }

    /**
     * @return string[]
     */
    protected static function getGraphQLServerComponentClasses(): array
    {
        return [
            ...parent::getGraphQLServerComponentClasses(),
            ...[
                \PHPUnitForGraphQLAPI\WPFakerSchema\Component::class,
            ]
        ];
    }
}

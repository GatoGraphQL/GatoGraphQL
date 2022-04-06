<?php

declare(strict_types=1);

namespace GraphQLAPI\WPFakerSchema;

use Brain\Faker\Providers;
use Faker\Generator;
use PoP\ComponentModel\App\AbstractComponentModelAppProxy;

use function Brain\faker;

/**
 * Keep all state in the application stored and accessible
 * through this class, so that regenerating this class
 * provides a new state.
 *
 * Needed for PHPUnit.
 */
class App extends AbstractComponentModelAppProxy implements AppInterface
{
    protected static Generator $faker;    
    protected static Providers $wpFaker;

    public static function initializeFaker(
        ?Generator $faker = null,
    ): void {
        self::$faker = $faker ?? static::createFaker();
        self::$wpFaker = self::$faker->wp();
    }

    protected static function createFaker(): Generator
    {
        return faker();
    }

    public static function getWPFaker(): Providers
    {
        return self::$wpFaker;
    }
}

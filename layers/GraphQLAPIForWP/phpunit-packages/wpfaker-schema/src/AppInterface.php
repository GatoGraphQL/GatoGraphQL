<?php

declare(strict_types=1);

namespace GraphQLAPI\WPFakerSchema;

use Brain\Faker\Providers;
use Faker\Generator;
use PoP\ComponentModel\AppInterface as UpstreamAppInterface;

interface AppInterface extends UpstreamAppInterface
{
    public static function initializeFaker(
        ?Generator $faker = null,
    ): void;

    public static function getWPFaker(): Providers;
}

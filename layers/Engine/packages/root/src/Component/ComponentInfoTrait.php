<?php

declare(strict_types=1);

namespace PoP\Root\Component;

trait ComponentInfoTrait
{
    /** @var array<string, mixed> */
    private static array $container = [];
    private static bool $initialized = false;

    /**
     * @param array<string, mixed> $container
     */
    public static function init(array $container): void
    {
        if (self::$initialized) {
            return;
        }

        self::$initialized = true;
        self::$container = $container;
    }

    public static function get(string $key): mixed
    {
        return self::$container[$key] ?? null;
    }
}

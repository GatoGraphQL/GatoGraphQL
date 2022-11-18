<?php

declare(strict_types=1);

namespace PoP\ComponentModel\ObjectModels;

abstract class AbstractTransientObject
{
    /**
     * Static ID generator
     */
    public static int $counter = 0;

    public readonly int $id;

    public function __construct()
    {
        self::$counter++;
        $this->id = self::$counter;
    }

    public function getID(): int
    {
        return $this->id;
    }
}

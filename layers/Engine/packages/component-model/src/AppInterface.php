<?php

declare(strict_types=1);

namespace PoP\ComponentModel;

use PoP\ComponentModel\Engine\EngineState;

interface AppInterface
{
    public static function getEngineState(): EngineState;

    public static function regenerateEngineState(): void;
}

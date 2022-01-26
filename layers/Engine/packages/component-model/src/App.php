<?php

declare(strict_types=1);

namespace PoP\ComponentModel;

use PoP\ComponentModel\App\AbstractRootAppProxy;
use PoP\ComponentModel\Engine\EngineState;

/**
 * Keep all state in the application stored and accessible
 * through this class, so that regenerating this class
 * provides a new state.
 *
 * Needed for PHPUnit.
 */
class App extends AbstractRootAppProxy implements AppInterface
{
    protected static EngineState $engineState;

    public static function getEngineState(): EngineState
    {
        return self::$engineState;
    }

    public static function regenerateEngineState(): void
    {
        self::$engineState = new EngineState();
    }
}

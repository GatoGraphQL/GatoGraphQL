<?php

declare(strict_types=1);

namespace PoP\ComponentModel;

use PoP\ComponentModel\App\AbstractRootAppProxy;
use PoP\ComponentModel\Engine\EngineState;

/**
 * Decorator wrapping the single class App hosting all the top-level instances,
 * plus addition of properties needed for the plugin.
 *
 * Using composition instead of inheritance, so that the original PoP\Root\App
 * is the single source of truth
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

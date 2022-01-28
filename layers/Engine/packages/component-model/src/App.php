<?php

declare(strict_types=1);

namespace PoP\ComponentModel;

use PoP\ComponentModel\App\AbstractRootAppProxy;
use PoP\ComponentModel\Engine\EngineState;
use PoP\ComponentModel\Stores\MutationResolutionStore;
use PoP\ComponentModel\Stores\MutationResolutionStoreInterface;

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
    protected static MutationResolutionStoreInterface $mutationResolutionStore;

    public static function getEngineState(): EngineState
    {
        return self::$engineState;
    }

    public static function getMutationResolutionStore(): MutationResolutionStoreInterface
    {
        return self::$mutationResolutionStore;
    }

    public static function regenerateEngineState(): void
    {
        self::$engineState = new EngineState();
    }

    public static function regenerateMutationResolutionStore(): void
    {
        self::$mutationResolutionStore = new MutationResolutionStore();
    }
}

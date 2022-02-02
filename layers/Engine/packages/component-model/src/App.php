<?php

declare(strict_types=1);

namespace PoP\ComponentModel;

use PoP\ComponentModel\App\AbstractRootAppProxy;
use PoP\ComponentModel\Engine\EngineState;
use PoP\ComponentModel\Feedback\FeedbackStore;
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
    protected static FeedbackStore $feedbackStore;
    protected static MutationResolutionStoreInterface $mutationResolutionStore;

    public static function getEngineState(): EngineState
    {
        return self::$engineState;
    }

    public static function getFeedbackStore(): FeedbackStore
    {
        return self::$feedbackStore;
    }

    public static function getMutationResolutionStore(): MutationResolutionStoreInterface
    {
        return self::$mutationResolutionStore;
    }

    public static function resetState(): void
    {
        self::$engineState = new EngineState();
        self::$feedbackStore = new FeedbackStore();
        self::$mutationResolutionStore = new MutationResolutionStore();
    }
}

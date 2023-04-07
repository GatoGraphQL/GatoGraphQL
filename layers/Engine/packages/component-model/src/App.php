<?php

declare(strict_types=1);

namespace PoP\ComponentModel;

use PoP\ComponentModel\Engine\EngineState;
use PoP\ComponentModel\Feedback\FeedbackStore;
use PoP\ComponentModel\Stores\MutationResolutionStoreInterface;
use PoP\ComponentModel\Tracing\TracingStore;
use PoP\Root\App\AbstractRootAppProxy;

/**
 * Facade to the current AppThread object that hosts
 * all the top-level instances to run the application.
 *
 * This interface contains all the methods from the
 * AppThreadInterface (to provide access to them)
 * but as static.
 */
class App extends AbstractRootAppProxy implements AppInterface
{
    protected static FeedbackStore $feedbackStore;
    protected static TracingStore $tracingStore;
    protected static EngineState $engineState;
    protected static MutationResolutionStoreInterface $mutationResolutionStore;

    public static function getFeedbackStore(): FeedbackStore
    {
        /** @var AppThreadInterface */
        $appThread = static::getAppThread();
        return $appThread->getFeedbackStore();
    }

    public static function getTracingStore(): TracingStore
    {
        /** @var AppThreadInterface */
        $appThread = static::getAppThread();
        return $appThread->getTracingStore();
    }

    public static function getEngineState(): EngineState
    {
        /** @var AppThreadInterface */
        $appThread = static::getAppThread();
        return $appThread->getEngineState();
    }

    public static function getMutationResolutionStore(): MutationResolutionStoreInterface
    {
        /** @var AppThreadInterface */
        $appThread = static::getAppThread();
        return $appThread->getMutationResolutionStore();
    }

    public static function regenerateFeedbackStore(): void
    {
        /** @var AppThreadInterface */
        $appThread = static::getAppThread();
        $appThread->regenerateFeedbackStore();
    }

    public static function regenerateTracingStore(): void
    {
        /** @var AppThreadInterface */
        $appThread = static::getAppThread();
        $appThread->regenerateTracingStore();
    }

    public static function regenerateEngineState(): void
    {
        /** @var AppThreadInterface */
        $appThread = static::getAppThread();
        $appThread->regenerateEngineState();
    }

    public static function regenerateMutationResolutionStore(): void
    {
        /** @var AppThreadInterface */
        $appThread = static::getAppThread();
        $appThread->regenerateMutationResolutionStore();
    }
}

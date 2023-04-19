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

    public static function generateAndStackFeedbackStore(): void
    {
        /** @var AppThreadInterface */
        $appThread = static::getAppThread();
        $appThread->generateAndStackFeedbackStore();
    }

    public static function generateAndStackTracingStore(): void
    {
        /** @var AppThreadInterface */
        $appThread = static::getAppThread();
        $appThread->generateAndStackTracingStore();
    }

    public static function generateAndStackEngineState(): void
    {
        /** @var AppThreadInterface */
        $appThread = static::getAppThread();
        $appThread->generateAndStackEngineState();
    }

    public static function generateAndStackMutationResolutionStore(): void
    {
        /** @var AppThreadInterface */
        $appThread = static::getAppThread();
        $appThread->generateAndStackMutationResolutionStore();
    }

    public static function popFeedbackStore(): void
    {
        /** @var AppThreadInterface */
        $appThread = static::getAppThread();
        $appThread->popFeedbackStore();
    }

    public static function popTracingStore(): void
    {
        /** @var AppThreadInterface */
        $appThread = static::getAppThread();
        $appThread->popTracingStore();
    }

    public static function popEngineState(): void
    {
        /** @var AppThreadInterface */
        $appThread = static::getAppThread();
        $appThread->popEngineState();
    }

    public static function popMutationResolutionStore(): void
    {
        /** @var AppThreadInterface */
        $appThread = static::getAppThread();
        $appThread->popMutationResolutionStore();
    }
}

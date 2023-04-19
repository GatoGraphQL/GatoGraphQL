<?php

declare(strict_types=1);

namespace PoP\ComponentModel\App;

use PoP\ComponentModel\App as ComponentModelApp;
use PoP\ComponentModel\AppInterface as ComponentModelAppInterface;
use PoP\ComponentModel\Engine\EngineState;
use PoP\ComponentModel\Feedback\FeedbackStore;
use PoP\ComponentModel\Stores\MutationResolutionStoreInterface;
use PoP\ComponentModel\Tracing\TracingStore;
use PoP\Root\App\AbstractRootAppProxy;

/**
 * Using proxy instead of inheritance, so that the upstream App
 * class is still the single source of truth for its own state
 */
abstract class AbstractComponentModelAppProxy extends AbstractRootAppProxy implements ComponentModelAppInterface
{
    public static function getEngineState(): EngineState
    {
        return ComponentModelApp::getEngineState();
    }

    public static function getFeedbackStore(): FeedbackStore
    {
        return ComponentModelApp::getFeedbackStore();
    }

    public static function getTracingStore(): TracingStore
    {
        return ComponentModelApp::getTracingStore();
    }

    public static function getMutationResolutionStore(): MutationResolutionStoreInterface
    {
        return ComponentModelApp::getMutationResolutionStore();
    }

    public static function generateAndStackEngineState(): void
    {
        ComponentModelApp::generateAndStackEngineState();
    }

    public static function generateAndStackFeedbackStore(): void
    {
        ComponentModelApp::generateAndStackFeedbackStore();
    }

    public static function generateAndStackTracingStore(): void
    {
        ComponentModelApp::generateAndStackTracingStore();
    }

    public static function generateAndStackMutationResolutionStore(): void
    {
        ComponentModelApp::generateAndStackMutationResolutionStore();
    }

    public static function popEngineState(): void
    {
        ComponentModelApp::popEngineState();
    }

    public static function popFeedbackStore(): void
    {
        ComponentModelApp::popFeedbackStore();
    }

    public static function popTracingStore(): void
    {
        ComponentModelApp::popTracingStore();
    }

    public static function popMutationResolutionStore(): void
    {
        ComponentModelApp::popMutationResolutionStore();
    }
}

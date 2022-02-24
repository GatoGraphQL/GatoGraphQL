<?php

declare(strict_types=1);

namespace GraphQLAPI\GraphQLAPI\App;

use PoP\ComponentModel\App as ComponentModelApp;
use PoP\ComponentModel\App\AbstractRootAppProxy;
use PoP\ComponentModel\AppInterface as ComponentModelAppInterface;
use PoP\ComponentModel\Engine\EngineState;
use PoP\ComponentModel\Feedback\FeedbackStore;
use PoP\ComponentModel\Stores\MutationResolutionStoreInterface;
use PoP\ComponentModel\Tracing\TracingStore;

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

    public static function regenerateEngineState(): void
    {
        ComponentModelApp::regenerateEngineState();
    }

    public static function regenerateFeedbackStore(): void
    {
        ComponentModelApp::regenerateFeedbackStore();
    }

    public static function regenerateTracingStore(): void
    {
        ComponentModelApp::regenerateTracingStore();
    }

    public static function regenerateMutationResolutionStore(): void
    {
        ComponentModelApp::regenerateMutationResolutionStore();
    }
}

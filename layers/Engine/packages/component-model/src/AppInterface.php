<?php

declare(strict_types=1);

namespace PoP\ComponentModel;

use PoP\ComponentModel\Engine\EngineState;
use PoP\ComponentModel\Feedback\FeedbackStore;
use PoP\ComponentModel\Stores\MutationResolutionStoreInterface;
use PoP\ComponentModel\Tracing\TracingStore;
use PoP\Root\AppInterface as UpstreamAppInterface;

/**
 * Facade to the current AppThread object that hosts
 * all the top-level instances to run the application.
 *
 * This interface contains all the methods from the
 * AppThreadInterface (to provide access to them)
 * but as static.
 */
interface AppInterface extends UpstreamAppInterface
{
    public static function getFeedbackStore(): FeedbackStore;

    public static function getTracingStore(): TracingStore;

    public static function getEngineState(): EngineState;

    public static function getMutationResolutionStore(): MutationResolutionStoreInterface;

    public static function generateAndStackFeedbackStore(): void;

    public static function generateAndStackTracingStore(): void;

    public static function generateAndStackEngineState(): void;

    public static function generateAndStackMutationResolutionStore(): void;

    public static function popFeedbackStore(): void;

    public static function popTracingStore(): void;

    public static function popEngineState(): void;

    public static function popMutationResolutionStore(): void;
}

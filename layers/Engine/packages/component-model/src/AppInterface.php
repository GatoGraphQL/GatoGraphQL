<?php

declare(strict_types=1);

namespace PoP\ComponentModel;

use PoP\ComponentModel\Engine\EngineState;
use PoP\ComponentModel\Feedback\FeedbackStore;
use PoP\ComponentModel\Stores\MutationResolutionStoreInterface;
use PoP\ComponentModel\Tracing\TracingStore;
use PoP\Root\AppInterface as UpstreamAppInterface;

interface AppInterface extends UpstreamAppInterface
{
    public static function getEngineState(): EngineState;

    public static function getFeedbackStore(): FeedbackStore;

    public static function getTracingStore(): TracingStore;

    public static function getMutationResolutionStore(): MutationResolutionStoreInterface;

    public static function regenerateEngineState(): void;

    public static function regenerateFeedbackStore(): void;

    public static function regenerateTracingStore(): void;

    public static function regenerateMutationResolutionStore(): void;
}

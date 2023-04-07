<?php

declare(strict_types=1);

namespace PoP\ComponentModel;

use PoP\ComponentModel\Engine\EngineState;
use PoP\ComponentModel\Feedback\FeedbackStore;
use PoP\ComponentModel\Stores\MutationResolutionStoreInterface;
use PoP\ComponentModel\Tracing\TracingStore;
use PoP\Root\AppThreadInterface as UpstreamAppThreadInterface;

interface AppThreadInterface extends UpstreamAppThreadInterface
{
    public function getFeedbackStore(): FeedbackStore;

    public function getTracingStore(): TracingStore;

    public function getEngineState(): EngineState;

    public function getMutationResolutionStore(): MutationResolutionStoreInterface;

    public function regenerateFeedbackStore(): void;

    public function regenerateTracingStore(): void;

    public function regenerateEngineState(): void;

    public function regenerateMutationResolutionStore(): void;
}

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

    public function generateAndStackFeedbackStore(): void;

    public function generateAndStackTracingStore(): void;

    public function generateAndStackEngineState(): void;

    public function generateAndStackMutationResolutionStore(): void;

    public function popFeedbackStore(): void;

    public function popTracingStore(): void;

    public function popEngineState(): void;

    public function popMutationResolutionStore(): void;
}

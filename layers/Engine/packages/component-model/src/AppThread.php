<?php

declare(strict_types=1);

namespace PoP\ComponentModel;

use PoP\ComponentModel\AppThreadInterface;
use PoP\ComponentModel\Engine\EngineState;
use PoP\ComponentModel\Feedback\FeedbackStore;
use PoP\ComponentModel\Stores\MutationResolutionStore;
use PoP\ComponentModel\Stores\MutationResolutionStoreInterface;
use PoP\ComponentModel\Tracing\TracingStore;
use PoP\Root\AppThread as RootAppThread;

class AppThread extends RootAppThread implements AppThreadInterface
{
    protected FeedbackStore $feedbackStore;
    protected TracingStore $tracingStore;
    protected EngineState $engineState;
    protected MutationResolutionStoreInterface $mutationResolutionStore;

    public function getFeedbackStore(): FeedbackStore
    {
        return $this->feedbackStore;
    }

    public function getTracingStore(): TracingStore
    {
        return $this->tracingStore;
    }

    public function getEngineState(): EngineState
    {
        return $this->engineState;
    }

    public function getMutationResolutionStore(): MutationResolutionStoreInterface
    {
        return $this->mutationResolutionStore;
    }

    public function regenerateFeedbackStore(): void
    {
        $this->feedbackStore = new FeedbackStore();
    }

    public function regenerateTracingStore(): void
    {
        $this->tracingStore = new TracingStore();
    }

    public function regenerateEngineState(): void
    {
        $this->engineState = new EngineState();
    }

    public function regenerateMutationResolutionStore(): void
    {
        $this->mutationResolutionStore = new MutationResolutionStore();
    }
}

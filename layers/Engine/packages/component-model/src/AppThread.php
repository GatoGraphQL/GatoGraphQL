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

/**
 * The state items are arrays, where a single one of them
 * will be active at a given time in the AppThread: the
 * element on the first position.
 *
 * This provides a stack of objects, so that an AttachedGraphQLServer
 * can resolve a query that includes resolving another GraphQL query
 * within it, for which a new internal state must be created, while
 * the previous one must be stored and then restored.
 *
 * At every time there must be at least 1 object created.
 * If that were not the case, the `get` methods will return
 * `null` and in that moment the PHP logic will throw an
 * Exception, and that's OK (since this would be a development
 * error, not expected to happen otherwise).
 */
class AppThread extends RootAppThread implements AppThreadInterface
{
    /**
     * @var FeedbackStore[]
     */
    protected array $feedbackStores = [];
    /**
     * @var TracingStore[]
     */
    protected array $tracingStores = [];
    /**
     * @var EngineState[]
     */
    protected array $engineStates = [];
    /**
     * @var MutationResolutionStoreInterface[]
     */
    protected array $mutationResolutionStores = [];

    public function getFeedbackStore(): FeedbackStore
    {
        return $this->feedbackStores[0];
    }

    public function getTracingStore(): TracingStore
    {
        return $this->tracingStores[0];
    }

    public function getEngineState(): EngineState
    {
        return $this->engineStates[0];
    }

    public function getMutationResolutionStore(): MutationResolutionStoreInterface
    {
        return $this->mutationResolutionStores[0];
    }

    public function generateAndStackFeedbackStore(): void
    {
        array_unshift($this->feedbackStores, new FeedbackStore());
    }

    public function generateAndStackTracingStore(): void
    {
        array_unshift($this->tracingStores, new TracingStore());
    }

    public function generateAndStackEngineState(): void
    {
        array_unshift($this->engineStates, new EngineState());
    }

    public function generateAndStackMutationResolutionStore(): void
    {
        array_unshift($this->mutationResolutionStores, new MutationResolutionStore());
    }

    public function popFeedbackStore(): void
    {
        array_shift($this->feedbackStores);
    }

    public function popTracingStore(): void
    {
        array_shift($this->tracingStores);
    }

    public function popEngineState(): void
    {
        array_shift($this->engineStates);
    }

    public function popMutationResolutionStore(): void
    {
        array_shift($this->mutationResolutionStores);
    }
}

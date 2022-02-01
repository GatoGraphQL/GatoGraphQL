<?php

declare(strict_types=1);

namespace PoP\Root\Registries;

use PoP\Root\Feedback\FeedbackMessageProviderInterface;

interface FeedbackMessageRegistryInterface
{
    public function useFeedbackMessageProvider(FeedbackMessageProviderInterface $feedbackMessageProvider): void;

    /**
     * @return array<string,array<string,string>> [key] Namespaced code, [value] Array of ['category' => ..., 'messagePlaceholder' => ..., 'specifiedByURL' => ...]
     */
    public function getFeedbackMessageEntries(): array;
}

<?php

declare(strict_types=1);

namespace PoP\Root\Registries;

use PoP\Root\FeedbackItemProviders\FeedbackItemProviderInterface;

interface FeedbackItemRegistryInterface
{
    public function useFeedbackItemProvider(FeedbackItemProviderInterface $feedbackItemProvider): void;

    /**
     * @return array<string,array<string,string>> [key] Namespaced code, [value] Array of ['category' => ..., 'messagePlaceholder' => ..., 'specifiedByURL' => ...]
     */
    public function getFeedbackItemEntries(): array;

    /**
     * @return array|null Array of ['category' => ..., 'messagePlaceholder' => ..., 'specifiedByURL' => ...], or null if no entry exists for that code
     */
    public function getFeedbackItemEntry(string $namespacedCode): ?array;
}

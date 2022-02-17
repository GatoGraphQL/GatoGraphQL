<?php

declare(strict_types=1);

namespace PoP\Root\Registries;

use PoP\Root\Feedback\FeedbackItemKeys;
use PoP\Root\FeedbackItemProviders\FeedbackItemProviderInterface;

class FeedbackItemRegistry implements FeedbackItemRegistryInterface
{
    /**
     * @var array<string,array<string,string>> [key] Namespaced code, [value] Array of ['category' => ..., 'messagePlaceholder' => ..., 'specifiedByURL' => ...]
     */
    protected array $feedbackMessageEntries = [];

    public function useFeedbackItemProvider(FeedbackItemProviderInterface $feedbackItemProvider): void
    {
        foreach ($feedbackItemProvider->getCodes() as $code) {
            $this->feedbackMessageEntries[$feedbackItemProvider->getNamespacedCode($code)] = [
                FeedbackItemKeys::CATEGORY => $feedbackItemProvider->getCategory($code),
                FeedbackItemKeys::MESSAGE_PLACEHOLDER => $feedbackItemProvider->getMessagePlaceholder($code),
                FeedbackItemKeys::SPECIFIED_BY_URL => $feedbackItemProvider->getSpecifiedByURL($code),
            ];
        }
    }

    public function getFeedbackMessageEntries(): array
    {
        return $this->feedbackMessageEntries;
    }

    public function getFeedbackMessageEntry(string $namespacedCode): ?array
    {
        return $this->feedbackMessageEntries[$namespacedCode] ?? null;
    }
}

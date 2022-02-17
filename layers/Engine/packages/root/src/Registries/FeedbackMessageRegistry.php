<?php

declare(strict_types=1);

namespace PoP\Root\Registries;

use PoP\Root\Feedback\FeedbackItemKeys;
use PoP\Root\FeedbackItemProviders\FeedbackItemProviderInterface;

class FeedbackMessageRegistry implements FeedbackMessageRegistryInterface
{
    /**
     * @var array<string,array<string,string>> [key] Namespaced code, [value] Array of ['category' => ..., 'messagePlaceholder' => ..., 'specifiedByURL' => ...]
     */
    protected array $feedbackMessageEntries = [];

    public function useFeedbackItemProvider(FeedbackItemProviderInterface $feedbackMessageProvider): void
    {
        foreach ($feedbackMessageProvider->getCodes() as $code) {
            $this->feedbackMessageEntries[$feedbackMessageProvider->getNamespacedCode($code)] = [
                FeedbackItemKeys::CATEGORY => $feedbackMessageProvider->getCategory($code),
                FeedbackItemKeys::MESSAGE_PLACEHOLDER => $feedbackMessageProvider->getMessagePlaceholder($code),
                FeedbackItemKeys::SPECIFIED_BY_URL => $feedbackMessageProvider->getSpecifiedByURL($code),
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

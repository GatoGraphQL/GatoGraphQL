<?php

declare(strict_types=1);

namespace PoP\Root\Registries;

use PoP\Root\Feedback\FeedbackItemEntryKeys;
use PoP\Root\FeedbackItemProviders\FeedbackItemProviderInterface;

class FeedbackItemRegistry implements FeedbackItemRegistryInterface
{
    /**
     * @var array<string,array<string,string>> [key] Namespaced code, [value] Array of ['category' => ..., 'messagePlaceholder' => ..., 'specifiedByURL' => ...]
     */
    protected array $feedbackItemEntries = [];

    public function useFeedbackItemProvider(FeedbackItemProviderInterface $feedbackItemProvider): void
    {
        foreach ($feedbackItemProvider->getCodes() as $code) {
            $feedbackItemEntry = [
                FeedbackItemEntryKeys::CATEGORY => $feedbackItemProvider->getCategory($code),
                FeedbackItemEntryKeys::MESSAGE_PLACEHOLDER => $feedbackItemProvider->getMessagePlaceholder($code),
            ];
            $specifiedByURL = $feedbackItemProvider->getSpecifiedByURL($code);
            if ($specifiedByURL !== null) {
                $feedbackItemEntry[FeedbackItemEntryKeys::SPECIFIED_BY_URL] = $specifiedByURL;
            }
            $this->feedbackItemEntries[$feedbackItemProvider->getNamespacedCode($code)] = $feedbackItemEntry;
        }
    }

    /**
     * @return array<string,array<string,string>>
     */
    public function getFeedbackItemEntries(): array
    {
        return $this->feedbackItemEntries;
    }

    /**
     * @return array<string,string>|null
     */
    public function getFeedbackItemEntry(string $namespacedCode): ?array
    {
        return $this->feedbackItemEntries[$namespacedCode] ?? null;
    }
}

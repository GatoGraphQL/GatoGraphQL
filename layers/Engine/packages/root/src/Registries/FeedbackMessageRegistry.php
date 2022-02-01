<?php

declare(strict_types=1);

namespace PoP\Root\Registries;

use PoP\Root\Feedback\FeedbackMessageProviderInterface;

class FeedbackMessageRegistry implements FeedbackMessageRegistryInterface
{
    /**
     * @var array<string,array<string,string>> [key] Namespaced code, [value] Array of ['category' => ..., 'messagePlaceholder' => ..., 'specifiedByURL' => ...]
     */
    protected array $feedbackMessageEntries = [];

    public function useFeedbackMessageProvider(FeedbackMessageProviderInterface $feedbackMessageProvider): void
    {
        $namespace = $feedbackMessageProvider->getNamespace();
        foreach ($feedbackMessageProvider->getCodeMessagePlaceholders() as $code => $messagePlaceholder) {
            $this->feedbackMessageEntries[$this->getNamespacedCode($namespace, $code)] = [
                FeedbackMessageEntryKeys::CATEGORY => $feedbackMessageProvider->getCategory($code),
                FeedbackMessageEntryKeys::MESSAGE_PLACEHOLDER => $messagePlaceholder,
                FeedbackMessageEntryKeys::SPECIFIED_BY_URL => $feedbackMessageProvider->getSpecifiedByURL($code),
            ];
        }
    }

    /**
     * @return array<string,array<string,string>> [key] Namespaced code, [value] Array of ['message' => ..., 'specifiedByURL' => ...]
     */
    public function getFeedbackMessageEntries(): array
    {
        return $this->feedbackMessageEntries;
    }

    protected function getNamespacedCode(string $namespace, string $code): string
    {
        return $namespace . $code;
    }
}

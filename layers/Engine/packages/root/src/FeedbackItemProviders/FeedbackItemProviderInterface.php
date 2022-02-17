<?php

declare(strict_types=1);

namespace PoP\Root\FeedbackItemProviders;

interface FeedbackItemProviderInterface
{
    /**
     * @return string[]
     */
    public function getCodes(): array;
    public function getNamespacedCode(string $code): string;
    public function getMessagePlaceholder(string $code): string;
    public function getMessage(string $code, string|int|float|bool ...$args): string;
    public function getCategory(string $code): string;
    public function getSpecifiedByURL(string $code): ?string;
}

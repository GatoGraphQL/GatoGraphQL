<?php

declare(strict_types=1);

namespace PoP\Root\FeedbackMessage;

interface FeedbackMessageProviderInterface
{
    /**
     * @return int[]
     */
    public function getCodes(): array;
    public function getNamespacedCode(int $code): string;
    public function getMessagePlaceholder(int $code): string;
    public function getMessage(int $code, string|int|float|bool ...$args): string;
    public function getCategory(int $code): string;
    public function getSpecifiedByURL(int $code): ?string;
}

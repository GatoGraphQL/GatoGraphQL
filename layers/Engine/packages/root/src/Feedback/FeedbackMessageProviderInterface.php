<?php

declare(strict_types=1);

namespace PoP\Root\Feedback;

interface FeedbackMessageProviderInterface
{
    /**
     * @return array<string,string> [key]: code, [value]: message placeholder
     */
    public function getNamespacedCode(string $code): string;
    public function getCodeMessagePlaceholders(): array;
    public function getCategory(string $code): string;
    public function getSpecifiedByURL(string $code): ?string;
}

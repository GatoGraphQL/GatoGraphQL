<?php

declare(strict_types=1);

namespace PoP\GraphQLParser\FeedbackMessage;

use PoP\Root\FeedbackMessage\AbstractFeedbackMessageProvider;

class FeedbackMessageProvider extends AbstractFeedbackMessageProvider
{
    public const E0001 = 1;

    protected function getNamespace(): string
    {
        return 'gql';
    }

    /**
     * @return int[]
     */
    public function getCodes(): array
    {
        return [];
    }

    public function getMessagePlaceholder(int $code): string
    {
        return '';
    }

    public function getSpecifiedByURL(int $code): ?string
    {
        return null;
    }
}

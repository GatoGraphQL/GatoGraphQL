<?php

declare(strict_types=1);

namespace PoP\GraphQLParser\FeedbackMessage;

use PoP\Root\FeedbackMessage\AbstractFeedbackMessageProvider;

class FeedbackMessageProvider extends AbstractFeedbackMessageProvider
{
    protected function getNamespace(): string
    {
        return 'gql';
    }

    /**
     * @return string[]
     */
    public function getCodes(): array
    {
        return [];
    }

    public function getMessagePlaceholder(string $code): string
    {
        return '';
    }

    public function getSpecifiedByURL(string $code): ?string
    {
        return null;
    }
}

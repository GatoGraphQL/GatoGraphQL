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
        return [
            self::E0001,
        ];
    }

    public function getMessagePlaceholder(int $code): string
    {
        return match($code) {
            self::E0001 => '',
            default => parent::getMessagePlaceholder($code),
        };
    }

    public function getSpecifiedByURL(int $code): ?string
    {
        return match($code) {
            self::E0001 => '',
            default => parent::getSpecifiedByURL($code),
        };
    }
}

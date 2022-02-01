<?php

declare(strict_types=1);

namespace PoP\GraphQLParser\FeedbackMessage;

use PoP\Root\FeedbackMessage\AbstractFeedbackMessageProvider;

class FeedbackMessageProvider extends AbstractFeedbackMessageProvider
{
    public const E1001 = 1001;

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
            self::E1001,
        ];
    }

    public function getMessagePlaceholder(int $code): string
    {
        return match($code) {
            self::E1001 => $this->__('Value is not set for non-nullable variable \'%s\'', 'graphql-server'),
            default => parent::getMessagePlaceholder($code),
        };
    }

    public function getSpecifiedByURL(int $code): ?string
    {
        return match($code) {
            self::E1001 => 'https://spec.graphql.org/draft/#sec-All-Variable-Usages-are-Allowed',
            default => parent::getSpecifiedByURL($code),
        };
    }
}

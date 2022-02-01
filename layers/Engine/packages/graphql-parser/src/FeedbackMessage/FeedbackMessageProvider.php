<?php

declare(strict_types=1);

namespace PoP\GraphQLParser\FeedbackMessage;

use PoP\Root\FeedbackMessage\AbstractFeedbackMessageProvider;

class FeedbackMessageProvider extends AbstractFeedbackMessageProvider
{
    public const E0001 = '1';
    public const E0002 = '2';
    public const E1001 = '1001';

    protected function getNamespace(): string
    {
        return 'gql';
    }

    /**
     * @return string[]
     */
    public function getCodes(): array
    {
        return [
            self::E0001,
            self::E0002,
            self::E1001,
        ];
    }

    public function getMessagePlaceholder(string $code): string
    {
        return match($code) {
            self::E0001 => $this->__('Operation name \'%s\' is duplicated', 'graphql-server'),
            self::E0002 => $this->__('When the document contains more than one operation, there can be no anonymous operation', 'graphql-server'),
            self::E1001 => $this->__('Value is not set for non-nullable variable \'%s\'', 'graphql-server'),
            default => parent::getMessagePlaceholder($code),
        };
    }

    public function getSpecifiedByURL(string $code): ?string
    {
        return match($code) {
            self::E0001 => 'https://spec.graphql.org/draft/#sec-Operation-Name-Uniqueness',
            self::E0002 => 'https://spec.graphql.org/draft/#sec-Lone-Anonymous-Operation',
            self::E1001 => 'https://spec.graphql.org/draft/#sec-All-Variable-Usages-are-Allowed',
            default => parent::getSpecifiedByURL($code),
        };
    }
}

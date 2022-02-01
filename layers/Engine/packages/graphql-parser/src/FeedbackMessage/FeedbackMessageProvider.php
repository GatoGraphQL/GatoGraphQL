<?php

declare(strict_types=1);

namespace PoP\GraphQLParser\FeedbackMessage;

use PoP\Root\FeedbackMessage\AbstractFeedbackMessageProvider;

class FeedbackMessageProvider extends AbstractFeedbackMessageProvider
{
    public const E0001 = 1;
    public const E0002 = 2;
    public const E0003 = 3;
    public const E0004 = 4;
    public const E0005 = 5;
    public const E0006 = 6;
    public const E0007 = 7;
    public const E0008 = 8;
    public const E0009 = 9;
    public const E0010 = 10;
    public const E0011 = 11;
    public const E0012 = 12;
    public const E0013 = 13;
    public const E0014 = 14;
    public const E0015 = 15;
    public const E0016 = 16;
    public const E0017 = 17;
    public const E0018 = 18;
    public const E0019 = 19;
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
            self::E0001,
            self::E0002,
            self::E0003,
            self::E0004,
            self::E0005,
            self::E0006,
            self::E0007,
            self::E0008,
            self::E0009,
            self::E0010,
            self::E0011,
            self::E0012,
            self::E0013,
            self::E0014,
            self::E0015,
            self::E0016,
            self::E0017,
            self::E0018,
            self::E0019,
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

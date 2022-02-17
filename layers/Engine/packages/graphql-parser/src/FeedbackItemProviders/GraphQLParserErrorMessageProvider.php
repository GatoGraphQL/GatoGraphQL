<?php

declare(strict_types=1);

namespace PoP\GraphQLParser\FeedbackItemProviders;

use PoP\Root\FeedbackItemProviders\AbstractFeedbackItemProvider;
use PoP\Root\Feedback\FeedbackCategories;

class GraphQLParserErrorMessageProvider extends AbstractFeedbackItemProvider
{
    public const E_1 = '1';
    public const E_2 = '2';
    public const E_3 = '3';
    public const E_4 = '4';
    public const E_5 = '5';
    public const E_6 = '6';

    protected function getNamespace(): string
    {
        return 'gqlparser';
    }

    /**
     * @return string[]
     */
    public function getCodes(): array
    {
        return [
            self::E_1,
            self::E_2,
            self::E_3,
            self::E_4,
            self::E_5,
            self::E_6,
        ];
    }

    public function getMessagePlaceholder(string $code): string
    {
        return match ($code) {
            self::E_1 => $this->__('Incorrect request syntax: %s', 'graphql-server'),
            self::E_2 => $this->__('Can\'t parse argument', 'graphql-parser'),
            self::E_3 => $this->__('Invalid string unicode escape sequece \'%s\'', 'graphql-server'),
            self::E_4 => $this->__('Unexpected string escaped character \'%s\'', 'graphql-server'),
            self::E_5 => $this->__('Can\t recognize token type', 'graphql-server'),
            self::E_6 => $this->__('Unexpected token \'%s\'', 'graphql-server'),
            default => parent::getMessagePlaceholder($code),
        };
    }

    public function getCategory(string $code): string
    {
        return FeedbackCategories::ERROR;
    }

    public function getSpecifiedByURL(string $code): ?string
    {
        return 'https://spec.graphql.org/draft/#sec-Language';
    }
}

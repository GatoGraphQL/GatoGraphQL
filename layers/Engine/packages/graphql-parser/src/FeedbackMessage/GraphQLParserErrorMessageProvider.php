<?php

declare(strict_types=1);

namespace PoP\GraphQLParser\FeedbackMessage;

use PoP\Root\FeedbackMessage\AbstractFeedbackMessageProvider;
use PoP\Root\FeedbackMessage\FeedbackMessageCategories;

class GraphQLParserErrorMessageProvider extends AbstractFeedbackMessageProvider
{
    public const E_1 = '1';
    public const E_2 = '2';

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
        ];
    }

    public function getMessagePlaceholder(string $code): string
    {
        return match($code) {
            self::E_1 => $this->__('Incorrect request syntax: %s', 'graphql-server'),
            self::E_2 => $this->__('Can\'t parse argument', 'graphql-parser'),
            default => parent::getMessagePlaceholder($code),
        };
    }
    
    public function getCategory(string $code): string
    {
        return FeedbackMessageCategories::ERROR;
    }

    public function getSpecifiedByURL(string $code): ?string
    {
        return 'https://spec.graphql.org/draft/#sec-Language';
    }
}

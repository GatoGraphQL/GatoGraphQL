<?php

declare(strict_types=1);

namespace PoP\GraphQLParser\FeedbackItemProviders;

use PoP\Root\FeedbackItemProviders\AbstractFeedbackItemProvider;
use PoP\Root\Feedback\FeedbackCategories;

class GraphQLExtendedSpecErrorFeedbackItemProvider extends AbstractFeedbackItemProvider
{
    public const E1 = '1';
    public const E2 = '2';
    public const E3 = '3';
    public const E4 = '4';
    public const E_5_8_3 = '5.8.3';

    protected function getNamespace(): string
    {
        return 'gqlext';
    }

    /**
     * @return string[]
     */
    public function getCodes(): array
    {
        return [
            self::E1,
            self::E2,
            self::E3,
            self::E4,
            self::E_5_8_3,
        ];
    }

    public function getMessagePlaceholder(string $code): string
    {
        return match ($code) {
            self::E1 => $this->__('Meta directive \'%s\' is nesting a directive already nested by another meta-directive', 'graphql-parser'),
            self::E2 => $this->__('Argument \'%1$s\' in directive \'%2$s\' cannot be null or empty', 'graphql-parser'),
            self::E3 => $this->__('Argument \'%1$s\' in directive \'%2$s\' must be an array of positive integers, array item \'%3$s\' is not allowed', 'graphql-parser'),
            self::E4 => $this->__('There is no directive in relative position \'%1$s\' from meta directive \'%2$s\', as indicated in argument \'%3$s\'', 'graphql-parser'),
            self::E_5_8_3 => $this->__('No value has been exported for dynamic variable \'%s\'', 'graphql-server'),
            default => parent::getMessagePlaceholder($code),
        };
    }

    public function getCategory(string $code): string
    {
        return FeedbackCategories::ERROR;
    }
}

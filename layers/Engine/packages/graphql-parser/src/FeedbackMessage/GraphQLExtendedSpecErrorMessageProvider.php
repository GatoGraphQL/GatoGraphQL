<?php

declare(strict_types=1);

namespace PoP\GraphQLParser\FeedbackMessage;

use PoP\Root\FeedbackMessage\AbstractFeedbackMessageProvider;
use PoP\Root\FeedbackMessage\FeedbackMessageCategories;

class GraphQLExtendedSpecErrorMessageProvider extends AbstractFeedbackMessageProvider
{
    public const E1 = '1';
    public const E2 = '2';
    public const E3 = '3';
    public const E4 = '4';

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
        ];
    }

    public function getMessagePlaceholder(string $code): string
    {
        return match($code) {
            self::E1 => $this->__('Meta directive \'%s\' is nesting a directive already nested by another meta-directive', 'graphql-parser'),
            self::E2 => $this->__('Argument \'%1$s\' in directive \'%2$s\' cannot be null or empty', 'graphql-parser'),
            self::E3 => '',
            self::E4 => '',
            default => parent::getMessagePlaceholder($code),
        };
    }
    
    public function getCategory(string $code): string
    {
        return FeedbackMessageCategories::ERROR;
    }
}

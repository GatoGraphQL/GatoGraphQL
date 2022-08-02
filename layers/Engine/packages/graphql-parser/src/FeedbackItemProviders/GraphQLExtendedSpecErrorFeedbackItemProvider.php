<?php

declare(strict_types=1);

namespace PoP\GraphQLParser\FeedbackItemProviders;

use PoP\Root\FeedbackItemProviders\AbstractFeedbackItemProvider;
use PoP\Root\Feedback\FeedbackCategories;

class GraphQLExtendedSpecErrorFeedbackItemProvider extends AbstractFeedbackItemProvider
{
    public final const E1 = '1';
    public final const E2 = '2';
    public final const E3 = '3';
    public final const E4 = '4';
    public final const E5 = '5';
    public final const E6 = '6';
    public final const E7 = '7';
    public final const E8 = '8';
    public final const E9 = '9';
    public final const E_5_8_3 = '5.8.3';

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
            self::E5,
            self::E6,
            self::E7,
            self::E8,
            self::E9,
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
            self::E5 => $this->__('There is no field in relative position \'%1$s\' from directive \'%2$s\', as indicated in argument \'%3$s\'', 'graphql-parser'),
            self::E6 => $this->__('The element in relative position \'%1$s\' from directive \'%2$s\' (as indicated in argument \'%3$s\') is not a field', 'graphql-parser'),
            self::E7 => $this->__('Dynamic variable \'%1$s\' cannot share the same name with a (static) variable', 'graphql-parser'),
            self::E8 => $this->__('The reference to the Resolved Field Value \'%1$s\' cannot share the same name with the variable \'%1$s\'', 'graphql-parser'),
            self::E9 => $this->__('Dynamic variable \'%1$s\' cannot share the same name with the reference to the Resolved Field Value \'%1$s\'', 'graphql-parser'),
            self::E_5_8_3 => $this->__('No value has been exported for dynamic variable \'%s\'', 'graphql-server'),
            default => parent::getMessagePlaceholder($code),
        };
    }

    public function getCategory(string $code): string
    {
        return FeedbackCategories::ERROR;
    }
}

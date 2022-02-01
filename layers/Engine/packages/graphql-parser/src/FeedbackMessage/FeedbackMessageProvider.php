<?php

declare(strict_types=1);

namespace PoP\GraphQLParser\FeedbackMessage;

use PoP\Root\FeedbackMessage\AbstractFeedbackMessageProvider;
use PoP\Root\FeedbackMessage\FeedbackMessageCategories;

class FeedbackMessageProvider extends AbstractFeedbackMessageProvider
{
    // public const E_5_1_1 = '5.1.1';
    public const E_5_2_1_1 = '5.2.1.1';
    public const E_5_2_2_1 = '5.2.2.1';
    // public const E_5_2_3_1 = '5.2.3.1';
    public const E_5_3_1 = '5.3.1';
    public const E_5_3_2 = '5.3.2';
    public const E_5_3_3 = '5.3.3';
    public const E_5_4_1 = '5.4.1';
    public const E_5_4_2 = '5.4.2';
    public const E_5_4_2_1 = '5.4.2.1';
    public const E_5_5_1 = '5.5.1';
    public const E_5_5_1_1 = '5.5.1.1';
    public const E_5_5_1_2 = '5.5.1.2';
    public const E_5_5_1_3 = '5.5.1.3';
    public const E_5_5_1_4 = '5.5.1.4';
    public const E_5_5_2 = '5.5.2';
    public const E_5_5_2_1 = '5.5.2.1';
    public const E_5_5_2_2 = '5.5.2.2';
    public const E_5_5_2_3 = '5.5.2.3';
    public const E_5_5_2_3_1 = '5.5.2.3.1';
    public const E_5_5_2_3_2 = '5.5.2.3.2';
    public const E_5_5_2_3_3 = '5.5.2.3.3';
    public const E_5_5_2_3_4 = '5.5.2.3.4';
    public const E_5_6 = '5.6';
    public const E_5_6_1 = '5.6.1';
    public const E_5_6_2 = '5.6.2';
    public const E_5_6_3 = '5.6.3';
    public const E_5_6_4 = '5.6.4';
    public const E_5_7_1 = '5.7.1';
    public const E_5_7_2 = '5.7.2';
    public const E_5_7_3 = '5.7.3';
    public const E_5_8_1 = '5.8.1';
    public const E_5_8_2 = '5.8.2';
    public const E_5_8_3 = '5.8.3';
    public const E_5_8_4 = '5.8.4';
    public const E_5_8_5 = '5.8.5';

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
            // self::E_5_1_1,
            self::E_5_2_1_1,
            self::E_5_2_2_1,
            // self::E_5_2_3_1,
            self::E_5_3_1,
            self::E_5_3_2,
            self::E_5_3_3,
            self::E_5_4_1,
            self::E_5_4_2,
            self::E_5_4_2_1,
            self::E_5_5_1,
            self::E_5_5_1_1,
            self::E_5_5_1_2,
            self::E_5_5_1_3,
            self::E_5_5_1_4,
            self::E_5_5_2,
            self::E_5_5_2_1,
            self::E_5_5_2_2,
            self::E_5_5_2_3,
            self::E_5_5_2_3_1,
            self::E_5_5_2_3_2,
            self::E_5_5_2_3_3,
            self::E_5_5_2_3_4,
            self::E_5_6,
            self::E_5_6_1,
            self::E_5_6_2,
            self::E_5_6_3,
            self::E_5_6_4,
            self::E_5_7_1,
            self::E_5_7_2,
            self::E_5_7_3,
            self::E_5_8_1,
            self::E_5_8_2,
            self::E_5_8_3,
            self::E_5_8_4,
            self::E_5_8_5,
        ];
    }

    public function getMessagePlaceholder(string $code): string
    {
        return match($code) {
            // self::E_5_1_1 => '__not_applicable__',
            self::E_5_2_1_1 => $this->__('Operation name \'%s\' is duplicated', 'graphql-server'),
            self::E_5_2_2_1 => $this->__('When the document contains more than one operation, there can be no anonymous operation', 'graphql-server'),
            // self::E_5_2_3_1 => '__subscriptions_not_yet_supported__',
            self::E_5_3_1 => '__provide__',
            self::E_5_3_2 => '__provide__',
            self::E_5_3_3 => '__provide__',
            self::E_5_4_1 => '__provide__',
            self::E_5_4_2 => '__provide__',
            self::E_5_4_2_1 => '__provide__',
            self::E_5_5_1 => '__provide__',
            self::E_5_5_1_1 => '__provide__',
            self::E_5_5_1_2 => '__provide__',
            self::E_5_5_1_3 => '__provide__',
            self::E_5_5_1_4 => '__provide__',
            self::E_5_5_2 => '__provide__',
            self::E_5_5_2_1 => '__provide__',
            self::E_5_5_2_2 => '__provide__',
            self::E_5_5_2_3 => '__provide__',
            self::E_5_5_2_3_1 => '__provide__',
            self::E_5_5_2_3_2 => '__provide__',
            self::E_5_5_2_3_3 => '__provide__',
            self::E_5_5_2_3_4 => '__provide__',
            self::E_5_6 => '__provide__',
            self::E_5_6_1 => '__provide__',
            self::E_5_6_2 => '__provide__',
            self::E_5_6_3 => '__provide__',
            self::E_5_6_4 => '__provide__',
            self::E_5_7_1 => '__provide__',
            self::E_5_7_2 => '__provide__',
            self::E_5_7_3 => '__provide__',
            self::E_5_8_1 => '__provide__',
            self::E_5_8_2 => '__provide__',
            self::E_5_8_3 => '__provide__',
            self::E_5_8_4 => '__provide__',
            self::E_5_8_5 => $this->__('Value is not set for non-nullable variable \'%s\'', 'graphql-server'),

            default => parent::getMessagePlaceholder($code),
        };
    }
    
    public function getCategory(string $code): string
    {
        return FeedbackMessageCategories::ERROR;
    }

    public function getSpecifiedByURL(string $code): ?string
    {
        return match($code) {
            // self::E_5_1_1 => 'https://spec.graphql.org/draft/#sec-Executable-Definitions',
            self::E_5_2_1_1 => 'https://spec.graphql.org/draft/#sec-Operation-Name-Uniqueness',
            self::E_5_2_2_1 => 'https://spec.graphql.org/draft/#sec-Lone-Anonymous-Operation',
            // self::E_5_2_3_1 => 'https://spec.graphql.org/draft/#sec-Single-root-field',
            self::E_5_3_1 => 'https://spec.graphql.org/draft/#sec-Field-Selections',
            self::E_5_3_2 => 'https://spec.graphql.org/draft/#sec-Field-Selection-Merging',
            self::E_5_3_3 => 'https://spec.graphql.org/draft/#sec-Leaf-Field-Selections',
            self::E_5_4_1 => 'https://spec.graphql.org/draft/#sec-Argument-Names',
            self::E_5_4_2 => 'https://spec.graphql.org/draft/#sec-Argument-Uniqueness',
            self::E_5_4_2_1 => 'https://spec.graphql.org/draft/#sec-Required-Arguments',
            self::E_5_5_1 => 'https://spec.graphql.org/draft/#sec-Fragment-Declarations',
            self::E_5_5_1_1 => 'https://spec.graphql.org/draft/#sec-Fragment-Name-Uniqueness',
            self::E_5_5_1_2 => 'https://spec.graphql.org/draft/#sec-Fragment-Spread-Type-Existence',
            self::E_5_5_1_3 => 'https://spec.graphql.org/draft/#sec-Fragments-On-Composite-Types',
            self::E_5_5_1_4 => 'https://spec.graphql.org/draft/#sec-Fragments-Must-Be-Used',
            self::E_5_5_2 => 'https://spec.graphql.org/draft/#sec-Fragment-Spreads',
            self::E_5_5_2_1 => 'https://spec.graphql.org/draft/#sec-Fragment-spread-target-defined',
            self::E_5_5_2_2 => 'https://spec.graphql.org/draft/#sec-Fragment-spreads-must-not-form-cycles',
            self::E_5_5_2_3 => 'https://spec.graphql.org/draft/#sec-Fragment-spread-is-possible',
            self::E_5_5_2_3_1 => 'https://spec.graphql.org/draft/#sec-Object-Spreads-In-Object-Scope',
            self::E_5_5_2_3_2 => 'https://spec.graphql.org/draft/#sec-Abstract-Spreads-in-Object-Scope',
            self::E_5_5_2_3_3 => 'https://spec.graphql.org/draft/#sec-Object-Spreads-In-Abstract-Scope',
            self::E_5_5_2_3_4 => 'https://spec.graphql.org/draft/#sec-Abstract-Spreads-in-Abstract-Scope',
            self::E_5_6 => 'https://spec.graphql.org/draft/#sec-Values',
            self::E_5_6_1 => 'https://spec.graphql.org/draft/#sec-Values-of-Correct-Type',
            self::E_5_6_2 => 'https://spec.graphql.org/draft/#sec-Input-Object-Field-Names',
            self::E_5_6_3 => 'https://spec.graphql.org/draft/#sec-Input-Object-Field-Uniqueness',
            self::E_5_6_4 => 'https://spec.graphql.org/draft/#sec-Input-Object-Required-Fields',
            self::E_5_7_1 => 'https://spec.graphql.org/draft/#sec-Directives-Are-Defined',
            self::E_5_7_2 => 'https://spec.graphql.org/draft/#sec-Directives-Are-In-Valid-Locations',
            self::E_5_7_3 => 'https://spec.graphql.org/draft/#sec-Directives-Are-Unique-Per-Location',
            self::E_5_8_1 => 'https://spec.graphql.org/draft/#sec-Variable-Uniqueness',
            self::E_5_8_2 => 'https://spec.graphql.org/draft/#sec-Variables-Are-Input-Types',
            self::E_5_8_3 => 'https://spec.graphql.org/draft/#sec-All-Variable-Uses-Defined',
            self::E_5_8_4 => 'https://spec.graphql.org/draft/#sec-All-Variables-Used',
            self::E_5_8_5 => 'https://spec.graphql.org/draft/#sec-All-Variable-Usages-are-Allowed',

            default => parent::getSpecifiedByURL($code),
        };
    }
}

<?php

declare(strict_types=1);

namespace PoP\GraphQLParser\FeedbackItemProviders;

use PoP\Root\FeedbackItemProviders\AbstractFeedbackItemProvider;
use PoP\Root\Feedback\FeedbackCategories;

class GraphQLSpecErrorFeedbackItemProvider extends AbstractFeedbackItemProvider
{
    // public final const E_5_1_1 = '5.1.1';
    public final const E_5_2_1_1 = '5.2.1.1';
    public final const E_5_2_2_1 = '5.2.2.1';
    // public final const E_5_2_3_1 = '5.2.3.1';
    public final const E_5_3_1 = '5.3.1';
    public final const E_5_3_2 = '5.3.2';
    public final const E_5_3_3 = '5.3.3';
    public final const E_5_4_1_A = '5.4.1[a]';
    public final const E_5_4_1_B = '5.4.1[b]';
    public final const E_5_4_2 = '5.4.2';
    public final const E_5_4_2_1_A = '5.4.2.1[a]';
    public final const E_5_4_2_1_B = '5.4.2.1[b]';
    public final const E_5_4_2_1_C = '5.4.2.1[c]';
    public final const E_5_4_2_1_D = '5.4.2.1[d]';
    public final const E_5_5_1_1 = '5.5.1.1';
    public final const E_5_5_1_2 = '5.5.1.2';
    public final const E_5_5_1_3 = '5.5.1.3';
    public final const E_5_5_1_4 = '5.5.1.4';
    public final const E_5_5_2_1 = '5.5.2.1';
    public final const E_5_5_2_2 = '5.5.2.2';
    public final const E_5_5_2_3 = '5.5.2.3';
    public final const E_5_5_2_3_1 = '5.5.2.3.1';
    public final const E_5_5_2_3_2 = '5.5.2.3.2';
    public final const E_5_5_2_3_3 = '5.5.2.3.3';
    public final const E_5_5_2_3_4 = '5.5.2.3.4';
    // public final const E_5_6_1 = '5.6.1';
    public final const E_5_6_2 = '5.6.2';
    public final const E_5_6_3 = '5.6.3';
    public final const E_5_6_4_A = '5.6.4[a]';
    public final const E_5_6_4_B = '5.6.4[b]';
    public final const E_5_7_1 = '5.7.1';
    public final const E_5_7_2 = '5.7.2';
    public final const E_5_7_3 = '5.7.3';
    public final const E_5_8_1 = '5.8.1';
    public final const E_5_8_2 = '5.8.2';
    public final const E_5_8_3 = '5.8.3';
    public final const E_5_8_4 = '5.8.4';
    public final const E_5_8_5 = '5.8.5';
    public final const E_6_1_A = '6.1[a]';
    public final const E_6_1_B = '6.1[b]';
    public final const E_6_1_C = '6.1[c]';
    public final const E_6_1_D = '6.1[d]';

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
            self::E_5_4_1_A,
            self::E_5_4_1_B,
            self::E_5_4_2,
            self::E_5_4_2_1_A,
            self::E_5_4_2_1_B,
            self::E_5_4_2_1_C,
            self::E_5_4_2_1_D,
            self::E_5_5_1_1,
            self::E_5_5_1_2,
            self::E_5_5_1_3,
            self::E_5_5_1_4,
            self::E_5_5_2_1,
            self::E_5_5_2_2,
            self::E_5_5_2_3,
            self::E_5_5_2_3_1,
            self::E_5_5_2_3_2,
            self::E_5_5_2_3_3,
            self::E_5_5_2_3_4,
            // self::E_5_6_1,
            self::E_5_6_2,
            self::E_5_6_3,
            self::E_5_6_4_A,
            self::E_5_6_4_B,
            self::E_5_7_1,
            self::E_5_7_2,
            self::E_5_7_3,
            self::E_5_8_1,
            self::E_5_8_2,
            self::E_5_8_3,
            self::E_5_8_4,
            self::E_5_8_5,
            self::E_6_1_A,
            self::E_6_1_B,
            self::E_6_1_C,
            self::E_6_1_D,
        ];
    }

    public function getMessagePlaceholder(string $code): string
    {
        return match ($code) {
            // self::E_5_1_1 => 'not_applicable',
            self::E_5_2_1_1 => $this->__('Operation name \'%s\' is duplicated', 'graphql-server'),
            self::E_5_2_2_1 => $this->__('When the document contains more than one operation, there can be no anonymous operation', 'graphql-server'),
            // self::E_5_2_3_1 => 'subscriptions_not_yet_supported',
            self::E_5_3_1 => $this->__('There is no field \'%s\' on type \'%s\'', 'graphql-parser'),
            self::E_5_3_2 => $this->__('The response for field \'%s\' on object with ID \'%s\' is ambiguous, as the previous (and non equivalent) field \'%s\' must also be printed under key \'%s\'', 'graphql-parser'),
            self::E_5_3_3 => $this->__('Field \'%s\' from type \'%s\' is not a connection', 'graphql-server'),
            self::E_5_4_1_A => $this->__('On field \'%1$s\' of type \'%2$s\', there is no argument with name \'%3$s\'', 'graphql-server'),
            self::E_5_4_1_B => $this->__('On directive \'%1$s\', there is no argument with name \'%2$s\'', 'graphql-server'),
            self::E_5_4_2 => $this->__('Argument \'%s\' is duplicated', 'graphql-server'),
            self::E_5_4_2_1_A => $this->__('Mandatory argument \'%1$s\' in field \'%2$s\' of type \'%3$s\' has not been provided', 'graphql-server'),
            self::E_5_4_2_1_B => $this->__('Argument \'%1$s\' in field \'%2$s\' of type \'%3$s\' cannot be null', 'graphql-server'),
            self::E_5_4_2_1_C => $this->__('Mandatory argument \'%1$s\' in directive \'%2$s\' has not been provided', 'graphql-server'),
            self::E_5_4_2_1_D => $this->__('Argument \'%1$s\' in directive \'%2$s\' cannot be null', 'graphql-server'),
            self::E_5_5_1_1 => $this->__('Fragment name \'%s\' is duplicated', 'graphql-server'),
            self::E_5_5_1_2 => $this->__('Fragment spread type \'%s\' is not defined in the schema', 'graphql-server'),
            self::E_5_5_1_3 => $this->__('The target type of fragment\'%s\' must have kind UNION, INTERFACE, or OBJECT.', 'graphql-server'),
            self::E_5_5_1_4 => $this->__('Fragment \'%s\' is not used', 'graphql-server'),
            self::E_5_5_2_1 => $this->__('Fragment \'%s\' is not defined in document', 'graphql-server'),
            self::E_5_5_2_2 => $this->__('Fragment \'%s\' is cyclical', 'graphql-server'),
            self::E_5_5_2_3 => 'TODO: satisfy',
            self::E_5_5_2_3_1 => 'TODO: satisfy',
            self::E_5_5_2_3_2 => 'TODO: satisfy',
            self::E_5_5_2_3_3 => 'TODO: satisfy',
            self::E_5_5_2_3_4 => 'TODO: satisfy',
            // self::E_5_6_1 => // 'satisfied in detail in InputValueCoercionGraphQLSpecErrorFeedbackItemProvider'
            self::E_5_6_2 => $this->__('There is no input field \'%s\' in input object \'%s\'', 'graphql-server'),
            self::E_5_6_3 => $this->__('Input object has duplicate key \'%s\'', 'graphql-server'),
            self::E_5_6_4_A => $this->__('Mandatory input field \'%s\' in input object \'%s\' has not been provided', 'graphql-server'),
            self::E_5_6_4_B => $this->__('Input field \'%s\' in input object \'%s\' cannot be null', 'graphql-server'),
            self::E_5_7_1 => $this->__('There is no directive with name \'%s\'', 'graphql-parser'),
            self::E_5_7_2 => 'TODO: satisfy',
            self::E_5_7_3 => $this->__('Directive \'%s\' can be executed only once for field(s) \'%s\'', 'graphql-parser'),
            self::E_5_8_1 => $this->__('Variable name \'%s\' is duplicated', 'graphql-server'),
            self::E_5_8_2 => $this->__('Variable \'%s\' must be of Input type, but type \'%s\' is not (UNION, INTERFACE, or OBJECT types are not allowed)', 'graphql-server'),
            self::E_5_8_3 => $this->__('Variable \'%s\' has not been defined in the operation', 'graphql-server'),
            self::E_5_8_4 => $this->__('Variable \'%s\' is not used', 'graphql-server'),
            self::E_5_8_5 => $this->__('Value is not set for non-nullable variable \'%s\'', 'graphql-server'),
            self::E_6_1_A => $this->__('Operation with name \'%s\' does not exist', 'graphql-server'),
            self::E_6_1_B => $this->__('When the document contains more than one operation, the operation name to execute must be provided', 'graphql-server'),
            self::E_6_1_C => $this->__('The document is empty', 'graphql-server'),
            self::E_6_1_D => $this->__('No operations defined in the document', 'graphql-server'),
            default => parent::getMessagePlaceholder($code),
        };
    }

    public function getCategory(string $code): string
    {
        return FeedbackCategories::ERROR;
    }

    public function getSpecifiedByURL(string $code): ?string
    {
        return match ($code) {
            // self::E_5_1_1 => 'https://spec.graphql.org/draft/#sec-Executable-Definitions',
            self::E_5_2_1_1 => 'https://spec.graphql.org/draft/#sec-Operation-Name-Uniqueness',
            self::E_5_2_2_1 => 'https://spec.graphql.org/draft/#sec-Lone-Anonymous-Operation',
            // self::E_5_2_3_1 => 'https://spec.graphql.org/draft/#sec-Single-root-field',
            self::E_5_3_1 => 'https://spec.graphql.org/draft/#sec-Field-Selections',
            self::E_5_3_2 => 'https://spec.graphql.org/draft/#sec-Field-Selection-Merging', // 'TODO: satisfy',
            self::E_5_3_3 => 'https://spec.graphql.org/draft/#sec-Leaf-Field-Selections',
            self::E_5_4_1_A,
            self::E_5_4_1_B => 'https://spec.graphql.org/draft/#sec-Argument-Names',
            self::E_5_4_2 => 'https://spec.graphql.org/draft/#sec-Argument-Uniqueness',
            self::E_5_4_2_1_A,
            self::E_5_4_2_1_B,
            self::E_5_4_2_1_C,
            self::E_5_4_2_1_D => 'https://spec.graphql.org/draft/#sec-Required-Arguments',
            self::E_5_5_1_1 => 'https://spec.graphql.org/draft/#sec-Fragment-Name-Uniqueness',
            self::E_5_5_1_2 => 'https://spec.graphql.org/draft/#sec-Fragment-Spread-Type-Existence',
            self::E_5_5_1_3 => 'https://spec.graphql.org/draft/#sec-Fragments-On-Composite-Types',
            self::E_5_5_1_4 => 'https://spec.graphql.org/draft/#sec-Fragments-Must-Be-Used',
            self::E_5_5_2_1 => 'https://spec.graphql.org/draft/#sec-Fragment-spread-target-defined',
            self::E_5_5_2_2 => 'https://spec.graphql.org/draft/#sec-Fragment-spreads-must-not-form-cycles',
            self::E_5_5_2_3 => 'https://spec.graphql.org/draft/#sec-Fragment-spread-is-possible', // 'TODO: satisfy',
            self::E_5_5_2_3_1 => 'https://spec.graphql.org/draft/#sec-Object-Spreads-In-Object-Scope', // 'TODO: satisfy',
            self::E_5_5_2_3_2 => 'https://spec.graphql.org/draft/#sec-Abstract-Spreads-in-Object-Scope', // 'TODO: satisfy',
            self::E_5_5_2_3_3 => 'https://spec.graphql.org/draft/#sec-Object-Spreads-In-Abstract-Scope', // 'TODO: satisfy',
            self::E_5_5_2_3_4 => 'https://spec.graphql.org/draft/#sec-Abstract-Spreads-in-Abstract-Scope', // 'TODO: satisfy',
            // self::E_5_6_1 => 'https://spec.graphql.org/draft/#sec-Values-of-Correct-Type',
            self::E_5_6_2 => 'https://spec.graphql.org/draft/#sec-Input-Object-Field-Names',
            self::E_5_6_3 => 'https://spec.graphql.org/draft/#sec-Input-Object-Field-Uniqueness',
            self::E_5_6_4_A,
            self::E_5_6_4_B => 'https://spec.graphql.org/draft/#sec-Input-Object-Required-Fields',
            self::E_5_7_1 => 'https://spec.graphql.org/draft/#sec-Directives-Are-Defined',
            self::E_5_7_2 => 'https://spec.graphql.org/draft/#sec-Directives-Are-In-Valid-Locations', // 'TODO: satisfy',
            self::E_5_7_3 => 'https://spec.graphql.org/draft/#sec-Directives-Are-Unique-Per-Location',
            self::E_5_8_1 => 'https://spec.graphql.org/draft/#sec-Variable-Uniqueness',
            self::E_5_8_2 => 'https://spec.graphql.org/draft/#sec-Variables-Are-Input-Types',
            self::E_5_8_3 => 'https://spec.graphql.org/draft/#sec-All-Variable-Uses-Defined',
            self::E_5_8_4 => 'https://spec.graphql.org/draft/#sec-All-Variables-Used',
            self::E_5_8_5 => 'https://spec.graphql.org/draft/#sec-All-Variable-Usages-are-Allowed',
            self::E_6_1_A,
            self::E_6_1_B,
            self::E_6_1_C,
            self::E_6_1_D
                => 'https://spec.graphql.org/draft/#sec-Executing-Requests',
            default => parent::getSpecifiedByURL($code),
        };
    }
}

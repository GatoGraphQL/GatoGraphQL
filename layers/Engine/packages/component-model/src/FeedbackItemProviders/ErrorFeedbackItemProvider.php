<?php

declare(strict_types=1);

namespace PoP\ComponentModel\FeedbackItemProviders;

use PoP\Root\FeedbackItemProviders\AbstractFeedbackItemProvider;
use PoP\ComponentModel\Feedback\FeedbackCategories;

class ErrorFeedbackItemProvider extends AbstractFeedbackItemProvider
{
    public final const E1 = 'e1';
    public final const E3 = 'e3';
    public final const E3A = 'e3a';
    public final const E4 = 'e4';
    public final const E5 = 'e5';
    public final const E5A = 'e5a';
    public final const E6 = 'e6';
    public final const E6A = 'e6a';
    public final const E7 = 'e7';
    public final const E8 = 'e8';
    public final const E9 = 'e9';
    public final const E10 = 'e10';
    public final const E11 = 'e11';
    public final const E11A = 'e11a';
    public final const E12 = 'e12';
    public final const E13 = 'e13';
    public final const E15 = 'e15';
    public final const E16 = 'e16';
    public final const E17 = 'e17';
    public final const E20 = 'e20';
    public final const E21 = 'e21';
    public final const E22 = 'e22';
    public final const E23 = 'e23';
    public final const E26 = 'e26';
    public final const E27 = 'e27';
    public final const E28 = 'e28';
    public final const E29 = 'e29';
    public final const E30 = 'e30';
    public final const E31 = 'e31';
    public final const E32 = 'e32';

    /**
     * @return string[]
     */
    public function getCodes(): array
    {
        return [
            self::E1,
            self::E3,
            self::E3A,
            self::E4,
            self::E5,
            self::E5A,
            self::E6,
            self::E6A,
            self::E7,
            self::E8,
            self::E9,
            self::E10,
            self::E11,
            self::E11A,
            self::E12,
            self::E13,
            self::E15,
            self::E16,
            self::E17,
            self::E20,
            self::E21,
            self::E22,
            self::E23,
            self::E26,
            self::E27,
            self::E28,
            self::E29,
            self::E30,
            self::E31,
            self::E32,
        ];
    }

    public function getMessagePlaceholder(string $code): string
    {
        return match ($code) {
            /**
             * @todo: This one is error "5.3.3 Leaf Field Selections"!
             * @see https://spec.graphql.org/October2021/#sec-Leaf-Field-Selections
             * in GraphQLSpecErrorFeedbackItemProvider.php
             */
            self::E1 => $this->__('Field \'%s\' from type \'%s\' is not a connection', 'component-model'),
            self::E3 => $this->__('Resolving field \'%s\' triggered exception: \'%s\'', 'component-model'),
            self::E3A => $this->__('Resolving field \'%s\' triggered exception: \'%s\'. Trace: %s', 'component-model'),
            self::E4 => $this->__('Resolving field \'%s\' triggered an exception, please contact the admin', 'component-model'),
            self::E5 => $this->__('Meta directive \'%s\' has no composed directives', 'component-model'),
            self::E5A => $this->__('The directive pipeline for \'%s\' is empty', 'component-model'),
            self::E6 => $this->__('Resolving mutation \'%s\' triggered exception: \'%s\'', 'component-model'),
            self::E6A => $this->__('Resolving mutation \'%s\' triggered exception: \'%s\'. Trace: %s', 'component-model'),
            self::E7 => $this->__('Resolving mutation \'%s\' triggered an exception, please contact the admin', 'component-model'),
            self::E8 => $this->__('No TypeResolver resolves object \'%s\'', 'component-model'),
            self::E9 => $this->__('The DataLoader can\'t load data for object of type \'%s\' with ID \'%s\'', 'component-model'),
            self::E10 => $this->__('Either the DataLoader can\'t load data, or no TypeResolver resolves, object with ID \'%s\'', 'component-model'),
            self::E11 => $this->__('Resolving directive \'%s\' triggered exception: \'%s\'', 'component-model'),
            self::E11A => $this->__('Resolving directive \'%s\' triggered exception: \'%s\'. Trace: %s', 'component-model'),
            self::E12 => $this->__('Resolving directive \'%s\' triggered an exception, please contact the admin', 'component-model'),
            self::E13 => $this->__('Object with ID \'%s\' does not exist or cannot be retrieved', 'component-model'),
            self::E15 => $this->__('For directive \'%s\', casting value \'%s\' for argument \'%s\' to type \'%s\' failed', 'component-model'),
            self::E16 => $this->__('There is no field \'%s\' on type \'%s\'', 'component-model'),
            self::E17 => $this->__('For field \'%s\', casting value \'%s\' for argument \'%s\' to type \'%s\' failed', 'component-model'),
            self::E20 => $this->__('There is no directive with name \'%s\'', 'component-model'),
            self::E21 => $this->__('No DirectiveResolver processes directive with name \'%s\' and arguments \'%s\' in field(s) \'%s\'', 'component-model'),
            self::E22 => $this->__('No DirectiveResolver processes directive with name \'%s\' and arguments \'%s\' in field \'%s\'', 'component-model'),
            self::E23 => $this->__('Directive \'%s\' can be executed only once for field(s) \'%s\'', 'component-model'),
            self::E26 => $this->__('There is no field \'%s\' on type \'%s\' satisfying version constraint \'%s\'', 'component-model'),
            self::E27 => $this->__('On field \'%1$s\' of type \'%2$s\', there is no argument with name \'%3$s\'', 'component-model'),
            self::E28 => $this->__('On directive \'%1$s\', there is no argument with name \'%2$s\'', 'component-model'),
            self::E29 => $this->__('Mandatory argument \'%1$s\' in field \'%2$s\' of type \'%3$s\' has not been provided', 'component-model'),
            self::E30 => $this->__('Argument \'%1$s\' in field \'%2$s\' of type \'%3$s\' cannot be null', 'component-model'),
            self::E31 => $this->__('Mandatory argument \'%1$s\' in directive \'%2$s\' has not been provided', 'component-model'),
            self::E32 => $this->__('Argument \'%1$s\' in directive \'%2$s\' cannot be null', 'component-model'),
            default => parent::getMessagePlaceholder($code),
        };
    }

    public function getCategory(string $code): string
    {
        return FeedbackCategories::ERROR;
    }
}

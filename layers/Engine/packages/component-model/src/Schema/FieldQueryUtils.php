<?php

declare(strict_types=1);

namespace PoP\ComponentModel\Schema;

use PoP\ComponentModel\Facades\Schema\FieldQueryInterpreterFacade;

class FieldQueryUtils
{
    public static function isAnyFieldArgumentValueAField(array $fieldArgValues): bool
    {
        $fieldQueryInterpreter = FieldQueryInterpreterFacade::getInstance();
        return self::isAnyFieldArgumentValueASomething(
            $fieldArgValues,
            [$fieldQueryInterpreter, 'isFieldArgumentValueAField']
        );
    }
    public static function isAnyFieldArgumentValueAFieldOrExpression(array $fieldArgValues): bool
    {
        $fieldQueryInterpreter = FieldQueryInterpreterFacade::getInstance();
        return self::isAnyFieldArgumentValueASomething(
            $fieldArgValues,
            function ($fieldArgValue) use ($fieldQueryInterpreter) {
                return $fieldQueryInterpreter->isFieldArgumentValueAField($fieldArgValue)
                    || $fieldQueryInterpreter->isFieldArgumentValueAnExpression($fieldArgValue);
            }
        );
    }

    public static function isAnyFieldArgumentValueDynamic(array $fieldArgValues): bool
    {
        $fieldQueryInterpreter = FieldQueryInterpreterFacade::getInstance();
        return self::isAnyFieldArgumentValueASomething(
            $fieldArgValues,
            function ($fieldArgValue) use ($fieldQueryInterpreter) {
                return $fieldQueryInterpreter->isFieldArgumentValueDynamic($fieldArgValue);
                    // // Is it a field?
                    // $fieldQueryInterpreter->isFieldArgumentValueAField($fieldArgValue) ||
                    // // Is it a variable?
                    // $fieldQueryInterpreter->isFieldArgumentValueAVariable($fieldArgValue) ||
                    // // Is it a variable?
                    // $fieldQueryInterpreter->isFieldArgumentValueAnExpression($fieldArgValue);
            }
        );
    }

    /**
     * Indicate if the fieldArgValue is whatever is needed to know, executed against a $callback function
     */
    public static function isAnyFieldArgumentValueASomething(array $fieldArgValues, callable $callback): bool
    {
        $isOrContainsAField = array_map(
            function ($fieldArgValue) use ($callback) {
                // Either the value is a field, or it is an array of fields
                if (is_array($fieldArgValue)) {
                    return self::isAnyFieldArgumentValueASomething((array)$fieldArgValue, $callback);
                }
                return $callback($fieldArgValue);
            },
            $fieldArgValues
        );
        return (in_array(true, $isOrContainsAField));
    }
}

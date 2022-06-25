<?php

declare(strict_types=1);

namespace PoPAPI\API\Schema;

use PoP\ComponentModel\Schema\FieldQueryInterpreter as UpstreamFieldQueryInterpreter;
use PoP\FieldQuery\QuerySyntax;
use PoP\FieldQuery\QueryUtils;

class FieldQueryInterpreter extends UpstreamFieldQueryInterpreter implements FieldQueryInterpreterInterface
{
    public function extractFieldOrDirectiveArgumentValues(string $fieldOrDirectiveArgsStr): array
    {
        $fieldOrDirectiveArgValues = [];
        // Remove the opening and closing brackets
        $fieldOrDirectiveArgsStr = substr($fieldOrDirectiveArgsStr, strlen(QuerySyntax::SYMBOL_FIELDARGS_OPENING), strlen($fieldOrDirectiveArgsStr) - strlen(QuerySyntax::SYMBOL_FIELDARGS_OPENING) - strlen(QuerySyntax::SYMBOL_FIELDARGS_CLOSING));
        // Remove the white spaces before and after
        if ($fieldOrDirectiveArgsStr = trim($fieldOrDirectiveArgsStr)) {
            // Iterate all the elements, and extract them into the array
            if ($fieldArgElems = $this->getQueryParser()->splitElements($fieldOrDirectiveArgsStr, QuerySyntax::SYMBOL_FIELDARGS_ARGSEPARATOR, [QuerySyntax::SYMBOL_FIELDARGS_OPENING, QuerySyntax::SYMBOL_FIELDARGS_ARGVALUEARRAY_OPENING, QuerySyntax::SYMBOL_FIELDARGS_ARGVALUEOBJECT_OPENING], [QuerySyntax::SYMBOL_FIELDARGS_CLOSING, QuerySyntax::SYMBOL_FIELDARGS_ARGVALUEARRAY_CLOSING, QuerySyntax::SYMBOL_FIELDARGS_ARGVALUEOBJECT_CLOSING], QuerySyntax::SYMBOL_FIELDARGS_ARGVALUESTRING_OPENING, QuerySyntax::SYMBOL_FIELDARGS_ARGVALUESTRING_CLOSING)) {
                for ($i = 0; $i < count($fieldArgElems); $i++) {
                    $fieldArg = $fieldArgElems[$i];
                    $fieldArg = trim($fieldArg);
                    // If there is no separator, then the element is the value
                    $separatorPos = QueryUtils::findFirstSymbolPosition(
                        $fieldArg,
                        QuerySyntax::SYMBOL_FIELDARGS_ARGKEYVALUESEPARATOR,
                        [QuerySyntax::SYMBOL_FIELDARGS_OPENING, QuerySyntax::SYMBOL_FIELDARGS_ARGVALUEARRAY_OPENING, QuerySyntax::SYMBOL_FIELDARGS_ARGVALUEOBJECT_OPENING],
                        [QuerySyntax::SYMBOL_FIELDARGS_CLOSING, QuerySyntax::SYMBOL_FIELDARGS_ARGVALUEARRAY_CLOSING, QuerySyntax::SYMBOL_FIELDARGS_ARGVALUEOBJECT_CLOSING],
                    );
                    if ($separatorPos === false) {
                        $fieldArgValue = $fieldArg;
                    } else {
                        $fieldArgValue = trim(substr($fieldArg, $separatorPos + strlen(QuerySyntax::SYMBOL_FIELDARGS_ARGKEYVALUESEPARATOR)));
                    }
                    $fieldOrDirectiveArgValues[] = $fieldArgValue;
                }
            }
        }

        return $fieldOrDirectiveArgValues;
    }
}

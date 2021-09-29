<?php

declare(strict_types=1);

namespace PoP\API\Schema;

use PoP\ComponentModel\Schema\FieldQueryInterpreter as UpstreamFieldQueryInterpreter;
use PoP\FieldQuery\QuerySyntax;
use PoP\FieldQuery\QueryUtils;

class FieldQueryInterpreter extends UpstreamFieldQueryInterpreter implements FieldQueryInterpreterInterface
{
    // Cache the output from functions
    /**
     * @var array<string, array >
     */
    private array $extractedFieldArgumentValuesCache = [];
    /**
     * @var array<string, array >
     */
    private array $extractedDirectiveArgumentValuesCache = [];

    public function extractFieldArgumentValues(string $field): array
    {
        if (!isset($this->extractedFieldArgumentValuesCache[$field])) {
            $this->extractedFieldArgumentValuesCache[$field] = $this->doExtractFieldArgumentValues($field);
        }
        return $this->extractedFieldArgumentValuesCache[$field];
    }

    protected function doExtractFieldArgumentValues(string $field): array
    {
        // Extract the args from the string into an array
        if ($fieldArgsStr = $this->getFieldDirectiveArgs($field)) {
            return $this->doExtractFieldOrDirectiveArgumentValues($fieldArgsStr);
        }

        return [];
    }

    public function extractDirectiveArgumentValues(string $field): array
    {
        if (!isset($this->extractedDirectiveArgumentValuesCache[$field])) {
            $this->extractedDirectiveArgumentValuesCache[$field] = $this->doExtractDirectiveArgumentValues($field);
        }
        return $this->extractedDirectiveArgumentValuesCache[$field];
    }

    protected function doExtractDirectiveArgumentValues(string $field): array
    {
        // Extract the directives from the field
        if ($fieldDirectives = $this->getFieldDirectives($field)) {
            // Extract the args from the string into an array
            if ($directiveArgsStr = $this->getFieldDirectiveArgs($fieldDirectives)) {
                return $this->doExtractFieldOrDirectiveArgumentValues($directiveArgsStr);
            }
        }

        return [];
    }

    protected function doExtractFieldOrDirectiveArgumentValues(string $fieldOrDirectiveArgsStr): array
    {
        $fieldOrDirectiveArgValues = [];
        // Remove the opening and closing brackets
        $fieldOrDirectiveArgsStr = substr($fieldOrDirectiveArgsStr, strlen(QuerySyntax::SYMBOL_FIELDARGS_OPENING), strlen($fieldOrDirectiveArgsStr) - strlen(QuerySyntax::SYMBOL_FIELDARGS_OPENING) - strlen(QuerySyntax::SYMBOL_FIELDARGS_CLOSING));
        // Remove the white spaces before and after
        if ($fieldOrDirectiveArgsStr = trim($fieldOrDirectiveArgsStr)) {
            // Iterate all the elements, and extract them into the array
            if ($fieldArgElems = $this->queryParser->splitElements($fieldOrDirectiveArgsStr, QuerySyntax::SYMBOL_FIELDARGS_ARGSEPARATOR, [QuerySyntax::SYMBOL_FIELDARGS_OPENING, QuerySyntax::SYMBOL_FIELDARGS_ARGVALUEARRAY_OPENING], [QuerySyntax::SYMBOL_FIELDARGS_CLOSING, QuerySyntax::SYMBOL_FIELDARGS_ARGVALUEARRAY_CLOSING], QuerySyntax::SYMBOL_FIELDARGS_ARGVALUESTRING_OPENING, QuerySyntax::SYMBOL_FIELDARGS_ARGVALUESTRING_CLOSING)) {
                for ($i = 0; $i < count($fieldArgElems); $i++) {
                    $fieldArg = $fieldArgElems[$i];
                    // If there is no separator, then the element is the value
                    $separatorPos = QueryUtils::findFirstSymbolPosition(
                        $fieldArg,
                        QuerySyntax::SYMBOL_FIELDARGS_ARGKEYVALUESEPARATOR,
                        [QuerySyntax::SYMBOL_FIELDARGS_OPENING, QuerySyntax::SYMBOL_FIELDARGS_ARGVALUEARRAY_OPENING],
                        [QuerySyntax::SYMBOL_FIELDARGS_CLOSING, QuerySyntax::SYMBOL_FIELDARGS_ARGVALUEARRAY_CLOSING],
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

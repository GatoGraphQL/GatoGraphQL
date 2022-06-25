<?php

declare(strict_types=1);

namespace PoP\FieldQuery;

use PoP\Root\Services\BasicServiceTrait;
use PoP\QueryParsing\QueryParserInterface;
use stdClass;

class FieldQueryInterpreter implements FieldQueryInterpreterInterface
{
    use BasicServiceTrait;

    // Cache the output from functions
    /**
     * @var array<string, string>
     */
    private array $fieldNamesCache = [];
    /**
     * @var array<string, string|null>
     */
    private array $fieldArgsCache = [];
    /**
     * @var array<string, array<string, int>|null>
     */
    private array $fieldAliasPositionSpansCache = [];

    public final const ALIAS_POSITION_KEY = 'pos';
    public final const ALIAS_LENGTH_KEY = 'length';

    private ?FeedbackMessageStoreInterface $feedbackMessageStore = null;
    private ?QueryParserInterface $queryParser = null;

    final public function setFeedbackMessageStore(FeedbackMessageStoreInterface $feedbackMessageStore): void
    {
        $this->feedbackMessageStore = $feedbackMessageStore;
    }
    final protected function getFeedbackMessageStore(): FeedbackMessageStoreInterface
    {
        return $this->feedbackMessageStore ??= $this->instanceManager->getInstance(FeedbackMessageStoreInterface::class);
    }
    final public function setQueryParser(QueryParserInterface $queryParser): void
    {
        $this->queryParser = $queryParser;
    }
    final protected function getQueryParser(): QueryParserInterface
    {
        return $this->queryParser ??= $this->instanceManager->getInstance(QueryParserInterface::class);
    }

    public function getFieldName(string $field): string
    {
        if (!isset($this->fieldNamesCache[$field])) {
            $this->fieldNamesCache[$field] = $this->doGetFieldName($field);
        }
        return $this->fieldNamesCache[$field];
    }

    protected function doGetFieldName(string $field): string
    {
        // Successively search for the position of some edge symbol
        // Everything before "@" (for the alias)
        $pos = QueryHelpers::findFieldAliasSymbolPosition($field);
        if ($pos !== false) {
            $field = trim(substr($field, $pos + strlen(QuerySyntax::SYMBOL_FIELDALIAS_PREFIX)));
        }

        // Everything before "(" (for the fieldArgs)
        list($pos) = QueryHelpers::listFieldArgsSymbolPositions($field);
        // Everything before "?" (for "skip output if null")
        if ($pos === false) {
            $pos = QueryHelpers::findSkipOutputIfNullSymbolPosition($field);
        }
        // Everything before "<" (for the field directive)
        if ($pos === false) {
            list($pos) = QueryHelpers::listFieldDirectivesSymbolPositions($field);
        }
        // If the field name is missing, show an error
        if ($pos === 0) {
            $this->getFeedbackMessageStore()->addQueryError(sprintf(
                $this->__('Name in \'%s\' is missing', 'field-query'),
                $field
            ));
            return '';
        }
        // Extract the query until the found position
        if ($pos !== false) {
            return trim(substr($field, 0, $pos));
        }
        // No fieldArgs, no alias => The field is the fieldName
        // Do trim for it there is a space, passed from copy/pasting the query in the browser (eg: "if (...)")
        return trim($field);
    }

    public function getFieldArgs(string $field): ?string
    {
        if (!isset($this->fieldArgsCache[$field])) {
            $this->fieldArgsCache[$field] = $this->doGetFieldArgs($field);
        }
        return $this->fieldArgsCache[$field];
    }

    protected function doGetFieldArgs(string $field): ?string
    {
        // We check that the format is "$fieldName($prop1;$prop2;...;$propN)"
        // or also with [] at the end: "$fieldName($prop1;$prop2;...;$propN)[somename]"
        list(
            $fieldArgsOpeningSymbolPos,
            $fieldArgsClosingSymbolPos
        ) = QueryHelpers::listFieldArgsSymbolPositions($field);

        // If there are no "(" and ")" then there are no field args
        if ($fieldArgsClosingSymbolPos === false && $fieldArgsOpeningSymbolPos === false) {
            return null;
        }
        // If there is only one of them, it's a query error, so discard the query bit
        if (
            (
                $fieldArgsClosingSymbolPos === false
                && $fieldArgsOpeningSymbolPos !== false
            )
            || (
                $fieldArgsClosingSymbolPos !== false
                && $fieldArgsOpeningSymbolPos === false
            )
        ) {
            $this->getFeedbackMessageStore()->addQueryError(sprintf(
                $this->__(
                    'Arguments \'%s\' must start with symbol \'%s\' and end with symbol \'%s\'',
                    'field-query'
                ),
                $field,
                QuerySyntax::SYMBOL_FIELDARGS_OPENING,
                QuerySyntax::SYMBOL_FIELDARGS_CLOSING
            ));
            return null;
        }

        // We have field args. Extract them, including the brackets
        return substr(
            $field,
            (int)$fieldArgsOpeningSymbolPos,
            $fieldArgsClosingSymbolPos + strlen(QuerySyntax::SYMBOL_FIELDARGS_CLOSING) - $fieldArgsOpeningSymbolPos
        );
    }

    public function isFieldArgumentValueAField(mixed $fieldArgValue): bool
    {
        // If the result fieldArgValue is a string (i.e. not numeric), and it has brackets (...),
        // then it is a field
        return
            !empty($fieldArgValue)
            && is_string($fieldArgValue)
            && substr(
                $fieldArgValue,
                -1 * strlen(QuerySyntax::SYMBOL_FIELDARGS_CLOSING)
            ) == QuerySyntax::SYMBOL_FIELDARGS_CLOSING
            // Please notice: if position is 0 (i.e. for a string "(something)") then it's not a field,
            // since the fieldName is missing
            // Then it's ok asking for strpos: either `false` or `0` must both fail
            && strpos($fieldArgValue, QuerySyntax::SYMBOL_FIELDARGS_OPENING);
    }

    public function isFieldArgumentValueAnExpression(mixed $fieldArgValue): bool
    {
        /**
         * Switched from "%{...}%" to "$__..."
         * @todo Convert expressions from "$__" to "$"
         */
        // // If it starts with "%{" and ends with "}%", it is an expression
        // return
        //     is_string($fieldArgValue)
        //     && substr(
        //         $fieldArgValue,
        //         0,
        //         strlen(QuerySyntax::SYMBOL_EXPRESSION_OPENING)
        //     ) == QuerySyntax::SYMBOL_EXPRESSION_OPENING
        //     && substr(
        //         $fieldArgValue,
        //         -1 * strlen(QuerySyntax::SYMBOL_EXPRESSION_CLOSING)
        //     ) == QuerySyntax::SYMBOL_EXPRESSION_CLOSING;

        // If it starts with "$_", it is a dynamic variable
        return is_string($fieldArgValue) && str_starts_with($fieldArgValue, '$__');
    }

    public function isFieldArgumentValueAVariable(mixed $fieldArgValue): bool
    {
        // If it starts with "$", it is a variable
        return
            is_string($fieldArgValue)
            && substr(
                $fieldArgValue,
                0,
                strlen(QuerySyntax::SYMBOL_VARIABLE_PREFIX)
            ) == QuerySyntax::SYMBOL_VARIABLE_PREFIX
            // If it starts with "$__", it is an expression, not a variable
            && !$this->isFieldArgumentValueAnExpression($fieldArgValue);
    }

    public function isFieldArgumentValueDynamic(mixed $fieldArgValue): bool
    {
        return
            $this->isFieldArgumentValueAField($fieldArgValue) ||
            $this->isFieldArgumentValueAnExpression($fieldArgValue) ||
            $this->isFieldArgumentValueAVariable($fieldArgValue);
    }

    protected function isFieldArgumentValueAnArrayRepresentedAsString(mixed $fieldArgValue): bool
    {
        // If it starts with "[" and finishes with "]"
        return
            is_string($fieldArgValue)
            && substr(
                $fieldArgValue,
                0,
                strlen(QuerySyntax::SYMBOL_FIELDARGS_ARGVALUEARRAY_OPENING)
            ) == QuerySyntax::SYMBOL_FIELDARGS_ARGVALUEARRAY_OPENING
            && substr(
                $fieldArgValue,
                -1 * strlen(QuerySyntax::SYMBOL_FIELDARGS_ARGVALUEARRAY_CLOSING)
            ) == QuerySyntax::SYMBOL_FIELDARGS_ARGVALUEARRAY_CLOSING;
    }

    protected function isFieldArgumentValueAnObjectRepresentedAsString(mixed $fieldArgValue): bool
    {
        // If it starts with "{" and finishes with "}"
        return
            is_string($fieldArgValue)
            && substr(
                $fieldArgValue,
                0,
                strlen(QuerySyntax::SYMBOL_FIELDARGS_ARGVALUEOBJECT_OPENING)
            ) == QuerySyntax::SYMBOL_FIELDARGS_ARGVALUEOBJECT_OPENING
            && substr(
                $fieldArgValue,
                -1 * strlen(QuerySyntax::SYMBOL_FIELDARGS_ARGVALUEOBJECT_CLOSING)
            ) == QuerySyntax::SYMBOL_FIELDARGS_ARGVALUEOBJECT_CLOSING;
    }

    /**
     * Return an array with the position where the alias starts (including the "@") and its length
     * Return null if the field has no alias
     *
     * @return array<string, int>|null
     */
    public function getFieldAliasPositionSpanInField(string $field): ?array
    {
        if (!isset($this->fieldAliasPositionSpansCache[$field])) {
            $this->fieldAliasPositionSpansCache[$field] = $this->doGetFieldAliasPositionSpanInField($field);
        }
        /** @var array */
        $fieldAliasPositionSpans = $this->fieldAliasPositionSpansCache[$field];
        return $fieldAliasPositionSpans;
    }

    /**
     * @return array<string, int>|null
     */
    protected function doGetFieldAliasPositionSpanInField(string $field): ?array
    {
        $aliasSymbolPos = QueryHelpers::findFieldAliasSymbolPosition($field);
        if ($aliasSymbolPos === false) {
            // There is no alias
            return null;
        }

        // Extract the alias, without the "@" symbol
        // $alias = substr($field, $aliasSymbolPos + strlen(QuerySyntax::SYMBOL_FIELDALIAS_PREFIX));
        $alias = substr($field, 0, $aliasSymbolPos);

        // If there is a "]", "?" or "<" after the alias, remove the string from then on
        // Everything before "]" (for if the alias is inside the bookmark)
        list (
            $bookmarkOpeningSymbolPos,
            $pos
        ) = QueryHelpers::listFieldBookmarkSymbolPositions($alias);
        // Everything before "?" (for "skip output if null")
        if ($pos === false) {
            $pos = QueryHelpers::findSkipOutputIfNullSymbolPosition($alias);
        }
        // Everything before "<" (for the field directive)
        if ($pos === false) {
            list($pos) = QueryHelpers::listFieldDirectivesSymbolPositions($alias);
        }
        if ($pos !== false) {
            $alias = substr($alias, 0, $pos);
        }
        // Return an array with the position where the alias starts (including the "@") and its length
        return [
            self::ALIAS_POSITION_KEY => $aliasSymbolPos,
            self::ALIAS_LENGTH_KEY => strlen($alias) + strlen(QuerySyntax::SYMBOL_FIELDALIAS_PREFIX),
        ];
    }

    public function getFieldDirectiveArgs(string $fieldDirective): ?string
    {
        return $this->getFieldArgs($fieldDirective);
    }

    /**
     * @param array<string, mixed> $fieldArgs
     */
    public function getFieldArgsAsString(
        array $fieldArgs,
        bool $addFieldArgSymbolsIfEmpty = false
    ): string {
        if (!$fieldArgs) {
            if ($addFieldArgSymbolsIfEmpty) {
                return
                    QuerySyntax::SYMBOL_FIELDARGS_OPENING .
                    QuerySyntax::SYMBOL_FIELDARGS_CLOSING;
            }
            return '';
        }
        $elems = [];
        foreach ($fieldArgs as $fieldArgKey => $fieldArgValue) {
            // If it is null, the unquoted `null` string will be represented as null
            if ($fieldArgValue === null) {
                $fieldArgValue = 'null';
            } elseif (is_bool($fieldArgValue)) {
                /**
                 * @todo Temporary addition to match `asQueryString` in the AST
                 * Before it printed "1" and "0" as true/false
                 */
                $fieldArgValue = $fieldArgValue ? 'true' : 'false';
            } elseif (is_array($fieldArgValue)) {
                // Convert from array to its representation of array in a string
                $fieldArgValue = $this->getArrayAsStringForQuery($fieldArgValue);
            } elseif ($fieldArgValue instanceof stdClass) {
                // Convert from array to its representation of object in a string
                $fieldArgValue = $this->getObjectAsStringForQuery($fieldArgValue);
            } elseif (is_object($fieldArgValue)) {
                /**
                 * This function accepts objects because it is called
                 * after calling `coerceValue`, so a string like `2020-01-01`
                 * will be transformed to a `Date` object
                 */
                $fieldArgValue = $this->wrapStringInQuotes($this->serializeObject($fieldArgValue));
            } elseif (is_string($fieldArgValue)) {
                // If it doesn't have them yet, wrap the string between quotes for if there's a special symbol
                // inside of it (eg: it if has a ",", it will split the element there when decoding again
                // from string to array in `getField`)
                $fieldArgValue = $this->maybeWrapStringInQuotes($fieldArgValue);
            }
            /**
             * @todo Temporary addition to match `asQueryString` in the AST
             * Added an extra " "
             */
            $elems[] = $fieldArgKey . QuerySyntax::SYMBOL_FIELDARGS_ARGKEYVALUESEPARATOR . ' ' . $fieldArgValue;
        }
        return
            QuerySyntax::SYMBOL_FIELDARGS_OPENING .
            /**
             * @todo Temporary addition to match `asQueryString` in the AST
             * Added an extra " "
             */
            implode(QuerySyntax::SYMBOL_FIELDARGS_ARGSEPARATOR . ' ', $elems) .
            QuerySyntax::SYMBOL_FIELDARGS_CLOSING;
    }

    /**
     * This is the base implementation. Override function whenever
     * the object does not contain `__serialize`
     */
    protected function serializeObject(object $object): string
    {
        return $object->__serialize();
    }

    protected function isStringWrappedInQuotes(string $value): bool
    {
        return
            str_starts_with($value, QuerySyntax::SYMBOL_FIELDARGS_ARGVALUESTRING_OPENING)
            && str_ends_with($value, QuerySyntax::SYMBOL_FIELDARGS_ARGVALUESTRING_CLOSING);
    }

    /**
     * If it is not a function, then wrap the string between quotes to avoid
     * special symbols inside of it generating trouble
     * Eg: it if has a ",", it will split the element there
     * when decoding again from string to array in `getField`
     */
    protected function maybeWrapStringInQuotes(string $value): string
    {
        // Check if it has the quotes already, or if it is a function
        if ($this->isStringWrappedInQuotes($value) || $this->isFieldArgumentValueDynamic($value)) {
            return $value;
        }

        /**
         * If it is an ENUM, don't add quotes.
         *
         * Because we don't know if it is or not, just assume that
         * it's an ENUM if it's a single word where all chars
         * are UPPERCASE or "_" (so horrible!!!!)
         *
         * @todo Remove this code! It is temporary and a hack to convert to PQL, which is being migrated away!
         */
        if (preg_match('/^[A-Z][A-Z_]+$/', $value)) {
            return $value;
        }

        return $this->wrapStringInQuotes($value);
    }

    public function wrapStringInQuotes(string $value): string
    {
        return
            QuerySyntax::SYMBOL_FIELDARGS_ARGVALUESTRING_OPENING .
            $value .
            QuerySyntax::SYMBOL_FIELDARGS_ARGVALUESTRING_CLOSING;
    }

    /**
     * @param array<string, mixed> $fieldArgValue
     */
    public function getArrayAsStringForQuery(array $fieldArgValue): string
    {
        // Iterate through all the elements of the array and, if they are an array themselves,
        // call this function recursively
        $elems = [];
        foreach ($fieldArgValue as $key => $value) {
            // Add the keyValueDelimiter
            if (is_array($value)) {
                $elems[] =
                    /**
                     * @todo Temporary addition to match `asQueryString` in the AST
                     * Do not print the array index
                     */
                    // $key .
                    // QuerySyntax::SYMBOL_FIELDARGS_ARGVALUEARRAY_KEYVALUEDELIMITER .
                    // /**
                    //  * @todo Temporary addition to match `asQueryString` in the AST
                    //  * Added an extra " "
                    //  */
                    // ' ' .
                    $this->getArrayAsStringForQuery($value);
            } elseif ($value instanceof stdClass) {
                $elems[] =
                    /**
                     * @todo Temporary addition to match `asQueryString` in the AST
                     * Do not print the array index
                     */
                    // $key .
                    // QuerySyntax::SYMBOL_FIELDARGS_ARGVALUEOBJECT_KEYVALUEDELIMITER .
                    // /**
                    //  * @todo Temporary addition to match `asQueryString` in the AST
                    //  * Added an extra " "
                    //  */
                    // ' ' .
                    $this->getObjectAsStringForQuery($value);
            } else {
                // If it is null, the unquoted `null` string will be represented as null
                if ($value === null) {
                    $value = 'null';
                } elseif (is_bool($value)) {
                    $value = $value ? 'true' : 'false';
                } elseif (is_string($value)) {
                    // If it doesn't have them yet, wrap the string between quotes for if there's a special symbol
                    // inside of it (eg: it if has a ",", it will split the element there when decoding again
                    // from string to array in `getField`)
                    $value = $this->maybeWrapStringInQuotes($value);
                }
                $elems[] =
                    /**
                     * @todo Temporary addition to match `asQueryString` in the AST
                     * Do not print the array index
                     */
                    // $key .
                    // QuerySyntax::SYMBOL_FIELDARGS_ARGVALUEARRAY_KEYVALUEDELIMITER .
                    // /**
                    //  * @todo Temporary addition to match `asQueryString` in the AST
                    //  * Added an extra " "
                    //  */
                    // ' ' .
                    $value;
            }
        }
        return
            QuerySyntax::SYMBOL_FIELDARGS_ARGVALUEARRAY_OPENING .
            implode(
                QuerySyntax::SYMBOL_FIELDARGS_ARGVALUEARRAY_SEPARATOR
                /**
                 * @todo Temporary addition to match `asQueryString` in the AST
                 * Added an extra " "
                 */
                . ' ',
                $elems
            ) .
            QuerySyntax::SYMBOL_FIELDARGS_ARGVALUEARRAY_CLOSING;
    }

    public function getObjectAsStringForQuery(stdClass $fieldArgValue): string
    {
        // Iterate through all the elements of the array and, if they are an stdClass themselves,
        // call this function recursively
        $elems = [];
        foreach ((array) $fieldArgValue as $key => $value) {
            // Add the keyValueDelimiter
            if (is_array($value)) {
                $elems[] =
                    $key .
                    QuerySyntax::SYMBOL_FIELDARGS_ARGVALUEOBJECT_KEYVALUEDELIMITER .
                    /**
                     * @todo Temporary addition to match `asQueryString` in the AST
                     * Added an extra " "
                     */
                    ' ' .
                    $this->getArrayAsStringForQuery($value);
            } elseif ($value instanceof stdClass) {
                $elems[] =
                    $key .
                    QuerySyntax::SYMBOL_FIELDARGS_ARGVALUEOBJECT_KEYVALUEDELIMITER .
                    /**
                     * @todo Temporary addition to match `asQueryString` in the AST
                     * Added an extra " "
                     */
                    ' ' .
                    $this->getObjectAsStringForQuery($value);
            } else {
                // If it is null, the unquoted `null` string will be represented as null
                if ($value === null) {
                    $value = 'null';
                } elseif (is_bool($value)) {
                    $value = $value ? 'true' : 'false';
                } elseif (is_string($value)) {
                    // If it doesn't have them yet, wrap the string between quotes for if there's a special symbol
                    // inside of it (eg: it if has a ",", it will split the element there when decoding again
                    // from string to array in `getField`)
                    $value = $this->maybeWrapStringInQuotes($value);
                } elseif (is_object($value)) {
                    /**
                     * This function accepts objects because it is called
                     * after calling `coerceValue`, so a string like `2020-01-01`
                     * will be transformed to a `Date` object
                     */
                    $value = $this->wrapStringInQuotes($this->serializeObject($value));
                }
                $elems[] =
                    $key .
                    QuerySyntax::SYMBOL_FIELDARGS_ARGVALUEOBJECT_KEYVALUEDELIMITER .
                    /**
                     * @todo Temporary addition to match `asQueryString` in the AST
                     * Added an extra " "
                     */
                    ' ' .
                    $value;
            }
        }
        return
            QuerySyntax::SYMBOL_FIELDARGS_ARGVALUEOBJECT_OPENING .
            implode(
                QuerySyntax::SYMBOL_FIELDARGS_ARGVALUEOBJECT_SEPARATOR
                /**
                 * @todo Temporary addition to match `asQueryString` in the AST
                 * Added an extra " "
                 */
                . ' ',
                $elems
            ) .
            QuerySyntax::SYMBOL_FIELDARGS_ARGVALUEOBJECT_CLOSING;
    }

    protected function getFieldAliasAsString(?string $fieldAlias = null): string
    {
        if (!$fieldAlias) {
            return '';
        }
        /**
         * @todo Temporary addition to match `asQueryString` in the AST
         * Added an extra " "
         */
        return $fieldAlias . QuerySyntax::SYMBOL_FIELDALIAS_PREFIX . ' ';
    }

    protected function getFieldSkipOutputIfNullAsString(?bool $skipOutputIfNull = false): string
    {
        if (!$skipOutputIfNull) {
            return '';
        }
        return QuerySyntax::SYMBOL_SKIPOUTPUTIFNULL;
    }
}

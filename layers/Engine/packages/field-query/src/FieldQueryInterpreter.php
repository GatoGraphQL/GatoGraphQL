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

    public function wrapStringInQuotes(string $value): string
    {
        return
            QuerySyntax::SYMBOL_FIELDARGS_ARGVALUESTRING_OPENING .
            $value .
            QuerySyntax::SYMBOL_FIELDARGS_ARGVALUESTRING_CLOSING;
    }
}

<?php

declare(strict_types=1);

namespace PoP\FieldQuery;

use PoP\Root\Services\BasicServiceTrait;
use PoP\QueryParsing\QueryParserInterface;
use PoP\Root\App;
use PoP\Root\Component as RootComponent;
use PoP\Root\ComponentConfiguration as RootComponentConfiguration;
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
     * @var array<string, bool>
     */
    private array $skipOutputIfNullCache = [];
    /**
     * @var array<string, string|null>
     */
    private array $fieldAliasesCache = [];
    /**
     * @var array<string, array<string, int>|null>
     */
    private array $fieldAliasPositionSpansCache = [];
    /**
     * @var array<string, string|null>
     */
    private array $fieldDirectivesCache = [];
    /**
     * @var array<string, array>
     */
    private array $directivesCache = [];
    /**
     * @var array<string, array>
     */
    private array $extractedFieldDirectivesCache = [];
    /**
     * @var array<string, string>
     */
    private array $fieldOutputKeysCache = [];

    // Cache vars to take from the request
    /**
     * @var array<string, mixed>|null
     */
    private ?array $variablesFromRequestCache = null;

    public const ALIAS_POSITION_KEY = 'pos';
    public const ALIAS_LENGTH_KEY = 'length';

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
        // Everything before "(" (for the fieldArgs)
        list($pos) = QueryHelpers::listFieldArgsSymbolPositions($field);
        // Everything before "@" (for the alias)
        if ($pos === false) {
            $pos = QueryHelpers::findFieldAliasSymbolPosition($field);
        }
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

    /**
     * @return array<string, mixed>
     */
    public function getVariablesFromRequest(): array
    {
        if (is_null($this->variablesFromRequestCache)) {
            $this->variablesFromRequestCache = $this->doGetVariablesFromRequest();
        }
        return $this->variablesFromRequestCache;
    }

    /**
     * @return array<string, mixed>
     */
    protected function doGetVariablesFromRequest(): array
    {
        /** @var RootComponentConfiguration */
        $rootComponentConfiguration = App::getComponent(RootComponent::class)->getConfiguration();
        if (!$rootComponentConfiguration->enablePassingStateViaRequest()) {
            return [];
        }

        // Watch out! GraphiQL also uses the "variables" URL param, but as a string
        // Hence, check if this param is an array, and only then process it
        return array_merge(
            App::getRequest()->query->all(),
            App::getRequest()->request->all(),
            App::getRequest()->query->has('variables') && is_array(App::getRequest()->query->all()['variables']) ? App::getRequest()->query->all()['variables'] : [],
            App::getRequest()->request->has('variables') && is_array(App::getRequest()->request->all()['variables']) ? App::getRequest()->request->all()['variables'] : []
        );
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

    public function isSkipOuputIfNullField(string $field): bool
    {
        if (!isset($this->skipOutputIfNullCache[$field])) {
            $this->skipOutputIfNullCache[$field] = $this->doIsSkipOuputIfNullField($field);
        }
        return $this->skipOutputIfNullCache[$field];
    }

    protected function doIsSkipOuputIfNullField(string $field): bool
    {
        return QueryHelpers::findSkipOutputIfNullSymbolPosition($field) !== false;
    }

    public function removeSkipOuputIfNullFromField(string $field): string
    {
        $pos = QueryHelpers::findSkipOutputIfNullSymbolPosition($field);
        if ($pos !== false) {
            // Replace the "?" with nothing
            $field = str_replace(
                QuerySyntax::SYMBOL_SKIPOUTPUTIFNULL,
                '',
                $field
            );
        }
        return $field;
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

    public function createFieldArgValueAsFieldFromFieldName(string $fieldName): string
    {
        return $fieldName . QueryHelpers::getEmptyFieldArgs();
    }

    public function getFieldAlias(string $field): ?string
    {
        if (!isset($this->fieldAliasesCache[$field])) {
            $this->fieldAliasesCache[$field] = $this->doGetFieldAlias($field);
        }
        return $this->fieldAliasesCache[$field];
    }

    protected function doGetFieldAlias(string $field): ?string
    {
        if ($fieldAliasPositionSpan = $this->getFieldAliasPositionSpanInField($field)) {
            $aliasSymbolPos = $fieldAliasPositionSpan[self::ALIAS_POSITION_KEY];
            if ($aliasSymbolPos === 0) {
                // Only there is the alias, nothing to alias to
                $this->getFeedbackMessageStore()->addQueryError(sprintf(
                    $this->__('The field to be aliased in \'%s\' is missing', 'field-query'),
                    $field
                ));
                return null;
            } elseif ($aliasSymbolPos === strlen($field) - 1) {
                // Only the "@" was added, but the alias is missing
                $this->getFeedbackMessageStore()->addQueryError(sprintf(
                    $this->__('Alias in \'%s\' is missing', 'field-query'),
                    $field
                ));
                return null;
            }

            // Extract the alias, without the "@" symbol
            $aliasSymbolLength = $fieldAliasPositionSpan[self::ALIAS_LENGTH_KEY];
            return substr(
                $field,
                $aliasSymbolPos + strlen(QuerySyntax::SYMBOL_FIELDALIAS_PREFIX),
                $aliasSymbolLength - strlen(QuerySyntax::SYMBOL_FIELDALIAS_PREFIX)
            );
        }
        return null;
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
        $alias = substr($field, $aliasSymbolPos + strlen(QuerySyntax::SYMBOL_FIELDALIAS_PREFIX));

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

    public function getFieldDirectives(string $field, bool $includeSyntaxDelimiters = false): ?string
    {
        if (!isset($this->fieldDirectivesCache[$field])) {
            $this->fieldDirectivesCache[$field] = $this->doGetFieldDirectives($field);
        }
        // Add the syntax delimiters "<...>" only if the directive is not empty
        return $this->fieldDirectivesCache[$field] && $includeSyntaxDelimiters ?
            (
                QuerySyntax::SYMBOL_FIELDDIRECTIVE_OPENING .
                $this->fieldDirectivesCache[$field] .
                QuerySyntax::SYMBOL_FIELDDIRECTIVE_CLOSING
            ) :
            $this->fieldDirectivesCache[$field];
    }

    protected function doGetFieldDirectives(string $field): ?string
    {
        list(
            $fieldDirectivesOpeningSymbolPos,
            $fieldDirectivesClosingSymbolPos
        ) = QueryHelpers::listFieldDirectivesSymbolPositions($field);

        // If there are no "<" and "." then there is no directive
        if ($fieldDirectivesClosingSymbolPos === false && $fieldDirectivesOpeningSymbolPos === false) {
            return null;
        }
        // If there is only one of them, it's a query error, so discard the query bit
        if (
            (
                $fieldDirectivesClosingSymbolPos === false
                && $fieldDirectivesOpeningSymbolPos !== false
            ) || (
                $fieldDirectivesClosingSymbolPos !== false
                && $fieldDirectivesOpeningSymbolPos === false
            )
        ) {
            $this->getFeedbackMessageStore()->addQueryError(sprintf(
                $this->__(
                    'Directive \'%s\' must start with symbol \'%s\' and end with symbol \'%s\'',
                    'field-query'
                ),
                $field,
                QuerySyntax::SYMBOL_FIELDDIRECTIVE_OPENING,
                QuerySyntax::SYMBOL_FIELDDIRECTIVE_CLOSING
            ));
            return null;
        }

        // We have a field directive. Extract it
        $fieldDirectiveOpeningSymbolStrPos =
            $fieldDirectivesOpeningSymbolPos
            + strlen(QuerySyntax::SYMBOL_FIELDDIRECTIVE_OPENING);
        $fieldDirectiveClosingStrPos = $fieldDirectivesClosingSymbolPos - $fieldDirectiveOpeningSymbolStrPos;
        return substr($field, $fieldDirectiveOpeningSymbolStrPos, $fieldDirectiveClosingStrPos);
    }

    /**
     * @return array<array<string|null>>
     */
    public function getDirectives(string $field): array
    {
        if (!isset($this->directivesCache[$field])) {
            $this->directivesCache[$field] = $this->doGetDirectives($field);
        }
        return $this->directivesCache[$field];
    }

    /**
     * @return array<array<string|null>>
     */
    protected function doGetDirectives(string $field): array
    {
        $fieldDirectives = $this->getFieldDirectives($field, false);
        if (is_null($fieldDirectives)) {
            return [];
        }
        return $this->extractFieldDirectives($fieldDirectives);
    }

    /**
     * @return array<array<string|null>>
     */
    public function extractFieldDirectives(string $fieldDirectives): array
    {
        if (!isset($this->extractedFieldDirectivesCache[$fieldDirectives])) {
            $this->extractedFieldDirectivesCache[$fieldDirectives] = $this->doExtractFieldDirectives($fieldDirectives);
        }
        return $this->extractedFieldDirectivesCache[$fieldDirectives];
    }

    /**
     * @return array<array<string|null>>
     */
    protected function doExtractFieldDirectives(string $fieldDirectives): array
    {
        if (!$fieldDirectives) {
            return [];
        }
        return array_map(
            [$this, 'listFieldDirective'],
            $this->getQueryParser()->splitElements(
                $fieldDirectives,
                QuerySyntax::SYMBOL_FIELDDIRECTIVE_SEPARATOR,
                [
                    QuerySyntax::SYMBOL_FIELDARGS_OPENING,
                    QuerySyntax::SYMBOL_BOOKMARK_OPENING,
                    QuerySyntax::SYMBOL_FIELDDIRECTIVE_OPENING
                ],
                [
                    QuerySyntax::SYMBOL_FIELDARGS_CLOSING,
                    QuerySyntax::SYMBOL_BOOKMARK_CLOSING,
                    QuerySyntax::SYMBOL_FIELDDIRECTIVE_CLOSING
                ],
                QuerySyntax::SYMBOL_FIELDARGS_ARGVALUESTRING_OPENING,
                QuerySyntax::SYMBOL_FIELDARGS_ARGVALUESTRING_CLOSING
            )
        );
    }

    /**
     * @param string[] $fieldDirectives
     */
    public function composeFieldDirectives(array $fieldDirectives): string
    {
        return implode(QuerySyntax::SYMBOL_FIELDDIRECTIVE_SEPARATOR, $fieldDirectives);
    }

    /**
     * @param array<string|null> $directive
     */
    public function convertDirectiveToFieldDirective(array $directive): string
    {
        $directiveArgs = $this->getDirectiveArgs($directive) ?? '';
        $nestedDirectives = $this->getDirectiveNestedDirectives($directive) ?? '';
        return $this->getDirectiveName($directive) . $directiveArgs . $nestedDirectives;
    }

    /**
     * @return array<string|null>
     */
    public function listFieldDirective(string $fieldDirective): array
    {
        // Each item is an array of up to 3 elements: 0 => name, 1 => args, 2 => composed directives
        return [
            $this->getFieldDirectiveName($fieldDirective),
            $this->getFieldDirectiveArgs($fieldDirective),
            $this->getFieldDirectiveNestedDirectives($fieldDirective, true),
        ];
    }

    public function getFieldDirectiveName(string $fieldDirective): string
    {
        return $this->getFieldName($fieldDirective);
    }

    public function getFieldDirectiveArgs(string $fieldDirective): ?string
    {
        return $this->getFieldArgs($fieldDirective);
    }

    public function getFieldDirectiveNestedDirectives(string $fieldDirective, bool $includeSyntaxDelimiters = false): ?string
    {
        return $this->getFieldDirectives($fieldDirective, $includeSyntaxDelimiters);
    }

    /**
     * @param array<string, mixed> $directiveArgs
     */
    public function getFieldDirective(string $directiveName, array $directiveArgs): string
    {
        return $this->getField($directiveName, $directiveArgs);
    }

    /**
     * @param array<string|null> $directive
     */
    public function getDirectiveName(array $directive): string
    {
        return (string)$directive[0];
    }

    /**
     * @param array<string|null> $directive
     */
    public function getDirectiveArgs(array $directive): ?string
    {
        return $directive[1] ?? null;
    }

    /**
     * @param array<string|null> $directive
     */
    public function getDirectiveNestedDirectives(array $directive): ?string
    {
        return $directive[2] ?? null;
    }

    public function getDirectiveOutputKey(string $fieldDirective): string
    {
        return $this->getFieldOutputKey($fieldDirective);
    }

    public function getFieldOutputKey(string $field): string
    {
        if (!isset($this->fieldOutputKeysCache[$field])) {
            $this->fieldOutputKeysCache[$field] = $this->doGetFieldOutputKey($field);
        }
        return $this->fieldOutputKeysCache[$field];
    }

    protected function doGetFieldOutputKey(string $field): string
    {
        // If there is an alias, use this to represent the field
        if ($fieldAlias = $this->getFieldAlias($field)) {
            return $fieldAlias;
        }
        // Otherwise, use fieldName+fieldArgs
        return $this->getNoAliasFieldOutputKey($field);
    }
    protected function getNoAliasFieldOutputKey(string $field): string
    {
        // Use fieldName+fieldArgs
        return $this->getFieldName($field) . $this->getFieldArgs($field);
    }

    /**
     * @return array<string|null>
     */
    public function listField(string $field): array
    {
        if ($fieldAlias = $this->getFieldAlias($field)) {
            $fieldAlias = QuerySyntax::SYMBOL_FIELDALIAS_PREFIX . $fieldAlias;
        }
        return [
            $this->getFieldName($field),
            $this->getFieldArgs($field),
            $fieldAlias,
            $this->getFieldSkipOutputIfNullAsString($this->isSkipOuputIfNullField($field)),
            $this->getFieldDirectives($field, true),
        ];
    }

    /**
     * @param array<string, mixed> $fieldArgs
     * @param array<array<string|null>>|null $fieldDirectives
     */
    public function getField(
        string $fieldName,
        array $fieldArgs,
        ?string $fieldAlias = null,
        bool $skipOutputIfNull = false,
        ?array $fieldDirectives = [],
        bool $addFieldArgSymbolsIfEmpty = false
    ): string {
        return
            $fieldName .
            $this->getFieldArgsAsString($fieldArgs, $addFieldArgSymbolsIfEmpty) .
            $this->getFieldAliasAsString($fieldAlias) .
            $this->getFieldSkipOutputIfNullAsString($skipOutputIfNull) .
            $this->getFieldDirectivesAsString($fieldDirectives ?? []);
    }

    public function composeField(
        string $fieldName,
        ?string $fieldArgs = '',
        ?string $fieldAlias = '',
        ?string $skipOutputIfNull = '',
        ?string $fieldDirectives = ''
    ): string {
        return $fieldName . ($fieldArgs ?? '') . ($fieldAlias ?? '') . ($skipOutputIfNull ?? '') . ($fieldDirectives ?? '');
    }

    /**
     * @return array<string|null>
     */
    public function composeDirective(
        string $directiveName,
        ?string $directiveArgs = '',
        ?string $directiveNestedDirectives = ''
    ): array {
        return [
            $directiveName,
            $directiveArgs,
            $directiveNestedDirectives,
        ];
    }

    /**
     * @param array<string, mixed> $directiveArgs
     * @return array<string|null>
     */
    public function getDirective(
        string $directiveName,
        array $directiveArgs,
        ?string $directiveNestedDirectives = ''
    ): array {
        return $this->composeDirective(
            $directiveName,
            $this->getDirectiveArgsAsString($directiveArgs),
            $directiveNestedDirectives ?? ''
        );
    }

    public function composeFieldDirective(
        string $directiveName,
        ?string $directiveArgs = '',
        ?string $directiveNestedDirectives = ''
    ): string {
        return $directiveName . ($directiveArgs ?? '') . ($directiveNestedDirectives ?? '');
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
            implode(QuerySyntax::SYMBOL_FIELDARGS_ARGSEPARATOR, $elems) .
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

    /**
     * @param array<string, mixed> $directiveArgs
     */
    public function getDirectiveArgsAsString(array $directiveArgs): string
    {
        return $this->getFieldArgsAsString($directiveArgs);
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
                    $key .
                    QuerySyntax::SYMBOL_FIELDARGS_ARGVALUEARRAY_KEYVALUEDELIMITER .
                    $this->getArrayAsStringForQuery($value);
            } elseif ($value instanceof stdClass) {
                $elems[] =
                    $key .
                    QuerySyntax::SYMBOL_FIELDARGS_ARGVALUEOBJECT_KEYVALUEDELIMITER .
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
                $elems[] = $key . QuerySyntax::SYMBOL_FIELDARGS_ARGVALUEARRAY_KEYVALUEDELIMITER . $value;
            }
        }
        return
            QuerySyntax::SYMBOL_FIELDARGS_ARGVALUEARRAY_OPENING .
            implode(
                QuerySyntax::SYMBOL_FIELDARGS_ARGVALUEARRAY_SEPARATOR,
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
                    $this->getArrayAsStringForQuery($value);
            } elseif ($value instanceof stdClass) {
                $elems[] =
                    $key .
                    QuerySyntax::SYMBOL_FIELDARGS_ARGVALUEOBJECT_KEYVALUEDELIMITER .
                    $this->getObjectAsStringForQuery($value);
            } else {
                // If it is null, the unquoted `null` string will be represented as null
                if ($value === null) {
                    $value = 'null';
                } elseif (is_string($value)) {
                    // If it doesn't have them yet, wrap the string between quotes for if there's a special symbol
                    // inside of it (eg: it if has a ",", it will split the element there when decoding again
                    // from string to array in `getField`)
                    $value = $this->maybeWrapStringInQuotes($value);
                } elseif (is_bool($value)) {
                    $value = $value ? 'true' : 'false';
                } elseif (is_object($value)) {
                    /**
                     * This function accepts objects because it is called
                     * after calling `coerceValue`, so a string like `2020-01-01`
                     * will be transformed to a `Date` object
                     */
                    $value = $this->wrapStringInQuotes($this->serializeObject($value));
                }
                $elems[] = $key . QuerySyntax::SYMBOL_FIELDARGS_ARGVALUEOBJECT_KEYVALUEDELIMITER . $value;
            }
        }
        return
            QuerySyntax::SYMBOL_FIELDARGS_ARGVALUEOBJECT_OPENING .
            implode(
                QuerySyntax::SYMBOL_FIELDARGS_ARGVALUEOBJECT_SEPARATOR,
                $elems
            ) .
            QuerySyntax::SYMBOL_FIELDARGS_ARGVALUEOBJECT_CLOSING;
    }

    protected function getFieldAliasAsString(?string $fieldAlias = null): string
    {
        if (!$fieldAlias) {
            return '';
        }
        return QuerySyntax::SYMBOL_FIELDALIAS_PREFIX . $fieldAlias;
    }

    protected function getFieldSkipOutputIfNullAsString(?bool $skipOutputIfNull = false): string
    {
        if (!$skipOutputIfNull) {
            return '';
        }
        return QuerySyntax::SYMBOL_SKIPOUTPUTIFNULL;
    }

    /**
     * @param array<array<string|null>> $fieldDirectives
     */
    public function getFieldDirectivesAsString(array $fieldDirectives): string
    {
        if (!$fieldDirectives) {
            return '';
        }
        return
            QuerySyntax::SYMBOL_FIELDDIRECTIVE_OPENING .
            implode(QuerySyntax::SYMBOL_FIELDDIRECTIVE_SEPARATOR, array_map(
                function ($fieldDirective) {
                    return $this->composeFieldDirective(
                        (string)$fieldDirective[0],
                        $fieldDirective[1] ?? null,
                        $fieldDirective[2] ?? null
                    );
                },
                $fieldDirectives
            )) .
            QuerySyntax::SYMBOL_FIELDDIRECTIVE_CLOSING;
    }
}

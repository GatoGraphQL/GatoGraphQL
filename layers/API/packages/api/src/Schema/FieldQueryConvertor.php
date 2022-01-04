<?php

declare(strict_types=1);

namespace PoP\API\Schema;

use PoP\API\Component;
use PoP\API\ComponentConfiguration;
use PoP\API\PersistedQueries\PersistedFragmentManagerInterface;
use PoP\API\Schema\FieldQueryInterpreterInterface as APIFieldQueryInterpreterInterface;
use PoP\API\Schema\QuerySyntax as APIQuerySyntax;
use PoP\ComponentModel\Constants\Params;
use PoP\ComponentModel\Schema\FeedbackMessageStoreInterface;
use PoP\ComponentModel\Schema\FieldQueryInterpreterInterface;
use PoP\BasicService\BasicServiceTrait;
use PoP\FieldQuery\QueryHelpers;
use PoP\FieldQuery\QuerySyntax as FieldQueryQuerySyntax;
use PoP\FieldQuery\QueryUtils;
use PoP\QueryParsing\QueryParserInterface;

use function count;
use function strlen;
use function substr;

class FieldQueryConvertor implements FieldQueryConvertorInterface
{
    use BasicServiceTrait;

    // Cache the output from functions
    /**
     * @var array<string, string>
     */
    private array $expandedRelationalPropertiesCache = [];

    // Cache vars to take from the request
    /**
     * @var array<string, mixed>
     */
    private ?array $fragmentsCache = null;
    /**
     * @var array<string, mixed>
     */
    private ?array $fragmentsFromRequestCache = null;
    private ?FeedbackMessageStoreInterface $feedbackMessageStore = null;
    private ?QueryParserInterface $queryParser = null;
    private ?FieldQueryInterpreterInterface $fieldQueryInterpreter = null;
    private ?PersistedFragmentManagerInterface $persistedFragmentManager = null;

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
    final public function setFieldQueryInterpreter(FieldQueryInterpreterInterface $fieldQueryInterpreter): void
    {
        $this->fieldQueryInterpreter = $fieldQueryInterpreter;
    }
    final protected function getFieldQueryInterpreter(): FieldQueryInterpreterInterface
    {
        return $this->fieldQueryInterpreter ??= $this->instanceManager->getInstance(FieldQueryInterpreterInterface::class);
    }
    final public function setPersistedFragmentManager(PersistedFragmentManagerInterface $persistedFragmentManager): void
    {
        $this->persistedFragmentManager = $persistedFragmentManager;
    }
    final protected function getPersistedFragmentManager(): PersistedFragmentManagerInterface
    {
        return $this->persistedFragmentManager ??= $this->instanceManager->getInstance(PersistedFragmentManagerInterface::class);
    }

    public function convertAPIQuery(string $operationDotNotation, ?array $fragments = null): FieldQuerySet
    {
        $fragments ??= $this->getFragments();

        // If it is a string, split the ElemCount with ',', the inner ElemCount with '.', and the inner fields with '|'
        $requestedFields = [];
        $executableFields = [];
        $executeQueryBatchInStrictOrder = ComponentConfiguration::executeQueryBatchInStrictOrder();
        $operationMaxLevels = 0;
        $maxDepth = 0;
        $dotNotations = $this->getQueryParser()->splitElements($operationDotNotation, FieldQueryQuerySyntax::SYMBOL_OPERATIONS_SEPARATOR, [FieldQueryQuerySyntax::SYMBOL_FIELDARGS_OPENING, FieldQueryQuerySyntax::SYMBOL_BOOKMARK_OPENING, FieldQueryQuerySyntax::SYMBOL_FIELDDIRECTIVE_OPENING], [FieldQueryQuerySyntax::SYMBOL_FIELDARGS_CLOSING, FieldQueryQuerySyntax::SYMBOL_BOOKMARK_CLOSING, FieldQueryQuerySyntax::SYMBOL_FIELDDIRECTIVE_CLOSING], FieldQueryQuerySyntax::SYMBOL_FIELDARGS_ARGVALUESTRING_OPENING, FieldQueryQuerySyntax::SYMBOL_FIELDARGS_ARGVALUESTRING_CLOSING);
        foreach ($dotNotations as $dotNotation) {
            // Support a query combining relational and properties:
            // ?field=posts.id|title|author.id|name|posts.id|title|author.name
            // Transform it into:
            // ?field=posts.id|title,posts.author.id|name,posts.author.posts.id|title,posts.author.posts.author.name
            $dotNotation = $this->expandRelationalProperties($dotNotation);

            // Replace all fragment placeholders with the actual fragments
            $replacedDotNotation = [];
            foreach ($this->getQueryParser()->splitElements($dotNotation, FieldQueryQuerySyntax::SYMBOL_QUERYFIELDS_SEPARATOR, [FieldQueryQuerySyntax::SYMBOL_FIELDARGS_OPENING, FieldQueryQuerySyntax::SYMBOL_BOOKMARK_OPENING, FieldQueryQuerySyntax::SYMBOL_FIELDDIRECTIVE_OPENING], [FieldQueryQuerySyntax::SYMBOL_FIELDARGS_CLOSING, FieldQueryQuerySyntax::SYMBOL_BOOKMARK_CLOSING, FieldQueryQuerySyntax::SYMBOL_FIELDDIRECTIVE_CLOSING], FieldQueryQuerySyntax::SYMBOL_FIELDARGS_ARGVALUESTRING_OPENING, FieldQueryQuerySyntax::SYMBOL_FIELDARGS_ARGVALUESTRING_CLOSING) as $commafields) {
                if ($replacedCommaFields = $this->replaceFragments($commafields, $fragments)) {
                    $replacedDotNotation[] = $replacedCommaFields;
                }
            }
            if ($dotNotation = implode(FieldQueryQuerySyntax::SYMBOL_QUERYFIELDS_SEPARATOR, $replacedDotNotation)) {
                // After replacing the fragments, expand relational properties once again, since any such string could have been provided through a fragment
                // Eg: a fragment can contain strings such as "id|author.id"
                $dotNotation = $this->expandRelationalProperties($dotNotation);

                // Allow for bookmarks, similar to GraphQL: https://graphql.org/learn/queries/#bookmarks
                // The bookmark "prev" (under constant TOKEN_BOOKMARK) is a reserved one: it always refers to the previous query node
                $bookmarkPaths = [];
                $operationMaxLevels = 0;

                // Split the ElemCount by ",". Use `splitElements` instead of `explode` so that the "," can also be inside the fieldArgs
                $commafieldSet = $this->getQueryParser()->splitElements($dotNotation, FieldQueryQuerySyntax::SYMBOL_QUERYFIELDS_SEPARATOR, [FieldQueryQuerySyntax::SYMBOL_FIELDARGS_OPENING, FieldQueryQuerySyntax::SYMBOL_BOOKMARK_OPENING, FieldQueryQuerySyntax::SYMBOL_FIELDDIRECTIVE_OPENING], [FieldQueryQuerySyntax::SYMBOL_FIELDARGS_CLOSING, FieldQueryQuerySyntax::SYMBOL_BOOKMARK_CLOSING, FieldQueryQuerySyntax::SYMBOL_FIELDDIRECTIVE_CLOSING], FieldQueryQuerySyntax::SYMBOL_FIELDARGS_ARGVALUESTRING_OPENING, FieldQueryQuerySyntax::SYMBOL_FIELDARGS_ARGVALUESTRING_CLOSING);
                foreach ($commafieldSet as $commafields) {
                    // Initialize the pointer
                    $requestedPointer = &$requestedFields;
                    $executablePointer = &$executableFields;

                    // Add as many "self" as the highest number of levels in the previous operation
                    for ($i = 0; $i < $maxDepth; $i++) {
                        $executablePointer['self'] ??= [];
                        $executablePointer = &$executablePointer['self'];
                    }

                    // The fields are split by "."
                    // Watch out: we need to ignore all instances of "(" and ")" which may happen inside the fieldArg values!
                    // Eg: /api/?query=posts(searchfor:this => ( and this => ) are part of the search too).id|title
                    $dotfields = $this->getQueryParser()->splitElements($commafields, FieldQueryQuerySyntax::SYMBOL_RELATIONALFIELDS_NEXTLEVEL, [FieldQueryQuerySyntax::SYMBOL_FIELDARGS_OPENING, FieldQueryQuerySyntax::SYMBOL_FIELDDIRECTIVE_OPENING], [FieldQueryQuerySyntax::SYMBOL_FIELDARGS_CLOSING, FieldQueryQuerySyntax::SYMBOL_FIELDDIRECTIVE_CLOSING], FieldQueryQuerySyntax::SYMBOL_FIELDARGS_ARGVALUESTRING_OPENING, FieldQueryQuerySyntax::SYMBOL_FIELDARGS_ARGVALUESTRING_CLOSING);

                    if ($executeQueryBatchInStrictOrder) {
                        // Count the depth of each query when doing batching
                        $operationMaxLevels = max(count($dotfields), $operationMaxLevels);
                    }
                    // If there is a path to the node...
                    if (count($dotfields) >= 2) {
                        // If surrounded by "[]", the first element references a bookmark from a previous iteration. If so, retrieve it
                        $firstPathLevel = $dotfields[0];
                        // Remove the fieldDirective, if it has one
                        if ($fieldDirectiveSplit = $this->getQueryParser()->splitElements($firstPathLevel, FieldQueryQuerySyntax::SYMBOL_FIELDDIRECTIVE_OPENING, FieldQueryQuerySyntax::SYMBOL_FIELDARGS_OPENING, FieldQueryQuerySyntax::SYMBOL_FIELDARGS_CLOSING, FieldQueryQuerySyntax::SYMBOL_FIELDARGS_ARGVALUESTRING_OPENING, FieldQueryQuerySyntax::SYMBOL_FIELDARGS_ARGVALUESTRING_CLOSING)) {
                            $firstPathLevel = $fieldDirectiveSplit[0];
                        }
                        if (
                            (substr($firstPathLevel, 0, strlen(FieldQueryQuerySyntax::SYMBOL_BOOKMARK_OPENING)) == FieldQueryQuerySyntax::SYMBOL_BOOKMARK_OPENING) &&
                            (substr($firstPathLevel, -1 * strlen(FieldQueryQuerySyntax::SYMBOL_BOOKMARK_CLOSING)) == FieldQueryQuerySyntax::SYMBOL_BOOKMARK_CLOSING)
                        ) {
                            $bookmark = substr($firstPathLevel, strlen(FieldQueryQuerySyntax::SYMBOL_BOOKMARK_OPENING), strlen($firstPathLevel) - 1 - strlen(FieldQueryQuerySyntax::SYMBOL_BOOKMARK_CLOSING));

                            // If this bookmark was not set...
                            if (!isset($bookmarkPaths[$bookmark])) {
                                // Show an error and discard this element
                                $errorMessage = sprintf(
                                    $this->getTranslationAPI()->__('Query path alias \'%s\' is undefined. Query section \'%s\' has been ignored', 'api'),
                                    $bookmark,
                                    $commafields
                                );
                                $this->getFeedbackMessageStore()->addQueryError($errorMessage);
                                unset($bookmarkPaths[QueryTokens::TOKEN_BOOKMARK_PREV]);
                                continue;
                            }
                            // Replace the first element with the bookmark path
                            array_shift($dotfields);
                            $dotfields = array_merge(
                                $bookmarkPaths[$bookmark],
                                $dotfields
                            );
                        }

                        // At every subpath, it can define a bookmark to that fragment by adding "[bookmarkName]" at its end
                        for ($pathLevel = 0; $pathLevel < count($dotfields) - 1; $pathLevel++) {
                            $errorMessageOrSymbolPositions = $this->validateProperty(
                                $dotfields[$pathLevel],
                                $commafields
                            );

                            // If the validation is a string, then it's an error
                            if (is_string($errorMessageOrSymbolPositions)) {
                                $error = (string)$errorMessageOrSymbolPositions;
                                $this->getFeedbackMessageStore()->addQueryError($error);
                                unset($bookmarkPaths[QueryTokens::TOKEN_BOOKMARK_PREV]);
                                // Exit 2 levels, so it doesn't process the whole query section, not just the property
                                continue 2;
                            }
                            // Otherwise, it is an array with all the symbol positions
                            $symbolPositions = (array)$errorMessageOrSymbolPositions;
                            $dotfields[$pathLevel] = $this->maybeReplaceBookmark($dotfields[$pathLevel], $symbolPositions, $dotfields, $pathLevel, $bookmarkPaths);
                            // Replace the embeddable fields
                            $dotfields[$pathLevel] = $this->maybeReplaceEmbeddableFields($dotfields[$pathLevel]);
                        }

                        // Calculate the new "prev" bookmark path
                        $bookmarkPrevPath = $dotfields;
                        array_pop($bookmarkPrevPath);
                        $bookmarkPaths[QueryTokens::TOKEN_BOOKMARK_PREV] = $bookmarkPrevPath;
                    }

                    // For each item, advance to the last level by following the "."
                    for ($i = 0; $i < count($dotfields) - 1; $i++) {
                        $requestedPointer[$dotfields[$i]] ??= [];
                        $requestedPointer = &$requestedPointer[$dotfields[$i]];

                        $executablePointer[$dotfields[$i]] ??= [];
                        $executablePointer = &$executablePointer[$dotfields[$i]];
                    }

                    // The last level can contain several fields, separated by "|"
                    $pipefields = $dotfields[count($dotfields) - 1];
                    // Use `splitElements` instead of `explode` so that the "|" can also be inside the fieldArgs (eg: order:title|asc)
                    foreach ($this->getQueryParser()->splitElements($pipefields, FieldQueryQuerySyntax::SYMBOL_FIELDPROPERTIES_SEPARATOR, [FieldQueryQuerySyntax::SYMBOL_FIELDARGS_OPENING, FieldQueryQuerySyntax::SYMBOL_FIELDDIRECTIVE_OPENING], [FieldQueryQuerySyntax::SYMBOL_FIELDARGS_CLOSING, FieldQueryQuerySyntax::SYMBOL_FIELDDIRECTIVE_CLOSING], FieldQueryQuerySyntax::SYMBOL_FIELDARGS_ARGVALUESTRING_OPENING, FieldQueryQuerySyntax::SYMBOL_FIELDARGS_ARGVALUESTRING_CLOSING) as $pipefield) {
                        $errorMessageOrSymbolPositions = $this->validateProperty(
                            $pipefield
                        );
                        // If the validation is a string, then it's an error
                        if (is_string($errorMessageOrSymbolPositions)) {
                            $error = (string)$errorMessageOrSymbolPositions;
                            $this->getFeedbackMessageStore()->addQueryError($error);
                            // Exit 1 levels, so it ignores only this property but keeps processing the others
                            continue;
                        }
                        // Otherwise, it is an array with all the symbol positions
                        $symbolPositions = (array)$errorMessageOrSymbolPositions;
                        $pipefield = $this->maybeReplaceBookmark($pipefield, $symbolPositions, $dotfields, count($dotfields) - 1, $bookmarkPaths);
                        // Replace the embeddable fields
                        $pipefield = $this->maybeReplaceEmbeddableFields($pipefield);
                        $requestedPointer[] = $pipefield;
                        $executablePointer[] = $pipefield;
                    }
                }
            }
            if ($executeQueryBatchInStrictOrder) {
                // Get the maximum number of connections in this operation,
                // and add it to the depth for the next operation
                $maxDepth += $operationMaxLevels;
            }
        }
        return new FieldQuerySet($requestedFields, $executableFields);
    }
    protected function maybeReplaceBookmark(string $field, array $symbolPositions, array $fieldPath, int $pathLevel, array &$bookmarkPaths): string
    {
        list(
            $fieldArgsOpeningSymbolPos,
            $fieldArgsClosingSymbolPos,
            $aliasSymbolPos,
            $bookmarkOpeningSymbolPos,
            $bookmarkClosingSymbolPos,
            $skipOutputIfNullSymbolPos,
            $fieldDirectivesOpeningSymbolPos,
            $fieldDirectivesClosingSymbolPos,
        ) = $symbolPositions;

        // If it has both "[" and "]"...
        if ($bookmarkClosingSymbolPos !== false && $bookmarkOpeningSymbolPos !== false) {
            // Extract the bookmark
            $bookmarkStartPos = $bookmarkOpeningSymbolPos + strlen(FieldQueryQuerySyntax::SYMBOL_BOOKMARK_OPENING);
            $bookmark = substr($field, $bookmarkStartPos, $bookmarkClosingSymbolPos - $bookmarkStartPos);

            // If the bookmark starts with "@", it's also a property alias.
            $alias = '';
            if (substr($bookmark, 0, strlen(FieldQueryQuerySyntax::SYMBOL_FIELDALIAS_PREFIX)) == FieldQueryQuerySyntax::SYMBOL_FIELDALIAS_PREFIX) {
                // Add the alias again to the pathLevel item, in the right format:
                // Instead of fieldName[@alias] it is fieldName@alias
                $alias = $bookmark;
                $bookmark = substr($bookmark, strlen(FieldQueryQuerySyntax::SYMBOL_FIELDALIAS_PREFIX));
            }

            // Remove the bookmark from the path. Add the alias again, and keep the fieldDirective "<...>
            $field =
                substr($field, 0, $bookmarkOpeningSymbolPos) .
                $alias .
                (
                    $skipOutputIfNullSymbolPos !== false ?
                        FieldQueryQuerySyntax::SYMBOL_SKIPOUTPUTIFNULL :
                        ''
                ) .
                (
                    $fieldDirectivesOpeningSymbolPos !== false ?
                        substr($field, $fieldDirectivesOpeningSymbolPos) :
                        ''
                );

            // Recalculate the path (all the levels until the pathLevel), and store it to be used on a later iteration
            $bookmarkPath = $fieldPath;
            array_splice($bookmarkPath, $pathLevel + 1);
            $bookmarkPaths[$bookmark] = $bookmarkPath;
            // This works now:
            // ?query=posts(limit:3,search:template)[@posts].id|title,[posts].url
            // Also support appending "@" before the bookmark for the aliases
            // ?query=posts(limit:3,search:template)[@posts].id|title,[@posts].url
            if ($alias) {
                $bookmarkPaths[$alias] = $bookmarkPath;
            }
        }

        return $field;
    }

    /**
     * Support resolving other fields from the same type in field/directive arguments:
     * Replace posts(searchfor: "{{title}}") with posts(searchfor: "sprintf(%s, [title()])")
     */
    protected function maybeReplaceEmbeddableFields(string $field): string
    {
        if (ComponentConfiguration::enableEmbeddableFields()) {
            /**
             * Identify all the fieldArgValues from the string, because
             * embeddable fields can only appear in field/directive arguments
             */
            /** @var APIFieldQueryInterpreterInterface */
            $fieldQueryInterpreter = $this->getFieldQueryInterpreter();
            if ($fieldArgValues = $fieldQueryInterpreter->extractFieldArgumentValues($field)) {
                $field = $this->maybeReplaceEmbeddableFieldOrDirectiveArguments($field, $fieldArgValues);
            }
            $directives = $fieldQueryInterpreter->getDirectives($field);
            foreach ($directives as $directive) {
                $field = $this->replaceEmbeddableFieldsInDirectiveArgs($field, $directive);
            }
        }

        return $field;
    }

    protected function replaceEmbeddableFieldsInDirectiveArgs(string $field, array $directive): string
    {
        /** @var APIFieldQueryInterpreterInterface */
        $fieldQueryInterpreter = $this->getFieldQueryInterpreter();
        list(
            $directiveName,
            $directiveArgsAsStr,
            $nestedDirectives
        ) = $directive;
        if ($directiveArgsAsStr !== null) {
            $directiveArgValues = $fieldQueryInterpreter->extractFieldOrDirectiveArgumentValues($directiveArgsAsStr);
            $field = $this->maybeReplaceEmbeddableFieldOrDirectiveArguments($field, $directiveArgValues);
        }
        // Also apply to the args in the nested directives
        if ($nestedDirectives !== null) {
            $directiveDirective = $fieldQueryInterpreter->convertDirectiveToFieldDirective($directive);
            $directiveDirectives = $fieldQueryInterpreter->getDirectives($directiveDirective);
            foreach ($directiveDirectives as $directive) {
                $field = $this->replaceEmbeddableFieldsInDirectiveArgs($field, $directive);
            }
        }

        return $field;
    }

    /**
     * Support resolving other fields from the same type in field/directive arguments:
     * Replace posts(searchfor: "{{title}}") with posts(searchfor: "title()")
     * Replace posts(searchfor: "{{title}} and {{excerpt}}") with posts(searchfor: "sprintf(%s and %s, [title(), excerpt()])")
     */
    protected function maybeReplaceEmbeddableFieldOrDirectiveArguments(string $field, array $fieldOrDirectiveArgValues): string
    {
        /**
         * Inside the string, everything of pattern "{{field}}" is a field from the same type
         * The field can include arguments:
         * "{{date(d/m/Y)}}" or "{{date(format:d/m/Y)}}"
         * There can be whitespaces before/after the field, not in between fieldName and fieldArgs:
         * "{{  date(d/m/Y)  }}", but not "{{  date  (d/m/Y)  }}"
         *
         * Use a single `sprintf` for all matches.
         * Eg: "title is {{title}} and authorID is {{authorID}}" is replaced
         * as "sprintf(title is %s and authorID is %s, [title(), authorID()])"
         */
        $regex = sprintf(
            '/%s(\s*)([a-zA-Z_][0-9a-zA-Z_]*(%s.*%s)?)(\s*)%s/',
            APIQuerySyntax::SYMBOL_EMBEDDABLE_FIELD_PREFIX,
            FieldQueryQuerySyntax::SYMBOL_FIELDARGS_OPENING,
            FieldQueryQuerySyntax::SYMBOL_FIELDARGS_CLOSING,
            APIQuerySyntax::SYMBOL_EMBEDDABLE_FIELD_SUFFIX
        );
        foreach ($fieldOrDirectiveArgValues as $fieldOrDirectiveArgValue) {
            $matches = [];
            if (
                preg_match_all(
                    $regex,
                    $fieldOrDirectiveArgValue,
                    $matches
                )
            ) {
                /**
                 * Most likely, the argValue is the embedded field.
                 * But the embed might also be inside an InputObject, as when doing:
                 *
                 * ```
                 * appendExpressions: {
                 *   toLang: "{{extract(__value__,translateTo)}}"
                 * }
                 * ```
                 *
                 * In that case, the embedded field will be a substring within the argValue.
                 */
                $embeddedField = $fieldOrDirectiveArgValue;
                // If there is only one item, and it occupies the whole param
                // (eg: echoStr("{{ title }}")), then don't use "sprintf" but that field directly.
                // That is to be able to retrieve objects other than strings
                // (eg: "{{ blockMetadata }}" becomes blockMetadata(), which is an array)
                $isSingleWholeEmbed = false;
                if (count($matches[0]) == 1) {
                    // Check if the embedded field is exactly the requested field
                    // Notice that it has '"' at the beginning and end
                    $embeddedField = $matches[0][0];
                    $embeddedField = $this->getFieldQueryInterpreter()->wrapStringInQuotes($embeddedField);
                    if ($embeddedField == $fieldOrDirectiveArgValue) {
                        $isSingleWholeEmbed = true;
                    }
                }

                /**
                 * Use a single `sprintf` for all matches.
                 * Eg: "title is {{title}} and authorID is {{authorID}}" is replaced
                 * as "sprintf(title is %s and authorID is %s, [title(), authorID()])"
                 *
                 * A field can appear more than once.
                 * Use %1$s instead of %s to handle all instances
                 */
                $fieldEmbeds = array_unique($matches[0]); // ["{{title}}"]
                $fieldNames = array_map('trim', array_unique($matches[2])); // ["title"]
                $fieldCount = count($fieldEmbeds);
                $fields = [];
                $replacedFieldArgValue = $embeddedField;
                for ($i = 0; $i < $fieldCount; $i++) {
                    $replacedFieldArgValue = str_replace(
                        $fieldEmbeds[$i],
                        '%' . ($i + 1) . '$s', // %1$s
                        $replacedFieldArgValue
                    );
                    /**
                     * If the field has no fieldArgs, then add "()" at the end, to make it resolvable
                     */
                    if (!str_ends_with($fieldNames[$i], FieldQueryQuerySyntax::SYMBOL_FIELDARGS_CLOSING)) {
                        $fieldNames[$i] = $this->getFieldQueryInterpreter()->composeField(
                            $fieldNames[$i],
                            $this->getFieldQueryInterpreter()->getFieldArgsAsString([], true)
                        );
                    }
                    $fields[] = $fieldNames[$i];
                }
                // If the embedded field is the whole arg value, use that field directly
                // Otherwise concatenate the different parts as a string, use "sprintf"
                $replacedFieldArgValue = $isSingleWholeEmbed ?
                    $fields[0]
                    : $this->getFieldQueryInterpreter()->getField(
                        'sprintf',
                        [
                            'string' => $replacedFieldArgValue,
                            'values' => $fields
                        ]
                    );
                $field = str_replace($embeddedField, $replacedFieldArgValue, $field);
            }
        }

        return $field;
    }

    protected function getFragments(): array
    {
        if (is_null($this->fragmentsCache)) {
            $this->fragmentsCache = $this->doGetFragments();
        }
        return $this->fragmentsCache;
    }

    protected function doGetFragments(): array
    {
        // Request overrides catalogue
        $fragments = array_merge(
            $this->getFragmentsFromCatalogue(),
            $this->getFragmentsFromRequest()
        );

        // Since it's getting values from $_REQUEST, filter out whichever value is not a string
        // Eg: ?someParam['foo'] = 'bar' => $fragments['someParam'] is an array
        $fragments = array_filter(
            $fragments,
            fn (mixed $fragment) => is_string($fragment)
        );

        // Validate that no fragment contains the `;` or `,` symbols
        $this->validateFragments($fragments);

        return $fragments;
    }

    /**
     * Validate that no fragment contains the `;` or `,` symbols
     * @see https://github.com/leoloso/PoP/issues/255
     */
    protected function validateFragments(array &$fragments): void
    {
        $errorMessage = $this->getTranslationAPI()->__('Fragment \'%s\' (which resolves to \'%s\'), cannot contain %s, so it has been ignored', 'api');
        foreach ($fragments as $fragmentName => $fragment) {
            $fragmentDotNotations = $this->getQueryParser()->splitElements($fragment, FieldQueryQuerySyntax::SYMBOL_OPERATIONS_SEPARATOR, [FieldQueryQuerySyntax::SYMBOL_FIELDARGS_OPENING, FieldQueryQuerySyntax::SYMBOL_BOOKMARK_OPENING, FieldQueryQuerySyntax::SYMBOL_FIELDDIRECTIVE_OPENING], [FieldQueryQuerySyntax::SYMBOL_FIELDARGS_CLOSING, FieldQueryQuerySyntax::SYMBOL_BOOKMARK_CLOSING, FieldQueryQuerySyntax::SYMBOL_FIELDDIRECTIVE_CLOSING], FieldQueryQuerySyntax::SYMBOL_FIELDARGS_ARGVALUESTRING_OPENING, FieldQueryQuerySyntax::SYMBOL_FIELDARGS_ARGVALUESTRING_CLOSING);
            if (count($fragmentDotNotations) >= 2) {
                $this->getFeedbackMessageStore()->addQueryError(sprintf(
                    $errorMessage,
                    $fragmentName,
                    $fragment,
                    $this->getTranslationAPI()->__('the `;` symbol (to split operations)', 'api'),
                ));
                unset($fragments[$fragmentName]);
                continue;
            }
            $fragmentCommaFields = $this->getQueryParser()->splitElements($fragment, FieldQueryQuerySyntax::SYMBOL_QUERYFIELDS_SEPARATOR, [FieldQueryQuerySyntax::SYMBOL_FIELDARGS_OPENING, FieldQueryQuerySyntax::SYMBOL_BOOKMARK_OPENING, FieldQueryQuerySyntax::SYMBOL_FIELDDIRECTIVE_OPENING], [FieldQueryQuerySyntax::SYMBOL_FIELDARGS_CLOSING, FieldQueryQuerySyntax::SYMBOL_BOOKMARK_CLOSING, FieldQueryQuerySyntax::SYMBOL_FIELDDIRECTIVE_CLOSING], FieldQueryQuerySyntax::SYMBOL_FIELDARGS_ARGVALUESTRING_OPENING, FieldQueryQuerySyntax::SYMBOL_FIELDARGS_ARGVALUESTRING_CLOSING);
            if (count($fragmentCommaFields) >= 2) {
                $this->getFeedbackMessageStore()->addQueryError(sprintf(
                    $errorMessage,
                    $fragmentName,
                    $fragment,
                    $this->getTranslationAPI()->__('the `,` symbol (to split queries)', 'api'),
                ));
                unset($fragments[$fragmentName]);
            }
        }
    }

    protected function getFragmentsFromCatalogue(): array
    {
        return $this->getPersistedFragmentManager()->getPersistedFragments();
    }

    protected function getFragmentsFromRequest(): array
    {
        if (is_null($this->fragmentsFromRequestCache)) {
            $this->fragmentsFromRequestCache = $this->doGetFragmentsFromRequest();
        }
        return $this->fragmentsFromRequestCache;
    }

    /**
     * Fragments cannot have the same name as expected query params,
     * or they could clash.
     *
     * Eg: this query would lead to an infinite recursion:
     *
     *   ?query=--query
     *
     */
    protected function getForbiddenFragmentNames(): array
    {
        return [
            'fragments',
            'variables',
            QueryInputs::QUERY,
            Params::SCHEME,
            Params::DATASTRUCTURE,
            Params::OUTPUT,
            Params::DATAOUTPUTMODE,
            Params::DATABASESOUTPUTMODE,
            Params::ACTIONS,
            Params::ACTION_PATH,
            Params::DATA_OUTPUT_ITEMS,
            Params::DATA_SOURCE,
            Params::TARGET,
        ];
    }

    protected function doGetFragmentsFromRequest(): array
    {
        // Each fragment is provided through $_REQUEST[fragments][fragmentName] or directly $_REQUEST[fragmentName]
        $fragments = array_merge(
            $_REQUEST,
            $_REQUEST['fragments'] ?? []
        );
        // Remove those query args which, we already know, are not fragments
        foreach ($this->getForbiddenFragmentNames() as $queryParam) {
            unset($fragments[$queryParam]);
        }
        return $fragments;
    }

    protected function expandRelationalProperties(string $dotNotation): string
    {
        if (!isset($this->expandedRelationalPropertiesCache[$dotNotation])) {
            $this->expandedRelationalPropertiesCache[$dotNotation] = $this->doExpandRelationalProperties($dotNotation);
        }
        return $this->expandedRelationalPropertiesCache[$dotNotation];
    }

    protected function doExpandRelationalProperties(string $dotNotation): string
    {
        // Support a query combining relational and properties:
        // ?field=posts.id|title|author.id|name|posts.id|title|author.name
        // Transform it into:
        // ?field=posts.id|title,posts.author.id|name,posts.author.posts.id|title,posts.author.posts.author.name
        // Strategy: continuously search for "." appearing after "|", recreate their full path, and add them as new query sections (separated by ",")
        $expandedDotNotations = [];
        foreach ($this->getQueryParser()->splitElements($dotNotation, FieldQueryQuerySyntax::SYMBOL_QUERYFIELDS_SEPARATOR, [FieldQueryQuerySyntax::SYMBOL_FIELDARGS_OPENING, FieldQueryQuerySyntax::SYMBOL_BOOKMARK_OPENING, FieldQueryQuerySyntax::SYMBOL_FIELDDIRECTIVE_OPENING], [FieldQueryQuerySyntax::SYMBOL_FIELDARGS_CLOSING, FieldQueryQuerySyntax::SYMBOL_BOOKMARK_CLOSING, FieldQueryQuerySyntax::SYMBOL_FIELDDIRECTIVE_CLOSING], FieldQueryQuerySyntax::SYMBOL_FIELDARGS_ARGVALUESTRING_OPENING, FieldQueryQuerySyntax::SYMBOL_FIELDARGS_ARGVALUESTRING_CLOSING) as $commafields) {
            $dotPos = QueryUtils::findFirstSymbolPosition(
                $commafields,
                FieldQueryQuerySyntax::SYMBOL_RELATIONALFIELDS_NEXTLEVEL,
                [FieldQueryQuerySyntax::SYMBOL_FIELDARGS_OPENING, FieldQueryQuerySyntax::SYMBOL_FIELDDIRECTIVE_OPENING],
                [FieldQueryQuerySyntax::SYMBOL_FIELDARGS_CLOSING, FieldQueryQuerySyntax::SYMBOL_FIELDDIRECTIVE_CLOSING],
            );
            if ($dotPos !== false) {
                while ($dotPos !== false) {
                    // Position of the first "|". Everything before there is path + first property
                    // We must make sure the "|" is not inside "()", otherwise this would fail:
                    // /api/graphql/?query=posts(order:title|asc).id|title
                    $pipeElements = $this->getQueryParser()->splitElements($commafields, FieldQueryQuerySyntax::SYMBOL_FIELDPROPERTIES_SEPARATOR, [FieldQueryQuerySyntax::SYMBOL_FIELDARGS_OPENING, FieldQueryQuerySyntax::SYMBOL_FIELDDIRECTIVE_OPENING], [FieldQueryQuerySyntax::SYMBOL_FIELDARGS_CLOSING, FieldQueryQuerySyntax::SYMBOL_FIELDDIRECTIVE_CLOSING], FieldQueryQuerySyntax::SYMBOL_FIELDARGS_ARGVALUESTRING_OPENING, FieldQueryQuerySyntax::SYMBOL_FIELDARGS_ARGVALUESTRING_CLOSING);
                    if (count($pipeElements) >= 2) {
                        $pipePos = strlen($pipeElements[0]);
                        // Make sure the dot is not inside "()". Otherwise this will not work:
                        // /api/graphql/?query=posts(order:title|asc).id|date(format:Y.m.d)
                        $pipeRest = substr($commafields, 0, $pipePos);
                        $dotElements = $this->getQueryParser()->splitElements($pipeRest, FieldQueryQuerySyntax::SYMBOL_RELATIONALFIELDS_NEXTLEVEL, [FieldQueryQuerySyntax::SYMBOL_FIELDARGS_OPENING, FieldQueryQuerySyntax::SYMBOL_FIELDDIRECTIVE_OPENING], [FieldQueryQuerySyntax::SYMBOL_FIELDARGS_CLOSING, FieldQueryQuerySyntax::SYMBOL_FIELDDIRECTIVE_CLOSING], FieldQueryQuerySyntax::SYMBOL_FIELDARGS_ARGVALUESTRING_OPENING, FieldQueryQuerySyntax::SYMBOL_FIELDARGS_ARGVALUESTRING_CLOSING);
                        // Watch out case in which there is no previous sectionPath. Eg: query=id|comments.id
                        if ($lastDotPos = strlen($pipeRest) - strlen($dotElements[count($dotElements) - 1])) {
                            // The path to the properties
                            $sectionPath = substr($commafields, 0, $lastDotPos);
                            // Combination of properties and, possibly, further relational ElemCount
                            $sectionRest = substr($commafields, $lastDotPos);
                        } else {
                            $sectionPath = '';
                            $sectionRest = $commafields;
                        }
                        // If there is another "." after a "|", then it keeps going down the relational path to load other elements
                        $sectionRestPipePos = QueryUtils::findFirstSymbolPosition(
                            $sectionRest,
                            FieldQueryQuerySyntax::SYMBOL_FIELDPROPERTIES_SEPARATOR,
                            [FieldQueryQuerySyntax::SYMBOL_FIELDARGS_OPENING, FieldQueryQuerySyntax::SYMBOL_FIELDDIRECTIVE_OPENING],
                            [FieldQueryQuerySyntax::SYMBOL_FIELDARGS_CLOSING, FieldQueryQuerySyntax::SYMBOL_FIELDDIRECTIVE_CLOSING],
                        );
                        $sectionRestDotPos = QueryUtils::findFirstSymbolPosition(
                            $sectionRest,
                            FieldQueryQuerySyntax::SYMBOL_RELATIONALFIELDS_NEXTLEVEL,
                            [FieldQueryQuerySyntax::SYMBOL_FIELDARGS_OPENING, FieldQueryQuerySyntax::SYMBOL_FIELDDIRECTIVE_OPENING],
                            [FieldQueryQuerySyntax::SYMBOL_FIELDARGS_CLOSING, FieldQueryQuerySyntax::SYMBOL_FIELDDIRECTIVE_CLOSING],
                        );
                        if ($sectionRestPipePos !== false && $sectionRestDotPos !== false && $sectionRestDotPos > $sectionRestPipePos) {
                            // Extract the last property, from which further relational ElemCount are loaded, and create a new query section for it
                            // This is the subtring from the last ocurrence of "|" before the "." up to the "."
                            $lastPipePos = QueryUtils::findLastSymbolPosition(
                                substr(
                                    $sectionRest,
                                    0,
                                    $sectionRestDotPos
                                ),
                                FieldQueryQuerySyntax::SYMBOL_FIELDPROPERTIES_SEPARATOR,
                                [FieldQueryQuerySyntax::SYMBOL_FIELDARGS_OPENING, FieldQueryQuerySyntax::SYMBOL_FIELDDIRECTIVE_OPENING],
                                [FieldQueryQuerySyntax::SYMBOL_FIELDARGS_CLOSING, FieldQueryQuerySyntax::SYMBOL_FIELDDIRECTIVE_CLOSING],
                            );
                            // Extract the new "rest" of the query section
                            $querySectionRest = substr(
                                $sectionRest,
                                $lastPipePos + strlen(FieldQueryQuerySyntax::SYMBOL_FIELDPROPERTIES_SEPARATOR)
                            );
                            // Remove the relational property from the now only properties part
                            $sectionRest = substr(
                                $sectionRest,
                                0,
                                $lastPipePos
                            );
                            // Add these as 2 independent ElemCount to the query
                            $expandedDotNotations[] = $sectionPath . $sectionRest;
                            $commafields = $sectionPath . $querySectionRest;
                            // Keep iterating
                            $dotPos = QueryUtils::findFirstSymbolPosition(
                                $commafields,
                                FieldQueryQuerySyntax::SYMBOL_RELATIONALFIELDS_NEXTLEVEL,
                                [FieldQueryQuerySyntax::SYMBOL_FIELDARGS_OPENING, FieldQueryQuerySyntax::SYMBOL_FIELDDIRECTIVE_OPENING],
                                [FieldQueryQuerySyntax::SYMBOL_FIELDARGS_CLOSING, FieldQueryQuerySyntax::SYMBOL_FIELDDIRECTIVE_CLOSING],
                            );
                        } else {
                            // The element has no further relationships
                            $expandedDotNotations[] = $commafields;
                            // Break out from the cycle
                            break;
                        }
                    } else {
                        // The element has no further relationships
                        $expandedDotNotations[] = $commafields;
                        // Break out from the cycle
                        break;
                    }
                }
            } else {
                // The element has no relationships
                $expandedDotNotations[] = $commafields;
            }
        }

        // Recombine all the elements
        return implode(FieldQueryQuerySyntax::SYMBOL_QUERYFIELDS_SEPARATOR, $expandedDotNotations);
    }

    protected function getFragment($fragmentName, array $fragments): ?string
    {
        // A fragment can itself contain fragments!
        if ($fragment = $fragments[$fragmentName] ?? null) {
            return $this->replaceFragments($fragment, $fragments);
        }
        return null;
    }

    protected function resolveFragmentOrAddError(string $fragment, array $fragments): ?string
    {
        // Replace with the actual fragment
        $fragmentName = substr($fragment, strlen(FieldQueryQuerySyntax::SYMBOL_FRAGMENT_PREFIX));
        // Validate the fragment name is not forbidden
        $forbiddenFragmentNames = $this->getForbiddenFragmentNames();
        if (in_array($fragmentName, $forbiddenFragmentNames)) {
            $this->getFeedbackMessageStore()->addQueryError(sprintf(
                $this->getTranslationAPI()->__('Fragment name \'%s\' is forbidden, please use another one. (All forbidden fragment names are: \'%s\'.)', 'api'),
                $fragmentName,
                implode(
                    $this->getTranslationAPI()->__('\', \'', 'api'),
                    $forbiddenFragmentNames
                )
            ));
            return null;
        }
        $aliasSymbolPos = QueryHelpers::findFieldAliasSymbolPosition($fragmentName);
        $skipOutputIfNullSymbolPos = QueryHelpers::findSkipOutputIfNullSymbolPosition($fragmentName);
        list(
            $fieldDirectivesOpeningSymbolPos,
            $fieldDirectivesClosingSymbolPos
        ) = QueryHelpers::listFieldDirectivesSymbolPositions($fragmentName);
        // If it has an alias, apply the alias to all the elements in the fragment, as an enumerated list
        // Eg: --fragment@list&--fragment=title|content is resolved as title@list1|content@list2
        $alias = '';
        if ($aliasSymbolPos !== false) {
            if ($aliasSymbolPos === 0) {
                // Only there is the alias, nothing to alias to
                $this->getFeedbackMessageStore()->addQueryError(sprintf(
                    $this->getTranslationAPI()->__('The fragment to be aliased in \'%s\' is missing', 'api'),
                    $fragmentName
                ));
                return null;
            } elseif ($aliasSymbolPos === strlen($fragmentName) - 1) {
                // Only the "@" was added, but the alias is missing
                $this->getFeedbackMessageStore()->addQueryError(sprintf(
                    $this->getTranslationAPI()->__('Alias in \'%s\' is missing', 'api'),
                    $fragmentName
                ));
                return null;
            }
            // If there is a "?" or "<" after the alias, remove the string from then on
            // Everything before "?" (for "skip output if null")
            $pos = $skipOutputIfNullSymbolPos;
            // Everything before "<" (for the field directive)
            if ($pos === false) {
                $pos = $fieldDirectivesOpeningSymbolPos;
            }
            // Extract the alias, without the "@" symbol
            if ($pos !== false) {
                $alias = substr($fragmentName, $aliasSymbolPos + strlen(FieldQueryQuerySyntax::SYMBOL_FIELDALIAS_PREFIX), $pos - strlen($fragmentName));
            } else {
                $alias = substr($fragmentName, $aliasSymbolPos + strlen(FieldQueryQuerySyntax::SYMBOL_FIELDALIAS_PREFIX));
            }
        }
        // If it has the "skip output if null" symbol, transfer it to the resolved fragments
        $skipOutputIfNull = false;
        if ($skipOutputIfNullSymbolPos !== false) {
            $skipOutputIfNull = true;
        }
        // If it has a fragment, extract it and then add it again on each component from the fragment
        $fragmentDirectives = '';
        if ($fieldDirectivesOpeningSymbolPos !== false || $fieldDirectivesClosingSymbolPos !== false) {
            // First check both "<" and ">" are present, or it's an error
            if ($fieldDirectivesOpeningSymbolPos === false || $fieldDirectivesClosingSymbolPos === false) {
                $this->getFeedbackMessageStore()->addQueryError(sprintf(
                    $this->getTranslationAPI()->__('Fragment \'%s\' must contain both \'%s\' and \'%s\' to define directives, so it has been ignored', 'api'),
                    $fragmentName,
                    FieldQueryQuerySyntax::SYMBOL_FIELDDIRECTIVE_OPENING,
                    FieldQueryQuerySyntax::SYMBOL_FIELDDIRECTIVE_CLOSING
                ));
                return null;
            }
            $fragmentDirectives = substr($fragmentName, $fieldDirectivesOpeningSymbolPos, $fieldDirectivesClosingSymbolPos);
        }
        // Extract the fragment name
        if ($aliasSymbolPos !== false) {
            $fragmentName = substr($fragmentName, 0, $aliasSymbolPos);
        } elseif ($skipOutputIfNullSymbolPos !== false) {
            $fragmentName = substr($fragmentName, 0, $skipOutputIfNullSymbolPos);
        } elseif ($fieldDirectivesOpeningSymbolPos !== false) {
            $fragmentName = substr($fragmentName, 0, $fieldDirectivesOpeningSymbolPos);
        }
        $fragment = $this->getFragment($fragmentName, $fragments);
        if (!$fragment) {
            $this->getFeedbackMessageStore()->addQueryError(sprintf(
                $this->getTranslationAPI()->__('Fragment \'%s\' is undefined, so it has been ignored', 'api'),
                $fragmentName
            ));
            return null;
        }
        // If the fragment has directives, attach them again to each component from the fragment
        // But only if the component doesn't already have a directive! Otherwise, the directive at the definition level takes priority
        // Same with adding "?" for Skip output if null
        if ($fragmentDirectives || $alias || $skipOutputIfNull) {
            $fragmentPipeFields = $this->getQueryParser()->splitElements($fragment, FieldQueryQuerySyntax::SYMBOL_FIELDPROPERTIES_SEPARATOR, [FieldQueryQuerySyntax::SYMBOL_FIELDARGS_OPENING, FieldQueryQuerySyntax::SYMBOL_FIELDDIRECTIVE_OPENING], [FieldQueryQuerySyntax::SYMBOL_FIELDARGS_CLOSING, FieldQueryQuerySyntax::SYMBOL_FIELDDIRECTIVE_CLOSING], FieldQueryQuerySyntax::SYMBOL_FIELDARGS_ARGVALUESTRING_OPENING, FieldQueryQuerySyntax::SYMBOL_FIELDARGS_ARGVALUESTRING_CLOSING);
            $fragment = implode(FieldQueryQuerySyntax::SYMBOL_FIELDPROPERTIES_SEPARATOR, array_filter(array_map(function ($fragmentField) use ($fragmentDirectives, $alias, $skipOutputIfNull, $fragmentPipeFields) {
                // Calculate if to add the alias
                $fragmentFieldAliasWithSymbol = $fragmentFieldSkipOutputIfNullSymbolPos = null;
                $addAliasToFragmentField = false;
                if ($alias) {
                    $fragmentAliasSymbolPos = QueryHelpers::findFieldAliasSymbolPosition($fragmentField);
                    $addAliasToFragmentField = $fragmentAliasSymbolPos === false;
                    if ($addAliasToFragmentField) {
                        $fragmentFieldAliasWithSymbol = FieldQueryQuerySyntax::SYMBOL_FIELDALIAS_PREFIX . $alias . array_search($fragmentField, $fragmentPipeFields);
                    }
                }
                // Calculate if to add "?"
                $addSkipOutputIfNullToFragmentField = false;
                if ($skipOutputIfNull) {
                    $fragmentFieldSkipOutputIfNullSymbolPos = QueryHelpers::findSkipOutputIfNullSymbolPosition($fragmentField);
                    $addSkipOutputIfNullToFragmentField = $fragmentFieldSkipOutputIfNullSymbolPos === false;
                }
                list(
                    $fragmentFieldDirectivesOpeningSymbolPos,
                    $fragmentFieldDirectivesClosingSymbolPos
                ) = QueryHelpers::listFieldDirectivesSymbolPositions($fragmentField);
                if ($fragmentFieldDirectivesOpeningSymbolPos !== false || $fragmentFieldDirectivesClosingSymbolPos !== false) {
                    // First check both "<" and ">" are present, or it's an error
                    if ($fragmentFieldDirectivesOpeningSymbolPos === false || $fragmentFieldDirectivesClosingSymbolPos === false) {
                        $this->getFeedbackMessageStore()->addQueryError(sprintf(
                            $this->getTranslationAPI()->__('Fragment field \'%s\' must contain both \'%s\' and \'%s\' to define directives, so it has been ignored', 'api'),
                            $fragmentField,
                            FieldQueryQuerySyntax::SYMBOL_FIELDDIRECTIVE_OPENING,
                            FieldQueryQuerySyntax::SYMBOL_FIELDDIRECTIVE_CLOSING
                        ));
                        return null;
                    }
                    // The fragmentField has directives, so prioritize these: do not attach the fragments directives
                    if ($addSkipOutputIfNullToFragmentField) {
                        // Add "?" after the propertyName, before the directive
                        return
                            substr($fragmentField, 0, $fragmentFieldDirectivesOpeningSymbolPos) .
                            ($addAliasToFragmentField ? $fragmentFieldAliasWithSymbol : '') .
                            FieldQueryQuerySyntax::SYMBOL_SKIPOUTPUTIFNULL .
                            substr($fragmentField, $fragmentFieldDirectivesOpeningSymbolPos);
                    }
                    if ($addAliasToFragmentField) {
                        // Either get everything until the already existing "?", or until "<"
                        $delimiterPos = $fragmentFieldSkipOutputIfNullSymbolPos;
                        if ($delimiterPos === false) {
                            $delimiterPos = $fragmentFieldDirectivesOpeningSymbolPos;
                        }
                        if ($delimiterPos) {
                            return
                                substr($fragmentField, 0, $delimiterPos) .
                                $fragmentFieldAliasWithSymbol .
                                substr($fragmentField, $delimiterPos);
                        }
                    }
                    return $fragmentField;
                }
                // Make sure that there is no "?" left in the field, or it may stay added before the "@" for the alias
                $fragmentFieldName = $fragmentField;
                if ($skipOutputIfNull && $fragmentFieldSkipOutputIfNullSymbolPos !== false) {
                    $fragmentFieldName = substr($fragmentFieldName, 0, $fragmentFieldSkipOutputIfNullSymbolPos);
                }
                // Attach the fragment resolution's directives to the field, and maybe the alias and "?"
                return
                    $fragmentFieldName .
                    // Because the alias for elements on the fragment must be distinct, attach to them their position on the fragment
                    ($addAliasToFragmentField ? $fragmentFieldAliasWithSymbol : '') .
                    ($addSkipOutputIfNullToFragmentField ? FieldQueryQuerySyntax::SYMBOL_SKIPOUTPUTIFNULL : '') .
                    $fragmentDirectives;
            }, $fragmentPipeFields)));
        }

        return $fragment;
    }

    protected function replaceFragments(string $commafields, array $fragments): ?string
    {
        // The fields are split by "."
        // Watch out: we need to ignore all instances of "(" and ")" which may happen inside the fieldArg values!
        // Eg: /api/?query=posts(searchfor:this => ( and this => ) are part of the search too).id|title
        $dotfields = $this->getQueryParser()->splitElements($commafields, FieldQueryQuerySyntax::SYMBOL_RELATIONALFIELDS_NEXTLEVEL, [FieldQueryQuerySyntax::SYMBOL_FIELDARGS_OPENING, FieldQueryQuerySyntax::SYMBOL_FIELDDIRECTIVE_OPENING], [FieldQueryQuerySyntax::SYMBOL_FIELDARGS_CLOSING, FieldQueryQuerySyntax::SYMBOL_FIELDDIRECTIVE_CLOSING], FieldQueryQuerySyntax::SYMBOL_FIELDARGS_ARGVALUESTRING_OPENING, FieldQueryQuerySyntax::SYMBOL_FIELDARGS_ARGVALUESTRING_CLOSING);

        // Replace all fragment placeholders with the actual fragments
        // Do this at the beginning, because the fragment may contain new leaves, which need be at the last level of the $dotfields array. So this array must be recalculated after replacing the fragments in
        // Iterate from right to left, because after replacing the fragment in, the length of $dotfields may increase
        // Right now only for the properties. For the path will be done immediately after
        $lastLevel = count($dotfields) - 1;
        // Replace fragments for the properties, adding them to temporary variable $lastLevelProperties
        $pipefields = $this->getQueryParser()->splitElements($dotfields[$lastLevel], FieldQueryQuerySyntax::SYMBOL_FIELDPROPERTIES_SEPARATOR, [FieldQueryQuerySyntax::SYMBOL_FIELDARGS_OPENING, FieldQueryQuerySyntax::SYMBOL_FIELDDIRECTIVE_OPENING], [FieldQueryQuerySyntax::SYMBOL_FIELDARGS_CLOSING, FieldQueryQuerySyntax::SYMBOL_FIELDDIRECTIVE_CLOSING], FieldQueryQuerySyntax::SYMBOL_FIELDARGS_ARGVALUESTRING_OPENING, FieldQueryQuerySyntax::SYMBOL_FIELDARGS_ARGVALUESTRING_CLOSING);
        $lastPropertyNumber = count($pipefields) - 1;
        $lastLevelProperties = [];
        for ($propertyNumber = 0; $propertyNumber <= $lastPropertyNumber; $propertyNumber++) {
            // If it starts with "--", then it's a fragment
            $pipeField = $pipefields[$propertyNumber];
            if (substr($pipeField, 0, strlen(FieldQueryQuerySyntax::SYMBOL_FRAGMENT_PREFIX)) == FieldQueryQuerySyntax::SYMBOL_FRAGMENT_PREFIX) {
                // Replace with the actual fragment
                $resolvedFragment = $this->resolveFragmentOrAddError($pipeField, $fragments);
                if (is_null($resolvedFragment)) {
                    continue;
                }
                $lastLevelProperties[] = $resolvedFragment;
            } else {
                $lastLevelProperties[] = $pipeField;
            }
        }
        // Assign variable $lastLevelProperties (which contains the replaced fragments) back to the last level of $dotfields
        $dotfields[$lastLevel] = implode(FieldQueryQuerySyntax::SYMBOL_FIELDPROPERTIES_SEPARATOR, $lastLevelProperties);

        // Now replace fragments for properties
        for ($pathLevel = $lastLevel - 1; $pathLevel >= 0; $pathLevel--) {
            // If it starts with "--", then it's a fragment
            $pipeField = $dotfields[$pathLevel];
            if (substr($pipeField, 0, strlen(FieldQueryQuerySyntax::SYMBOL_FRAGMENT_PREFIX)) == FieldQueryQuerySyntax::SYMBOL_FRAGMENT_PREFIX) {
                // Replace with the actual fragment
                $resolvedFragment = $this->resolveFragmentOrAddError($pipeField, $fragments);
                if (is_null($resolvedFragment)) {
                    $this->getFeedbackMessageStore()->addQueryError(sprintf(
                        $this->getTranslationAPI()->__('Because fragment \'%s\' has errors, query section \'%s\' has been ignored', 'api'),
                        $pipeField,
                        $commafields
                    ));
                    // Remove whole query section
                    return null;
                }
                $fragmentDotfields = $this->getQueryParser()->splitElements($resolvedFragment, FieldQueryQuerySyntax::SYMBOL_RELATIONALFIELDS_NEXTLEVEL, [FieldQueryQuerySyntax::SYMBOL_FIELDARGS_OPENING, FieldQueryQuerySyntax::SYMBOL_FIELDDIRECTIVE_OPENING], [FieldQueryQuerySyntax::SYMBOL_FIELDARGS_CLOSING, FieldQueryQuerySyntax::SYMBOL_FIELDDIRECTIVE_CLOSING], FieldQueryQuerySyntax::SYMBOL_FIELDARGS_ARGVALUESTRING_OPENING, FieldQueryQuerySyntax::SYMBOL_FIELDARGS_ARGVALUESTRING_CLOSING);
                array_splice($dotfields, $pathLevel, 1, $fragmentDotfields);
            }
        }

        // If we reach here, there were no errors with any path level, so add element again on array
        return implode(FieldQueryQuerySyntax::SYMBOL_RELATIONALFIELDS_NEXTLEVEL, $dotfields);
    }

    protected function validateProperty($property, $querySection = null)
    {
        $errorMessageEnd = $querySection ?
            sprintf(
                $this->getTranslationAPI()->__('Query section \'%s\' has been ignored', 'api'),
                $querySection
            ) :
            $this->getTranslationAPI()->__('The property has been ignored', 'api');

        // --------------------------------------------------------
        // Validate correctness of query constituents: fieldArgs, bookmark, skipOutputIfNull, directive
        // --------------------------------------------------------
        // Field Args
        list(
            $fieldArgsOpeningSymbolPos,
            $fieldArgsClosingSymbolPos
        ) = QueryHelpers::listFieldArgsSymbolPositions($property);

        // If it has "(" from the very beginning, then there's no fieldName, it's an error
        if ($fieldArgsOpeningSymbolPos === 0) {
            return sprintf(
                $this->getTranslationAPI()->__('Property \'%s\' is missing the field name. %s', 'api'),
                $property,
                $errorMessageEnd
            );
        }

        // If it has only "(" or ")" but not the other one, it's an error
        if (($fieldArgsClosingSymbolPos === false && $fieldArgsOpeningSymbolPos !== false) || ($fieldArgsClosingSymbolPos !== false && $fieldArgsOpeningSymbolPos === false)) {
            return sprintf(
                $this->getTranslationAPI()->__('Arguments \'%s\' must start with symbol \'%s\' and end with symbol \'%s\'. %s', 'api'),
                $property,
                FieldQueryQuerySyntax::SYMBOL_FIELDARGS_OPENING,
                FieldQueryQuerySyntax::SYMBOL_FIELDARGS_CLOSING,
                $errorMessageEnd
            );
        }

        // Bookmarks
        list(
            $bookmarkOpeningSymbolPos,
            $bookmarkClosingSymbolPos
        ) = QueryHelpers::listFieldBookmarkSymbolPositions($property);

        // If it has "[" from the very beginning, then there's no fieldName, it's an error
        if ($bookmarkOpeningSymbolPos === 0) {
            return sprintf(
                $this->getTranslationAPI()->__('Property \'%s\' is missing the field name. %s', 'api'),
                $property,
                $errorMessageEnd
            );
        }

        // If it has only "[" or "]" but not the other one, it's an error
        if (($bookmarkClosingSymbolPos === false && $bookmarkOpeningSymbolPos !== false) || ($bookmarkClosingSymbolPos !== false && $bookmarkOpeningSymbolPos === false)) {
            return sprintf(
                $this->getTranslationAPI()->__('Bookmark \'%s\' must start with symbol \'%s\' and end with symbol \'%s\'. %s', 'api'),
                $property,
                FieldQueryQuerySyntax::SYMBOL_BOOKMARK_OPENING,
                FieldQueryQuerySyntax::SYMBOL_BOOKMARK_CLOSING,
                $errorMessageEnd
            );
        }

        // Field Directives
        list(
            $fieldDirectivesOpeningSymbolPos,
            $fieldDirectivesClosingSymbolPos
        ) = QueryHelpers::listFieldDirectivesSymbolPositions($property);

        // If it has "<" from the very beginning, then there's no fieldName, it's an error
        if ($fieldDirectivesOpeningSymbolPos === 0) {
            return sprintf(
                $this->getTranslationAPI()->__('Property \'%s\' is missing the field name. %s', 'api'),
                $property,
                $errorMessageEnd
            );
        }

        // If it has only "[" or "]" but not the other one, it's an error
        if (($fieldDirectivesClosingSymbolPos === false && $fieldDirectivesOpeningSymbolPos !== false) || ($fieldDirectivesClosingSymbolPos !== false && $fieldDirectivesOpeningSymbolPos === false)) {
            return sprintf(
                $this->getTranslationAPI()->__('Directive \'%s\' must start with symbol \'%s\' and end with symbol \'%s\'. %s', 'api'),
                $property,
                FieldQueryQuerySyntax::SYMBOL_FIELDDIRECTIVE_OPENING,
                FieldQueryQuerySyntax::SYMBOL_FIELDDIRECTIVE_CLOSING,
                $errorMessageEnd
            );
        }

        // --------------------------------------------------------
        // Validate correctness of order of elements: ...(...)[...]<...>
        // (0. field name, 1. field args, 2. bookmarks, 3. skip output if null?, 4. field directives)
        // --------------------------------------------------------
        if ($fieldArgsOpeningSymbolPos !== false) {
            if ($fieldArgsOpeningSymbolPos == 0) {
                return sprintf(
                    $this->getTranslationAPI()->__('Name is missing in property \'%s\'. %s', 'api'),
                    $property,
                    $errorMessageEnd
                );
            }
        }

        // After the ")", it must be either the end, "@", "[", "?" or "<"
        $aliasSymbolPos = QueryHelpers::findFieldAliasSymbolPosition($property);
        $skipOutputIfNullSymbolPos = QueryHelpers::findSkipOutputIfNullSymbolPosition($property);
        if ($fieldArgsClosingSymbolPos !== false) {
            $nextCharPos = $fieldArgsClosingSymbolPos + strlen(FieldQueryQuerySyntax::SYMBOL_FIELDARGS_CLOSING);
            if (
                !(
                // It's in the last position
                ($fieldArgsClosingSymbolPos == strlen($property) - strlen(FieldQueryQuerySyntax::SYMBOL_FIELDARGS_CLOSING)) ||
                // Next comes "["
                ($bookmarkOpeningSymbolPos !== false && $bookmarkOpeningSymbolPos == $nextCharPos) ||
                // Next comes "@"
                ($aliasSymbolPos !== false && $aliasSymbolPos == $nextCharPos) ||
                // Next comes "?"
                ($skipOutputIfNullSymbolPos !== false && $skipOutputIfNullSymbolPos == $nextCharPos) ||
                // Next comes "<"
                ($fieldDirectivesOpeningSymbolPos !== false && $fieldDirectivesOpeningSymbolPos == $nextCharPos)
                )
            ) {
                return sprintf(
                    $this->getTranslationAPI()->__('After \'%s\', property \'%s\' must either end or be followed by \'%s\', \'%s\', \'%s\' or \'%s\'. %s', 'api'),
                    FieldQueryQuerySyntax::SYMBOL_FIELDARGS_CLOSING,
                    $property,
                    FieldQueryQuerySyntax::SYMBOL_BOOKMARK_OPENING,
                    FieldQueryQuerySyntax::SYMBOL_FIELDALIAS_PREFIX,
                    FieldQueryQuerySyntax::SYMBOL_SKIPOUTPUTIFNULL,
                    FieldQueryQuerySyntax::SYMBOL_FIELDDIRECTIVE_OPENING,
                    $errorMessageEnd
                );
            }
        }

        // After the "]", it must be either the end, "?" or "<"
        if ($bookmarkClosingSymbolPos !== false) {
            $nextCharPos = $bookmarkClosingSymbolPos + strlen(FieldQueryQuerySyntax::SYMBOL_FIELDARGS_CLOSING);
            if (
                !(
                // It's in the last position
                ($bookmarkClosingSymbolPos == strlen($property) - strlen(FieldQueryQuerySyntax::SYMBOL_BOOKMARK_CLOSING)) ||
                // Next comes "?"
                ($skipOutputIfNullSymbolPos !== false && $skipOutputIfNullSymbolPos == $nextCharPos) ||
                // Next comes "<"
                ($fieldDirectivesOpeningSymbolPos !== false && $fieldDirectivesOpeningSymbolPos == $nextCharPos)
                )
            ) {
                return sprintf(
                    $this->getTranslationAPI()->__('After \'%s\', property \'%s\' must either end or be followed by \'%s\' or \'%s\'. %s', 'api'),
                    FieldQueryQuerySyntax::SYMBOL_BOOKMARK_CLOSING,
                    $property,
                    FieldQueryQuerySyntax::SYMBOL_SKIPOUTPUTIFNULL,
                    FieldQueryQuerySyntax::SYMBOL_FIELDDIRECTIVE_OPENING,
                    $errorMessageEnd
                );
            }
        }

        // After the "?", it must be either the end or "<"
        if ($skipOutputIfNullSymbolPos !== false) {
            $nextCharPos = $skipOutputIfNullSymbolPos + strlen(FieldQueryQuerySyntax::SYMBOL_SKIPOUTPUTIFNULL);
            if (
                !(
                // It's in the last position
                ($skipOutputIfNullSymbolPos == strlen($property) - strlen(FieldQueryQuerySyntax::SYMBOL_SKIPOUTPUTIFNULL)) ||
                // Next comes "<"
                ($fieldDirectivesOpeningSymbolPos !== false && $fieldDirectivesOpeningSymbolPos == $nextCharPos)
                )
            ) {
                return sprintf(
                    $this->getTranslationAPI()->__('After \'%s\', property \'%s\' must either end or be followed by \'%s\'. %s', 'api'),
                    FieldQueryQuerySyntax::SYMBOL_SKIPOUTPUTIFNULL,
                    $property,
                    FieldQueryQuerySyntax::SYMBOL_FIELDDIRECTIVE_OPENING,
                    $errorMessageEnd
                );
            }
        }

        // After the ">", it must be the end
        if ($fieldDirectivesClosingSymbolPos !== false) {
            if (
                !(
                // It's in the last position
                ($fieldDirectivesClosingSymbolPos == strlen($property) - strlen(FieldQueryQuerySyntax::SYMBOL_FIELDDIRECTIVE_CLOSING))
                )
            ) {
                return sprintf(
                    $this->getTranslationAPI()->__('After \'%s\', property \'%s\' must end (there cannot be any extra character). %s', 'api'),
                    FieldQueryQuerySyntax::SYMBOL_FIELDDIRECTIVE_CLOSING,
                    $property,
                    $errorMessageEnd
                );
            }
        }

        return [
            $fieldArgsOpeningSymbolPos,
            $fieldArgsClosingSymbolPos,
            $aliasSymbolPos,
            $bookmarkOpeningSymbolPos,
            $bookmarkClosingSymbolPos,
            $skipOutputIfNullSymbolPos,
            $fieldDirectivesOpeningSymbolPos,
            $fieldDirectivesClosingSymbolPos,
        ];
    }
}

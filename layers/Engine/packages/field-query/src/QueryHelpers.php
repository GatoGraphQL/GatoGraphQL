<?php

declare(strict_types=1);

namespace PoP\FieldQuery;

use PoP\QueryParsing\Facades\QueryParserFacade;

class QueryHelpers
{
    /**
     * @return array<int|false>
     */
    public static function listFieldArgsSymbolPositions(string $field): array
    {
        return [
            QueryUtils::findFirstSymbolPosition(
                $field,
                QuerySyntax::SYMBOL_FIELDARGS_OPENING,
                [
                    QuerySyntax::SYMBOL_FIELDDIRECTIVE_OPENING
                ],
                [
                    QuerySyntax::SYMBOL_FIELDDIRECTIVE_CLOSING
                ]
            ),
            QueryUtils::findLastSymbolPosition(
                $field,
                QuerySyntax::SYMBOL_FIELDARGS_CLOSING,
                [
                    QuerySyntax::SYMBOL_FIELDDIRECTIVE_OPENING
                ],
                [
                    QuerySyntax::SYMBOL_FIELDDIRECTIVE_CLOSING
                ]
            ),
        ];
    }

    /**
     * @return array<int|false>
     */
    public static function listFieldBookmarkSymbolPositions(string $field): array
    {
        return [
            QueryUtils::findFirstSymbolPosition(
                $field,
                QuerySyntax::SYMBOL_BOOKMARK_OPENING,
                [
                    QuerySyntax::SYMBOL_FIELDARGS_OPENING,
                    QuerySyntax::SYMBOL_FIELDDIRECTIVE_OPENING
                ],
                [
                    QuerySyntax::SYMBOL_FIELDARGS_CLOSING,
                    QuerySyntax::SYMBOL_FIELDDIRECTIVE_CLOSING
                ]
            ),
            QueryUtils::findLastSymbolPosition(
                $field,
                QuerySyntax::SYMBOL_BOOKMARK_CLOSING,
                [
                    QuerySyntax::SYMBOL_FIELDARGS_OPENING,
                    QuerySyntax::SYMBOL_FIELDDIRECTIVE_OPENING
                ],
                [
                    QuerySyntax::SYMBOL_FIELDARGS_CLOSING,
                    QuerySyntax::SYMBOL_FIELDDIRECTIVE_CLOSING
                ]
            ),
        ];
    }

    public static function findFieldAliasSymbolPosition(string $field): int|false
    {
        return QueryUtils::findFirstSymbolPosition(
            $field,
            QuerySyntax::SYMBOL_FIELDALIAS_PREFIX,
            [
                QuerySyntax::SYMBOL_FIELDARGS_OPENING,
                QuerySyntax::SYMBOL_FIELDDIRECTIVE_OPENING
            ],
            [
                QuerySyntax::SYMBOL_FIELDARGS_CLOSING,
                QuerySyntax::SYMBOL_FIELDDIRECTIVE_CLOSING
            ]
        );
    }

    public static function findSkipOutputIfNullSymbolPosition(string $field): int|false
    {
        return QueryUtils::findFirstSymbolPosition(
            $field,
            QuerySyntax::SYMBOL_SKIPOUTPUTIFNULL,
            [
                QuerySyntax::SYMBOL_FIELDARGS_OPENING,
                QuerySyntax::SYMBOL_BOOKMARK_OPENING,
                QuerySyntax::SYMBOL_FIELDDIRECTIVE_OPENING
            ],
            [
                QuerySyntax::SYMBOL_FIELDARGS_CLOSING,
                QuerySyntax::SYMBOL_BOOKMARK_CLOSING,
                QuerySyntax::SYMBOL_FIELDDIRECTIVE_CLOSING
            ]
        );
    }

    /**
     * @return array<int|false>
     */
    public static function listFieldDirectivesSymbolPositions(string $field): array
    {
        return [
            QueryUtils::findFirstSymbolPosition(
                $field,
                QuerySyntax::SYMBOL_FIELDDIRECTIVE_OPENING,
                QuerySyntax::SYMBOL_FIELDARGS_OPENING,
                QuerySyntax::SYMBOL_FIELDARGS_CLOSING
            ),
            QueryUtils::findLastSymbolPosition(
                $field,
                QuerySyntax::SYMBOL_FIELDDIRECTIVE_CLOSING,
                QuerySyntax::SYMBOL_FIELDARGS_OPENING,
                QuerySyntax::SYMBOL_FIELDARGS_CLOSING
            ),
        ];
    }

    public static function getEmptyFieldArgs(): string
    {
        return QuerySyntax::SYMBOL_FIELDARGS_OPENING . QuerySyntax::SYMBOL_FIELDARGS_CLOSING;
    }

    /**
     * @return string[]
     */
    public static function getFieldArgElements(?string $fieldArgsAsString): array
    {
        if ($fieldArgsAsString) {
            // Remove the opening and closing brackets
            $fieldArgsAsString = substr(
                $fieldArgsAsString,
                strlen(QuerySyntax::SYMBOL_FIELDARGS_OPENING),
                (
                    strlen($fieldArgsAsString)
                    - strlen(QuerySyntax::SYMBOL_FIELDARGS_OPENING)
                    - strlen(QuerySyntax::SYMBOL_FIELDARGS_CLOSING)
                )
            );
            // Remove the white spaces before and after
            $fieldArgsAsString = trim($fieldArgsAsString);
            // Use `strlen` to allow for "0" as value. Eg: <skip(0)> meaning false
            if (!empty($fieldArgsAsString) || strlen($fieldArgsAsString)) {
                $queryParser = QueryParserFacade::getInstance();
                $fieldArgElements = $queryParser->splitElements(
                    $fieldArgsAsString,
                    QuerySyntax::SYMBOL_FIELDARGS_ARGSEPARATOR,
                    [
                        QuerySyntax::SYMBOL_FIELDARGS_OPENING,
                        QuerySyntax::SYMBOL_FIELDARGS_ARGVALUEARRAY_OPENING,
                        QuerySyntax::SYMBOL_FIELDARGS_ARGVALUEOBJECT_OPENING,
                    ],
                    [
                        QuerySyntax::SYMBOL_FIELDARGS_CLOSING,
                        QuerySyntax::SYMBOL_FIELDARGS_ARGVALUEARRAY_CLOSING,
                        QuerySyntax::SYMBOL_FIELDARGS_ARGVALUEOBJECT_CLOSING,
                    ],
                    QuerySyntax::SYMBOL_FIELDARGS_ARGVALUESTRING_OPENING,
                    QuerySyntax::SYMBOL_FIELDARGS_ARGVALUESTRING_CLOSING
                );
                $fieldArgElements = array_map(
                    'trim',
                    $fieldArgElements
                );
                return $fieldArgElements;
            }
        }
        return [];
    }

    public static function getVariableQuery(string $variableName): string
    {
        return QuerySyntax::SYMBOL_VARIABLE_PREFIX . $variableName;
    }

    public static function getExpressionQuery(string $expressionName): string
    {
        /**
         * Switched from "%{...}%" to "$__..."
         * @todo Convert expressions from "$__" to "$"
         */
        // return QuerySyntax::SYMBOL_EXPRESSION_OPENING . $expressionName . QuerySyntax::SYMBOL_EXPRESSION_CLOSING;
        return '$__' . $expressionName;
    }

    /**
     * @return string[]
     */
    public static function splitFieldDirectives(string $fieldDirectives): array
    {
        $queryParser = QueryParserFacade::getInstance();
        return $queryParser->splitElements(
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
        );
    }
}

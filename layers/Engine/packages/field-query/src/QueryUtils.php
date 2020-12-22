<?php

declare(strict_types=1);

namespace PoP\FieldQuery;

use PoP\QueryParsing\QueryParserOptions;
use PoP\QueryParsing\Facades\QueryParserFacade;

class QueryUtils
{
    /**
     * @param string[]|string|null $skipFromChars
     * @param string[]|string|null $skipUntilChars
     * @return int|false
     */
    public static function findFirstSymbolPosition(
        string $haystack,
        string $needle,
        $skipFromChars = '',
        $skipUntilChars = ''
    ) {
        // Edge case: If the string starts with the symbol, then the array count of splitting the elements will be 1
        if (substr($haystack, 0, strlen($needle)) == $needle) {
            return 0;
        }
        // Split on that searching element: If it appears within the string,
        // it will produce an array with exactly 2 elements (since using option "ONLY_FIRST_OCCURRENCE")
        // The length of the first element equals the position of that symbol
        $fieldQueryInterpreter = QueryParserFacade::getInstance();
        $options = [
            QueryParserOptions::ONLY_FIRST_OCCURRENCE => true,
        ];
        $symbolElems = $fieldQueryInterpreter->splitElements(
            $haystack,
            $needle,
            $skipFromChars,
            $skipUntilChars,
            QuerySyntax::SYMBOL_FIELDARGS_ARGVALUESTRING_OPENING,
            QuerySyntax::SYMBOL_FIELDARGS_ARGVALUESTRING_CLOSING,
            $options
        );
        if (count($symbolElems) == 2) {
            return strlen($symbolElems[0]);
        }
        // Edge case: If the string finishes with the symbol, then the array count of splitting the elements will be 1
        if (substr($haystack, -1 * strlen($needle)) == $needle) {
            return strlen($haystack) - strlen($needle);
        }

        return false;
    }

    /**
     * @param string[]|string|null $skipFromChars
     * @param string[]|string|null $skipUntilChars
     * @return int|false
     */
    public static function findLastSymbolPosition(
        string $haystack,
        string $needle,
        $skipFromChars = '',
        $skipUntilChars = ''
    ) {
        // Edge case: If the string finishes with the symbol, then the array count of splitting the elements will be 1
        if (substr($haystack, -1 * strlen($needle)) == $needle) {
            return strlen($haystack) - strlen($needle);
        }
        // Split on that searching element: If it appears within the string, it will produce
        // an array with exactly 2 elements (since using option "ONLY_FIRST_OCCURRENCE")
        // The length of the first element equals the position of that symbol
        $fieldQueryInterpreter = QueryParserFacade::getInstance();
        $options = [
            QueryParserOptions::START_FROM_END => true,
            QueryParserOptions::ONLY_FIRST_OCCURRENCE => true,
        ];
        $symbolElems = $fieldQueryInterpreter->splitElements(
            $haystack,
            $needle,
            $skipFromChars,
            $skipUntilChars,
            QuerySyntax::SYMBOL_FIELDARGS_ARGVALUESTRING_OPENING,
            QuerySyntax::SYMBOL_FIELDARGS_ARGVALUESTRING_CLOSING,
            $options
        );
        if (count($symbolElems) == 2) {
            return strlen($symbolElems[0]);
        }
        // Edge case: If the string starts with the symbol, then the array count of splitting the elements will be 1
        if (substr($haystack, 0, strlen($needle)) == $needle) {
            return 0;
        }

        return false;
    }
}

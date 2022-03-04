<?php

declare(strict_types=1);

namespace PoP\QueryParsing;

use PoP\Root\Exception\GenericSystemException;
use PoP\Root\Services\BasicServiceTrait;

class QueryParser implements QueryParserInterface
{
    use BasicServiceTrait;

    /**
     * Parse elements by a separator, not failing whenever the separator
     * is also inside the fieldArgs (i.e. inside the brackets "(" and ")")
     * Eg 1: Split elements by "|": ?query=id|posts(limit:3,order:title|ASC)
     * Eg 2: Split elements by ",": ?query=id,posts(ids:1175,1152).id|title
     * @param string[]|string|null $skipFromChars
     * @param string[]|string|null $skipUntilChars
     * @param array<string,mixed> $options
     * @return string[]
     * @see https://stackoverflow.com/a/1084924
     */
    public function splitElements(
        string $query,
        string $separator = ',',
        $skipFromChars = '(',
        $skipUntilChars = ')',
        ?string $ignoreSkippingFromChar = null,
        ?string $ignoreSkippingUntilChar = null,
        array $options = []
    ): array {
        if ($query === '') {
            return [$query];
        }
        $buffer = '';
        $stack = array();
        $depth = 0;
        $len = strlen($query);
        if (!is_array($skipFromChars)) {
            $skipFromChars = [$skipFromChars];
        }
        if (!is_array($skipUntilChars)) {
            $skipUntilChars = [$skipUntilChars];
        }
        /**
         * Watch out! $skipFromChars and $skipUntilChars can only be chars,
         * i.e. strings of length 1, otherwise the function doesn't work.
         *
         * So validate this is the case
         *
         * @see https://github.com/leoloso/PoP/pull/734#issuecomment-871074708
         */
        if (
            $longStrings = array_filter(
                array_unique(array_merge($skipFromChars, $skipUntilChars)),
                fn ($string) => strlen($string) > 1
            )
        ) {
            throw new GenericSystemException(
                sprintf(
                    $this->__('Only strings of length 1 are valid in function `splitElements`, for params `$skipFromChars` and `$skipUntilChars`. The following string(s) are not valid: \'%s\''),
                    implode(
                        $this->__('\', \''),
                        $longStrings
                    )
                )
            );
        }

        // To reduce the amount of "if" statements executed, first ask if the character is any of the special chars
        $specialChars = array_merge(
            [
                $separator,
            ],
            $skipFromChars,
            $skipUntilChars
        );
        // Both $ignoreSkippingFromChar and $ignoreSkippingUntilChar must be provided to be used, only 1 cannot
        if (is_null($ignoreSkippingFromChar) || is_null($ignoreSkippingUntilChar)) {
            $ignoreSkippingFromChar = $ignoreSkippingUntilChar = null;
        } else {
            $specialChars[] = $ignoreSkippingFromChar;
            $specialChars[] = $ignoreSkippingUntilChar;
        }
        $specialChars = array_unique($specialChars);
        // If there is any character that is both in $skipFromChars and $skipUntilChars,
        // then allow only 1 instance of it for starting/closing
        // Potential eg: "%" for demarcating variables
        $skipFromUntilChars = array_intersect(
            $skipFromChars,
            $skipUntilChars
        );
        // From the options we may indicate to stop after either the first or last occurrences are found
        $onlyFirstOccurrence = $options[QueryParserOptions::ONLY_FIRST_OCCURRENCE] ?? false;
        $startFromEnd = $options[QueryParserOptions::START_FROM_END] ?? false;
        // If iterating right to left, we reverse the string, treat closing symbols
        // as opening ones and vice versa, and then inverse once again the results
        // just before returing them
        if ($startFromEnd) {
            // Reverse string
            $query = strrev($query);
            // Treat "skip" from symbols as until, and viceversa
            $temp = $skipFromChars;
            $skipFromChars = $skipUntilChars;
            $skipUntilChars = $temp;
            // Treat "ignore" from symbols as until, and viceversa
            $temp = $ignoreSkippingFromChar;
            $ignoreSkippingFromChar = $ignoreSkippingUntilChar;
            $ignoreSkippingUntilChar = $temp;
        }
        $isInsideSkipFromUntilChars = [];
        $charPos = -1;
        while ($charPos < $len - 1) {
            $charPos++;
            $char = $query[$charPos];
            $charStretch = substr($query, $charPos, strlen($separator));
            if (in_array($char, $specialChars) || $charStretch == $separator) {
                if ($char == $ignoreSkippingFromChar) {
                    // Search the closing symbol and shortcut to that position
                    // (eg: opening then closing quotes for strings)
                    // If it is not there, then treat this char as a normal char
                    // Eg: search:with some quote " is ok
                    $restStrIgnoreSkippingUntilCharPos = strpos($query, (string) $ignoreSkippingUntilChar, $charPos + 1);
                    if ($restStrIgnoreSkippingUntilCharPos !== false) {
                        // Add this stretch of string into the buffer
                        $buffer .= substr($query, $charPos, $restStrIgnoreSkippingUntilCharPos - $charPos + 1);
                        // Continue iterating from that position
                        $charPos = $restStrIgnoreSkippingUntilCharPos;
                        continue;
                    }
                } elseif (in_array($char, $skipFromUntilChars)) {
                    // If first occurrence, flag that from now on we start ignoring the chars,
                    // so everything goes to the buffer
                    if (!$isInsideSkipFromUntilChars[$char]) {
                        $isInsideSkipFromUntilChars[$char] = true;
                        $depth++;
                    } else {
                        // If second occurrence, flag it as false
                        $isInsideSkipFromUntilChars[$char] = false;
                        $depth--;
                    }
                } elseif (in_array($char, $skipFromChars)) {
                    $depth++;
                } elseif (in_array($char, $skipUntilChars)) {
                    if ($depth) {
                        $depth--;
                    } else {
                        // If there can only be one occurrence of "()", then ignore
                        // any "(" and ")" found in between other "()"
                        // Then, we can search by strings like this (notice that the ".", "(" and ")"
                        // inside the search are ignored):
                        // /api/?query=posts(searchfor:(.)).id|title
                        $restStr = substr($query, $charPos + strlen($separator));
                        $restStrEndBracketPos = strpos($restStr, (string) $skipUntilChars[0]);
                        $restStrSeparatorPos = strpos($restStr, $separator);
                        if (
                            $restStrEndBracketPos === false
                            || (
                                $restStrSeparatorPos !== false
                                && $restStrEndBracketPos !== false
                                && $restStrEndBracketPos > $restStrSeparatorPos
                            )
                        ) {
                            $depth--;
                        }
                    }
                } elseif ($charStretch == $separator) {
                    if (!$depth) {
                        if ($buffer !== '') {
                            $stack[] = $buffer;
                            $buffer = '';
                            // If we need only one occurrence, then already return.
                            if ($onlyFirstOccurrence) {
                                $restStr = substr($query, $charPos + strlen($separator));
                                $stack[] = $restStr;
                                if ($startFromEnd) {
                                    // Reverse each result, and the order of the results
                                    $stack = array_reverse(array_map('strrev', $stack));
                                }
                                return $stack;
                            }
                        }
                        // Advance the whole stretch
                        // Minus one because on the new iteration it will do $charPos++ again
                        $charPos = $charPos + strlen($separator) - 1;
                        continue;
                    }
                }
            }
            $buffer .= $char;
        }
        if ($buffer !== '') {
            $stack[] = $buffer;
        }
        if ($startFromEnd) {
            // Reverse each result, and the order of the results
            $stack = array_reverse(array_map('strrev', $stack));
        }
        return $stack;
    }
}

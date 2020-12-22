<?php

declare(strict_types=1);

namespace PoP\QueryParsing;

interface QueryParserInterface
{
    /**
     * Parse elements by a separator, not failing whenever the separator
     * is also inside the fieldArgs (i.e. inside the brackets "(" and ")")
     * Eg 1: Split elements by "|": ?query=id|posts(limit:3,order:title|ASC)
     * Eg 2: Split elements by ",": ?query=id,posts(ids:1175,1152).id|title
     * @param string[]|string|null $skipFromChars
     * @param string[]|string|null $skipUntilChars
     * @param array<string,mixed> $options
     * @return string[]
     */
    public function splitElements(
        string $query,
        string $separator = ',',
        $skipFromChars = '(',
        $skipUntilChars = ')',
        ?string $ignoreSkippingFromChar = null,
        ?string $ignoreSkippingUntilChar = null,
        array $options = []
    ): array;
}

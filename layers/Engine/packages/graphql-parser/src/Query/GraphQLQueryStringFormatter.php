<?php

declare(strict_types=1);

namespace PoP\GraphQLParser\Query;

use PoP\GraphQLParser\Spec\Parser\Ast\ArgumentValue\ArgumentValueAstInterface;
use stdClass;

class GraphQLQueryStringFormatter implements GraphQLQueryStringFormatterInterface
{
    public function getElementAsQueryString(null|int|float|bool|string|array|stdClass $elem): string
    {
        if (is_array($elem)) {
            return $this->getListAsQueryString($elem);
        }
        if ($elem instanceof stdClass) {
            return $this->getObjectAsQueryString($elem);
        }
        return $this->getLiteralAsQueryString($elem);
    }

    /**
     * @param mixed[] $list
     */
    public function getListAsQueryString(array $list): string
    {
        $listStrElems = [];
        foreach ($list as $elem) {
            $listStrElems[] = $elem instanceof ArgumentValueAstInterface
                ? $elem->asQueryString()
                : $this->getElementAsQueryString($elem);
        }
        return sprintf(
            '[%s]',
            implode(', ', $listStrElems)
        );
    }

    public function getObjectAsQueryString(stdClass $object): string
    {
        $objectStrElems = [];
        foreach ((array) $object as $key => $value) {
            $objectStrElems[] = sprintf(
                '%s: %s',
                $key,
                $value instanceof ArgumentValueAstInterface
                    ? $value->asQueryString()
                    : $this->getElementAsQueryString($value)
            );
        }
        return sprintf(
            '{%s}',
            implode(', ', $objectStrElems)
        );
    }

    public function getLiteralAsQueryString(null|int|float|bool|string $literal): string
    {
        if ($literal === null) {
            return 'null';
        }
        if (is_bool($literal)) {
            return $literal ? 'true' : 'false';
        }
        if (is_string($literal)) {
            // String, wrap between quotes
            return sprintf('"%s"', $literal);
        }
        // Numeric: int or float
        return (string)$literal;
    }
}

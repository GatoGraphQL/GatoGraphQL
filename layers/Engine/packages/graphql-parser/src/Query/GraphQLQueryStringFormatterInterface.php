<?php

declare(strict_types=1);

namespace PoP\GraphQLParser\Query;

use stdClass;

interface GraphQLQueryStringFormatterInterface
{
    public function getElementAsQueryString(null|int|float|bool|string|array|stdClass $elem): string;
    public function getListAsQueryString(array $list): string;
    public function getObjectAsQueryString(stdClass $object): string;
    public function getLiteralAsQueryString(null|int|float|bool|string $literal): string;
}

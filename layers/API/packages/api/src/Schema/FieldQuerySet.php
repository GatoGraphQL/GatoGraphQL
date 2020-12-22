<?php

declare(strict_types=1);

namespace PoP\API\Schema;

/**
 * Object containing a pair of [requestedFieldQuery, executableFieldQuery]
 *
 * @author Leonardo Losoviz <leo@getpop.org>
 */
class FieldQuerySet
{
    protected array $requestedFieldQuery;
    protected array $executableFieldQuery;

    public function __construct(
        array $requestedFieldQuery,
        array $executableFieldQuery
    ) {
        $this->requestedFieldQuery = $requestedFieldQuery;
        $this->executableFieldQuery = $executableFieldQuery;
    }

    public function getRequestedFieldQuery(): array
    {
        return $this->requestedFieldQuery;
    }

    public function getExecutableFieldQuery(): array
    {
        return $this->executableFieldQuery;
    }

    public function areRequestedAndExecutableFieldQueriesDifferent(): bool
    {
        return $this->requestedFieldQuery != $this->executableFieldQuery;
    }
}

<?php

declare(strict_types=1);

namespace PoP\ComponentModel\QueryInputOutputHandlers;

use PoP\ComponentModel\Constants\PaginationParams;
use PoP\ComponentModel\Constants\Params;

class ListQueryInputOutputHandler extends AbstractQueryInputOutputHandler
{
    public function prepareQueryArgs(&$query_args): void
    {
        parent::prepareQueryArgs($query_args);

        // Handle edge cases for the limit (for security measures)
        $configuredLimit = $this->getLimit();
        if (isset($query_args[PaginationParams::LIMIT])) {
            $limit = $query_args[PaginationParams::LIMIT];
            if ($limit === -1 || $limit === 0 || $limit > $configuredLimit) {
                $limit = $configuredLimit;
            }
        } else {
            $limit = $configuredLimit;
        }
        $query_args[PaginationParams::LIMIT] = intval($limit);
        $query_args[PaginationParams::PAGE_NUMBER] = isset($query_args[PaginationParams::PAGE_NUMBER]) ? intval($query_args[PaginationParams::PAGE_NUMBER]) : 1;
    }

    protected function getLimit()
    {
        // By default: no limit
        return -1;
    }
}

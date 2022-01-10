<?php

declare(strict_types=1);

namespace PoP\Application\QueryInputOutputHandlers;

use PoP\ComponentModel\Constants\PaginationParams;
use PoP\ComponentModel\Constants\Params;
use PoP\ComponentModel\ModuleProcessors\DataloadingConstants;
use PoP\ComponentModel\State\ApplicationState;

class Utils
{
    public static function stopFetching($dbObjectIDOrIDs, array $data_properties)
    {
        // If data is not to be loaded, then "stop-fetching" as to not show the Load More button
        if ($data_properties[DataloadingConstants::SKIPDATALOAD] ?? null) {
            return true;
        }

        // Do not announce to stop loading when doing loadLatest
        $vars = ApplicationState::getVars();
        if (isset($vars['loading-latest']) && $vars['loading-latest']) {
            return false;
        }

        $query_args = $data_properties[DataloadingConstants::QUERYARGS];

        // Keep loading? (If limit = 0 or -1, this will always return false => keep fetching!)
        // If limit = 0 or -1, then it brought already all the results, so stop fetching
        $limit = $query_args[PaginationParams::LIMIT];
        if ($data_properties[GD_DATALOAD_QUERYHANDLERPROPERTY_LIST_STOPFETCHING] || $limit <= 0) {
            return true;
        }

        return $dbObjectIDOrIDs && is_array($dbObjectIDOrIDs) && count($dbObjectIDOrIDs) < $limit;
    }
}

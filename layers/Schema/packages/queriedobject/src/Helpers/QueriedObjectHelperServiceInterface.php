<?php

declare(strict_types=1);

namespace PoPSchema\QueriedObject\Helpers;

interface QueriedObjectHelperServiceInterface
{
    /**
     * Return the minimum number from between the request limit and the max limit.
     */
    public static function getLimitOrMaxLimit(
        ?int $limit,
        ?int $maxLimit/*, bool $addSchemaWarning = true*/
    ): ?int;
}

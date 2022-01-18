<?php

declare(strict_types=1);

namespace PoPAPI\API\Configuration;

use PoPAPI\API\Schema\QueryInputs;

/**
 * @see layers/Engine/packages/component-model/src/Configuration/EngineRequest.php
 */
class EngineRequest
{
    public static function getQuery(bool $enableModifyingEngineBehaviorViaRequestParams): ?string
    {
        $default = null;
        if (!$enableModifyingEngineBehaviorViaRequestParams) {
            return $default;
        }

        return $_REQUEST[QueryInputs::QUERY] ?? $default;
    }
}

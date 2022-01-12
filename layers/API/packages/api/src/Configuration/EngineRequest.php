<?php

declare(strict_types=1);

namespace PoP\API\Configuration;

use PoP\API\Schema\QueryInputs;

/**
 * @see layers/Engine/packages/component-model/src/Configuration/EngineRequest.php
 */
class EngineRequest
{
    public static function getQuery(bool $enableModifyingEngineBehaviorViaRequestParam): ?string
    {
        $default = null;
        if (!$enableModifyingEngineBehaviorViaRequestParam) {
            return $default;
        }

        return $_REQUEST[QueryInputs::QUERY] ?? $default;
    }
}

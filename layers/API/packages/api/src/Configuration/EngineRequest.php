<?php

declare(strict_types=1);

namespace PoPAPI\API\Configuration;

use PoP\Root\App;
use PoPAPI\API\Schema\QueryInputs;

/**
 * @see layers/Engine/packages/component-model/src/Configuration/EngineRequest.php
 */
class EngineRequest
{
    public static function getQuery(bool $enableModifyingEngineBehaviorViaRequest): ?string
    {
        $default = null;
        if (!$enableModifyingEngineBehaviorViaRequest) {
            return $default;
        }

        return $_POST[QueryInputs::QUERY] ?? App::query(QueryInputs::QUERY, $default);
    }
}

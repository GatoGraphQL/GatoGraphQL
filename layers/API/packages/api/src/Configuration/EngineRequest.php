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
    public static function getQuery(): ?string
    {
        $default = null;
        return App::request(QueryInputs::QUERY) ?? App::query(QueryInputs::QUERY, $default);
    }

    public static function getOperationName(): ?string
    {
        $default = null;
        return App::request(QueryInputs::OPERATION_NAME) ?? App::query(QueryInputs::OPERATION_NAME, $default);
    }

    public static function getPersistedQuery(): ?string
    {
        $default = null;
        return App::request(QueryInputs::PERSISTED_QUERY) ?? App::query(QueryInputs::PERSISTED_QUERY, $default);
    }
}

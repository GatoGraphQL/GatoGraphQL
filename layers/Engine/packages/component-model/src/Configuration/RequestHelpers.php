<?php

declare(strict_types=1);

namespace PoP\ComponentModel\Configuration;

use PoP\ComponentModel\Constants\FrameworkParams;
use PoP\ComponentModel\Misc\GeneralUtils;
use PoP\Root\App;
use PoP\Root\Environment as RootEnvironment;

class RequestHelpers
{
    /**
     * Add all the needed params from the Request into
     * the endpoint:
     *
     * - Mandatory params passed on the request
     * - XDebug params (for debugging on DEV)
     */
    public static function addRequestParamsToEndpoint(string $endpoint): string
    {
        $requestParamValues = array_merge(
            static::getParamValuesFromRequest(),
            static::getXDebugParamValues()
        );
        if ($requestParamValues !== []) {
            return GeneralUtils::addQueryArgs($requestParamValues, $endpoint);
        }
        return $endpoint;
    }

    /**
     * Retrieve all the needed params from the Request.
     *
     * @return array<string,mixed>
     */
    protected static function getParamValuesFromRequest(): array
    {
        $requestParamValues = [];
        $request = App::getRequest();
        foreach (static::getTransferrableToEndpointRequestParams() as $requestParam) {
            if (!$request->query->has($requestParam)) {
                continue;
            }

            /**
             * Use `all` instead of `get` because non-scalar values
             * (eg: arrays) are not supported by `get`
             */
            $requestParamValues[$requestParam] = $request->query->all()[$requestParam];
        }
        return $requestParamValues;
    }

    /**
     * All the Request params that must be transferred
     * to the endpoint
     *
     * @return string[]
     */
    protected static function getTransferrableToEndpointRequestParams(): array
    {
        return [
            'actions'
        ];
    }

    /**
     * Indicate if param "XDEBUG_TRIGGER=1" is appended to the request
     */
    public static function isRequestingXDebug(): bool
    {
        return RootEnvironment::isApplicationEnvironmentDev() && App::getRequest()->query->has(FrameworkParams::XDEBUG_TRIGGER);
    }

    /**
     * If XDebug enabled, append param "XDEBUG_TRIGGER=1" to debug the request
     *
     * @return string[]
     */
    protected static function getXDebugParamValues(): array
    {
        if (!static::isRequestingXDebug()) {
            return [];
        }

        return [
            FrameworkParams::XDEBUG_TRIGGER => (string)App::getRequest()->query->get(FrameworkParams::XDEBUG_TRIGGER),
            /**
             * Must also pass ?XDEBUG_SESSION_STOP=1 in the URL to avoid
             * setting cookie XDEBUG_SESSION="1", which launches the
             * debugger every single time
             */
            FrameworkParams::XDEBUG_SESSION_STOP => '1',
        ];
    }
}

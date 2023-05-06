<?php

declare(strict_types=1);

namespace PHPUnitForGatoGraphQL\GatoGraphQL\Integration;

use PHPUnitForGatoGraphQL\GatoGraphQL\Integration\AbstractFixtureEndpointWebserverRequestTestCase;

class PassQueryViaURLParamQueryExecutionFixtureWebserverRequestTest extends AbstractFixtureEndpointWebserverRequestTestCase
{
    protected function getFixtureFolder(): string
    {
        return __DIR__ . '/fixture-pass-query-via-url-param';
    }

    protected function getEndpoint(): string
    {
        /**
         * Add the query in the endpoint. If no query is passed via the body,
         * eg: because of executing via GET (instead of POST), then
         * this query will be executed.
         */
        return 'graphql/website/?query={ self { id } }';
    }

    protected function getMethod(): string
    {
        if ($this->dataName() === 'via-get-url-param-query-will-be-executed') {
            return 'GET';
        }
        return parent::getMethod();
    }
}

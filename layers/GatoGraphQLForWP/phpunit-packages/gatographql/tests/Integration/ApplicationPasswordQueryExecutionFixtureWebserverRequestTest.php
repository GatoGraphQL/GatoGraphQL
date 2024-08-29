<?php

declare(strict_types=1);

namespace PHPUnitForGatoGraphQL\GatoGraphQL\Integration;

use GuzzleHttp\RequestOptions;

class ApplicationPasswordQueryExecutionFixtureWebserverRequestTest extends AbstractApplicationPasswordQueryExecutionFixtureWebserverRequestTestCase
{
    use ApplicationPasswordQueryExecutionFixtureWebserverRequestTestTrait;

    protected static function getEndpoint(): string
    {
        return 'graphql/';
    }
    
    /**
     * Make the authentication fail
     *
     * @param array<string,mixed> $options
     * @return array<string,mixed>
     */
    protected function customizeRequestOptions(array $options): array
    {
        $options = parent::customizeRequestOptions($options);

        $dataName = $this->getDataName();
        if (str_starts_with($dataName, 'error/')) {
            $options[RequestOptions::HEADERS]['Authorization'] = '___fail___';
        }

        return $options;
    }
}

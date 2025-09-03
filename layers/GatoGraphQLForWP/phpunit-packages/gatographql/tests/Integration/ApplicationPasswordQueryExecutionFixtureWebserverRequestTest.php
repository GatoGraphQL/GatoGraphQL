<?php

declare(strict_types=1);

namespace PHPUnitForGatoGraphQL\GatoGraphQL\Integration;

use GuzzleHttp\RequestOptions;
use PHPUnitForGatoGraphQL\WebserverRequests\RequestPluginRESTAPIWebserverRequestTestTrait;

class ApplicationPasswordQueryExecutionFixtureWebserverRequestTest extends AbstractApplicationPasswordQueryExecutionFixtureWebserverRequestTestCase
{
    use RequestPluginRESTAPIWebserverRequestTestTrait;

    protected static function getFixtureFolder(): string
    {
        return __DIR__ . '/fixture-application-password';
    }

    protected static function getEndpoint(): string
    {
        return 'graphql';
    }

    /**
     * Disable WooCommerce before running the tests
     */
    protected function setUp(): void
    {
        parent::setUp();

        if ($this->isWooCommercePluginEnabledByDefault()) {
            $this->executeRESTEndpointToEnableOrDisablePlugin('woocommerce/woocommerce', 'inactive');
        }
    }

    /**
     * Enable WooCommerce after running the tests
     */
    protected function tearDown(): void
    {
        if ($this->isWooCommercePluginEnabledByDefault()) {
            $this->executeRESTEndpointToEnableOrDisablePlugin('woocommerce/woocommerce', 'active');
        }

        parent::tearDown();
    }

    protected function isWooCommercePluginEnabledByDefault(): bool
    {
        return true;
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
            $options[RequestOptions::HEADERS]['Authorization'] = sprintf(
                'Basic %s',
                base64_encode('admin:___fail___')
            );
        }

        return $options;
    }
}

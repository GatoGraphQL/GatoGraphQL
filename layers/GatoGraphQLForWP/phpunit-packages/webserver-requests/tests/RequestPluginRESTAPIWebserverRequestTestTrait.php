<?php

declare(strict_types=1);

namespace PHPUnitForGatoGraphQL\WebserverRequests;

use GuzzleHttp\RequestOptions;
use PHPUnitForGatoGraphQL\GatoGraphQL\Integration\ApplicationPasswordUsersInterface;

trait RequestPluginRESTAPIWebserverRequestTestTrait
{
    use RequestRESTAPIWebserverRequestTestTrait;

    /**
     * @see https://developer.wordpress.org/rest-api/using-the-rest-api/authentication/
     */
    protected function executeRESTEndpointToEnableOrDisablePlugin(string $pluginName, string $status): void
    {
        $client = static::getClient();
        $restEndpointPlaceholder = 'wp-json/wp/v2/plugins/%s/?status=%s';
        $endpointURLPlaceholder = static::getWebserverHomeURL() . '/' . $restEndpointPlaceholder;
        $client->post(
            sprintf(
                $endpointURLPlaceholder,
                $pluginName,
                $status
            ),
            static::getEnableDisablePluginsRESTEndpointRequestOptions()
        );
    }

    /**
     * Enable/disable plugins as the admin user,
     * to allow testing with subscribers
     *
     * @return array<string,mixed>
     */
    protected function getEnableDisablePluginsRESTEndpointRequestOptions(): array
    {
        $options = static::getRESTEndpointRequestOptions();
        $options[RequestOptions::HEADERS]['Authorization'] = static::getApplicationPasswordAuthorizationHeader(ApplicationPasswordUsersInterface::USER_ADMIN);
        return $options;
    }
}

<?php

declare(strict_types=1);

namespace GatoGraphQL\GatoGraphQL\Marketplace;

use PoP\ComponentModel\Misc\GeneralUtils;
use stdClass;
use WP_Error;

use function home_url;
use function wp_remote_post;
use function wp_remote_retrieve_response_code;
use function wp_remote_retrieve_response_message;

class LemonSqueezyCommercialExtensionActivationService implements MarketplaceProviderCommercialExtensionActivationServiceInterface
{
    /**
     * @see https://docs.lemonsqueezy.com/help/licensing/license-api#post-v1-licenses-activate
     */
    public function activateLicense(string $licenseKey): stdClass
    {
        $instanceName = $this->getInstanceName();
        $endpoint = sprintf(
            '%s/v1/licenses/activate?license_key=%s&instance_name=%s',
            $this->getLemonSqueezyAPIBaseURL(),
            $licenseKey,
            $instanceName
        );
        $response = wp_remote_post(
            $endpoint,
            [
                'headers' => $this->getLemonSqueezyAPIBaseHeaders(),
            ]
        );

        if ($response instanceof WP_Error) {
            $errorMessage = $response->get_error_message();
            // ...
            return (object) [];
        }

        $body = json_decode($response['body'], false);

        if (wp_remote_retrieve_response_code($response) !== 200) {
            $errorMessage = isset($body->error) ? $body->error : wp_remote_retrieve_response_message($response);
            // ...
            return (object) [];
        }

        return $body;
    }

    protected function getLemonSqueezyAPIBaseURL(): string
    {
        return 'https://api.lemonsqueezy.com';
    }

    /**
     * @return array<string,string>
     */
    protected function getLemonSqueezyAPIBaseHeaders(): array
    {
        return [
            'Accept' => 'application/json',
        ];
    }

    /**
     * Use the site's domain as the instance name in Lemon Squeezy
     */
    protected function getInstanceName(): string
    {
        return GeneralUtils::getHost(home_url());
    }

    /**
     * @see https://docs.lemonsqueezy.com/help/licensing/license-api#post-v1-licenses-deactivate
     */
    public function deactivateLicense(string $licenseKey): stdClass
    {
        $instanceID = $request->get_param( 'instance_id' );
        $is_valid      = false;
        $errorMessage = null;
        $api_key       = get_option( 'lsq_api_key' );

        if ( empty( $api_key ) ) {
            return new \WP_REST_Response(
                array(
                    'success' => false,
                    'error'   => __( 'Unauthorized request', 'lemon-squeezy' ),
                ),
                401
            );
        }

        $endpoint = sprintf(
            '%s/v1/licenses/deactivate?license_key=%s&instance_id=%s',
            $this->getLemonSqueezyAPIBaseURL(),
            $licenseKey,
            $instanceID
        );
        $response = wp_remote_post(
            $endpoint,
            [
                'headers' => $this->getLemonSqueezyAPIBaseHeaders(),
            ]
        );

        if ( ! is_wp_error( $response ) ) {
            $body = json_decode( $response['body'], true);
            if ( 200 === wp_remote_retrieve_response_code( $response ) ) {
                $is_valid = $body['deactivated'];
            } else {
                $errorMessage = isset($body['error']) ? $body['error'] : wp_remote_retrieve_response_message( $response );
            }
        } else {
            $errorMessage = $response->get_error_message();
        }

        return new \WP_REST_Response(
            array(
                'success' => $is_valid,
                'error'   => $errorMessage,
            ),
            $is_valid ? 200 : 400
        );

        $payload = [];
        return (object) $payload;
    }
}

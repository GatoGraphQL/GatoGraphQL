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
        $response = wp_remote_post(
			"https://api.lemonsqueezy.com/v1/licenses/activate?license_key={$licenseKey}&instance_name={$instanceName}",
			array(
				'headers' => array(
					// 'Authorization' => 'Bearer ' . $api_key,
					// 'Accept'        => 'application/vnd.api+json',
					// 'Content-Type'  => 'application/vnd.api+json',
					// 'Cache-Control' => 'no-cache',
                    'Accept' => 'application/json',
				),
			)
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

		$response = wp_remote_post(
			LSQ_API_URL . "/v1/licenses/deactivate?license_key={$licenseKey}&instance_id={$instanceID}",
			array(
				'headers' => array(
					'Authorization' => 'Bearer ' . $api_key,
					'Accept'        => 'application/vnd.api+json',
					'Content-Type'  => 'application/vnd.api+json',
					'Cache-Control' => 'no-cache',
				),
			)
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

<?php

declare(strict_types=1);

namespace GraphQLAPI\GraphQLAPI\Services\Clients;

use GraphQLAPI\GraphQLAPI\Constants\RequestParams;
use GraphQLAPI\GraphQLAPI\Services\CustomPostTypes\GraphQLCustomEndpointCustomPostType;
use PoP\ComponentModel\HelperServices\RequestHelperServiceInterface;
use Symfony\Contracts\Service\Attribute\Required;

trait CustomEndpointClientTrait
{
    protected GraphQLCustomEndpointCustomPostType $graphQLCustomEndpointCustomPostType;
    protected RequestHelperServiceInterface $requestHelperService;

    #[Required]
    public function autowireCustomEndpointClientTrait(
        GraphQLCustomEndpointCustomPostType $graphQLCustomEndpointCustomPostType,
        RequestHelperServiceInterface $requestHelperService,
    ): void {
        $this->graphQLCustomEndpointCustomPostType = $graphQLCustomEndpointCustomPostType;
        $this->requestHelperService = $requestHelperService;
    }

    /**
     * Enable only when executing a single CPT
     */
    protected function isClientDisabled(): bool
    {
        if (!\is_singular($this->graphQLCustomEndpointCustomPostType->getCustomPostType())) {
            return true;
        }
        return parent::isClientDisabled();
    }

    /**
     * Endpoint URL
     */
    protected function getEndpointURL(): string
    {
        /**
         * If accessing from Nginx, the server_name might point to localhost
         * instead of the actual server domain. So use the user-requested host
         */
        $fullURL = $this->requestHelperService->getRequestedFullURL(true);
        // Remove the ?view=...
        $endpointURL = \remove_query_arg(RequestParams::VIEW, $fullURL);
        // // Maybe add ?use_namespace=true
        // if (ComponentModelComponentConfiguration::namespaceTypesAndInterfaces()) {
        //     $endpointURL = \add_query_arg(APIRequest::URLPARAM_USE_NAMESPACE, true, $endpointURL);
        // }
        return $endpointURL;
    }
}

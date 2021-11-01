<?php

declare(strict_types=1);

namespace GraphQLAPI\GraphQLAPI\Services\Clients;

use GraphQLAPI\GraphQLAPI\Constants\RequestParams;
use GraphQLAPI\GraphQLAPI\Services\CustomPostTypes\GraphQLCustomEndpointCustomPostType;
use PoP\ComponentModel\HelperServices\RequestHelperServiceInterface;
use Symfony\Contracts\Service\Attribute\Required;

trait CustomEndpointClientTrait
{
    abstract protected function getGraphQLCustomEndpointCustomPostType(): GraphQLCustomEndpointCustomPostType;
    abstract protected function getRequestHelperService(): RequestHelperServiceInterface;

    /**
     * Enable only when executing a single CPT
     */
    protected function isClientDisabled(): bool
    {
        if (!\is_singular($this->getGraphQLCustomEndpointCustomPostType()->getCustomPostType())) {
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
        $fullURL = $this->getRequestHelperService()->getRequestedFullURL(true);
        // Remove the ?view=...
        $endpointURL = \remove_query_arg(RequestParams::VIEW, $fullURL);
        // // Maybe add ?use_namespace=true
        // if (ComponentModelComponentConfiguration::mustNamespaceTypes()) {
        //     $endpointURL = \add_query_arg(APIRequest::URLPARAM_USE_NAMESPACE, true, $endpointURL);
        // }
        return $endpointURL;
    }
}

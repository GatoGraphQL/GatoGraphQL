<?php

declare(strict_types=1);

namespace GraphQLAPI\GraphQLAPI\Clients;

use GraphQLAPI\GraphQLAPI\Constants\RequestParams;
use GraphQLAPI\GraphQLAPI\Services\PostTypes\GraphQLEndpointPostType;
use PoP\ComponentModel\Misc\RequestUtils;

trait CustomEndpointClientTrait
{
    /**
     * Enable only when executing a single CPT
     */
    protected function isClientDisabled(): bool
    {
        if (!\is_singular(GraphQLEndpointPostType::POST_TYPE)) {
            return true;
        }
        return parent::isClientDisabled();
    }

    /**
     * Endpoint URL
     *
     * @return string
     */
    protected function getEndpointURL(): string
    {
        /**
         * If accessing from Nginx, the server_name might point to localhost
         * instead of the actual server domain. So use the user-requested host
         */
        $fullURL = RequestUtils::getRequestedFullURL(true);
        // Remove the ?view=...
        $endpointURL = \remove_query_arg(RequestParams::VIEW, $fullURL);
        // // Maybe add ?use_namespace=true
        // if (ComponentModelComponentConfiguration::namespaceTypesAndInterfaces()) {
        //     $endpointURL = \add_query_arg(APIRequest::URLPARAM_USE_NAMESPACE, true, $endpointURL);
        // }
        return $endpointURL;
    }
}

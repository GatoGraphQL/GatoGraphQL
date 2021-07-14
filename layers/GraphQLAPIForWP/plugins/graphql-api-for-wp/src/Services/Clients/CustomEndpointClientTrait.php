<?php

declare(strict_types=1);

namespace GraphQLAPI\GraphQLAPI\Services\Clients;

use GraphQLAPI\GraphQLAPI\Constants\RequestParams;
use GraphQLAPI\GraphQLAPI\Services\CustomPostTypes\GraphQLEndpointCustomPostType;
use PoP\ComponentModel\Facades\HelperServices\RequestHelperServiceFacade;
use PoP\ComponentModel\Facades\Instances\InstanceManagerFacade;

trait CustomEndpointClientTrait
{
    /**
     * Enable only when executing a single CPT
     */
    protected function isClientDisabled(): bool
    {
        $instanceManager = InstanceManagerFacade::getInstance();
        /** @var GraphQLEndpointCustomPostType */
        $customPostTypeService = $instanceManager->getInstance(GraphQLEndpointCustomPostType::class);
        if (!\is_singular($customPostTypeService->getCustomPostType())) {
            return true;
        }
        return parent::isClientDisabled();
    }

    /**
     * Endpoint URL
     */
    protected function getEndpointURL(): string
    {
        $requestHelperService = RequestHelperServiceFacade::getInstance();
        /**
         * If accessing from Nginx, the server_name might point to localhost
         * instead of the actual server domain. So use the user-requested host
         */
        $fullURL = $requestHelperService->getRequestedFullURL(true);
        // Remove the ?view=...
        $endpointURL = \remove_query_arg(RequestParams::VIEW, $fullURL);
        // // Maybe add ?use_namespace=true
        // if (ComponentModelComponentConfiguration::namespaceTypesAndInterfaces()) {
        //     $endpointURL = \add_query_arg(APIRequest::URLPARAM_USE_NAMESPACE, true, $endpointURL);
        // }
        return $endpointURL;
    }
}

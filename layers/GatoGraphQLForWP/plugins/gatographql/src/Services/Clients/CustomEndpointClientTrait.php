<?php

declare(strict_types=1);

namespace GatoGraphQL\GatoGraphQL\Services\Clients;

use GatoGraphQL\GatoGraphQL\AppHelpers;
use GatoGraphQL\GatoGraphQL\Constants\RequestParams;
use GatoGraphQL\GatoGraphQL\Services\CustomPostTypes\GraphQLCustomEndpointCustomPostType;
use PoP\ComponentModel\HelperServices\RequestHelperServiceInterface;
use PoP\ComponentModel\Misc\GeneralUtils;

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
     * Endpoint URL or URL Path
     */
    protected function getEndpointURLOrURLPath(): ?string
    {
        /**
         * If accessing from Nginx, the server_name might point to localhost
         * instead of the actual server domain. So use the user-requested host
         */
        $fullURL = $this->getRequestHelperService()->getRequestedFullURL(true);
        if ($fullURL === null) {
            return null;
        }

        // Remove the ?view=...
        $endpointURL = \remove_query_arg(RequestParams::VIEW, $fullURL);

        /**
         * When using LocalWP, the port "10003" is somehow added to the URL,
         * and it doesn't work anymore.
         *
         * Then, simply use the path from the current URL, and retrieve the
         * domain from the WP site
         */
        $endpointPath = GeneralUtils::removeDomain($endpointURL);
        $endpointURL = untrailingslashit(get_site_url()) . $endpointPath;
        
        // // Maybe add ?use_namespace=true
        // /** @var ComponentModelModuleConfiguration */
        // $moduleConfiguration = \PoP\Root\App::getModule(ComponentModelModule::class)->getConfiguration();
        // if ($moduleConfiguration->mustNamespaceTypes()) {
        //     $endpointURL = \add_query_arg(APIParams::USE_NAMESPACE, true, $endpointURL);
        // }
        return $endpointURL;
    }

    /**
     * Only initialize once, for the main AppThread
     */
    public function isServiceEnabled(): bool
    {
        if (!AppHelpers::isMainAppThread()) {
            return false;
        }
        return parent::isServiceEnabled();
    }
}

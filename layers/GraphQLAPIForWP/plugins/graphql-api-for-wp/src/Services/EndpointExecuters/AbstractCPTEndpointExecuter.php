<?php

declare(strict_types=1);

namespace GatoGraphQL\GatoGraphQL\Services\EndpointExecuters;

use PoP\Root\App;
use GatoGraphQL\GatoGraphQL\Constants\RequestParams;
use GatoGraphQL\GatoGraphQL\Services\CustomPostTypes\GraphQLEndpointCustomPostTypeInterface;

abstract class AbstractCPTEndpointExecuter extends AbstractEndpointExecuter
{
    /**
     * Only enable the service, if the corresponding module is also enabled
     */
    public function isServiceEnabled(): bool
    {
        if (!parent::isServiceEnabled()) {
            return false;
        }

        // Check we're loading the corresponding CPT
        $customPostType = $this->getCustomPostType();
        if (!\is_singular($customPostType->getCustomPostType())) {
            return false;
        }

        // Check the endpoint is not disabled
        global $post;
        if (!$customPostType->isEndpointEnabled($post)) {
            return false;
        }

        // Check it is not password protected
        if (\post_password_required($post)) {
            return false;
        }

        return true;
    }

    abstract protected function getCustomPostType(): GraphQLEndpointCustomPostTypeInterface;

    /**
     * Check the expected ?view=... is requested.
     */
    public function isEndpointBeingRequested(): bool
    {
        // Use `''` instead of `null` so that the query resolution
        // works either without param or empty (?view=)
        return App::query(RequestParams::VIEW, '') === $this->getView();
    }

    abstract protected function getView(): string;
}

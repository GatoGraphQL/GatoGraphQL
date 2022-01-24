<?php

declare(strict_types=1);

namespace GraphQLAPI\GraphQLAPI\Services\EndpointExecuters;

use GraphQLAPI\GraphQLAPI\Constants\RequestParams;
use GraphQLAPI\GraphQLAPI\Services\CustomPostTypes\GraphQLEndpointCustomPostTypeInterface;

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
        return ($_GET[RequestParams::VIEW] ?? '') === $this->getView();
    }

    abstract protected function getView(): string;
}

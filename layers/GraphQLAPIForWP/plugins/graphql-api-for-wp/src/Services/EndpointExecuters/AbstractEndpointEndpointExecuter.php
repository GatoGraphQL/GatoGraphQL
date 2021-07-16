<?php

declare(strict_types=1);

namespace GraphQLAPI\GraphQLAPI\Services\EndpointExecuters;

use GraphQLAPI\GraphQLAPI\Services\CustomPostTypes\AbstractGraphQLEndpointCustomPostType;

abstract class AbstractEndpointEndpointExecuter extends AbstractEndpointExecuter
{
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

    abstract protected function getCustomPostType(): AbstractGraphQLEndpointCustomPostType;
}

<?php

declare(strict_types=1);

namespace GraphQLAPI\GraphQLAPI\Services\EndpointExecuters;

use GraphQLAPI\GraphQLAPI\Registries\ModuleRegistryInterface;
use GraphQLAPI\GraphQLAPI\Services\CustomPostTypes\AbstractGraphQLEndpointCustomPostType;
use PoP\ComponentModel\Instances\InstanceManagerInterface;

abstract class AbstractEndpointExecuter implements EndpointExecuterInterface
{
    public function __construct(
        protected InstanceManagerInterface $instanceManager,
        protected ModuleRegistryInterface $moduleRegistry,
    ) {
    }

    public function getEnablingModule(): ?string
    {
        return null;
    }

    /**
     * Only enable the service, if the corresponding module is also enabled
     */
    public function isServiceEnabled(): bool
    {
        $enablingModule = $this->getEnablingModule();
        if ($enablingModule !== null) {
            return $this->moduleRegistry->isModuleEnabled($enablingModule);
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

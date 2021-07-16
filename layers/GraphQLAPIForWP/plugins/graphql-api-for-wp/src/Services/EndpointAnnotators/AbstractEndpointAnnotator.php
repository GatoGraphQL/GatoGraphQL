<?php

declare(strict_types=1);

namespace GraphQLAPI\GraphQLAPI\Services\EndpointAnnotators;

use WP_Post;
use GraphQLAPI\GraphQLAPI\Registries\ModuleRegistryInterface;
use GraphQLAPI\GraphQLAPI\Services\CustomPostTypes\AbstractGraphQLEndpointCustomPostType;
use PoP\ComponentModel\Instances\InstanceManagerInterface;

abstract class AbstractEndpointAnnotator implements EndpointAnnotatorInterface
{
    public function __construct(
        protected InstanceManagerInterface $instanceManager,
        protected ModuleRegistryInterface $moduleRegistry,
    ) {
    }

    /**
     * Add actions to the CPT list
     * @param array<string, string> $actions
     */
    public function addCustomPostTypeTableActions(array &$actions, WP_Post $post): void
    {
        // Do nothing
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
        if ($enablingModule !== null && !$this->moduleRegistry->isModuleEnabled($enablingModule)) {
            return false;
        }

        return true;
    }

    abstract protected function getCustomPostType(): AbstractGraphQLEndpointCustomPostType;
}

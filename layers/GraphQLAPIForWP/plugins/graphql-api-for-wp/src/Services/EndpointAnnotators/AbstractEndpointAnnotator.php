<?php

declare(strict_types=1);

namespace GraphQLAPI\GraphQLAPI\Services\EndpointAnnotators;

use GraphQLAPI\GraphQLAPI\Registries\ModuleRegistryInterface;
use GraphQLAPI\GraphQLAPI\Services\CustomPostTypes\GraphQLEndpointCustomPostTypeInterface;
use PoP\ComponentModel\Instances\InstanceManagerInterface;
use Symfony\Contracts\Service\Attribute\Required;
use WP_Post;

abstract class AbstractEndpointAnnotator implements EndpointAnnotatorInterface
{
    protected InstanceManagerInterface $instanceManager;
    protected ModuleRegistryInterface $moduleRegistry;

    #[Required]
    public function autowireAbstractEndpointAnnotator(InstanceManagerInterface $instanceManager, ModuleRegistryInterface $moduleRegistry): void
    {
        $this->instanceManager = $instanceManager;
        $this->moduleRegistry = $moduleRegistry;
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

    abstract protected function getCustomPostType(): GraphQLEndpointCustomPostTypeInterface;
}

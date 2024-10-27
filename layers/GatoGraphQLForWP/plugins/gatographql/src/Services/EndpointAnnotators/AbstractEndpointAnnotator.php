<?php

declare(strict_types=1);

namespace GatoGraphQL\GatoGraphQL\Services\EndpointAnnotators;

use GatoGraphQL\GatoGraphQL\AppHelpers;
use GatoGraphQL\GatoGraphQL\Registries\ModuleRegistryInterface;
use GatoGraphQL\GatoGraphQL\Services\CustomPostTypes\GraphQLEndpointCustomPostTypeInterface;
use PoP\Root\Services\BasicServiceTrait;
use WP_Post;

abstract class AbstractEndpointAnnotator implements EndpointAnnotatorInterface
{
    use BasicServiceTrait;

    private ?ModuleRegistryInterface $moduleRegistry = null;

    final protected function getModuleRegistry(): ModuleRegistryInterface
    {
        if ($this->moduleRegistry === null) {
            /** @var ModuleRegistryInterface */
            $moduleRegistry = $this->instanceManager->getInstance(ModuleRegistryInterface::class);
            $this->moduleRegistry = $moduleRegistry;
        }
        return $this->moduleRegistry;
    }

    /**
     * Add actions to the CPT list
     * @param array<string,string> $actions
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
        /**
         * Only initialize once, for the main AppThread
         */
        if (!AppHelpers::isMainAppThread()) {
            return false;
        }

        $enablingModule = $this->getEnablingModule();
        if ($enablingModule !== null && !$this->getModuleRegistry()->isModuleEnabled($enablingModule)) {
            return false;
        }

        return true;
    }

    abstract protected function getCustomPostType(): GraphQLEndpointCustomPostTypeInterface;
}

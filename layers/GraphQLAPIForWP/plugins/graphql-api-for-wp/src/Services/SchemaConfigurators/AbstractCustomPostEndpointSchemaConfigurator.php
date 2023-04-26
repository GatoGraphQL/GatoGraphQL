<?php

declare(strict_types=1);

namespace GraphQLAPI\GraphQLAPI\Services\SchemaConfigurators;

use GraphQLAPI\GraphQLAPI\Services\Helpers\EndpointBlockHelpers;

abstract class AbstractCustomPostEndpointSchemaConfigurator extends AbstractEndpointSchemaConfigurator
{
    private ?EndpointBlockHelpers $endpointBlockHelpers = null;

    final public function setEndpointBlockHelpers(EndpointBlockHelpers $endpointBlockHelpers): void
    {
        $this->endpointBlockHelpers = $endpointBlockHelpers;
    }
    final protected function getEndpointBlockHelpers(): EndpointBlockHelpers
    {
        /** @var EndpointBlockHelpers */
        return $this->endpointBlockHelpers ??= $this->instanceManager->getInstance(EndpointBlockHelpers::class);
    }

    /**
     * Only enable the service, if any of the corresponding modules is also enabled
     */
    public function isServiceEnabled(): bool
    {
        return $this->getModuleRegistry()->isModuleEnabled($this->getEnablingModule())
            && parent::isServiceEnabled();
    }

    abstract protected function getEnablingModule(): string;

    /**
     * Extract the Schema Configuration ID from the block stored in the post
     */
    protected function getSchemaConfigurationID(int $customPostID): ?int
    {
        return $this->getEndpointBlockHelpers()->getSchemaConfigurationID(
            $this->getEnablingModule(),
            $customPostID,
        );
    }
}

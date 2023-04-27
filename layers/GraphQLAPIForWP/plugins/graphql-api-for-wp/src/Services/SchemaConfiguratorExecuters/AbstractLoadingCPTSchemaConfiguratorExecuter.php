<?php

declare(strict_types=1);

namespace GraphQLAPI\GraphQLAPI\Services\SchemaConfiguratorExecuters;

use GraphQLAPI\GraphQLAPI\AppHelpers;
use GraphQLAPI\GraphQLAPI\Services\Helpers\EndpointBlockHelpers;
use PoP\Root\App;

abstract class AbstractLoadingCPTSchemaConfiguratorExecuter extends AbstractSchemaConfiguratorExecuter
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
     * Only initialize once, for the main AppThread
     */
    public function isServiceEnabled(): bool
    {
        if (!AppHelpers::isMainAppThread()) {
            return false;
        }
        return parent::isServiceEnabled();
    }

    /**
     * Initialize the configuration if visiting the corresponding CPT
     */
    protected function getSchemaConfigurationID(): ?int
    {
        if (!\is_singular($this->getCustomPostType())) {
            return null;
        }
        $customPostID = App::getState(['routing', 'queried-object-id']);
        if ($customPostID === null) {
            return null;
        }
        return $this->getEndpointBlockHelpers()->getSchemaConfigurationID(
            $this->getLoadingCPTSchemaConfiguratorModule(),
            $customPostID,
        );
    }

    abstract protected function getCustomPostType(): string;
    abstract protected function getLoadingCPTSchemaConfiguratorModule(): string;
}

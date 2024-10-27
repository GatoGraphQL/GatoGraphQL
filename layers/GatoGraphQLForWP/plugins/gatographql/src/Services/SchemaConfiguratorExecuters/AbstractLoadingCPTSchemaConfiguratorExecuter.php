<?php

declare(strict_types=1);

namespace GatoGraphQL\GatoGraphQL\Services\SchemaConfiguratorExecuters;

use GatoGraphQL\GatoGraphQL\AppHelpers;
use GatoGraphQL\GatoGraphQL\Services\Helpers\EndpointBlockHelpers;
use PoP\Root\App;

abstract class AbstractLoadingCPTSchemaConfiguratorExecuter extends AbstractSchemaConfiguratorExecuter
{
    private ?EndpointBlockHelpers $endpointBlockHelpers = null;

    final protected function getEndpointBlockHelpers(): EndpointBlockHelpers
    {
        if ($this->endpointBlockHelpers === null) {
            /** @var EndpointBlockHelpers */
            $endpointBlockHelpers = $this->instanceManager->getInstance(EndpointBlockHelpers::class);
            $this->endpointBlockHelpers = $endpointBlockHelpers;
        }
        return $this->endpointBlockHelpers;
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

    protected function isSchemaConfiguratorActive(): bool
    {
        if (!\is_singular($this->getCustomPostType())) {
            return false;
        }
        $customPostID = App::getState(['routing', 'queried-object-id']);
        if ($customPostID === null) {
            return false;
        }
        return true;
    }

    /**
     * Initialize the configuration if visiting the corresponding CPT.
     *
     * @return int|null The Schema Configuration ID, null if none was selected (in which case a default Schema Configuration can be applied), or -1 if "None" was selected (i.e. no default Schema Configuration must be applied)
     */
    protected function getSchemaConfigurationID(): ?int
    {
        $customPostID = App::getState(['routing', 'queried-object-id']);
        return $this->getEndpointBlockHelpers()->getSchemaConfigurationID(
            $this->getLoadingCPTSchemaConfiguratorModule(),
            $customPostID,
        );
    }

    abstract protected function getCustomPostType(): string;
    abstract protected function getLoadingCPTSchemaConfiguratorModule(): string;
}

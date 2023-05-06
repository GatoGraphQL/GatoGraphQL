<?php

declare(strict_types=1);

namespace GatoGraphQL\GatoGraphQL\Services\SchemaConfiguratorExecuters;

use GatoGraphQL\GatoGraphQL\AppHelpers;
use GatoGraphQL\GatoGraphQL\ModuleResolvers\EndpointFunctionalityModuleResolver;
use GatoGraphQL\GatoGraphQL\Services\Helpers\EndpointBlockHelpers;
use GatoGraphQL\GatoGraphQL\Services\Helpers\EndpointHelpers;
use GatoGraphQL\GatoGraphQL\Services\SchemaConfigurators\PersistedQueryEndpointSchemaConfigurator;
use GatoGraphQL\GatoGraphQL\Services\SchemaConfigurators\SchemaConfiguratorInterface;

class EditingPersistedQueryEndpointSchemaConfiguratorExecuter extends AbstractSchemaConfiguratorExecuter
{
    private ?EndpointHelpers $endpointHelpers = null;
    private ?PersistedQueryEndpointSchemaConfigurator $persistedQueryEndpointSchemaConfigurator = null;
    private ?EndpointBlockHelpers $endpointBlockHelpers = null;

    final public function setEndpointHelpers(EndpointHelpers $endpointHelpers): void
    {
        $this->endpointHelpers = $endpointHelpers;
    }
    final protected function getEndpointHelpers(): EndpointHelpers
    {
        /** @var EndpointHelpers */
        return $this->endpointHelpers ??= $this->instanceManager->getInstance(EndpointHelpers::class);
    }
    final public function setPersistedQueryEndpointSchemaConfigurator(PersistedQueryEndpointSchemaConfigurator $persistedQueryEndpointSchemaConfigurator): void
    {
        $this->persistedQueryEndpointSchemaConfigurator = $persistedQueryEndpointSchemaConfigurator;
    }
    final protected function getPersistedQueryEndpointSchemaConfigurator(): PersistedQueryEndpointSchemaConfigurator
    {
        /** @var PersistedQueryEndpointSchemaConfigurator */
        return $this->persistedQueryEndpointSchemaConfigurator ??= $this->instanceManager->getInstance(PersistedQueryEndpointSchemaConfigurator::class);
    }
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

    protected function isSchemaConfiguratorActive(): bool
    {
        return $this->getEndpointHelpers()->isRequestingAdminPersistedQueryGraphQLEndpoint();
    }

    /**
     * Initialize the configuration if editing a persisted query.
     *
     * @return int|null The Schema Configuration ID, null if none was selected (in which case a default Schema Configuration can be applied), or -1 if "None" was selected (i.e. no default Schema Configuration must be applied)
     */
    protected function getSchemaConfigurationID(): ?int
    {
        $customPostID = (int) $this->getEndpointHelpers()->getAdminPersistedQueryCustomPostID();
        return $this->getEndpointBlockHelpers()->getSchemaConfigurationID(
            EndpointFunctionalityModuleResolver::PERSISTED_QUERIES,
            $customPostID,
        );
    }

    protected function getSchemaConfigurator(): SchemaConfiguratorInterface
    {
        return $this->getPersistedQueryEndpointSchemaConfigurator();
    }
}

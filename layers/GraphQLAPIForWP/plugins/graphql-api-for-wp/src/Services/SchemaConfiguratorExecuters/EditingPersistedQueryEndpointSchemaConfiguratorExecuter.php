<?php

declare(strict_types=1);

namespace GraphQLAPI\GraphQLAPI\Services\SchemaConfiguratorExecuters;

use GraphQLAPI\GraphQLAPI\Services\Helpers\EndpointHelpers;
use GraphQLAPI\GraphQLAPI\Services\SchemaConfigurators\PersistedQueryEndpointSchemaConfigurator;
use GraphQLAPI\GraphQLAPI\Services\SchemaConfigurators\SchemaConfiguratorInterface;
use Symfony\Contracts\Service\Attribute\Required;

class EditingPersistedQueryEndpointSchemaConfiguratorExecuter extends AbstractSchemaConfiguratorExecuter
{
    private ?EndpointHelpers $endpointHelpers = null;
    private ?PersistedQueryEndpointSchemaConfigurator $persistedQueryEndpointSchemaConfigurator = null;

    public function setEndpointHelpers(EndpointHelpers $endpointHelpers): void
    {
        $this->endpointHelpers = $endpointHelpers;
    }
    protected function getEndpointHelpers(): EndpointHelpers
    {
        return $this->endpointHelpers ??= $this->instanceManager->getInstance(EndpointHelpers::class);
    }
    public function setPersistedQueryEndpointSchemaConfigurator(PersistedQueryEndpointSchemaConfigurator $persistedQueryEndpointSchemaConfigurator): void
    {
        $this->persistedQueryEndpointSchemaConfigurator = $persistedQueryEndpointSchemaConfigurator;
    }
    protected function getPersistedQueryEndpointSchemaConfigurator(): PersistedQueryEndpointSchemaConfigurator
    {
        return $this->persistedQueryEndpointSchemaConfigurator ??= $this->instanceManager->getInstance(PersistedQueryEndpointSchemaConfigurator::class);
    }

    //#[Required]
    final public function autowireEditingPersistedQueryEndpointSchemaConfiguratorExecuter(
        EndpointHelpers $endpointHelpers,
        PersistedQueryEndpointSchemaConfigurator $persistedQueryEndpointSchemaConfigurator
    ): void {
        $this->endpointHelpers = $endpointHelpers;
        $this->persistedQueryEndpointSchemaConfigurator = $persistedQueryEndpointSchemaConfigurator;
    }

    /**
     * Initialize the configuration if editing a persisted query
     */
    protected function getCustomPostID(): ?int
    {
        if ($this->getEndpointHelpers()->isRequestingAdminPersistedQueryGraphQLEndpoint()) {
            return (int) $this->getEndpointHelpers()->getAdminPersistedQueryCustomPostID();
        }
        return null;
    }

    protected function getSchemaConfigurator(): SchemaConfiguratorInterface
    {
        return $this->getPersistedQueryEndpointSchemaConfigurator();
    }
}

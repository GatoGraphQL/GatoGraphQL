<?php

declare(strict_types=1);

namespace GraphQLAPI\GraphQLAPI\Services\SchemaConfiguratorExecuters;

use GraphQLAPI\GraphQLAPI\Services\Helpers\EndpointHelpers;
use GraphQLAPI\GraphQLAPI\Services\SchemaConfigurators\PersistedQueryEndpointSchemaConfigurator;
use GraphQLAPI\GraphQLAPI\Services\SchemaConfigurators\SchemaConfiguratorInterface;
use Symfony\Contracts\Service\Attribute\Required;

class EditingPersistedQueryEndpointSchemaConfiguratorExecuter extends AbstractSchemaConfiguratorExecuter
{
    protected EndpointHelpers $endpointHelpers;
    protected PersistedQueryEndpointSchemaConfigurator $persistedQueryEndpointSchemaConfigurator;

    #[Required]
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
        if ($this->endpointHelpers->isRequestingAdminPersistedQueryGraphQLEndpoint()) {
            return (int) $this->endpointHelpers->getAdminPersistedQueryCustomPostID();
        }
        return null;
    }

    protected function getSchemaConfigurator(): SchemaConfiguratorInterface
    {
        return $this->persistedQueryEndpointSchemaConfigurator;
    }
}

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

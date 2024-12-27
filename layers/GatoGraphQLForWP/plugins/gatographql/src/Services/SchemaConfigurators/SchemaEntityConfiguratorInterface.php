<?php

declare(strict_types=1);

namespace GatoGraphQL\GatoGraphQL\Services\SchemaConfigurators;

use PoP\Root\Services\ActivableServiceInterface;

interface SchemaEntityConfiguratorInterface extends ActivableServiceInterface
{
    /**
     * Execute the schema configuration for entities (fields, directives, etc),
     * retrieving the data from the custom post with certain ID
     */
    public function executeSchemaEntityConfiguration(int $customPostID): void;
}

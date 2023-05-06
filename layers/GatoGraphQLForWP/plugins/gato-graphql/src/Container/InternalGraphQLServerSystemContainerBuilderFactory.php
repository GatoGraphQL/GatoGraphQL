<?php

declare(strict_types=1);

namespace GatoGraphQL\GatoGraphQL\Container;

use PoP\Root\Container\SystemContainerBuilderFactory;

class InternalGraphQLServerSystemContainerBuilderFactory extends SystemContainerBuilderFactory
{
    public function getContainerClassName(): string
    {
        return 'InternalGraphQLServer' . parent::getContainerClassName();
    }
}

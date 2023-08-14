<?php

declare(strict_types=1);

namespace GatoGraphQL\GatoGraphQL\Container;

use PoP\Root\Container\ContainerBuilderFactory;

class InternalGraphQLServerContainerBuilderFactory extends ContainerBuilderFactory
{
    public function getContainerClassName(): string
    {
        return 'InternalGraphQLServer' . parent::getContainerClassName();
    }
}

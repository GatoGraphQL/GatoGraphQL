<?php

declare(strict_types=1);

namespace PoP\AccessControl\RelationalTypeResolverDecorators;

use PoP\AccessControl\Services\AccessControlManagerInterface;

abstract class AbstractConfigurableAccessControlForDirectivesInPublicSchemaRelationalTypeResolverDecorator extends AbstractPublicSchemaRelationalTypeResolverDecorator
{
    use ConfigurableAccessControlForDirectivesRelationalTypeResolverDecoratorTrait;

    private ?AccessControlManagerInterface $accessControlManager = null;

    final protected function getAccessControlManager(): AccessControlManagerInterface
    {
        if ($this->accessControlManager === null) {
            /** @var AccessControlManagerInterface */
            $accessControlManager = $this->instanceManager->getInstance(AccessControlManagerInterface::class);
            $this->accessControlManager = $accessControlManager;
        }
        return $this->accessControlManager;
    }
}

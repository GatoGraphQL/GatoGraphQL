<?php

declare(strict_types=1);

namespace PoP\AccessControl\RelationalTypeResolverDecorators;

use PoP\AccessControl\Services\AccessControlManagerInterface;

abstract class AbstractConfigurableAccessControlForFieldsInPublicSchemaRelationalTypeResolverDecorator extends AbstractPublicSchemaRelationalTypeResolverDecorator
{
    use ConfigurableAccessControlForFieldsRelationalTypeResolverDecoratorTrait;

    private ?AccessControlManagerInterface $accessControlManager = null;

    final public function setAccessControlManager(AccessControlManagerInterface $accessControlManager): void
    {
        $this->accessControlManager = $accessControlManager;
    }
    final protected function getAccessControlManager(): AccessControlManagerInterface
    {
        return $this->accessControlManager ??= $this->instanceManager->getInstance(AccessControlManagerInterface::class);
    }
}

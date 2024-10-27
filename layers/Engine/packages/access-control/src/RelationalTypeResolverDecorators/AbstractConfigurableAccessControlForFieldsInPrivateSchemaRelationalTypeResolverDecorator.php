<?php

declare(strict_types=1);

namespace PoP\AccessControl\RelationalTypeResolverDecorators;

use PoP\AccessControl\Services\AccessControlManagerInterface;

abstract class AbstractConfigurableAccessControlForFieldsInPrivateSchemaRelationalTypeResolverDecorator extends AbstractPrivateSchemaRelationalTypeResolverDecorator
{
    use ConfigurableAccessControlForFieldsRelationalTypeResolverDecoratorTrait;

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

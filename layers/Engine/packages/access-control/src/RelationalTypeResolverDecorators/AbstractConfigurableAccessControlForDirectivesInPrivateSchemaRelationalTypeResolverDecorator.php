<?php

declare(strict_types=1);

namespace PoP\AccessControl\RelationalTypeResolverDecorators;

use PoP\AccessControl\Services\AccessControlManagerInterface;

abstract class AbstractConfigurableAccessControlForDirectivesInPrivateSchemaRelationalTypeResolverDecorator extends AbstractPrivateSchemaRelationalTypeResolverDecorator
{
    use ConfigurableAccessControlForDirectivesRelationalTypeResolverDecoratorTrait;

    private ?AccessControlManagerInterface $accessControlManager = null;

    final public function setAccessControlManager(AccessControlManagerInterface $accessControlManager): void
    {
        $this->accessControlManager = $accessControlManager;
    }
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

<?php

declare(strict_types=1);

namespace PoP\AccessControl\RelationalTypeResolverDecorators;

use Symfony\Contracts\Service\Attribute\Required;
use PoP\AccessControl\Services\AccessControlManagerInterface;

abstract class AbstractConfigurableAccessControlForDirectivesInPrivateSchemaRelationalTypeResolverDecorator extends AbstractPrivateSchemaRelationalTypeResolverDecorator
{
    use ConfigurableAccessControlForDirectivesRelationalTypeResolverDecoratorTrait;

    protected AccessControlManagerInterface $accessControlManager;

    #[Required]
    public function autowireAbstractConfigurableAccessControlForDirectivesInPrivateSchemaRelationalTypeResolverDecorator(
        AccessControlManagerInterface $accessControlManager,
    ): void {
        $this->accessControlManager = $accessControlManager;
    }
}

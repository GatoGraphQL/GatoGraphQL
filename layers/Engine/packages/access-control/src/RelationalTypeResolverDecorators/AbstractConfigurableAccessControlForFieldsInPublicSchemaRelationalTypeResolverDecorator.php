<?php

declare(strict_types=1);

namespace PoP\AccessControl\RelationalTypeResolverDecorators;

use PoP\AccessControl\Services\AccessControlManagerInterface;
use Symfony\Contracts\Service\Attribute\Required;

abstract class AbstractConfigurableAccessControlForFieldsInPublicSchemaRelationalTypeResolverDecorator extends AbstractPublicSchemaRelationalTypeResolverDecorator
{
    use ConfigurableAccessControlForFieldsRelationalTypeResolverDecoratorTrait;

    protected AccessControlManagerInterface $accessControlManager;

    #[Required]
    final public function autowireAbstractConfigurableAccessControlForFieldsInPublicSchemaRelationalTypeResolverDecorator(
        AccessControlManagerInterface $accessControlManager,
    ): void {
        $this->accessControlManager = $accessControlManager;
    }
}

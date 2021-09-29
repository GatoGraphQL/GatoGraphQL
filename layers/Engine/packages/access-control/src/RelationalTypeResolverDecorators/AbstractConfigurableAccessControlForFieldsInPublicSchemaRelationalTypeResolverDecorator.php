<?php

declare(strict_types=1);

namespace PoP\AccessControl\RelationalTypeResolverDecorators;

use Symfony\Contracts\Service\Attribute\Required;
use PoP\AccessControl\Services\AccessControlManagerInterface;

abstract class AbstractConfigurableAccessControlForFieldsInPublicSchemaRelationalTypeResolverDecorator extends AbstractPublicSchemaRelationalTypeResolverDecorator
{
    use ConfigurableAccessControlForFieldsRelationalTypeResolverDecoratorTrait;

    protected AccessControlManagerInterface $accessControlManager;

    #[Required]
    public function autowireAbstractConfigurableAccessControlForFieldsInPublicSchemaRelationalTypeResolverDecorator(
        AccessControlManagerInterface $accessControlManager,
    ): void {
        $this->accessControlManager = $accessControlManager;
    }
}

<?php

declare(strict_types=1);

namespace PoP\AccessControl\RelationalTypeResolverDecorators;

use Symfony\Contracts\Service\Attribute\Required;
use PoP\AccessControl\Services\AccessControlManagerInterface;

abstract class AbstractConfigurableAccessControlForDirectivesInPublicSchemaRelationalTypeResolverDecorator extends AbstractPublicSchemaRelationalTypeResolverDecorator
{
    use ConfigurableAccessControlForDirectivesRelationalTypeResolverDecoratorTrait;

    protected AccessControlManagerInterface $accessControlManager;

    #[Required]
    public function autowireAbstractConfigurableAccessControlForDirectivesInPublicSchemaRelationalTypeResolverDecorator(
        AccessControlManagerInterface $accessControlManager,
    ): void {
        $this->accessControlManager = $accessControlManager;
    }
}

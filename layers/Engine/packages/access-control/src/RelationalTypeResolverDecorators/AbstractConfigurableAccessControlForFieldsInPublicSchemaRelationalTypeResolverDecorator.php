<?php

declare(strict_types=1);

namespace PoP\AccessControl\RelationalTypeResolverDecorators;

use PoP\ComponentModel\Instances\InstanceManagerInterface;
use PoP\AccessControl\Services\AccessControlManagerInterface;
use PoP\ComponentModel\Schema\FieldQueryInterpreterInterface;
use PoP\AccessControl\RelationalTypeResolverDecorators\AbstractPublicSchemaRelationalTypeResolverDecorator;

abstract class AbstractConfigurableAccessControlForFieldsInPublicSchemaRelationalTypeResolverDecorator extends AbstractPublicSchemaRelationalTypeResolverDecorator
{
    use ConfigurableAccessControlForFieldsRelationalTypeResolverDecoratorTrait;
    protected AccessControlManagerInterface $accessControlManager;
    
    #[\Symfony\Contracts\Service\Attribute\Required]
    public function autowireAbstractConfigurableAccessControlForFieldsInPublicSchemaRelationalTypeResolverDecorator(
        AccessControlManagerInterface $accessControlManager,
    ) {
        $this->accessControlManager = $accessControlManager;
    }
}

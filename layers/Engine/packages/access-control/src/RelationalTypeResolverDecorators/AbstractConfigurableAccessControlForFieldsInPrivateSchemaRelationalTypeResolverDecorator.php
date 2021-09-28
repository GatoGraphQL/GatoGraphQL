<?php

declare(strict_types=1);

namespace PoP\AccessControl\RelationalTypeResolverDecorators;

use PoP\ComponentModel\Instances\InstanceManagerInterface;
use PoP\AccessControl\Services\AccessControlManagerInterface;
use PoP\ComponentModel\Schema\FieldQueryInterpreterInterface;
use PoP\AccessControl\RelationalTypeResolverDecorators\ConfigurableAccessControlForFieldsRelationalTypeResolverDecoratorTrait;

abstract class AbstractConfigurableAccessControlForFieldsInPrivateSchemaRelationalTypeResolverDecorator extends AbstractPrivateSchemaRelationalTypeResolverDecorator
{
    use ConfigurableAccessControlForFieldsRelationalTypeResolverDecoratorTrait;
    protected AccessControlManagerInterface $accessControlManager;

    public function __construct(
        InstanceManagerInterface $instanceManager,
        FieldQueryInterpreterInterface $fieldQueryInterpreter,
        AccessControlManagerInterface $accessControlManager,
    ) {
        $this->accessControlManager = $accessControlManager;
        }
}

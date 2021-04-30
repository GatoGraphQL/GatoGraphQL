<?php

declare(strict_types=1);

namespace PoP\AccessControl\TypeResolverDecorators;

use PoP\ComponentModel\Instances\InstanceManagerInterface;
use PoP\AccessControl\Services\AccessControlManagerInterface;
use PoP\ComponentModel\Schema\FieldQueryInterpreterInterface;

abstract class AbstractConfigurableAccessControlForDirectivesInPrivateSchemaTypeResolverDecorator extends AbstractPrivateSchemaTypeResolverDecorator
{
    use ConfigurableAccessControlForDirectivesTypeResolverDecoratorTrait;

    function __construct(
        InstanceManagerInterface $instanceManager,
        FieldQueryInterpreterInterface $fieldQueryInterpreter,
        protected AccessControlManagerInterface $accessControlManager,
    ) {
        parent::__construct(
            $instanceManager,
            $fieldQueryInterpreter,
        );
    }
}

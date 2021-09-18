<?php

declare(strict_types=1);

namespace PoP\AccessControl\RelationalTypeResolverDecorators;

use PoP\AccessControl\DirectiveResolvers\DisableAccessDirectiveResolver;
use PoP\AccessControl\RelationalTypeResolverDecorators\AbstractConfigurableAccessControlForFieldsInPublicSchemaRelationalTypeResolverDecorator;
use PoP\AccessControl\Services\AccessControlManagerInterface;
use PoP\ComponentModel\DirectiveResolvers\DirectiveResolverInterface;
use PoP\ComponentModel\Instances\InstanceManagerInterface;
use PoP\ComponentModel\Schema\FieldQueryInterpreterInterface;

abstract class AbstractDisableAccessConfigurableAccessControlForFieldsInPublicSchemaRelationalTypeResolverDecorator extends AbstractConfigurableAccessControlForFieldsInPublicSchemaRelationalTypeResolverDecorator
{
    public function __construct(
        InstanceManagerInterface $instanceManager,
        FieldQueryInterpreterInterface $fieldQueryInterpreter,
        AccessControlManagerInterface $accessControlManager,
    ) {
        parent::__construct(
            $instanceManager,
            $fieldQueryInterpreter,
            $accessControlManager,
        );
    }
    
    protected function getMandatoryDirectives(mixed $entryValue = null): array
    {
        /** @var DirectiveResolverInterface */
        $disableAccessDirectiveResolver = $this->instanceManager->getInstance(DisableAccessDirectiveResolver::class);
        $disableAccessDirective = $this->fieldQueryInterpreter->getDirective(
            $disableAccessDirectiveResolver->getDirectiveName()
        );
        return [
            $disableAccessDirective,
        ];
    }
}

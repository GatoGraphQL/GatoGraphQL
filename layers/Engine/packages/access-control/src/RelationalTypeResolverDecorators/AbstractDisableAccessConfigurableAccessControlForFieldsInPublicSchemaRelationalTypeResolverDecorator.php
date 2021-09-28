<?php

declare(strict_types=1);

namespace PoP\AccessControl\RelationalTypeResolverDecorators;

use PoP\AccessControl\DirectiveResolvers\DisableAccessDirectiveResolver;
use PoP\AccessControl\RelationalTypeResolverDecorators\AbstractConfigurableAccessControlForFieldsInPublicSchemaRelationalTypeResolverDecorator;
use PoP\AccessControl\Services\AccessControlManagerInterface;
use PoP\ComponentModel\Instances\InstanceManagerInterface;
use PoP\ComponentModel\Schema\FieldQueryInterpreterInterface;

abstract class AbstractDisableAccessConfigurableAccessControlForFieldsInPublicSchemaRelationalTypeResolverDecorator extends AbstractConfigurableAccessControlForFieldsInPublicSchemaRelationalTypeResolverDecorator
{
    protected DisableAccessDirectiveResolver $disableAccessDirectiveResolver;
    public function __construct(
        InstanceManagerInterface $instanceManager,
        FieldQueryInterpreterInterface $fieldQueryInterpreter,
        AccessControlManagerInterface $accessControlManager,
        DisableAccessDirectiveResolver $disableAccessDirectiveResolver,
    ) {
        $this->disableAccessDirectiveResolver = $disableAccessDirectiveResolver;
        parent::__construct(
            $instanceManager,
            $fieldQueryInterpreter,
            $accessControlManager,
        );
    }

    protected function getMandatoryDirectives(mixed $entryValue = null): array
    {
        $disableAccessDirective = $this->fieldQueryInterpreter->getDirective(
            $this->disableAccessDirectiveResolver->getDirectiveName()
        );
        return [
            $disableAccessDirective,
        ];
    }
}

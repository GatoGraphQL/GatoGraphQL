<?php

declare(strict_types=1);

namespace PoP\AccessControl\RelationalTypeResolverDecorators;

use PoP\AccessControl\DirectiveResolvers\DisableAccessForDirectivesDirectiveResolver;
use PoP\AccessControl\RelationalTypeResolverDecorators\AbstractConfigurableAccessControlForDirectivesInPublicSchemaRelationalTypeResolverDecorator;
use PoP\AccessControl\Services\AccessControlManagerInterface;
use PoP\ComponentModel\DirectiveResolvers\DirectiveResolverInterface;
use PoP\ComponentModel\Instances\InstanceManagerInterface;
use PoP\ComponentModel\Schema\FieldQueryInterpreterInterface;

abstract class AbstractDisableAccessConfigurableAccessControlForDirectivesInPublicSchemaRelationalTypeResolverDecorator extends AbstractConfigurableAccessControlForDirectivesInPublicSchemaRelationalTypeResolverDecorator
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
        $disableAccessForDirectivesDirectiveResolver = $this->instanceManager->getInstance(DisableAccessForDirectivesDirectiveResolver::class);
        $disableAccessDirective = $this->fieldQueryInterpreter->getDirective(
            $disableAccessForDirectivesDirectiveResolver->getDirectiveName()
        );
        return [
            $disableAccessDirective,
        ];
    }
}

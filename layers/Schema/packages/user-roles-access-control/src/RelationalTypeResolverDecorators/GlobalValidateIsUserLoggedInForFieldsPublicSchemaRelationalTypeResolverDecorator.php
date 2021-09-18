<?php

declare(strict_types=1);

namespace PoPSchema\UserRolesAccessControl\RelationalTypeResolverDecorators;

use PoP\ComponentModel\DirectiveResolvers\DirectiveResolverInterface;
use PoP\ComponentModel\Instances\InstanceManagerInterface;
use PoP\ComponentModel\Schema\FieldQueryInterpreterInterface;
use PoP\ComponentModel\TypeResolvers\AbstractRelationalTypeResolver;
use PoPSchema\UserRolesAccessControl\DirectiveResolvers\ValidateDoesLoggedInUserHaveAnyCapabilityDirectiveResolver;
use PoPSchema\UserRolesAccessControl\DirectiveResolvers\ValidateDoesLoggedInUserHaveAnyRoleDirectiveResolver;
use PoPSchema\UserStateAccessControl\DirectiveResolvers\ValidateIsUserLoggedInDirectiveResolver;
use PoPSchema\UserStateAccessControl\RelationalTypeResolverDecorators\AbstractValidateIsUserLoggedInForFieldsPublicSchemaRelationalTypeResolverDecorator;

class GlobalValidateIsUserLoggedInForFieldsPublicSchemaRelationalTypeResolverDecorator extends AbstractValidateIsUserLoggedInForFieldsPublicSchemaRelationalTypeResolverDecorator
{
    public function __construct(
        InstanceManagerInterface $instanceManager,
        FieldQueryInterpreterInterface $fieldQueryInterpreter,
        ValidateIsUserLoggedInDirectiveResolver $validateIsUserLoggedInDirectiveResolver,
        protected ValidateDoesLoggedInUserHaveAnyRoleDirectiveResolver $validateDoesLoggedInUserHaveAnyRoleDirectiveResolver,
        protected ValidateDoesLoggedInUserHaveAnyCapabilityDirectiveResolver $validateDoesLoggedInUserHaveAnyCapabilityDirectiveResolver,
    ) {
        parent::__construct(
            $instanceManager,
            $fieldQueryInterpreter,
            $validateIsUserLoggedInDirectiveResolver,
        );
    }

    public function getRelationalTypeResolverClassesToAttachTo(): array
    {
        return [
            AbstractRelationalTypeResolver::class,
        ];
    }

    /**
     * Provide the DirectiveResolvers that need the "validateIsUserLoggedIn" directive
     *
     * @return DirectiveResolverInterface[]
     */
    protected function getDirectiveResolvers(): array
    {
        return [
            $this->validateDoesLoggedInUserHaveAnyRoleDirectiveResolver,
            $this->validateDoesLoggedInUserHaveAnyCapabilityDirectiveResolver,
        ];
    }
}

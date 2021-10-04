<?php

declare(strict_types=1);

namespace PoPSchema\UserRolesAccessControl\RelationalTypeResolverDecorators;

use PoP\ComponentModel\DirectiveResolvers\DirectiveResolverInterface;
use PoP\ComponentModel\TypeResolvers\AbstractRelationalTypeResolver;
use PoPSchema\UserRolesAccessControl\DirectiveResolvers\ValidateDoesLoggedInUserHaveAnyCapabilityForDirectivesDirectiveResolver;
use PoPSchema\UserRolesAccessControl\DirectiveResolvers\ValidateDoesLoggedInUserHaveAnyRoleForDirectivesDirectiveResolver;
use PoPSchema\UserStateAccessControl\RelationalTypeResolverDecorators\AbstractValidateIsUserLoggedInForDirectivesPublicSchemaRelationalTypeResolverDecorator;
use Symfony\Contracts\Service\Attribute\Required;

class GlobalValidateIsUserLoggedInForDirectivesPublicSchemaRelationalTypeResolverDecorator extends AbstractValidateIsUserLoggedInForDirectivesPublicSchemaRelationalTypeResolverDecorator
{
    protected ValidateDoesLoggedInUserHaveAnyRoleForDirectivesDirectiveResolver $validateDoesLoggedInUserHaveAnyRoleForDirectivesDirectiveResolver;
    protected ValidateDoesLoggedInUserHaveAnyCapabilityForDirectivesDirectiveResolver $validateDoesLoggedInUserHaveAnyCapabilityForDirectivesDirectiveResolver;

    #[Required]
    final public function autowireGlobalValidateIsUserLoggedInForDirectivesPublicSchemaRelationalTypeResolverDecorator(
        ValidateDoesLoggedInUserHaveAnyRoleForDirectivesDirectiveResolver $validateDoesLoggedInUserHaveAnyRoleForDirectivesDirectiveResolver,
        ValidateDoesLoggedInUserHaveAnyCapabilityForDirectivesDirectiveResolver $validateDoesLoggedInUserHaveAnyCapabilityForDirectivesDirectiveResolver,
    ): void {
        $this->validateDoesLoggedInUserHaveAnyRoleForDirectivesDirectiveResolver = $validateDoesLoggedInUserHaveAnyRoleForDirectivesDirectiveResolver;
        $this->validateDoesLoggedInUserHaveAnyCapabilityForDirectivesDirectiveResolver = $validateDoesLoggedInUserHaveAnyCapabilityForDirectivesDirectiveResolver;
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
            $this->validateDoesLoggedInUserHaveAnyRoleForDirectivesDirectiveResolver,
            $this->validateDoesLoggedInUserHaveAnyCapabilityForDirectivesDirectiveResolver,
        ];
    }
}

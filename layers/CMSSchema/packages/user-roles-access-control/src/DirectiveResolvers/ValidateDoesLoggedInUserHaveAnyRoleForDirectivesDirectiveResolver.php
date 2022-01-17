<?php

declare(strict_types=1);

namespace PoPSchema\UserRolesAccessControl\DirectiveResolvers;

class ValidateDoesLoggedInUserHaveAnyRoleForDirectivesDirectiveResolver extends ValidateDoesLoggedInUserHaveAnyRoleDirectiveResolver
{
    public function getDirectiveName(): string
    {
        return 'validateDoesLoggedInUserHaveAnyRoleForDirectives';
    }

    protected function isValidatingDirective(): bool
    {
        return true;
    }
}

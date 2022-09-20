<?php

declare(strict_types=1);

namespace PoPCMSSchema\UserRolesAccessControl\DirectiveResolvers;

class ValidateDoesLoggedInUserHaveAnyCapabilityForDirectivesFieldDirectiveResolver extends ValidateDoesLoggedInUserHaveAnyCapabilityFieldDirectiveResolver
{
    public function getDirectiveName(): string
    {
        return 'validateDoesLoggedInUserHaveAnyCapabilityForDirectives';
    }

    protected function isValidatingDirective(): bool
    {
        return true;
    }
}

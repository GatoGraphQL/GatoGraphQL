<?php

declare(strict_types=1);

namespace PoPSchema\UserRolesAccessControl\DirectiveResolvers;

class ValidateDoesLoggedInUserHaveAnyCapabilityForDirectivesDirectiveResolver extends ValidateDoesLoggedInUserHaveAnyCapabilityDirectiveResolver
{
    const DIRECTIVE_NAME = 'validateDoesLoggedInUserHaveAnyCapabilityForDirectives';
    public static function getDirectiveName(): string
    {
        return self::DIRECTIVE_NAME;
    }

    protected function isValidatingDirective(): bool
    {
        return true;
    }
}

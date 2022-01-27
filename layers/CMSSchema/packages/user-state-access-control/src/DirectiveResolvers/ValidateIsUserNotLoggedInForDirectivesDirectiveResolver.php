<?php

declare(strict_types=1);

namespace PoPCMSSchema\UserStateAccessControl\DirectiveResolvers;

class ValidateIsUserNotLoggedInForDirectivesDirectiveResolver extends ValidateIsUserNotLoggedInDirectiveResolver
{
    public function getDirectiveName(): string
    {
        return 'validateIsUserNotLoggedInForDirectives';
    }

    protected function isValidatingDirective(): bool
    {
        return true;
    }
}

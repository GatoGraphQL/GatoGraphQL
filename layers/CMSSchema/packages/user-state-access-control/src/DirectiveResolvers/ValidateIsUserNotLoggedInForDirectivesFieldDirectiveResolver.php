<?php

declare(strict_types=1);

namespace PoPCMSSchema\UserStateAccessControl\DirectiveResolvers;

class ValidateIsUserNotLoggedInForDirectivesFieldDirectiveResolver extends ValidateIsUserNotLoggedInFieldDirectiveResolver
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

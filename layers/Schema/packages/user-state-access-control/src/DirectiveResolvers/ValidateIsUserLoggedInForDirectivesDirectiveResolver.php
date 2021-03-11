<?php

declare(strict_types=1);

namespace PoPSchema\UserStateAccessControl\DirectiveResolvers;

class ValidateIsUserLoggedInForDirectivesDirectiveResolver extends ValidateIsUserLoggedInDirectiveResolver
{
    public function getDirectiveName(): string
    {
        return 'validateIsUserLoggedInForDirectives';
    }

    protected function isValidatingDirective(): bool
    {
        return true;
    }
}

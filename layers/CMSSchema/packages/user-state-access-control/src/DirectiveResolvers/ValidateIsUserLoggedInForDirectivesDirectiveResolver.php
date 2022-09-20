<?php

declare(strict_types=1);

namespace PoPCMSSchema\UserStateAccessControl\DirectiveResolvers;

class ValidateIsUserLoggedInForDirectivesFieldDirectiveResolver extends ValidateIsUserLoggedInFieldDirectiveResolver
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

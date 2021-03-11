<?php

declare(strict_types=1);

namespace PoPSchema\UserStateAccessControl\DirectiveResolvers;

class ValidateIsUserNotLoggedInForDirectivesDirectiveResolver extends ValidateIsUserNotLoggedInDirectiveResolver
{
    const DIRECTIVE_NAME = 'validateIsUserNotLoggedInForDirectives';
    public function getDirectiveName(): string
    {
        return self::DIRECTIVE_NAME;
    }

    protected function isValidatingDirective(): bool
    {
        return true;
    }
}

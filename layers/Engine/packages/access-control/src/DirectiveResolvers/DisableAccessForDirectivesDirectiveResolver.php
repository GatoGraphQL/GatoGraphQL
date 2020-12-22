<?php

declare(strict_types=1);

namespace PoP\AccessControl\DirectiveResolvers;

class DisableAccessForDirectivesDirectiveResolver extends DisableAccessDirectiveResolver
{
    const DIRECTIVE_NAME = 'disableAccessForDirectives';
    public static function getDirectiveName(): string
    {
        return self::DIRECTIVE_NAME;
    }

    protected function isValidatingDirective(): bool
    {
        return true;
    }
}

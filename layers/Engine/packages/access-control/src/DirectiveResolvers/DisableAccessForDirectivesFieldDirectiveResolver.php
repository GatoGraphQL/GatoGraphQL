<?php

declare(strict_types=1);

namespace PoP\AccessControl\DirectiveResolvers;

class DisableAccessForDirectivesFieldDirectiveResolver extends DisableAccessFieldDirectiveResolver
{
    public function getDirectiveName(): string
    {
        return 'disableAccessForDirectives';
    }

    protected function isValidatingDirective(): bool
    {
        return true;
    }
}

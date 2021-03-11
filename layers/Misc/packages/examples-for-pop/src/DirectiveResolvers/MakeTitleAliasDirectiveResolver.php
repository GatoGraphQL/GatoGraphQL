<?php

declare(strict_types=1);

namespace Leoloso\ExamplesForPoP\DirectiveResolvers;

use PoP\ComponentModel\DirectiveResolvers\AbstractGlobalDirectiveResolver;
use PoP\ComponentModel\DirectiveResolvers\AliasSchemaDirectiveResolverTrait;

class MakeTitleAliasDirectiveResolver extends AbstractGlobalDirectiveResolver
{
    use AliasSchemaDirectiveResolverTrait;

    public function getDirectiveName(): string
    {
        return 'unambiguousMakeTitle';
    }

    protected function getAliasedDirectiveResolverClass(): string
    {
        return MakeTitleDirectiveResolver::class;
    }
}

<?php

declare(strict_types=1);

namespace Leoloso\ExamplesForPoP\DirectiveResolvers;

use PoP\ComponentModel\DirectiveResolvers\AbstractGlobalDirectiveResolver;
use PoP\ComponentModel\DirectiveResolvers\AliasSchemaDirectiveResolverTrait;

class MakeTitleAliasDirectiveResolver extends AbstractGlobalDirectiveResolver
{
    use AliasSchemaDirectiveResolverTrait;

    const DIRECTIVE_NAME = 'unambiguousMakeTitle';
    public function getDirectiveName(): string
    {
        return self::DIRECTIVE_NAME;
    }

    protected static function getAliasedDirectiveResolverClass(): string
    {
        return MakeTitleDirectiveResolver::class;
    }
}

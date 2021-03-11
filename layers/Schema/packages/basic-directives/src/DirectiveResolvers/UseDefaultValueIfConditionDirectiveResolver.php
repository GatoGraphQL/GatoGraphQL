<?php

declare(strict_types=1);

namespace PoPSchema\BasicDirectives\DirectiveResolvers;

use PoP\ComponentModel\DirectiveResolvers\GlobalDirectiveResolverTrait;
use PoPSchema\BasicDirectives\DirectiveResolvers\AbstractUseDefaultValueIfConditionDirectiveResolver;

class UseDefaultValueIfConditionDirectiveResolver extends AbstractUseDefaultValueIfConditionDirectiveResolver
{
    use GlobalDirectiveResolverTrait;

    public function getDirectiveName(): string
    {
        return 'default';
    }
}

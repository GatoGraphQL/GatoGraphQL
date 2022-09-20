<?php

declare(strict_types=1);

namespace PoP\ComponentModel\DirectiveResolvers;

abstract class AbstractGlobalFieldDirectiveResolver extends AbstractFieldDirectiveResolver
{
    use GlobalFieldDirectiveResolverTrait;
}

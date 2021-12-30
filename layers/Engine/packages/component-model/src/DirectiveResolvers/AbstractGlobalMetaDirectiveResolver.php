<?php

declare(strict_types=1);

namespace PoP\ComponentModel\DirectiveResolvers;

abstract class AbstractGlobalMetaDirectiveResolver extends AbstractMetaDirectiveResolver
{
    use GlobalDirectiveResolverTrait;
}

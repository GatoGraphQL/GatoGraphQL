<?php

declare(strict_types=1);

namespace PoP\ComponentModel\Registries;

use PoP\ComponentModel\DirectiveResolvers\MetaDirectiveResolverInterface;

interface MetaDirectiveRegistryInterface
{
    public function addMetaDirectiveResolver(MetaDirectiveResolverInterface $metaDirectiveResolver): void;
    /**
     * @return MetaDirectiveResolverInterface[]
     */
    public function getMetaDirectiveResolvers(): array;
}

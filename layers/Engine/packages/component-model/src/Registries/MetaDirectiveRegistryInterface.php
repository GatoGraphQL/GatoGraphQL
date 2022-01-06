<?php

declare(strict_types=1);

namespace PoP\ComponentModel\Registries;

use PoP\ComponentModel\DirectiveResolvers\MetaDirectiveResolverInterface;

interface MetaDirectiveRegistryInterface
{
    public function addMetaDirectiveResolver(MetaDirectiveResolverInterface $metaDirectiveResolver): void;
    /**
     * @return array<string,MetaDirectiveResolverInterface>
     */
    public function getMetaDirectiveResolvers(): array;
    public function getMetaDirectiveResolver(string $directiveName): ?MetaDirectiveResolverInterface;
}

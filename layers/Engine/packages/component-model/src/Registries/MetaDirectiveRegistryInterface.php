<?php

declare(strict_types=1);

namespace PoP\ComponentModel\Registries;

use PoP\ComponentModel\DirectiveResolvers\MetaFieldDirectiveResolverInterface;

interface MetaDirectiveRegistryInterface
{
    public function addMetaFieldDirectiveResolver(MetaFieldDirectiveResolverInterface $metaFieldDirectiveResolver): void;
    /**
     * @return array<string,MetaFieldDirectiveResolverInterface>
     */
    public function getMetaFieldDirectiveResolvers(): array;
    public function getMetaFieldDirectiveResolver(string $directiveName): ?MetaFieldDirectiveResolverInterface;
}

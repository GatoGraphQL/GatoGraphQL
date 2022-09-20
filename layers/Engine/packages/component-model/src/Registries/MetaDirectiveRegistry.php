<?php

declare(strict_types=1);

namespace PoP\ComponentModel\Registries;

use PoP\ComponentModel\DirectiveResolvers\MetaFieldDirectiveResolverInterface;

class MetaDirectiveRegistry implements MetaDirectiveRegistryInterface
{
    /**
     * @var array<string,MetaFieldDirectiveResolverInterface>
     */
    protected array $metaFieldDirectiveResolvers = [];

    public function addMetaFieldDirectiveResolver(MetaFieldDirectiveResolverInterface $metaFieldDirectiveResolver): void
    {
        $this->metaFieldDirectiveResolvers[$metaFieldDirectiveResolver->getDirectiveName()] = $metaFieldDirectiveResolver;
    }

    /**
     * @return array<string,MetaFieldDirectiveResolverInterface>
     */
    public function getMetaFieldDirectiveResolvers(): array
    {
        return $this->metaFieldDirectiveResolvers;
    }

    public function getMetaFieldDirectiveResolver(string $directiveName): ?MetaFieldDirectiveResolverInterface
    {
        return $this->metaFieldDirectiveResolvers[$directiveName] ?? null;
    }
}

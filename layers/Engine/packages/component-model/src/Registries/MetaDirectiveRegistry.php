<?php

declare(strict_types=1);

namespace PoP\ComponentModel\Registries;

use PoP\ComponentModel\DirectiveResolvers\MetaDirectiveResolverInterface;

class MetaDirectiveRegistry implements MetaDirectiveRegistryInterface
{
    /**
     * @var array<string,MetaDirectiveResolverInterface>
     */
    protected array $metaDirectiveResolvers = [];

    public function addMetaDirectiveResolver(MetaDirectiveResolverInterface $metaDirectiveResolver): void
    {
        $this->metaDirectiveResolvers[$metaDirectiveResolver->getDirectiveName()] = $metaDirectiveResolver;
    }

    /**
     * @return array<string,MetaDirectiveResolverInterface>
     */
    public function getMetaDirectiveResolvers(): array
    {
        return $this->metaDirectiveResolvers;
    }

    public function getMetaDirectiveResolver($directiveName): ?MetaDirectiveResolverInterface
    {
        return $this->metaDirectiveResolvers[$directiveName] ?? null;
    }
}

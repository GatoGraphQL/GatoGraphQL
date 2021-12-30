<?php

declare(strict_types=1);

namespace PoP\ComponentModel\Registries;

use PoP\ComponentModel\DirectiveResolvers\MetaDirectiveResolverInterface;

class MetaDirectiveRegistry implements MetaDirectiveRegistryInterface
{
    /**
     * @var MetaDirectiveResolverInterface[]
     */
    protected array $metaDirectiveResolvers = [];

    public function addMetaDirectiveResolver(MetaDirectiveResolverInterface $metaDirectiveResolver): void
    {
        $this->metaDirectiveResolvers[] = $metaDirectiveResolver;
    }
    /**
     * @return MetaDirectiveResolverInterface[]
     */
    public function getMetaDirectiveResolvers(): array
    {
        return $this->metaDirectiveResolvers;
    }
}

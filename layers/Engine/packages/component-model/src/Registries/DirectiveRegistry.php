<?php

declare(strict_types=1);

namespace PoP\ComponentModel\Registries;

use PoP\ComponentModel\DirectiveResolvers\DirectiveResolverInterface;

class DirectiveRegistry implements DirectiveRegistryInterface
{
    /**
     * @var array<string,DirectiveResolverInterface>
     */
    protected array $directiveResolvers = [];

    public function addDirectiveResolver(DirectiveResolverInterface $directiveResolver): void
    {
        $this->directiveResolvers[$directiveResolver->getDirectiveName()] = $directiveResolver;
    }

    /**
     * @return array<string,DirectiveResolverInterface>
     */
    public function getDirectiveResolvers(): array
    {
        return $this->directiveResolvers;
    }

    public function getDirectiveResolver($directiveName): ?DirectiveResolverInterface
    {
        return $this->directiveResolvers[$directiveName] ?? null;
    }
}

<?php

declare(strict_types=1);

namespace PoP\ComponentModel\Registries;

use PoP\ComponentModel\DirectiveResolvers\FieldDirectiveResolverInterface;

class DirectiveRegistry implements DirectiveRegistryInterface
{
    /**
     * @var array<string,FieldDirectiveResolverInterface>
     */
    protected array $directiveResolvers = [];

    public function addFieldDirectiveResolver(FieldDirectiveResolverInterface $directiveResolver): void
    {
        $this->directiveResolvers[$directiveResolver->getDirectiveName()] = $directiveResolver;
    }

    /**
     * @return array<string,FieldDirectiveResolverInterface>
     */
    public function getFieldDirectiveResolvers(): array
    {
        return $this->directiveResolvers;
    }

    public function getFieldDirectiveResolver(string $directiveName): ?FieldDirectiveResolverInterface
    {
        return $this->directiveResolvers[$directiveName] ?? null;
    }
}

<?php

declare(strict_types=1);

namespace PoP\ComponentModel\Registries;

use PoP\ComponentModel\DirectiveResolvers\DynamicVariableDefinerDirectiveResolverInterface;

class DynamicVariableDefinerDirectiveRegistry implements DynamicVariableDefinerDirectiveRegistryInterface
{
    /**
     * @var array<string,DynamicVariableDefinerDirectiveResolverInterface>
     */
    protected array $dynamicVariableDefinerDirectiveResolvers = [];

    public function addDynamicVariableDefinerDirectiveResolver(DynamicVariableDefinerDirectiveResolverInterface $dynamicVariableDefinerDirectiveResolver): void
    {
        $this->dynamicVariableDefinerDirectiveResolvers[$dynamicVariableDefinerDirectiveResolver->getDirectiveName()] = $dynamicVariableDefinerDirectiveResolver;
    }

    /**
     * @return array<string,DynamicVariableDefinerDirectiveResolverInterface>
     */
    public function getDynamicVariableDefinerDirectiveResolvers(): array
    {
        return $this->dynamicVariableDefinerDirectiveResolvers;
    }

    public function getDynamicVariableDefinerDirectiveResolver(string $directiveName): ?DynamicVariableDefinerDirectiveResolverInterface
    {
        return $this->dynamicVariableDefinerDirectiveResolvers[$directiveName] ?? null;
    }
}

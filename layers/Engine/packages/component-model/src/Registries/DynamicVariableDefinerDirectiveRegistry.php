<?php

declare(strict_types=1);

namespace PoP\ComponentModel\Registries;

use PoP\ComponentModel\DirectiveResolvers\DynamicVariableDefinerFieldDirectiveResolverInterface;

class DynamicVariableDefinerDirectiveRegistry implements DynamicVariableDefinerDirectiveRegistryInterface
{
    /**
     * @var array<string,DynamicVariableDefinerFieldDirectiveResolverInterface>
     */
    protected array $dynamicVariableDefinerFieldDirectiveResolvers = [];

    public function addDynamicVariableDefinerFieldDirectiveResolver(DynamicVariableDefinerFieldDirectiveResolverInterface $dynamicVariableDefinerFieldDirectiveResolver): void
    {
        $this->dynamicVariableDefinerFieldDirectiveResolvers[$dynamicVariableDefinerFieldDirectiveResolver->getDirectiveName()] = $dynamicVariableDefinerFieldDirectiveResolver;
    }

    /**
     * @return array<string,DynamicVariableDefinerFieldDirectiveResolverInterface>
     */
    public function getDynamicVariableDefinerFieldDirectiveResolvers(): array
    {
        return $this->dynamicVariableDefinerFieldDirectiveResolvers;
    }

    public function getDynamicVariableDefinerFieldDirectiveResolver(string $directiveName): ?DynamicVariableDefinerFieldDirectiveResolverInterface
    {
        return $this->dynamicVariableDefinerFieldDirectiveResolvers[$directiveName] ?? null;
    }
}

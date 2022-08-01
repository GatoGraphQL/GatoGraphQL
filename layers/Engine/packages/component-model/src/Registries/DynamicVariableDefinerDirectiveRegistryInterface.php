<?php

declare(strict_types=1);

namespace PoP\ComponentModel\Registries;

use PoP\ComponentModel\DirectiveResolvers\DynamicVariableDefinerDirectiveResolverInterface;

interface DynamicVariableDefinerDirectiveRegistryInterface
{
    public function addDynamicVariableDefinerDirectiveResolver(DynamicVariableDefinerDirectiveResolverInterface $metaDirectiveResolver): void;
    /**
     * @return array<string,DynamicVariableDefinerDirectiveResolverInterface>
     */
    public function getDynamicVariableDefinerDirectiveResolvers(): array;
    public function getDynamicVariableDefinerDirectiveResolver(string $directiveName): ?DynamicVariableDefinerDirectiveResolverInterface;
}

<?php

declare(strict_types=1);

namespace PoP\ComponentModel\Registries;

use PoP\ComponentModel\DirectiveResolvers\DynamicVariableDefinerFieldDirectiveResolverInterface;

interface DynamicVariableDefinerDirectiveRegistryInterface
{
    public function addDynamicVariableDefinerFieldDirectiveResolver(DynamicVariableDefinerFieldDirectiveResolverInterface $metaFieldDirectiveResolver): void;
    /**
     * @return array<string,DynamicVariableDefinerFieldDirectiveResolverInterface>
     */
    public function getDynamicVariableDefinerFieldDirectiveResolvers(): array;
    public function getDynamicVariableDefinerFieldDirectiveResolver(string $directiveName): ?DynamicVariableDefinerFieldDirectiveResolverInterface;
}

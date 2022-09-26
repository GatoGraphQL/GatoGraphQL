<?php

declare(strict_types=1);

namespace PoP\ComponentModel\Registries;

use PoP\ComponentModel\DirectiveResolvers\OperationDependencyDefinerFieldDirectiveResolverInterface;

interface OperationDependencyDefinerDirectiveRegistryInterface
{
    public function addOperationDependencyDefinerFieldDirectiveResolver(OperationDependencyDefinerFieldDirectiveResolverInterface $metaFieldDirectiveResolver): void;
    /**
     * @return array<string,OperationDependencyDefinerFieldDirectiveResolverInterface>
     */
    public function getOperationDependencyDefinerFieldDirectiveResolvers(): array;
    public function getOperationDependencyDefinerFieldDirectiveResolver(string $directiveName): ?OperationDependencyDefinerFieldDirectiveResolverInterface;
}

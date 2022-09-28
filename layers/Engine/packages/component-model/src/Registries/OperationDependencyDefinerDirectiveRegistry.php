<?php

declare(strict_types=1);

namespace PoP\ComponentModel\Registries;

use PoP\ComponentModel\DirectiveResolvers\OperationDependencyDefinerFieldDirectiveResolverInterface;

class OperationDependencyDefinerDirectiveRegistry implements OperationDependencyDefinerDirectiveRegistryInterface
{
    /**
     * @var array<string,OperationDependencyDefinerFieldDirectiveResolverInterface>
     */
    protected array $operationDependencyDefinerFieldDirectiveResolvers = [];

    public function addOperationDependencyDefinerFieldDirectiveResolver(OperationDependencyDefinerFieldDirectiveResolverInterface $operationDependencyDefinerFieldDirectiveResolver): void
    {
        $this->operationDependencyDefinerFieldDirectiveResolvers[$operationDependencyDefinerFieldDirectiveResolver->getDirectiveName()] = $operationDependencyDefinerFieldDirectiveResolver;
    }

    /**
     * @return array<string,OperationDependencyDefinerFieldDirectiveResolverInterface>
     */
    public function getOperationDependencyDefinerFieldDirectiveResolvers(): array
    {
        return $this->operationDependencyDefinerFieldDirectiveResolvers;
    }

    public function getOperationDependencyDefinerFieldDirectiveResolver(string $directiveName): ?OperationDependencyDefinerFieldDirectiveResolverInterface
    {
        return $this->operationDependencyDefinerFieldDirectiveResolvers[$directiveName] ?? null;
    }
}

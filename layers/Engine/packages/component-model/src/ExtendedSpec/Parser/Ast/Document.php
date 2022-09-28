<?php

declare(strict_types=1);

namespace PoP\ComponentModel\ExtendedSpec\Parser\Ast;

use PoP\ComponentModel\DirectiveResolvers\DynamicVariableDefinerFieldDirectiveResolverInterface;
use PoP\ComponentModel\Registries\DynamicVariableDefinerDirectiveRegistryInterface;
use PoP\ComponentModel\DirectiveResolvers\OperationDependencyDefinerFieldDirectiveResolverInterface;
use PoP\ComponentModel\Registries\OperationDependencyDefinerDirectiveRegistryInterface;
use PoP\GraphQLParser\ExtendedSpec\Parser\Ast\AbstractDocument;
use PoP\GraphQLParser\Spec\Parser\Ast\Argument;
use PoP\GraphQLParser\Spec\Parser\Ast\Directive;
use PoP\Root\Facades\Instances\InstanceManagerFacade;

class Document extends AbstractDocument
{
    private ?DynamicVariableDefinerDirectiveRegistryInterface $dynamicVariableDefinerDirectiveRegistry = null;
    private ?OperationDependencyDefinerDirectiveRegistryInterface $operationDependencyDefinerDirectiveRegistry = null;

    final public function setDynamicVariableDefinerDirectiveRegistry(DynamicVariableDefinerDirectiveRegistryInterface $dynamicVariableDefinerDirectiveRegistry): void
    {
        $this->dynamicVariableDefinerDirectiveRegistry = $dynamicVariableDefinerDirectiveRegistry;
    }
    final protected function getDynamicVariableDefinerDirectiveRegistry(): DynamicVariableDefinerDirectiveRegistryInterface
    {
        /** @var DynamicVariableDefinerDirectiveRegistryInterface */
        return $this->dynamicVariableDefinerDirectiveRegistry ??= InstanceManagerFacade::getInstance()->getInstance(DynamicVariableDefinerDirectiveRegistryInterface::class);
    }
    final public function setOperationDependencyDefinerDirectiveRegistry(OperationDependencyDefinerDirectiveRegistryInterface $operationDependencyDefinerDirectiveRegistry): void
    {
        $this->operationDependencyDefinerDirectiveRegistry = $operationDependencyDefinerDirectiveRegistry;
    }
    final protected function getOperationDependencyDefinerDirectiveRegistry(): OperationDependencyDefinerDirectiveRegistryInterface
    {
        /** @var OperationDependencyDefinerDirectiveRegistryInterface */
        return $this->operationDependencyDefinerDirectiveRegistry ??= InstanceManagerFacade::getInstance()->getInstance(OperationDependencyDefinerDirectiveRegistryInterface::class);
    }

    protected function isDynamicVariableDefinerDirective(Directive $directive): bool
    {
        return $this->getDynamicVariableDefinerFieldDirectiveResolver($directive) !== null;
    }

    protected function getDynamicVariableDefinerFieldDirectiveResolver(Directive $directive): ?DynamicVariableDefinerFieldDirectiveResolverInterface
    {
        return $this->getDynamicVariableDefinerDirectiveRegistry()->getDynamicVariableDefinerFieldDirectiveResolver($directive->getName());
    }

    protected function getExportUnderVariableNameArgument(Directive $directive): ?Argument
    {
        $dynamicVariableDefinerFieldDirectiveResolver = $this->getDynamicVariableDefinerFieldDirectiveResolver($directive);
        if ($dynamicVariableDefinerFieldDirectiveResolver === null) {
            return null;
        }
        $exportUnderVariableNameArgumentName = $dynamicVariableDefinerFieldDirectiveResolver->getExportUnderVariableNameArgumentName();
        return $directive->getArgument($exportUnderVariableNameArgumentName);
    }

    protected function isOperationDependencyDefinerDirective(Directive $directive): bool
    {
        return $this->getOperationDependencyDefinerFieldDirectiveResolver($directive) !== null;
    }

    protected function getOperationDependencyDefinerFieldDirectiveResolver(Directive $directive): ?OperationDependencyDefinerFieldDirectiveResolverInterface
    {
        return $this->getOperationDependencyDefinerDirectiveRegistry()->getOperationDependencyDefinerFieldDirectiveResolver($directive->getName());
    }

    protected function getProvideDependedUponOperationNamesArgument(Directive $directive): ?Argument
    {
        $operationDependencyDefinerFieldDirectiveResolver = $this->getOperationDependencyDefinerFieldDirectiveResolver($directive);
        if ($operationDependencyDefinerFieldDirectiveResolver === null) {
            return null;
        }
        $provideDependedUponOperationNamesArgumentName = $operationDependencyDefinerFieldDirectiveResolver->getProvideDependedUponOperationNamesArgumentName();
        return $directive->getArgument($provideDependedUponOperationNamesArgumentName);
    }
}

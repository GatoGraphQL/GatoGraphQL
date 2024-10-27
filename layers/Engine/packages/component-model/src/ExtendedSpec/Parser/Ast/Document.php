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

    final protected function getDynamicVariableDefinerDirectiveRegistry(): DynamicVariableDefinerDirectiveRegistryInterface
    {
        if ($this->dynamicVariableDefinerDirectiveRegistry === null) {
            /** @var DynamicVariableDefinerDirectiveRegistryInterface */
            $dynamicVariableDefinerDirectiveRegistry = InstanceManagerFacade::getInstance()->getInstance(DynamicVariableDefinerDirectiveRegistryInterface::class);
            $this->dynamicVariableDefinerDirectiveRegistry = $dynamicVariableDefinerDirectiveRegistry;
        }
        return $this->dynamicVariableDefinerDirectiveRegistry;
    }
    final protected function getOperationDependencyDefinerDirectiveRegistry(): OperationDependencyDefinerDirectiveRegistryInterface
    {
        if ($this->operationDependencyDefinerDirectiveRegistry === null) {
            /** @var OperationDependencyDefinerDirectiveRegistryInterface */
            $operationDependencyDefinerDirectiveRegistry = InstanceManagerFacade::getInstance()->getInstance(OperationDependencyDefinerDirectiveRegistryInterface::class);
            $this->operationDependencyDefinerDirectiveRegistry = $operationDependencyDefinerDirectiveRegistry;
        }
        return $this->operationDependencyDefinerDirectiveRegistry;
    }

    protected function isDynamicVariableDefinerDirective(Directive $directive): bool
    {
        return $this->getDynamicVariableDefinerFieldDirectiveResolver($directive) !== null;
    }

    protected function getDynamicVariableDefinerFieldDirectiveResolver(Directive $directive): ?DynamicVariableDefinerFieldDirectiveResolverInterface
    {
        return $this->getDynamicVariableDefinerDirectiveRegistry()->getDynamicVariableDefinerFieldDirectiveResolver($directive->getName());
    }

    /**
     * @return Argument[]|null
     */
    protected function getExportUnderVariableNameArguments(Directive $directive): ?array
    {
        $dynamicVariableDefinerFieldDirectiveResolver = $this->getDynamicVariableDefinerFieldDirectiveResolver($directive);
        if ($dynamicVariableDefinerFieldDirectiveResolver === null) {
            return null;
        }
        $exportUnderVariableNameArgumentNames = $dynamicVariableDefinerFieldDirectiveResolver->getExportUnderVariableNameArgumentNames();
        return array_values(array_filter(array_map(
            fn (string $exportUnderVariableNameArgumentName) => $directive->getArgument($exportUnderVariableNameArgumentName),
            $exportUnderVariableNameArgumentNames
        )));
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

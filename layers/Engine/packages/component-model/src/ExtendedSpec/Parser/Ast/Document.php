<?php

declare(strict_types=1);

namespace PoP\ComponentModel\ExtendedSpec\Parser\Ast;

use PoP\ComponentModel\DirectiveResolvers\DynamicVariableDefinerDirectiveResolverInterface;
use PoP\ComponentModel\Registries\DynamicVariableDefinerDirectiveRegistryInterface;
use PoP\GraphQLParser\ExtendedSpec\Parser\Ast\AbstractDocument;
use PoP\GraphQLParser\Spec\Parser\Ast\Argument;
use PoP\GraphQLParser\Spec\Parser\Ast\Directive;
use PoP\Root\Facades\Instances\InstanceManagerFacade;

class Document extends AbstractDocument
{
    private ?DynamicVariableDefinerDirectiveRegistryInterface $dynamicVariableDefinerDirectiveRegistry = null;

    final public function setDynamicVariableDefinerDirectiveRegistry(DynamicVariableDefinerDirectiveRegistryInterface $dynamicVariableDefinerDirectiveRegistry): void
    {
        $this->dynamicVariableDefinerDirectiveRegistry = $dynamicVariableDefinerDirectiveRegistry;
    }
    final protected function getDynamicVariableDefinerDirectiveRegistry(): DynamicVariableDefinerDirectiveRegistryInterface
    {
        return $this->dynamicVariableDefinerDirectiveRegistry ??= InstanceManagerFacade::getInstance()->getInstance(DynamicVariableDefinerDirectiveRegistryInterface::class);
    }

    protected function isDynamicVariableDefinerDirective(Directive $directive): bool
    {
        return $this->getDynamicVariableDefinerDirectiveResolver($directive) !== null;
    }

    protected function getDynamicVariableDefinerDirectiveResolver(Directive $directive): ?DynamicVariableDefinerDirectiveResolverInterface
    {
        return $this->getDynamicVariableDefinerDirectiveRegistry()->getDynamicVariableDefinerDirectiveResolver($directive->getName());
    }

    protected function getExportUnderVariableNameArgument(Directive $directive): ?Argument
    {
        $dynamicVariableDefinerDirectiveResolver = $this->getDynamicVariableDefinerDirectiveResolver($directive);
        if ($dynamicVariableDefinerDirectiveResolver === null) {
            return null;
        }
        $exportUnderVariableNameArgumentName = $dynamicVariableDefinerDirectiveResolver->getExportUnderVariableNameArgumentName();
        return $directive->getArgument($exportUnderVariableNameArgumentName);
    }
}

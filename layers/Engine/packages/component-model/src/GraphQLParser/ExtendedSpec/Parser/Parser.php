<?php

declare(strict_types=1);

namespace PoP\ComponentModel\GraphQLParser\ExtendedSpec\Parser;

use PoP\ComponentModel\DirectiveResolvers\DirectiveResolverInterface;
use PoP\ComponentModel\DirectiveResolvers\DynamicVariableDefinerDirectiveResolverInterface;
use PoP\ComponentModel\DirectiveResolvers\MetaDirectiveResolverInterface;
use PoP\ComponentModel\ExtendedSpec\Parser\Ast\Document;
use PoP\ComponentModel\Registries\DirectiveRegistryInterface;
use PoP\ComponentModel\Registries\DynamicVariableDefinerDirectiveRegistryInterface;
use PoP\ComponentModel\Registries\MetaDirectiveRegistryInterface;
use PoP\GraphQLParser\ExtendedSpec\Parser\AbstractParser;
use PoP\GraphQLParser\ExtendedSpec\Parser\Ast\AbstractDocument;
use PoP\GraphQLParser\Spec\Parser\Ast\Argument;
use PoP\GraphQLParser\Spec\Parser\Ast\Directive;

class Parser extends AbstractParser
{
    private ?MetaDirectiveRegistryInterface $metaDirectiveRegistry = null;
    private ?DynamicVariableDefinerDirectiveRegistryInterface $dynamicVariableDefinerDirectiveRegistry = null;
    private ?DirectiveRegistryInterface $directiveRegistry = null;

    final public function setMetaDirectiveRegistry(MetaDirectiveRegistryInterface $metaDirectiveRegistry): void
    {
        $this->metaDirectiveRegistry = $metaDirectiveRegistry;
    }
    final protected function getMetaDirectiveRegistry(): MetaDirectiveRegistryInterface
    {
        return $this->metaDirectiveRegistry ??= $this->instanceManager->getInstance(MetaDirectiveRegistryInterface::class);
    }
    final public function setDynamicVariableDefinerDirectiveRegistry(DynamicVariableDefinerDirectiveRegistryInterface $dynamicVariableDefinerDirectiveRegistry): void
    {
        $this->dynamicVariableDefinerDirectiveRegistry = $dynamicVariableDefinerDirectiveRegistry;
    }
    final protected function getDynamicVariableDefinerDirectiveRegistry(): DynamicVariableDefinerDirectiveRegistryInterface
    {
        return $this->dynamicVariableDefinerDirectiveRegistry ??= $this->instanceManager->getInstance(DynamicVariableDefinerDirectiveRegistryInterface::class);
    }
    final public function setDirectiveRegistry(DirectiveRegistryInterface $directiveRegistry): void
    {
        $this->directiveRegistry = $directiveRegistry;
    }
    final protected function getDirectiveRegistry(): DirectiveRegistryInterface
    {
        return $this->directiveRegistry ??= $this->instanceManager->getInstance(DirectiveRegistryInterface::class);
    }

    protected function isMetaDirective(string $directiveName): bool
    {
        $metaDirectiveResolver = $this->getMetaDirectiveResolver($directiveName);
        return $metaDirectiveResolver !== null;
    }

    protected function getMetaDirectiveResolver(string $directiveName): ?MetaDirectiveResolverInterface
    {
        return $this->getMetaDirectiveRegistry()->getMetaDirectiveResolver($directiveName);
    }

    protected function getAffectDirectivesUnderPosArgument(
        Directive $directive,
    ): ?Argument {
        /** @var MetaDirectiveResolverInterface */
        $metaDirectiveResolver = $this->getMetaDirectiveResolver($directive->getName());
        $affectDirectivesUnderPosArgumentName = $metaDirectiveResolver->getAffectDirectivesUnderPosArgumentName();
        foreach ($directive->getArguments() as $argument) {
            if ($argument->getName() !== $affectDirectivesUnderPosArgumentName) {
                continue;
            }
            return $argument;
        }
        return null;
    }

    protected function getAffectDirectivesUnderPosArgumentDefaultValue(
        Directive $directive,
    ): mixed {
        /** @var MetaDirectiveResolverInterface */
        $metaDirectiveResolver = $this->getMetaDirectiveResolver($directive->getName());
        return $metaDirectiveResolver->getAffectDirectivesUnderPosArgumentDefaultValue();
    }

    protected function createDocumentInstance(
        /** @var OperationInterface[] */
        array $operations,
        /** @var Fragment[] */
        array $fragments,
    ): AbstractDocument {
        return new Document(
            $operations,
            $fragments,
        );
    }

    protected function getDirectiveResolver(string $directiveName): ?DirectiveResolverInterface
    {
        return $this->getDirectiveRegistry()->getDirectiveResolver($directiveName);
    }

    protected function isDynamicVariableDefinerDirective(Directive $directive): bool
    {
        return $this->getDynamicVariableDefinerDirectiveResolver($directive) !== null;
    }

    protected function getDynamicVariableDefinerDirectiveResolver(Directive $directive): ?DynamicVariableDefinerDirectiveResolverInterface
    {
        return $this->getDynamicVariableDefinerDirectiveRegistry()->getDynamicVariableDefinerDirectiveResolver($directive->getName());
    }

    protected function getExportUnderVariableNameArgumentName(Directive $directive): ?Argument
    {
        $dynamicVariableDefinerDirectiveResolver = $this->getDynamicVariableDefinerDirectiveResolver($directive);
        if ($dynamicVariableDefinerDirectiveResolver === null) {
            return null;
        }
        $exportUnderVariableNameArgumentName = $dynamicVariableDefinerDirectiveResolver->getExportUnderVariableNameArgumentName();
        return $directive->getArgument($exportUnderVariableNameArgumentName);
    }

    protected function getAffectAdditionalFieldsUnderPosArgumentName(Directive $directive): ?string
    {
        $directiveResolver = $this->getDirectiveResolver($directive->getName());
        if ($directiveResolver === null) {
            return null;
        }
        return $directiveResolver->getAffectAdditionalFieldsUnderPosArgumentName();
    }
}

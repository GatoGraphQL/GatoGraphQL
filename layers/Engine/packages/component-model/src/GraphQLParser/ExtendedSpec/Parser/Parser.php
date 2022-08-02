<?php

declare(strict_types=1);

namespace PoP\ComponentModel\GraphQLParser\ExtendedSpec\Parser;

use PoP\ComponentModel\DirectiveResolvers\DynamicVariableDefinerDirectiveResolverInterface;
use PoP\ComponentModel\DirectiveResolvers\MetaDirectiveResolverInterface;
use PoP\ComponentModel\ExtendedSpec\Parser\Ast\Document;
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

    protected function isMetaDirective(string $directiveName): bool
    {
        $metaDirectiveResolver = $this->getMetaDirectiveResolver($directiveName);
        return $metaDirectiveResolver !== null;
    }

    protected function getMetaDirectiveResolver(string $directiveName): ?MetaDirectiveResolverInterface
    {
        return $this->getMetaDirectiveRegistry()->getMetaDirectiveResolver($directiveName);
    }

    protected function getDynamicVariableDefinerDirectiveResolver(string $directiveName): ?DynamicVariableDefinerDirectiveResolverInterface
    {
        return $this->getDynamicVariableDefinerDirectiveRegistry()->getDynamicVariableDefinerDirectiveResolver($directiveName);
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
}

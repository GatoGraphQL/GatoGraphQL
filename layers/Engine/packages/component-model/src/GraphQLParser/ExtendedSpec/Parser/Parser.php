<?php

declare(strict_types=1);

namespace PoP\ComponentModel\GraphQLParser\ExtendedSpec\Parser;

use PoP\ComponentModel\DirectiveResolvers\DirectiveResolverInterface;
use PoP\ComponentModel\DirectiveResolvers\DynamicVariableDefinerFieldDirectiveResolverInterface;
use PoP\ComponentModel\DirectiveResolvers\MetaFieldDirectiveResolverInterface;
use PoP\ComponentModel\ExtendedSpec\Parser\Ast\Document;
use PoP\ComponentModel\Registries\DirectiveRegistryInterface;
use PoP\ComponentModel\Registries\DynamicVariableDefinerDirectiveRegistryInterface;
use PoP\ComponentModel\Registries\MetaDirectiveRegistryInterface;
use PoP\GraphQLParser\ExtendedSpec\Parser\AbstractParser;
use PoP\GraphQLParser\ExtendedSpec\Parser\Ast\AbstractDocument;
use PoP\GraphQLParser\Spec\Parser\Ast\Argument;
use PoP\GraphQLParser\Spec\Parser\Ast\Directive;
use PoP\GraphQLParser\Spec\Parser\Ast\Fragment;
use PoP\GraphQLParser\Spec\Parser\Ast\OperationInterface;
use PoP\Root\Facades\Instances\InstanceManagerFacade;

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
        /** @var MetaDirectiveRegistryInterface */
        return $this->metaDirectiveRegistry ??= InstanceManagerFacade::getInstance()->getInstance(MetaDirectiveRegistryInterface::class);
    }
    final public function setDynamicVariableDefinerDirectiveRegistry(DynamicVariableDefinerDirectiveRegistryInterface $dynamicVariableDefinerDirectiveRegistry): void
    {
        $this->dynamicVariableDefinerDirectiveRegistry = $dynamicVariableDefinerDirectiveRegistry;
    }
    final protected function getDynamicVariableDefinerDirectiveRegistry(): DynamicVariableDefinerDirectiveRegistryInterface
    {
        /** @var DynamicVariableDefinerDirectiveRegistryInterface */
        return $this->dynamicVariableDefinerDirectiveRegistry ??= InstanceManagerFacade::getInstance()->getInstance(DynamicVariableDefinerDirectiveRegistryInterface::class);
    }
    final public function setDirectiveRegistry(DirectiveRegistryInterface $directiveRegistry): void
    {
        $this->directiveRegistry = $directiveRegistry;
    }
    final protected function getDirectiveRegistry(): DirectiveRegistryInterface
    {
        /** @var DirectiveRegistryInterface */
        return $this->directiveRegistry ??= InstanceManagerFacade::getInstance()->getInstance(DirectiveRegistryInterface::class);
    }

    protected function isMetaDirective(string $directiveName): bool
    {
        $metaFieldDirectiveResolver = $this->getMetaFieldDirectiveResolver($directiveName);
        return $metaFieldDirectiveResolver !== null;
    }

    protected function getMetaFieldDirectiveResolver(string $directiveName): ?MetaFieldDirectiveResolverInterface
    {
        return $this->getMetaDirectiveRegistry()->getMetaFieldDirectiveResolver($directiveName);
    }

    protected function getAffectDirectivesUnderPosArgument(
        Directive $directive,
    ): ?Argument {
        /** @var MetaFieldDirectiveResolverInterface */
        $metaFieldDirectiveResolver = $this->getMetaFieldDirectiveResolver($directive->getName());
        $affectDirectivesUnderPosArgumentName = $metaFieldDirectiveResolver->getAffectDirectivesUnderPosArgumentName();
        foreach ($directive->getArguments() as $argument) {
            if ($argument->getName() !== $affectDirectivesUnderPosArgumentName) {
                continue;
            }
            return $argument;
        }
        return null;
    }

    /**
     * @return int[]
     */
    protected function getAffectDirectivesUnderPosArgumentDefaultValue(
        Directive $directive,
    ): array {
        /** @var MetaFieldDirectiveResolverInterface */
        $metaFieldDirectiveResolver = $this->getMetaFieldDirectiveResolver($directive->getName());
        return $metaFieldDirectiveResolver->getAffectDirectivesUnderPosArgumentDefaultValue();
    }

    /**
     * @param OperationInterface[] $operations
     * @param Fragment[] $fragments
     */
    protected function createDocumentInstance(
        array $operations,
        array $fragments,
    ): AbstractDocument {
        return new Document(
            $operations,
            $fragments,
        );
    }

    protected function getFieldDirectiveResolver(string $directiveName): ?DirectiveResolverInterface
    {
        return $this->getDirectiveRegistry()->getFieldDirectiveResolver($directiveName);
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

    protected function getAffectAdditionalFieldsUnderPosArgumentName(Directive $directive): ?string
    {
        $directiveResolver = $this->getFieldDirectiveResolver($directive->getName());
        if ($directiveResolver === null) {
            return null;
        }
        return $directiveResolver->getAffectAdditionalFieldsUnderPosArgumentName();
    }

    protected function mustResolveDynamicVariableOnObject(Directive $directive): ?bool
    {
        $dynamicVariableDefinerFieldDirectiveResolver = $this->getDynamicVariableDefinerFieldDirectiveResolver($directive);
        if ($dynamicVariableDefinerFieldDirectiveResolver === null) {
            return null;
        }
        return $dynamicVariableDefinerFieldDirectiveResolver->mustResolveDynamicVariableOnObject();
    }
}

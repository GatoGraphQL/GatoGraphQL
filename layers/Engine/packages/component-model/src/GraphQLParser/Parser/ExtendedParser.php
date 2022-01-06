<?php

declare(strict_types=1);

namespace PoP\ComponentModel\GraphQLParser\Parser;

use PoP\ComponentModel\DirectiveResolvers\MetaDirectiveResolverInterface;
use PoP\ComponentModel\Registries\MetaDirectiveRegistryInterface;
use PoP\GraphQLParser\Parser\AbstractExtendedParser;
use PoPBackbone\GraphQLParser\Parser\Ast\Argument;
use PoPBackbone\GraphQLParser\Parser\Ast\Directive;

class ExtendedParser extends AbstractExtendedParser
{
    private ?MetaDirectiveRegistryInterface $metaDirectiveRegistry = null;

    final public function setMetaDirectiveRegistry(MetaDirectiveRegistryInterface $metaDirectiveRegistry): void
    {
        $this->metaDirectiveRegistry = $metaDirectiveRegistry;
    }
    final protected function getMetaDirectiveRegistry(): MetaDirectiveRegistryInterface
    {
        return $this->metaDirectiveRegistry ??= $this->instanceManager->getInstance(MetaDirectiveRegistryInterface::class);
    }

    protected function isMetaDirective(string $directiveName): bool
    {
        $metaDirectiveResolver = $this->getMetaDirectiveResolver($directiveName);
        return $metaDirectiveResolver !== null;
    }

    protected function getMetaDirectiveResolver(string $directiveName): ?MetaDirectiveResolverInterface
    {
        $metaDirectiveResolvers = $this->getMetaDirectiveRegistry()->getMetaDirectiveResolvers();
        foreach ($metaDirectiveResolvers as $metaDirectiveResolver) {
            if ($metaDirectiveResolver->getDirectiveName() !== $directiveName) {
                continue;
            }
            return $metaDirectiveResolver;
        }
        return null;
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
}

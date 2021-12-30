<?php

declare(strict_types=1);

namespace PoP\GraphQLParser\Parser;

use GraphQLByPoP\GraphQLQuery\Schema\SchemaElements;
use PoP\ComponentModel\DirectiveResolvers\MetaDirectiveResolverInterface;
use PoP\ComponentModel\Registries\MetaDirectiveRegistryInterface;
use PoP\GraphQLParser\Parser\Ast\MetaDirective;
use PoPBackbone\GraphQLParser\Exception\Parser\InvalidRequestException;
use PoPBackbone\GraphQLParser\Parser\Ast\Argument;
use PoPBackbone\GraphQLParser\Parser\Ast\Directive;
use PoPBackbone\GraphQLParser\Parser\Location;

class ExtendedParser extends Parser implements ExtendedParserInterface
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

    /**
     * Replace `Directive` with `MetaDirective`, and nest the affected
     * directives inside.
     *
     * @return Directive[]
     */
    protected function parseDirectiveList(): array
    {
        $directives = parent::parseDirectiveList();

        // Identify meta directives
        $metaDirectiveResolvers = $this->getMetaDirectiveRegistry()->getMetaDirectiveResolvers();
        $metaDirectiveResolverNames = array_map(
            fn(MetaDirectiveResolverInterface $metaDirectiveResolver) => $metaDirectiveResolver->getDirectiveName(),
            $metaDirectiveResolvers
        );
        $directiveCount = count($directives);
        $counter = 0;
        foreach ($directives as $directive) {
            if (!in_array($directive->getName(), $metaDirectiveResolverNames)) {
                continue;
            }
            $affectDirectivesUnderPosArgument = $this->getAffectDirectivesUnderPosArgument($directive);
            if ($affectDirectivesUnderPosArgument === null) {
                continue;
            }
            $affectDirectivesUnderPositions = $this->getAffectDirectivesUnderPosArgumentValue(
                $directive,
                $affectDirectivesUnderPosArgument,
                $counter,
                $directiveCount,
            );
            foreach ($affectDirectivesUnderPositions as $affectDirectiveUnderPosition) {

            }
            $counter++;
        }
        return $directives;
    }

    protected function getAffectDirectivesUnderPosArgument(
        Directive $directive
    ): ?Argument {
        foreach ($directive->getArguments() as $argument) {
            if ($argument->getName() !== SchemaElements::DIRECTIVE_PARAM_AFFECT_DIRECTIVES_UNDER_POS) {
                continue;
            }
            return $argument;
        }
        return null;
    }

    /**
     * @return int[]
     * @throws InvalidRequestException
     */
    protected function getAffectDirectivesUnderPosArgumentValue(
        Directive $directive,
        Argument $argument,
        int $directivePos,
        int $directiveCount,
    ): array {
        $argumentValue = $argument->getValue()->getValue();
        if ($argumentValue === null) {
            throw new InvalidRequestException(
                $this->getAffectedDirectivesUnderPosNotEmptyErrorMessage($directive, $argument),
                $argument->getLocation()
            );
        }

        // Enable single value to array coercing
        if (!is_array($argumentValue)) {
            $argumentValue = [$argumentValue];
        }

        if ($argumentValue === []) {
            throw new InvalidRequestException(
                $this->getAffectedDirectivesUnderPosNotEmptyErrorMessage($directive, $argument),
                $argument->getLocation()
            );
        }

        foreach ($argumentValue as $argumentValueItem) {
            if (!is_int($argumentValueItem)) {
                throw new InvalidRequestException(
                    $this->getAffectedDirectivesUnderPosNotPositiveIntErrorMessage($directive, $argument, $argumentValueItem),
                    $argument->getLocation()
                );
            }
            $argumentValueItem = (int)$argumentValueItem;
            if ($argumentValueItem <= 0) {
                throw new InvalidRequestException(
                    $this->getAffectedDirectivesUnderPosNotPositiveIntErrorMessage($directive, $argument, $argumentValueItem),
                    $argument->getLocation()
                );
            }
            if ($directivePos + $argumentValueItem > $directiveCount) {
                throw new InvalidRequestException(
                    $this->getNoAffectedDirectiveUnderPosErrorMessage($directive, $argument, $argumentValueItem),
                    $argument->getLocation()
                );
            }
        }

        return $argumentValue;
    }

    protected function getAffectedDirectivesUnderPosNotEmptyErrorMessage(
        Directive $directive,
        Argument $argument
    ): string {
        return \sprintf(
            $this->getTranslationAPI()->__('Argument \'%s\' in directive \'%s\' cannot be null or empty', 'graphql-parser'),
            $argument->getName(),
            $directive->getName()
        );
    }

    protected function getAffectedDirectivesUnderPosNotPositiveIntErrorMessage(
        Directive $directive,
        Argument $argument,
        int $itemValue
    ): string {
        return \sprintf(
            $this->getTranslationAPI()->__('Argument \'%s\' in directive \'%s\' must be an array of positive integers, \'%s\' is not allowed', 'graphql-parser'),
            $argument->getName(),
            $directive->getName(),
            $itemValue
        );
    }

    protected function getNoAffectedDirectiveUnderPosErrorMessage(
        Directive $directive,
        Argument $argument,
        int $itemValue
    ): string {
        return \sprintf(
            $this->getTranslationAPI()->__('There is no directive in relative position \'%s\' from meta directive \'%s\', as indicated in argument \'%s\'', 'graphql-parser'),
            $itemValue,
            $directive->getName(),
            $argument->getName(),
        );
    }

    /**
     * @param Argument[] $arguments
     * @param Directive[] $nestedDirectives
     */
    protected function createMetaDirective(
        $name,
        array $arguments,
        array $nestedDirectives,
        Location $location,
    ): MetaDirective {
        return new MetaDirective($name, $arguments, $nestedDirectives, $location);
    }
}

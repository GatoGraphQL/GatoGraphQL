<?php

declare(strict_types=1);

namespace PoP\GraphQLParser\Parser;

use PoP\ComponentModel\DirectiveResolvers\MetaDirectiveResolverInterface;
use PoP\ComponentModel\Registries\MetaDirectiveRegistryInterface;
use PoP\GraphQLParser\Parser\Ast\MetaDirective;
use PoPBackbone\GraphQLParser\Exception\Parser\InvalidRequestException;
use PoPBackbone\GraphQLParser\Parser\Ast\Argument;
use PoPBackbone\GraphQLParser\Parser\Ast\Directive;
use PoPBackbone\GraphQLParser\Parser\Location;
use PoP\GraphQLParser\ComponentConfiguration;

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

        if (!ComponentConfiguration::enableComposableDirectives()) {
            return $directives;
        }

        $metaDirectiveResolvers = $this->getMetaDirectiveRegistry()->getMetaDirectiveResolvers();
        /**
         * For each directive, indicate which meta-directive is composing it
         * by indicating their relative position (as a negative int)
         * @var array<int, int>
         */
        $composingMetaDirectiveRelativePosition = [];
        $directiveCount = count($directives);
        $directivePos = 0;
        while ($directivePos < $directiveCount) {
            $directive = $directives[$directivePos];
            $metaDirectiveResolver = null;
            foreach ($metaDirectiveResolvers as $maybeMetaDirectiveResolver) {
                if ($maybeMetaDirectiveResolver->getDirectiveName() !== $directive->getName()) {
                    continue;
                }
                $metaDirectiveResolver = $maybeMetaDirectiveResolver;
                break;
            }
            if ($metaDirectiveResolver === null) {
                continue;
            }
            $affectDirectivesUnderPosArgument = $this->getAffectDirectivesUnderPosArgument($metaDirectiveResolver, $directive);
            if ($affectDirectivesUnderPosArgument === null) {
                continue;
            }
            $affectDirectivesUnderPositions = $this->getAffectDirectivesUnderPosArgumentValue(
                $directive,
                $affectDirectivesUnderPosArgument,
                $directivePos,
                $directiveCount,
            );
            foreach ($affectDirectivesUnderPositions as $affectDirectiveUnderPosition) {
                $composingMetaDirectiveRelativePosition[$directivePos + $affectDirectiveUnderPosition] = $affectDirectiveUnderPosition;
            }
            $directivePos++;
        }

        /**
         * Iterate from right to left, as to enable composable directives.
         * 
         * Because we can have <directive1<directive2<directive3>>>, represented as:
         * 
         *   @directive1(affect: [1]) @directive2(affect: [1]) @directive3
         *
         * then @directive3 must first be added under @directive2, and then this one
         * must be added under @directive1.
         *
         * If we iterated from left to right, @directive3 would not be added under
         * @directive1=>@directive2
         */
        $directivesAndMetaDirectives = [];
        $directivePos = $directiveCount - 1;
        while ($directivePos >= 0) {
            $directive = $directives[$directivePos];
            $nestedUnderMetaDirectiveInRelativePosition = $composingMetaDirectiveRelativePosition[$directivePos] ?? null;
            if ($nestedUnderMetaDirectiveInRelativePosition === null) {
                $directivesAndMetaDirectives[$directivePos] = $directive;
                $directivePos--;
                continue;
            }
            
            $metaDirectivePos = $directivePos - $nestedUnderMetaDirectiveInRelativePosition;
            if (!isset($directivesAndMetaDirectives[$metaDirectivePos])) {
                $sourceDirective = $directives[$metaDirectivePos];
                $directivesAndMetaDirectives[$metaDirectivePos] = $this->createMetaDirective(
                    $sourceDirective->getName(),
                    $sourceDirective->getArguments(),
                    [],
                    $sourceDirective->getLocation()
                );
            }
            /** @var MetaDirective */
            $metaDirective = $directivesAndMetaDirectives[$metaDirectivePos];
            $metaDirective->addNestedDirective($directive);
            $directivePos--;
        }

        // Remove the empty slots from those directives which were added under some metaDirective
        return array_values($directivesAndMetaDirectives);
    }

    protected function getAffectDirectivesUnderPosArgument(
        MetaDirectiveResolverInterface $metaDirectiveResolver,
        Directive $directive,
    ): ?Argument {
        $affectDirectivesUnderPosArgumentName = $metaDirectiveResolver->getAffectDirectivesUnderPosArgumentName();
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

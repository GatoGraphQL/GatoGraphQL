<?php

declare(strict_types=1);

namespace PoP\GraphQLParser\ExtendedSpec\Parser;

use PoP\GraphQLParser\Component;
use PoP\GraphQLParser\ComponentConfiguration;
use PoP\GraphQLParser\ExtendedSpec\Parser\Ast\MetaDirective;
use PoP\GraphQLParser\Response\OutputServiceInterface;
use PoP\Root\App;
use PoPBackbone\GraphQLParser\Exception\Parser\InvalidRequestException;
use PoPBackbone\GraphQLParser\Parser\Ast\Argument;
use PoPBackbone\GraphQLParser\Parser\Ast\Directive;
use PoPBackbone\GraphQLParser\Parser\Location;
use stdClass;

abstract class AbstractExtendedParser extends Parser implements ExtendedParserInterface
{
    private ?OutputServiceInterface $outputService = null;

    final public function setOutputService(OutputServiceInterface $outputService): void
    {
        $this->outputService = $outputService;
    }
    final protected function getOutputService(): OutputServiceInterface
    {
        return $this->outputService ??= $this->instanceManager->getInstance(OutputServiceInterface::class);
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
        /** @var ComponentConfiguration */
        $componentConfiguration = App::getComponent(Component::class)->getConfiguration();
        if (!$componentConfiguration->enableComposableDirectives()) {
            return $directives;
        }

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
            if (!$this->isMetaDirective($directive->getName())) {
                $directivePos++;
                continue;
            }
            /**
             * Obtain the value from the "affect" argument.
             * If not set, use the default value
             */
            $affectDirectivesUnderPosArgument = $this->getAffectDirectivesUnderPosArgument($directive);
            $affectDirectivesUnderPositions = $affectDirectivesUnderPosArgument !== null ?
                $this->getAffectDirectivesUnderPosArgumentValue(
                    $directive,
                    $affectDirectivesUnderPosArgument,
                    $directivePos,
                    $directiveCount,
                )
                : $this->getAffectDirectivesUnderPosArgumentDefaultValue($directive);

            foreach ($affectDirectivesUnderPositions as $affectDirectiveUnderPosition) {
                /**
                 * Every directive can be referenced only once.
                 *
                 * Eg: This query is not valid (@upperCase is referenced twice):
                 *
                 *   { groupCapabilities @forEach(affectDirectivesUnderPos: [1,2]) @advancePointerInArrayOrObject @upperCase }
                 */
                if (isset($composingMetaDirectiveRelativePosition[$directivePos + $affectDirectiveUnderPosition])) {
                    throw new InvalidRequestException(
                        $this->getAffectedDirectivesReferencedMoreThanOnceErrorMessage($directive),
                        $directive->getLocation()
                    );
                }
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
        $rootDirectivePositions = [];
        $metaDirectives = [];
        $directivePos = $directiveCount - 1;
        while ($directivePos >= 0) {
            $directive = $metaDirectives[$directivePos] ?? $directives[$directivePos];
            $nestedUnderMetaDirectiveInRelativePosition = $composingMetaDirectiveRelativePosition[$directivePos] ?? null;
            if ($nestedUnderMetaDirectiveInRelativePosition === null) {
                array_unshift($rootDirectivePositions, $directivePos);
                $directivePos--;
                continue;
            }

            $metaDirectivePos = $directivePos - $nestedUnderMetaDirectiveInRelativePosition;
            if (!isset($metaDirectives[$metaDirectivePos])) {
                $sourceDirective = $directives[$metaDirectivePos];
                $metaDirectives[$metaDirectivePos] = $this->createMetaDirective(
                    $sourceDirective->getName(),
                    $sourceDirective->getArguments(),
                    [],
                    $sourceDirective->getLocation()
                );
            }
            /** @var MetaDirective */
            $metaDirective = $metaDirectives[$metaDirectivePos];
            $metaDirective->prependNestedDirective($directive);
            $directivePos--;
        }

        $rootDirectives = [];
        foreach ($rootDirectivePositions as $rootDirectivePosition) {
            $rootDirectives[] = $metaDirectives[$rootDirectivePosition] ?? $directives[$rootDirectivePosition];
        }
        return $rootDirectives;
    }

    abstract protected function isMetaDirective(string $directiveName): bool;

    abstract protected function getAffectDirectivesUnderPosArgument(
        Directive $directive,
    ): ?Argument;

    abstract protected function getAffectDirectivesUnderPosArgumentDefaultValue(
        Directive $directive,
    ): mixed;

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
            if (!is_int($argumentValueItem) || ((int)$argumentValueItem <= 0)) {
                throw new InvalidRequestException(
                    $this->getAffectedDirectivesUnderPosNotPositiveIntErrorMessage($directive, $argument, $argumentValueItem),
                    $argument->getLocation()
                );
            }
            $nestedDirectivePos = $directivePos + (int)$argumentValueItem;
            if ($nestedDirectivePos >= $directiveCount) {
                throw new InvalidRequestException(
                    $this->getNoAffectedDirectiveUnderPosErrorMessage($directive, $argument, $argumentValueItem),
                    $argument->getLocation()
                );
            }
        }

        return $argumentValue;
    }

    protected function getAffectedDirectivesReferencedMoreThanOnceErrorMessage(
        Directive $directive,
    ): string {
        return \sprintf(
            $this->__('Meta directive \'%s\' is nesting a directive already nested by another meta-directive', 'graphql-parser'),
            $directive->getName()
        );
    }

    protected function getAffectedDirectivesUnderPosNotEmptyErrorMessage(
        Directive $directive,
        Argument $argument
    ): string {
        return \sprintf(
            $this->__('Argument \'%s\' in directive \'%s\' cannot be null or empty', 'graphql-parser'),
            $argument->getName(),
            $directive->getName()
        );
    }

    protected function getAffectedDirectivesUnderPosNotPositiveIntErrorMessage(
        Directive $directive,
        Argument $argument,
        mixed $itemValue
    ): string {
        return \sprintf(
            $this->__('Argument \'%s\' in directive \'%s\' must be an array of positive integers, array item \'%s\' is not allowed', 'graphql-parser'),
            $argument->getName(),
            $directive->getName(),
            is_array($itemValue) || ($itemValue instanceof stdClass)
                ? $this->getOutputService()->jsonEncodeArrayOrStdClassValue($itemValue)
                : $itemValue
        );
    }

    protected function getNoAffectedDirectiveUnderPosErrorMessage(
        Directive $directive,
        Argument $argument,
        int $itemValue
    ): string {
        return \sprintf(
            $this->__('There is no directive in relative position \'%s\' from meta directive \'%s\', as indicated in argument \'%s\'', 'graphql-parser'),
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

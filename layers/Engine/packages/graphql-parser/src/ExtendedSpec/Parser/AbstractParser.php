<?php

declare(strict_types=1);

namespace PoP\GraphQLParser\ExtendedSpec\Parser;

use PoP\ComponentModel\DirectiveResolvers\DirectiveResolverInterface;
use PoP\ComponentModel\Registries\DirectiveRegistryInterface;
use PoP\GraphQLParser\Exception\Parser\InvalidDynamicContextException;
use PoP\GraphQLParser\Exception\Parser\InvalidRequestException;
use PoP\GraphQLParser\ExtendedSpec\Constants\QuerySyntax;
use PoP\GraphQLParser\ExtendedSpec\Parser\Ast\ArgumentValue\DynamicVariableReference;
use PoP\GraphQLParser\ExtendedSpec\Parser\Ast\ArgumentValue\ObjectResolvedFieldValueReference;
use PoP\GraphQLParser\ExtendedSpec\Parser\Ast\Document;
use PoP\GraphQLParser\ExtendedSpec\Parser\Ast\MetaDirective;
use PoP\GraphQLParser\FeedbackItemProviders\GraphQLExtendedSpecErrorFeedbackItemProvider;
use PoP\GraphQLParser\Module;
use PoP\GraphQLParser\ModuleConfiguration;
use PoP\GraphQLParser\Query\QueryAugmenterServiceInterface;
use PoP\GraphQLParser\Spec\Parser\Ast\Argument;
use PoP\GraphQLParser\Spec\Parser\Ast\ArgumentValue\VariableReference;
use PoP\GraphQLParser\Spec\Parser\Ast\Directive;
use PoP\GraphQLParser\Spec\Parser\Ast\FieldInterface;
use PoP\GraphQLParser\Spec\Parser\Ast\Fragment;
use PoP\GraphQLParser\Spec\Parser\Ast\FragmentBondInterface;
use PoP\GraphQLParser\Spec\Parser\Ast\FragmentReference;
use PoP\GraphQLParser\Spec\Parser\Ast\InlineFragment;
use PoP\GraphQLParser\Spec\Parser\Ast\LeafField;
use PoP\GraphQLParser\Spec\Parser\Ast\OperationInterface;
use PoP\GraphQLParser\Spec\Parser\Ast\RelationalField;
use PoP\GraphQLParser\Spec\Parser\Ast\Variable;
use PoP\GraphQLParser\Spec\Parser\Location;
use PoP\GraphQLParser\Spec\Parser\Parser as UpstreamParser;
use PoP\Root\App;
use PoP\Root\Feedback\FeedbackItemResolution;

abstract class AbstractParser extends UpstreamParser implements ParserInterface
{
    /**
     * Use this variable to keep track of which are the
     * fields already defined inside the current block.
     * It will be used to identify ObjectResolvedFieldValueReferences,
     * i.e. a variable with a name to an existing and previous field:
     *
     * ```
     * {
     *   someField
     *   echo(value: $__someField)
     * }
     * ```
     *
     * The variable is a stack composed of [Field],
     * with the first item in the stack being the
     * current level being parsed.
     *
     * @var array<FieldInterface[]>
     */
    protected array $parsedFieldBlockStack = [];

    /**
     * ObjectResolvedFieldValueReferences are not supported
     * within Directive Arguments.
     */
    protected bool $parsingDirectiveArgumentList = false;

    private ?QueryAugmenterServiceInterface $queryHelperService = null;
    private ?DirectiveRegistryInterface $directiveRegistry = null;

    final public function setQueryAugmenterService(QueryAugmenterServiceInterface $queryHelperService): void
    {
        $this->queryHelperService = $queryHelperService;
    }
    final protected function getQueryAugmenterService(): QueryAugmenterServiceInterface
    {
        return $this->queryHelperService ??= $this->instanceManager->getInstance(QueryAugmenterServiceInterface::class);
    }

    final public function setDirectiveRegistry(DirectiveRegistryInterface $directiveRegistry): void
    {
        $this->directiveRegistry = $directiveRegistry;
    }
    final protected function getDirectiveRegistry(): DirectiveRegistryInterface
    {
        return $this->directiveRegistry ??= $this->instanceManager->getInstance(DirectiveRegistryInterface::class);
    }

    protected function parseOperation(string $type): OperationInterface
    {
        $this->parsedFieldBlockStack = [];

        return parent::parseOperation($type);
    }

    /**
     * Append a new, empty block of [Field]
     */
    protected function beforeParsingFieldsOrFragmentBonds(): void
    {
        array_unshift($this->parsedFieldBlockStack, []);
    }

    /**
     * Remove the (now previous) block of [Field]
     */
    protected function afterParsingFieldsOrFragmentBonds(): void
    {
        array_shift($this->parsedFieldBlockStack);
    }

    /**
     * ObjectResolvedFieldValueReferences are not supported
     * within Directive Arguments
     */
    protected function beforeParsingDirectiveArgumentList(): void
    {
        $this->parsingDirectiveArgumentList = true;
    }

    /**
     * ObjectResolvedFieldValueReferences are not supported
     * within Directive Arguments
     */
    protected function afterParsingDirectiveArgumentList(): void
    {
        $this->parsingDirectiveArgumentList = false;
    }

    /**
     * @param Argument[] $arguments
     * @param array<FieldInterface|FragmentBondInterface> $fieldsOrFragmentBonds
     * @param Directive[] $directives
     */
    protected function createRelationalField(
        string $name,
        ?string $alias,
        array $arguments,
        array $fieldsOrFragmentBonds,
        array $directives,
        Location $location
    ): RelationalField {
        $relationalField = parent::createRelationalField(
            $name,
            $alias,
            $arguments,
            $fieldsOrFragmentBonds,
            $directives,
            $location
        );
        $this->addFieldToCurrentlyParsedFieldBlock($relationalField);
        return $relationalField;
    }

    protected function addFieldToCurrentlyParsedFieldBlock(
        FieldInterface $field,
    ): void {
        $this->parsedFieldBlockStack[0][] = $field;
    }

    /**
     * @param Argument[] $arguments
     * @param Directive[] $directives
     */
    protected function createLeafField(
        string $name,
        ?string $alias,
        array $arguments,
        array $directives,
        Location $location,
    ): LeafField {
        $leafField = parent::createLeafField(
            $name,
            $alias,
            $arguments,
            $directives,
            $location,
        );
        $this->addFieldToCurrentlyParsedFieldBlock($leafField);
        return $leafField;
    }

    /**
     * @return Directive[]
     */
    protected function parseDirectiveList(): array
    {
        $directives = parent::parseDirectiveList();

        /** @var ModuleConfiguration */
        $moduleConfiguration = App::getModule(Module::class)->getConfiguration();
        if ($moduleConfiguration->enableComposableDirectives()) {
            $directives = $this->addMetaDirectiveList($directives);
        }

        return $directives;
    }

    /**
     * Replace `Directive` with `MetaDirective`, and nest the affected
     * directives inside.
     *
     * @param Directive[] $directives
     * @return Directive[]
     */
    protected function addMetaDirectiveList(array $directives): array
    {
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
                        new FeedbackItemResolution(
                            GraphQLExtendedSpecErrorFeedbackItemProvider::class,
                            GraphQLExtendedSpecErrorFeedbackItemProvider::E1,
                            [
                                $directive->getName(),
                            ]
                        ),
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
     * @throws InvalidDynamicContextException When accessing non-declared Dynamic Variables
     */
    protected function getAffectDirectivesUnderPosArgumentValue(
        Directive $directive,
        Argument $argument,
        int $directivePos,
        int $directiveCount,
    ): array {
        $argumentValue = $argument->getValue();
        if ($argumentValue === null) {
            throw new InvalidRequestException(
                new FeedbackItemResolution(
                    GraphQLExtendedSpecErrorFeedbackItemProvider::class,
                    GraphQLExtendedSpecErrorFeedbackItemProvider::E2,
                    [
                        $argument->getName(),
                        $directive->getName(),
                    ]
                ),
                $argument->getLocation()
            );
        }

        // Enable single value to array coercing
        if (!is_array($argumentValue)) {
            $argumentValue = [$argumentValue];
        }

        if ($argumentValue === []) {
            throw new InvalidRequestException(
                new FeedbackItemResolution(
                    GraphQLExtendedSpecErrorFeedbackItemProvider::class,
                    GraphQLExtendedSpecErrorFeedbackItemProvider::E2,
                    [
                        $argument->getName(),
                        $directive->getName(),
                    ]
                ),
                $argument->getLocation()
            );
        }

        foreach ($argumentValue as $argumentValueItem) {
            if (!is_int($argumentValueItem) || ((int)$argumentValueItem <= 0)) {
                throw new InvalidRequestException(
                    new FeedbackItemResolution(
                        GraphQLExtendedSpecErrorFeedbackItemProvider::class,
                        GraphQLExtendedSpecErrorFeedbackItemProvider::E3,
                        [
                            $argument->getName(),
                            $directive->getName(),
                            $argumentValueItem === null ? 'null' : $argumentValueItem,
                        ]
                    ),
                    $argument->getLocation()
                );
            }
            $nestedDirectivePos = $directivePos + (int)$argumentValueItem;
            if ($nestedDirectivePos >= $directiveCount) {
                throw new InvalidRequestException(
                    new FeedbackItemResolution(
                        GraphQLExtendedSpecErrorFeedbackItemProvider::class,
                        GraphQLExtendedSpecErrorFeedbackItemProvider::E4,
                        [
                            $argumentValueItem,
                            $directive->getName(),
                            $argument->getName(),
                        ]
                    ),
                    $argument->getLocation()
                );
            }
        }

        return $argumentValue;
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

    protected function createVariableReference(
        string $name,
        ?Variable $variable,
        Location $location,
    ): VariableReference {
        if (
            !$this->parsingDirectiveArgumentList
            && $this->getQueryAugmenterService()->isObjectResolvedFieldValueReference($name, $variable)
        ) {
            /**
             * Make sure the field appears _before_ the reference,
             * to avoid circular references.
             */
            $fieldNameOrAlias = $this->getQueryAugmenterService()->extractObjectResolvedFieldName($name);
            $field = $this->findFieldWithNameWithinCurrentSiblingFields($fieldNameOrAlias);
            if ($field !== null) {
                return $this->createObjectResolvedFieldValueReference($name, $field, $location);
            }
        }

        if ($this->isDynamicVariableReference($name, $variable)) {
            return $this->createDynamicVariableReference($name, $location);
        }

        return parent::createVariableReference(
            $name,
            $variable,
            $location,
        );
    }

    protected function isDynamicVariableReference(
        string $name,
        ?Variable $variable,
    ): bool {
        /** @var ModuleConfiguration */
        $moduleConfiguration = App::getModule(Module::class)->getConfiguration();
        if (!$moduleConfiguration->enableDynamicVariables()) {
            return false;
        }

        return $variable === null
            && \str_starts_with(
                $name,
                QuerySyntax::DYNAMIC_VARIABLE_NAME_PREFIX
            );
    }

    protected function findFieldWithNameWithinCurrentSiblingFields(string $referencedFieldNameOrAlias): ?FieldInterface
    {
        if ($this->parsedFieldBlockStack === []) {
            return null;
        }

        $currentlyParsedBlockFields = $this->parsedFieldBlockStack[0];
        foreach ($currentlyParsedBlockFields as $field) {
            if (
                ($field->getAlias() !== null && $field->getAlias() === $referencedFieldNameOrAlias)
                || ($field->getAlias() === null && $field->getName() === $referencedFieldNameOrAlias)
            ) {
                return $field;
            }
        }
        return null;
    }

    protected function createDynamicVariableReference(
        string $name,
        Location $location,
    ): VariableReference {
        return new DynamicVariableReference($name, $location);
    }

    /**
     * If a Dynamic Variable Reference has the same name as a
     * field resolved in the same query block, then replace it
     * with the corresponding Object Resolved Field Value Reference
     * to that field.
     *
     * Only accept fields that have appeared before, as to avoid
     * circular recursions:
     *
     * ```
     * {
     *   first: echo(value: $second)
     *   second: echo(value: $first)
     * }
     * ```
     *
     * This strategy also avoid a field referencing itself:
     *
     * ```
     * {
     *   field: echo(value: $field)
     * }
     * ```
     */
    protected function createObjectResolvedFieldValueReference(
        string $name,
        FieldInterface $field,
        Location $location,
    ): ObjectResolvedFieldValueReference {
        return new ObjectResolvedFieldValueReference(
            $name,
            $field,
            $location,
        );
    }

    public function createDocument(
        /** @var OperationInterface[] */
        array $operations,
        /** @var Fragment[] */
        array $fragments,
    ) {
        $document = new Document(
            $operations,
            $fragments,
        );

        /** @var ModuleConfiguration */
        $moduleConfiguration = App::getModule(Module::class)->getConfiguration();
        if ($moduleConfiguration->enableMultiFieldDirectives()) {
            $this->spreadMultiFieldDirectives($document);
        }

        return $document;
    }

    /**
     * Iterate the elements in the Document AST, and whenever a Directive
     * is to be applied to multiple fields, add it under the corresponding Fields
     */
    protected function spreadMultiFieldDirectives(
        Document $document,
    ): void {
        foreach ($document->getOperations() as $operation) {
            $this->spreadMultiFieldDirectivesInFieldsOrInlineFragments(
                $operation->getFieldsOrFragmentBonds(),
                $document->getFragments(),
            );
        }
        foreach ($document->getFragments() as $fragment) {
            $this->spreadMultiFieldDirectivesInFieldsOrInlineFragments(
                $fragment->getFieldsOrFragmentBonds(),
                $document->getFragments(),
            );
        }
    }

    /**
     * @param array<FieldInterface|FragmentBondInterface> $fieldsOrFragmentBonds
     * @param Fragment[] $fragments
     */
    protected function spreadMultiFieldDirectivesInFieldsOrInlineFragments(
        array $fieldsOrFragmentBonds,
        array $fragments,
    ): void {
        $fieldsOrFragmentBondsCount = count($fieldsOrFragmentBonds);
        for ($i = 0; $i < $fieldsOrFragmentBondsCount; $i++) {
            $fieldOrFragmentBond = $fieldsOrFragmentBonds[$i];
            if ($fieldOrFragmentBond instanceof FragmentReference) {
                continue;
            }
            if ($fieldOrFragmentBond instanceof InlineFragment) {
                /** @var InlineFragment */
                $inlineFragment = $fieldOrFragmentBond;
                $this->spreadMultiFieldDirectivesInFieldsOrInlineFragments(
                    $inlineFragment->getFieldsOrFragmentBonds(),
                    $fragments,
                );
                continue;
            }
            /** @var FieldInterface */
            $field = $fieldOrFragmentBond;
            foreach ($field->getDirectives() as $directive) {
                $this->maybeSpreadDirectiveToFields(
                    $directive,
                    $i,
                    $fieldsOrFragmentBonds,
                );
                continue;
            }
            if ($field instanceof RelationalField) {
                /** @var RelationalField */
                $relationalField = $field;
                $this->spreadMultiFieldDirectivesInFieldsOrInlineFragments(
                    $relationalField->getFieldsOrFragmentBonds(),
                    $fragments,
                );
            }
        }
    }

    /**
     * @param array<FieldInterface|FragmentBondInterface> $fieldsOrFragmentBonds
     * @throws InvalidDynamicContextException When accessing non-declared Dynamic Variables
     */
    protected function maybeSpreadDirectiveToFields(
        Directive $directive,
        int $originFieldPosition,
        array $fieldsOrFragmentBonds,
    ): void {
        // Check if it is a MultiField Directive
        $argument = $this->getAffectAdditionalFieldsUnderPosArgument($directive);
        if ($argument === null) {
            return;
        }

        if (empty($argument->getValue())) {
            return;
        }

        $this->spreadDirectiveToFields(
            $directive,
            $argument,
            $originFieldPosition,
            $fieldsOrFragmentBonds,
        );
    }

    protected function getAffectAdditionalFieldsUnderPosArgument(
        Directive $directive,
    ): ?Argument {
        $directiveResolver = $this->getDirectiveResolver($directive->getName());
        if ($directiveResolver === null) {
            return null;
        }
        $affectAdditionalFieldsUnderPosArgName = $directiveResolver->getAffectAdditionalFieldsUnderPosArgumentName();
        if ($affectAdditionalFieldsUnderPosArgName === null) {
            // Disabled for the directive
            return null;
        }
        foreach ($directive->getArguments() as $argument) {
            if ($argument->getName() !== $affectAdditionalFieldsUnderPosArgName) {
                continue;
            }
            return $argument;
        }
        return null;
    }

    protected function getDirectiveResolver(string $directiveName): ?DirectiveResolverInterface
    {
        return $this->getDirectiveRegistry()->getDirectiveResolver($directiveName);
    }

    /**
     * Append the directive to the fields on the defined
     * relative positions to its left.
     *
     * @param array<FieldInterface|FragmentBondInterface> $fieldsOrFragmentBonds
     * @throws InvalidDynamicContextException When accessing non-declared Dynamic Variables
     */
    protected function spreadDirectiveToFields(
        Directive $directive,
        Argument $argument,
        int $originFieldPosition,
        array $fieldsOrFragmentBonds,
    ): void {
        /**
         * List of integers, as relative positions to the affected fields
         * (to the left of the directive)
         */
        $affectedFieldPositions = $argument->getValue();
        if (!is_array($affectedFieldPositions)) {
            $affectedFieldPositions = [$affectedFieldPositions];
        }
        foreach ($affectedFieldPositions as $affectedFieldPosition) {
            if (!is_int($affectedFieldPosition) || ((int)$affectedFieldPosition <= 0)) {
                throw new InvalidRequestException(
                    new FeedbackItemResolution(
                        GraphQLExtendedSpecErrorFeedbackItemProvider::class,
                        GraphQLExtendedSpecErrorFeedbackItemProvider::E3,
                        [
                            $argument->getName(),
                            $directive->getName(),
                            $affectedFieldPosition === null ? 'null' : $affectedFieldPosition,
                        ]
                    ),
                    $argument->getLocation()
                );
            }

            $fieldPosition = $originFieldPosition - $affectedFieldPosition;
            if ($fieldPosition < 0) {
                throw new InvalidRequestException(
                    new FeedbackItemResolution(
                        GraphQLExtendedSpecErrorFeedbackItemProvider::class,
                        GraphQLExtendedSpecErrorFeedbackItemProvider::E5,
                        [
                            $affectedFieldPosition,
                            $directive->getName(),
                            $argument->getName(),
                        ]
                    ),
                    $argument->getLocation()
                );
            }

            /**
             * Get the element at that position, and validate
             * it is indeed a Field (eg: not a FragmentReference)
             */
            $field = $fieldsOrFragmentBonds[$fieldPosition];
            if (!($field instanceof FieldInterface)) {
                throw new InvalidRequestException(
                    new FeedbackItemResolution(
                        GraphQLExtendedSpecErrorFeedbackItemProvider::class,
                        GraphQLExtendedSpecErrorFeedbackItemProvider::E6,
                        [
                            $affectedFieldPosition,
                            $directive->getName(),
                            $argument->getName(),
                        ]
                    ),
                    $argument->getLocation()
                );
            }
            /** @var FieldInterface $field */

            /**
             * Everything is valid, append the Directive to the field
             */
            $field->addDirective($directive);
        }
    }
    
    abstract protected function isDynamicVariableDefinerDirective(string $directiveName): bool;
}

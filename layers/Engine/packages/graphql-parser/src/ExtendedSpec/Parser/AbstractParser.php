<?php

declare(strict_types=1);

namespace PoP\GraphQLParser\ExtendedSpec\Parser;

use PoP\ComponentModel\DirectiveResolvers\DirectiveResolverInterface;
use PoP\ComponentModel\Registries\DirectiveRegistryInterface;
use PoP\GraphQLParser\Exception\Parser\InvalidDynamicContextException;
use PoP\GraphQLParser\Exception\Parser\InvalidRequestException;
use PoP\GraphQLParser\ExtendedSpec\Parser\Ast\ArgumentValue\DynamicVariableReference;
use PoP\GraphQLParser\ExtendedSpec\Parser\Ast\ArgumentValue\ResolvedFieldVariableReference;
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
use PoP\GraphQLParser\Spec\Parser\Ast\RelationalField;
use PoP\GraphQLParser\Spec\Parser\Ast\Variable;
use PoP\GraphQLParser\Spec\Parser\Location;
use PoP\GraphQLParser\Spec\Parser\Parser as UpstreamParser;
use PoP\Root\App;
use PoP\Root\Exception\ShouldNotHappenException;
use PoP\Root\Feedback\FeedbackItemResolution;

abstract class AbstractParser extends UpstreamParser implements ParserInterface
{
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
        if ($this->getQueryAugmenterService()->isDynamicVariableReference($name, $variable)) {
            return new DynamicVariableReference($name, $location);
        }

        return parent::createVariableReference(
            $name,
            $variable,
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
        if ($moduleConfiguration->enableResolvedFieldVariableReferences()) {
            $this->replaceResolvedFieldVariableReferences($document);
        }

        if ($moduleConfiguration->enableMultiFieldDirectives()) {
            $this->spreadMultiFieldDirectives($document);
        }

        return $document;
    }

    /**
     * Iterate the elements in the Document AST, and replace the
     * "Dynamic Variables References" with "Resolved Field Variable References"
     */
    protected function replaceResolvedFieldVariableReferences(
        Document $document,
    ): void {
        foreach ($document->getOperations() as $operation) {
            $this->replaceResolvedFieldVariableReferencesInFieldsOrInlineFragments(
                $operation->getFieldsOrFragmentBonds(),
                $document->getFragments(),
            );
        }
        foreach ($document->getFragments() as $fragment) {
            $this->replaceResolvedFieldVariableReferencesInFieldsOrInlineFragments(
                $fragment->getFieldsOrFragmentBonds(),
                $document->getFragments(),
            );
        }
    }

    /**
     * @param FieldInterface[]|FragmentBondInterface[] $fieldsOrFragmentBonds
     * @param Fragment[] $fragments
     */
    protected function replaceResolvedFieldVariableReferencesInFieldsOrInlineFragments(
        array $fieldsOrFragmentBonds,
        array $fragments,
    ): void {
        foreach ($fieldsOrFragmentBonds as $fieldOrFragmentBond) {
            if ($fieldOrFragmentBond instanceof FragmentReference) {
                continue;
            }
            if ($fieldOrFragmentBond instanceof InlineFragment) {
                /** @var InlineFragment */
                $inlineFragment = $fieldOrFragmentBond;
                $this->replaceResolvedFieldVariableReferencesInFieldsOrInlineFragments(
                    $inlineFragment->getFieldsOrFragmentBonds(),
                    $fragments,
                );
                continue;
            }
            /** @var FieldInterface */
            $field = $fieldOrFragmentBond;
            $this->replaceResolvedFieldVariableReferencesInArguments(
                $field,
                $field->getArguments(),
                $fieldsOrFragmentBonds,
                $fragments,
            );
            $this->replaceResolvedFieldVariableReferencesInDirectives(
                $field,
                $field->getDirectives(),
                $fieldsOrFragmentBonds,
                $fragments,
            );
            if ($field instanceof RelationalField) {
                /** @var RelationalField */
                $relationalField = $field;
                $this->replaceResolvedFieldVariableReferencesInFieldsOrInlineFragments(
                    $relationalField->getFieldsOrFragmentBonds(),
                    $fragments,
                );
            }
        }
    }

    /**
     * @param Directive[] $directives
     * @param FieldInterface[]|FragmentBondInterface[] $fieldsOrFragmentBonds
     * @param Fragment[] $fragments
     */
    protected function replaceResolvedFieldVariableReferencesInDirectives(
        FieldInterface $field,
        array $directives,
        array $fieldsOrFragmentBonds,
        array $fragments,
    ): void {
        foreach ($directives as $directive) {
            $this->replaceResolvedFieldVariableReferencesInArguments(
                $field,
                $directive->getArguments(),
                $fieldsOrFragmentBonds,
                $fragments,
            );
            if (!($directive instanceof MetaDirective)) {
                continue;
            }
            /** @var MetaDirective */
            $metaDirective = $directive;
            $this->replaceResolvedFieldVariableReferencesInDirectives(
                $field,
                $metaDirective->getNestedDirectives(),
                $fieldsOrFragmentBonds,
                $fragments,
            );
        }
    }

    /**
     * @param Argument[] $arguments
     * @param FieldInterface[]|FragmentBondInterface[] $fieldsOrFragmentBonds
     * @param Fragment[] $fragments
     */
    protected function replaceResolvedFieldVariableReferencesInArguments(
        FieldInterface $field,
        array $arguments,
        array $fieldsOrFragmentBonds,
        array $fragments,
    ): void {
        foreach ($arguments as $argument) {
            $this->replaceDynamicVariableReferenceWithResolvedFieldVariableReference(
                $field,
                $argument,
                $fieldsOrFragmentBonds,
                $fragments,
            );
        }
    }

    /**
     * If a Dynamic Variable Reference has the same name as a
     * field resolved in the same query block, then replace it
     * with the corresponding Resolved Field Variable Reference
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
     *
     * @param FieldInterface[]|FragmentBondInterface[] $fieldsOrFragmentBonds
     * @param Fragment[] $fragments
     */
    protected function replaceDynamicVariableReferenceWithResolvedFieldVariableReference(
        FieldInterface $field,
        Argument $argument,
        array $fieldsOrFragmentBonds,
        array $fragments,
    ): void {
        if (!($argument->getValueAST() instanceof DynamicVariableReference)) {
            return;
        }
        /** @var DynamicVariableReference */
        $dynamicVariableReference = $argument->getValueAST();

        /**
         * Make sure the field appears _before_ the reference,
         * to avoid circular references.
         */
        $previousFieldsOrFragmentBonds = $this->getPreviousFieldsOrFragmentBonds(
            $field,
            $fieldsOrFragmentBonds,
            $fragments
        );

        // Check if there is a field with the variable name
        $referencedFieldNameOrAlias = $this->getQueryAugmenterService()->extractDynamicVariableName($dynamicVariableReference->getName());
        $referencedField = $this->findFieldInQueryBlock(
            $referencedFieldNameOrAlias,
            $previousFieldsOrFragmentBonds,
            $fragments,
        );
        if ($referencedField === null) {
            return;
        }

        // Replace the "Dynamic Variables Reference" with "Resolved Field Variable Reference"
        $resolvedFieldVariableReference = new ResolvedFieldVariableReference(
            $dynamicVariableReference->getName(),
            $referencedField,
            $dynamicVariableReference->getLocation()
        );
        $argument->setValueAST($resolvedFieldVariableReference);
    }

    /**
     * Calculate the list of fields and fragment bonds that
     * appear _before_ the provided field
     *
     * @param FieldInterface[]|FragmentBondInterface[] $fieldsOrFragmentBonds
     * @param Fragment[] $fragments
     * @return FieldInterface[]|FragmentBondInterface[]
     */
    protected function getPreviousFieldsOrFragmentBonds(
        FieldInterface $field,
        array $fieldsOrFragmentBonds,
        array $fragments,
    ): array {
        $previousFieldsOrFragmentBonds = [];
        foreach ($fieldsOrFragmentBonds as $fieldOrFragmentBond) {
            // We found the Field, everything else is the "previous" ones
            if ($fieldOrFragmentBond === $field) {
                return $previousFieldsOrFragmentBonds;
            }
            $previousFieldsOrFragmentBonds[] = $fieldOrFragmentBond;
        } 
        throw new ShouldNotHappenException(
            sprintf(
                $this->__('Field \'%s\' is not contained within the `$fieldsOrFragmentBonds` array'),
                $field->asFieldOutputQueryString()
            )
        );
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
     * @param FieldInterface[]|FragmentBondInterface[] $fieldsOrFragmentBonds
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
     * @param FieldInterface[]|FragmentBondInterface[] $fieldsOrFragmentBonds
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
     * @param FieldInterface[]|FragmentBondInterface[] $fieldsOrFragmentBonds
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

    /**
     * Find the field in the same query block,
     * or return `null` if there is none.
     *
     * The referenced field is the field alias, if it is defined,
     * or the field name otherwise.
     *
     * @param FieldInterface[]|FragmentBondInterface[] $fieldsOrFragmentBonds
     * @param Fragment[] $fragments
     */
    protected function findFieldInQueryBlock(
        string $referencedFieldNameOrAlias,
        array $fieldsOrFragmentBonds,
        array $fragments,
    ): ?FieldInterface {
        foreach ($fieldsOrFragmentBonds as $fieldOrFragmentBond) {
            if ($fieldOrFragmentBond instanceof FragmentReference) {
                /** @var FragmentReference */
                $fragmentReference = $fieldOrFragmentBond;
                $fragment = $this->getFragment($fragmentReference->getName(), $fragments);
                if ($fragment === null) {
                    continue;
                }
                $referencedField = $this->findFieldInQueryBlock(
                    $referencedFieldNameOrAlias,
                    $fragment->getFieldsOrFragmentBonds(),
                    $fragments,
                );
                if ($referencedField !== null) {
                    return $referencedField;
                }
                continue;
            }
            if ($fieldOrFragmentBond instanceof InlineFragment) {
                /** @var InlineFragment */
                $inlineFragment = $fieldOrFragmentBond;
                $referencedField = $this->findFieldInQueryBlock(
                    $referencedFieldNameOrAlias,
                    $inlineFragment->getFieldsOrFragmentBonds(),
                    $fragments,
                );
                if ($referencedField !== null) {
                    return $referencedField;
                }
                continue;
            }
            /** @var FieldInterface */
            $field = $fieldOrFragmentBond;
            if (
                ($field->getAlias() !== null && $field->getAlias() === $referencedFieldNameOrAlias)
                || ($field->getAlias() === null && $field->getName() === $referencedFieldNameOrAlias)
            ) {
                return $field;
            }
        }
        return null;
    }

    /**
     * @param Fragment[] $fragments
     */
    protected function getFragment(
        string $fragmentName,
        array $fragments,
    ): ?Fragment {
        foreach ($fragments as $fragment) {
            if ($fragment->getName() === $fragmentName) {
                return $fragment;
            }
        }
        return null;
    }
}

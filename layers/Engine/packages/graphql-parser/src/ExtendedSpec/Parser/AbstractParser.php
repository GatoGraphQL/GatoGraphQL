<?php

declare(strict_types=1);

namespace PoP\GraphQLParser\ExtendedSpec\Parser;

use PoP\GraphQLParser\Exception\Parser\InvalidRequestException;
use PoP\GraphQLParser\Exception\Parser\SyntaxErrorException;
use PoP\GraphQLParser\ExtendedSpec\Constants\QuerySyntax;
use PoP\GraphQLParser\ExtendedSpec\Parser\Ast\AbstractDocument;
use PoP\GraphQLParser\ExtendedSpec\Parser\Ast\ArgumentValue\DynamicVariableReference;
use PoP\GraphQLParser\ExtendedSpec\Parser\Ast\ArgumentValue\ObjectResolvedFieldValueReference;
use PoP\GraphQLParser\ExtendedSpec\Parser\Ast\MetaDirective;
use PoP\GraphQLParser\FeedbackItemProviders\GraphQLExtendedSpecErrorFeedbackItemProvider;
use PoP\GraphQLParser\Module;
use PoP\GraphQLParser\ModuleConfiguration;
use PoP\GraphQLParser\Spec\Parser\Ast\Argument;
use PoP\GraphQLParser\Spec\Parser\Ast\ArgumentValue\VariableReference;
use PoP\GraphQLParser\Spec\Parser\Ast\Directive;
use PoP\GraphQLParser\Spec\Parser\Ast\Document;
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
    protected array $parsedFieldBlockStack;

    /**
     * ObjectResolvedFieldValueReferences are not supported
     * within Directive Arguments.
     */
    protected bool $parsingDirectiveArgumentList;

    /**
     * Use this variable to keep track of which
     * DynamicVariableDefinerDirectives (such as `@export`)
     * have been already parsed in the query.
     *
     * @var string[]
     */
    protected array $parsedDefinedDynamicVariableNames;

    /**
     * List of all the Fields in the query which are
     * referenced via an ObjectResolvedFieldValueReference.
     *
     * @var FieldInterface[]
     */
    protected array $objectResolvedFieldValueReferencedFields;

    protected function resetState(): void
    {
        parent::resetState();

        $this->parsedFieldBlockStack = [];
        $this->parsingDirectiveArgumentList = false;
        $this->parsedDefinedDynamicVariableNames = [];
        $this->objectResolvedFieldValueReferencedFields = [];
    }

    /**
     * Override to express the additional type of Exception
     * that can be thrown.
     *
     * @throws InvalidRequestException
     * @throws SyntaxErrorException
     */
    public function parse(string $source): Document
    {
        return parent::parse($source);
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
     * Store the "DynamicVariableDefiner" Directives
     *
     * @param Argument[] $arguments
     */
    protected function createDirective(
        string $name,
        array $arguments,
        Location $location,
    ): Directive {
        $directive = parent::createDirective(
            $name,
            $arguments,
            $location,
        );

        $this->maybeStoreParsedDefinedDynamicVariableName($directive);

        return $directive;
    }

    /**
     * Store the "DynamicVariableDefiner" Directives
     */
    protected function maybeStoreParsedDefinedDynamicVariableName(
        Directive $directive
    ): void {
        /** @var ModuleConfiguration */
        $moduleConfiguration = App::getModule(Module::class)->getConfiguration();
        if (!$moduleConfiguration->enableDynamicVariables()) {
            return;
        }

        /**
         * Check if this Directive is a "DynamicVariableDefiner"
         */
        if (!$this->isDynamicVariableDefinerDirective($directive)) {
            return;
        }

        /**
         * Obtain the name under which to export the value,
         * and stored in the the "parsed" list.
         *
         * There is no need to check if there's a (static) Variable with
         * the same name, as that validation will happen in the Document.
         *
         * @see layers/Engine/packages/graphql-parser/src/ExtendedSpec/Parser/Ast/Document.php
         */
        $exportUnderVariableNameArgument = $this->getExportUnderVariableNameArgument($directive);
        if ($exportUnderVariableNameArgument === null) {
            return;
        }
        $exportUnderVariableName = (string)$exportUnderVariableNameArgument->getValue();
        $this->parsedDefinedDynamicVariableNames[] = $exportUnderVariableName;
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
                        $directive
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
                $argument
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
                $argument
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
                    $argument
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
                    $argument
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
        $resolvedFieldValueReferenceField = $this->findObjectResolvedFieldValueReferenceField($name);
        if ($resolvedFieldValueReferenceField !== null) {
            $this->objectResolvedFieldValueReferencedFields[] = $resolvedFieldValueReferenceField;
            return $this->createObjectResolvedFieldValueReference($name, $resolvedFieldValueReferenceField, $location);
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

    /**
     * If referencing a variable that starts with "__", the variable
     * has not been defined in the operation, and there's a field
     * in the same query block, then it's a reference to the value
     * of the resolved field on the same object
     */
    protected function findObjectResolvedFieldValueReferenceField(
        string $name,
    ): ?FieldInterface {
        /** @var ModuleConfiguration */
        $moduleConfiguration = App::getModule(Module::class)->getConfiguration();
        if (!$moduleConfiguration->enableObjectResolvedFieldValueReferences()) {
            return null;
        }

        if ($this->parsingDirectiveArgumentList) {
            return null;
        }

        if (!$this->isObjectResolvedFieldValueReferenceName($name)) {
            return null;
        }

        /**
         * Make sure the field appears _before_ the reference,
         * to avoid circular references.
         */
        $fieldNameOrAlias = $this->extractObjectResolvedFieldName($name);
        return $this->findFieldWithNameWithinCurrentSiblingFields($fieldNameOrAlias);
    }

    /**
     * Actual name of the field (without the leading "__")
     */
    protected function isObjectResolvedFieldValueReferenceName(string $name): bool
    {
        return \str_starts_with(
            $name,
            QuerySyntax::OBJECT_RESOLVED_FIELD_VALUE_REFERENCE_PREFIX
        );
    }

    /**
     * Actual name of the field (without the leading "__")
     */
    protected function extractObjectResolvedFieldName(string $name): string
    {
        return substr(
            $name,
            strlen(QuerySyntax::OBJECT_RESOLVED_FIELD_VALUE_REFERENCE_PREFIX)
        );
    }

    protected function isDynamicVariableReference(
        string $variableName,
        ?Variable $variable,
    ): bool {
        /**
         * If there's a variable with that name, then it has priority
         */
        if ($variable !== null) {
            return false;
        }

        /**
         * Check that any previous "DynamicVariableDefiner" Directive
         * has defined the same dynamic variable name.
         * Eg: `@export(as: "someVariableName")`
         */
        return in_array($variableName, $this->parsedDefinedDynamicVariableNames);
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

    /**
     * This function must be invoked after running `->parse()`.
     *
     * It produces the list of all the Fields in the query
     * which are referenced via an ObjectResolvedFieldValueReference.
     *
     * Eg: field `id` in:
     *
     *   ```
     *   {
     *     id
     *     echo(value: $__id)
     *   }
     *   ```
     *
     * @return FieldInterface[]
     */
    public function getObjectResolvedFieldValueReferencedFields(): array
    {
        return array_values(array_unique($this->objectResolvedFieldValueReferencedFields));
    }

    public function createDocument(
        /** @var OperationInterface[] */
        array $operations,
        /** @var Fragment[] */
        array $fragments,
    ): Document {
        $document = $this->createDocumentInstance(
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
     * Set the instance with the implementation
     * from ComponentModel
     */
    abstract protected function createDocumentInstance(
        /** @var OperationInterface[] */
        array $operations,
        /** @var Fragment[] */
        array $fragments,
    ): AbstractDocument;

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
        $affectAdditionalFieldsUnderPosArgName = $this->getAffectAdditionalFieldsUnderPosArgumentName($directive);
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

    /**
     * Append the directive to the fields on the defined
     * relative positions to its left.
     *
     * @param array<FieldInterface|FragmentBondInterface> $fieldsOrFragmentBonds
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
                    $argument
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
                    $argument
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
                    $argument
                );
            }
            /** @var FieldInterface $field */

            /**
             * Everything is valid, append the Directive to the field
             */
            $field->addDirective($directive);
        }
    }

    abstract protected function isDynamicVariableDefinerDirective(Directive $directive): bool;
    abstract protected function getExportUnderVariableNameArgument(Directive $directive): ?Argument;
    abstract protected function getAffectAdditionalFieldsUnderPosArgumentName(Directive $directive): ?string;
}

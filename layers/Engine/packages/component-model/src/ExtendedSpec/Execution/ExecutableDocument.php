<?php

declare(strict_types=1);

namespace PoP\ComponentModel\ExtendedSpec\Execution;

use PoP\ComponentModel\DirectiveResolvers\OperationDependencyDefinerFieldDirectiveResolverInterface;
use PoP\ComponentModel\FeedbackItemProviders\InputValueCoercionGraphQLSpecErrorFeedbackItemProvider;
use PoP\ComponentModel\Module;
use PoP\ComponentModel\ModuleConfiguration;
use PoP\ComponentModel\Registries\FieldDirectiveResolverRegistryInterface;
use PoP\ComponentModel\Registries\OperationDependencyDefinerDirectiveRegistryInterface;
use PoP\ComponentModel\Registries\TypeRegistryInterface;
use PoP\ComponentModel\TypeResolvers\EnumType\EnumTypeResolverInterface;
use PoP\ComponentModel\TypeResolvers\InterfaceType\InterfaceTypeResolverInterface;
use PoP\ComponentModel\TypeResolvers\ObjectType\ObjectTypeResolverInterface;
use PoP\ComponentModel\TypeResolvers\ScalarType\ScalarTypeResolverInterface;
use PoP\ComponentModel\TypeResolvers\UnionType\UnionTypeResolverInterface;
use PoP\GraphQLParser\Enums\FragmentInclusionDirectiveName;
use PoP\GraphQLParser\Exception\FeatureNotSupportedException;
use PoP\GraphQLParser\Exception\InvalidRequestException;
use PoP\GraphQLParser\ExtendedSpec\Execution\AbstractExecutableDocument;
use PoP\GraphQLParser\ExtendedSpec\Parser\Ast\ArgumentValue\DynamicVariableReferenceInterface;
use PoP\GraphQLParser\FeedbackItemProviders\GraphQLSpecErrorFeedbackItemProvider;
use PoP\GraphQLParser\FeedbackItemProviders\GraphQLUnsupportedFeatureErrorFeedbackItemProvider;
use PoP\GraphQLParser\Spec\Execution\Context;
use PoP\GraphQLParser\Spec\Parser\Ast\Argument;
use PoP\GraphQLParser\Spec\Parser\Ast\ArgumentValue\Literal;
use PoP\GraphQLParser\Spec\Parser\Ast\ArgumentValue\VariableReference;
use PoP\GraphQLParser\Spec\Parser\Ast\Directive;
use PoP\GraphQLParser\Spec\Parser\Ast\Document;
use PoP\GraphQLParser\Spec\Parser\Ast\FieldInterface;
use PoP\GraphQLParser\Spec\Parser\Ast\Fragment;
use PoP\GraphQLParser\Spec\Parser\Ast\FragmentBondInterface;
use PoP\GraphQLParser\Spec\Parser\Ast\FragmentReference;
use PoP\GraphQLParser\Spec\Parser\Ast\InlineFragment;
use PoP\GraphQLParser\Spec\Parser\Ast\MutationOperation;
use PoP\GraphQLParser\Spec\Parser\Ast\RelationalField;
use PoP\GraphQLParser\Spec\Parser\Ast\Variable;
use PoP\Root\App;
use PoP\Root\Facades\Instances\InstanceManagerFacade;
use PoP\ComponentModel\Feedback\FeedbackItemResolution;

use function is_bool;
use function sprintf;

class ExecutableDocument extends AbstractExecutableDocument
{
    /**
     * @var array<ObjectTypeResolverInterface|UnionTypeResolverInterface|InterfaceTypeResolverInterface>
     */
    protected array $compositeUnionTypeResolvers;
    /**
     * @var array<EnumTypeResolverInterface|ScalarTypeResolverInterface>
     */
    protected array $nonCompositeUnionTypeResolvers;
    /**
     * Set of type names (both `getTypeName()` and `getNamespacedTypeName()`)
     * that map to a composite-union resolver. Used as a hash-lookup
     * replacement for the per-variable / per-fragment-spread linear scans
     * over `$compositeUnionTypeResolvers`. With ~525 type resolvers and
     * thousands of variable/fragment-spread checks per request, the
     * O(N×M) scan dominated `assertVariableIsInputType` and friends in
     * profiling — collapsing to O(N+M) one-time index plus O(1) lookups.
     *
     * @var array<string,bool>
     */
    private array $compositeUnionTypeResolverNameSet;
    /**
     * Same shape as `$compositeUnionTypeResolverNameSet`, for the
     * Enum/Scalar resolvers. Used by `assertFragmentSpreadTypeExistsInSchema`.
     *
     * @var array<string,bool>
     */
    private array $nonCompositeUnionTypeResolverNameSet;

    private ?TypeRegistryInterface $typeRegistry = null;
    private ?OperationDependencyDefinerDirectiveRegistryInterface $operationDependencyDefinerDirectiveRegistry = null;
    private ?FieldDirectiveResolverRegistryInterface $fieldDirectiveResolverRegistry = null;

    final protected function getTypeRegistry(): TypeRegistryInterface
    {
        if ($this->typeRegistry === null) {
            /** @var TypeRegistryInterface */
            $typeRegistry = InstanceManagerFacade::getInstance()->getInstance(TypeRegistryInterface::class);
            $this->typeRegistry = $typeRegistry;
        }
        return $this->typeRegistry;
    }
    final protected function getOperationDependencyDefinerDirectiveRegistry(): OperationDependencyDefinerDirectiveRegistryInterface
    {
        if ($this->operationDependencyDefinerDirectiveRegistry === null) {
            /** @var OperationDependencyDefinerDirectiveRegistryInterface */
            $operationDependencyDefinerDirectiveRegistry = InstanceManagerFacade::getInstance()->getInstance(OperationDependencyDefinerDirectiveRegistryInterface::class);
            $this->operationDependencyDefinerDirectiveRegistry = $operationDependencyDefinerDirectiveRegistry;
        }
        return $this->operationDependencyDefinerDirectiveRegistry;
    }

    final protected function getFieldDirectiveResolverRegistry(): FieldDirectiveResolverRegistryInterface
    {
        if ($this->fieldDirectiveResolverRegistry === null) {
            /** @var FieldDirectiveResolverRegistryInterface */
            $fieldDirectiveResolverRegistry = InstanceManagerFacade::getInstance()->getInstance(FieldDirectiveResolverRegistryInterface::class);
            $this->fieldDirectiveResolverRegistry = $fieldDirectiveResolverRegistry;
        }
        return $this->fieldDirectiveResolverRegistry;
    }

    public function __construct(
        Document $document,
        Context $context,
    ) {
        parent::__construct(
            $document,
            $context
        );
        $this->compositeUnionTypeResolvers = [
            ...$this->getTypeRegistry()->getObjectTypeResolvers(),
            ...$this->getTypeRegistry()->getUnionTypeResolvers(),
            ...$this->getTypeRegistry()->getInterfaceTypeResolvers(),
        ];
        $this->nonCompositeUnionTypeResolvers = [
            ...$this->getTypeRegistry()->getEnumTypeResolvers(),
            ...$this->getTypeRegistry()->getScalarTypeResolvers(),
        ];
        // Pre-index by type name so per-variable / per-fragment-spread
        // type-resolution becomes a single hash lookup instead of a
        // linear scan over hundreds of resolvers.
        $this->compositeUnionTypeResolverNameSet = [];
        foreach ($this->compositeUnionTypeResolvers as $typeResolver) {
            $this->compositeUnionTypeResolverNameSet[$typeResolver->getTypeName()] = true;
            $this->compositeUnionTypeResolverNameSet[$typeResolver->getNamespacedTypeName()] = true;
        }
        $this->nonCompositeUnionTypeResolverNameSet = [];
        foreach ($this->nonCompositeUnionTypeResolvers as $typeResolver) {
            $this->nonCompositeUnionTypeResolverNameSet[$typeResolver->getTypeName()] = true;
            $this->nonCompositeUnionTypeResolverNameSet[$typeResolver->getNamespacedTypeName()] = true;
        }
    }

    /**
     * @throws InvalidRequestException
     */
    protected function validate(): void
    {
        parent::validate();
        $this->assertOperationTypesExistInSchema();
        $this->assertFragmentSpreadTypesExistInSchema();
        $this->assertFragmentBondDirectivesAreValid();
        $this->assertVariablesAreInputTypes();
    }

    /**
     * The schema has no mutation type when mutations are disabled.
     * (Subscriptions are rejected already when parsing the query.)
     *
     * @throws InvalidRequestException
     * @see https://spec.graphql.org/draft/#sec-Operation-Type-Existence
     */
    protected function assertOperationTypesExistInSchema(): void
    {
        /** @var ModuleConfiguration */
        $moduleConfiguration = App::getModule(Module::class)->getConfiguration();
        if ($moduleConfiguration->enableMutations()) {
            return;
        }
        foreach ($this->document->getOperations() as $operation) {
            if (!($operation instanceof MutationOperation)) {
                continue;
            }
            throw new InvalidRequestException(
                new FeedbackItemResolution(
                    GraphQLSpecErrorFeedbackItemProvider::class,
                    GraphQLSpecErrorFeedbackItemProvider::E_5_2_A,
                    [
                        $operation->getOperationType(),
                    ]
                ),
                $operation
            );
        }
    }

    /**
     * @throws InvalidRequestException
     * @see https://spec.graphql.org/draft/#sec-Fragment-Spread-Type-Existence
     */
    protected function assertFragmentSpreadTypesExistInSchema(): void
    {
        foreach ($this->document->getFragments() as $fragment) {
            $this->assertFragmentSpreadTypeExistsInSchema($fragment->getModel(), $fragment);
            $this->assertInlineFragmentSpreadTypesInFieldsOrFragmentsExistInSchema($fragment->getFieldsOrFragmentBonds());
        }
        foreach ($this->document->getOperations() as $operation) {
            $this->assertInlineFragmentSpreadTypesInFieldsOrFragmentsExistInSchema($operation->getFieldsOrFragmentBonds());
        }
    }

    /**
     * @throws InvalidRequestException
     */
    protected function assertFragmentSpreadTypeExistsInSchema(
        string $fragmentSpreadType,
        Fragment|InlineFragment $astNode,
    ): void {
        if (isset($this->compositeUnionTypeResolverNameSet[$fragmentSpreadType])) {
            return;
        }

        /**
         * The type is neither Union, Object or Interface.
         * Check if it is Enum/Scalar as to determine which validation
         * from the GraphQL spec it belongs to.
         */
        if (isset($this->nonCompositeUnionTypeResolverNameSet[$fragmentSpreadType])) {
            throw new InvalidRequestException(
                new FeedbackItemResolution(
                    GraphQLSpecErrorFeedbackItemProvider::class,
                    GraphQLSpecErrorFeedbackItemProvider::E_5_5_1_3,
                    [
                        $fragmentSpreadType,
                    ]
                ),
                $astNode
            );
        }
        throw new InvalidRequestException(
            new FeedbackItemResolution(
                GraphQLSpecErrorFeedbackItemProvider::class,
                GraphQLSpecErrorFeedbackItemProvider::E_5_5_1_2,
                [
                    $fragmentSpreadType,
                ]
            ),
            $astNode
        );
    }

    /**
     * @param array<FieldInterface|FragmentBondInterface> $fieldsOrFragmentBonds
     * @throws InvalidRequestException
     */
    protected function assertInlineFragmentSpreadTypesInFieldsOrFragmentsExistInSchema(array $fieldsOrFragmentBonds): void
    {
        foreach ($fieldsOrFragmentBonds as $fieldOrFragmentBond) {
            if ($fieldOrFragmentBond instanceof FragmentReference) {
                /** @var FragmentReference */
                $fragmentReference = $fieldOrFragmentBond;
                $fragment = $this->document->getFragment($fragmentReference->getName());
                if ($fragment === null) {
                    continue;
                }
                $this->assertInlineFragmentSpreadTypesInFieldsOrFragmentsExistInSchema($fragment->getFieldsOrFragmentBonds());
                continue;
            }
            if ($fieldOrFragmentBond instanceof RelationalField) {
                /** @var RelationalField */
                $relationalField = $fieldOrFragmentBond;
                $this->assertInlineFragmentSpreadTypesInFieldsOrFragmentsExistInSchema($relationalField->getFieldsOrFragmentBonds());
                continue;
            }
            if ($fieldOrFragmentBond instanceof InlineFragment) {
                /** @var InlineFragment */
                $inlineFragment = $fieldOrFragmentBond;
                $inlineFragmentTypeName = $inlineFragment->getTypeName();
                if ($inlineFragmentTypeName !== null) {
                    $this->assertFragmentSpreadTypeExistsInSchema($inlineFragmentTypeName, $inlineFragment);
                }
                $this->assertInlineFragmentSpreadTypesInFieldsOrFragmentsExistInSchema($inlineFragment->getFieldsOrFragmentBonds());
                continue;
            }
        }
    }

    /**
     * Fragment spreads and inline fragments only support directives
     * `@include` and `@skip`, which are evaluated when collecting the
     * fields, before resolving the query. Then, argument `if` must be
     * a Boolean, or an operation variable of type Boolean.
     *
     * @throws InvalidRequestException
     * @throws FeatureNotSupportedException
     * @see https://spec.graphql.org/September2025/#sec-Field-Collection
     */
    protected function assertFragmentBondDirectivesAreValid(): void
    {
        foreach ($this->document->getFragments() as $fragment) {
            $this->assertFragmentBondDirectivesInFieldsOrFragmentBondsAreValid($fragment->getFieldsOrFragmentBonds());
        }
        foreach ($this->document->getOperations() as $operation) {
            $this->assertFragmentBondDirectivesInFieldsOrFragmentBondsAreValid($operation->getFieldsOrFragmentBonds());
        }
    }

    /**
     * @param array<FieldInterface|FragmentBondInterface> $fieldsOrFragmentBonds
     * @throws InvalidRequestException
     * @throws FeatureNotSupportedException
     */
    protected function assertFragmentBondDirectivesInFieldsOrFragmentBondsAreValid(array $fieldsOrFragmentBonds): void
    {
        foreach ($fieldsOrFragmentBonds as $fieldOrFragmentBond) {
            if ($fieldOrFragmentBond instanceof RelationalField) {
                $this->assertFragmentBondDirectivesInFieldsOrFragmentBondsAreValid($fieldOrFragmentBond->getFieldsOrFragmentBonds());
                continue;
            }
            if ($fieldOrFragmentBond instanceof InlineFragment) {
                foreach ($fieldOrFragmentBond->getDirectives() as $directive) {
                    $this->assertFragmentBondDirectiveIsValid($directive);
                }
                $this->assertFragmentBondDirectivesInFieldsOrFragmentBondsAreValid($fieldOrFragmentBond->getFieldsOrFragmentBonds());
                continue;
            }
            if ($fieldOrFragmentBond instanceof FragmentReference) {
                foreach ($fieldOrFragmentBond->getDirectives() as $directive) {
                    $this->assertFragmentBondDirectiveIsValid($directive);
                }
            }
        }
    }

    /**
     * @throws InvalidRequestException
     * @throws FeatureNotSupportedException
     */
    protected function assertFragmentBondDirectiveIsValid(Directive $directive): void
    {
        $directiveName = $directive->getName();
        if (FragmentInclusionDirectiveName::tryFrom($directiveName) === null) {
            throw new InvalidRequestException(
                new FeedbackItemResolution(
                    GraphQLSpecErrorFeedbackItemProvider::class,
                    $this->getFieldDirectiveResolverRegistry()->getFieldDirectiveResolver($directiveName) !== null
                        ? GraphQLSpecErrorFeedbackItemProvider::E_5_7_2
                        : GraphQLSpecErrorFeedbackItemProvider::E_5_7_1,
                    [
                        $directiveName,
                    ]
                ),
                $directive
            );
        }
        foreach ($directive->getArguments() as $argument) {
            if ($argument->getName() === 'if') {
                continue;
            }
            throw new InvalidRequestException(
                new FeedbackItemResolution(
                    GraphQLSpecErrorFeedbackItemProvider::class,
                    GraphQLSpecErrorFeedbackItemProvider::E_5_4_1_B,
                    [
                        $directiveName,
                        $argument->getName(),
                    ]
                ),
                $argument
            );
        }
        $ifArgument = $directive->getArgument('if');
        if ($ifArgument === null) {
            throw new InvalidRequestException(
                new FeedbackItemResolution(
                    GraphQLSpecErrorFeedbackItemProvider::class,
                    GraphQLSpecErrorFeedbackItemProvider::E_5_4_2_1_C,
                    [
                        'if',
                        $directiveName,
                    ]
                ),
                $directive
            );
        }
        $ifValueAST = $ifArgument->getValueAST();
        if ($ifValueAST instanceof DynamicVariableReferenceInterface) {
            throw new FeatureNotSupportedException(
                new FeedbackItemResolution(
                    GraphQLUnsupportedFeatureErrorFeedbackItemProvider::class,
                    GraphQLUnsupportedFeatureErrorFeedbackItemProvider::E_5
                ),
                $ifValueAST
            );
        }
        if ($ifValueAST instanceof VariableReference) {
            $variable = $ifValueAST->getVariable();
            if ($variable === null || ($variable->getTypeName() === 'Boolean' && !$variable->isArray())) {
                return;
            }
            throw new InvalidRequestException(
                new FeedbackItemResolution(
                    GraphQLSpecErrorFeedbackItemProvider::class,
                    GraphQLSpecErrorFeedbackItemProvider::E_5_8_5_B,
                    [
                        $variable->getName(),
                        $this->getVariableTypeAsString($variable),
                        'Boolean!',
                    ]
                ),
                $ifValueAST
            );
        }
        if ($ifValueAST instanceof Literal && $ifValueAST->getValue() === null) {
            throw new InvalidRequestException(
                new FeedbackItemResolution(
                    GraphQLSpecErrorFeedbackItemProvider::class,
                    GraphQLSpecErrorFeedbackItemProvider::E_5_4_2_1_D,
                    [
                        'if',
                        $directiveName,
                    ]
                ),
                $ifValueAST
            );
        }
        if ($ifValueAST instanceof Literal && is_bool($ifValueAST->getValue())) {
            return;
        }
        throw new InvalidRequestException(
            new FeedbackItemResolution(
                InputValueCoercionGraphQLSpecErrorFeedbackItemProvider::class,
                InputValueCoercionGraphQLSpecErrorFeedbackItemProvider::E_5_6_1_16,
                [
                    $ifArgument->getValueAST()->asQueryString(),
                    'Boolean',
                ]
            ),
            $ifArgument
        );
    }

    protected function getVariableTypeAsString(Variable $variable): string
    {
        $typeName = $variable->getTypeName();
        if ($variable->isArrayOfArrays()) {
            $typeName = sprintf('[[%s%s]%s]', $typeName, $variable->isArrayOfArraysElementRequired() ? '!' : '', $variable->isArrayElementRequired() ? '!' : '');
        } elseif ($variable->isArray()) {
            $typeName = sprintf('[%s%s]', $typeName, $variable->isArrayElementRequired() ? '!' : '');
        }
        return $typeName . ($variable->isRequired() ? '!' : '');
    }

    /**
     * @throws InvalidRequestException
     * @see https://spec.graphql.org/draft/#sec-Variables-Are-Input-Types
     */
    protected function assertVariablesAreInputTypes(): void
    {
        foreach ($this->document->getOperations() as $operation) {
            foreach ($operation->getVariables() as $variable) {
                $this->assertVariableIsInputType($variable);
            }
        }
    }

    /**
     * @throws InvalidRequestException
     */
    protected function assertVariableIsInputType(Variable $variable): void
    {
        $variableTypeName = $variable->getTypeName();
        if (isset($this->compositeUnionTypeResolverNameSet[$variableTypeName])) {
            throw new InvalidRequestException(
                new FeedbackItemResolution(
                    GraphQLSpecErrorFeedbackItemProvider::class,
                    GraphQLSpecErrorFeedbackItemProvider::E_5_8_2,
                    [
                        $variable->getName(),
                        $variableTypeName,
                    ]
                ),
                $variable
            );
        }
    }

    protected function isOperationDependencyDefinerDirective(Directive $directive): bool
    {
        return $this->getOperationDependencyDefinerFieldDirectiveResolver($directive) !== null;
    }

    protected function getOperationDependencyDefinerFieldDirectiveResolver(Directive $directive): ?OperationDependencyDefinerFieldDirectiveResolverInterface
    {
        return $this->getOperationDependencyDefinerDirectiveRegistry()->getOperationDependencyDefinerFieldDirectiveResolver($directive->getName());
    }

    protected function getProvideDependedUponOperationNamesArgument(Directive $directive): ?Argument
    {
        $operationDependencyDefinerFieldDirectiveResolver = $this->getOperationDependencyDefinerFieldDirectiveResolver($directive);
        if ($operationDependencyDefinerFieldDirectiveResolver === null) {
            return null;
        }
        $provideDependedUponOperationNamesArgumentName = $operationDependencyDefinerFieldDirectiveResolver->getProvideDependedUponOperationNamesArgumentName();
        return $directive->getArgument($provideDependedUponOperationNamesArgumentName);
    }
}

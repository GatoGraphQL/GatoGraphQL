<?php

declare(strict_types=1);

namespace PoP\ComponentModel\ExtendedSpec\Execution;

use PoP\ComponentModel\DirectiveResolvers\OperationDependencyDefinerFieldDirectiveResolverInterface;
use PoP\ComponentModel\Registries\OperationDependencyDefinerDirectiveRegistryInterface;
use PoP\ComponentModel\Registries\TypeRegistryInterface;
use PoP\ComponentModel\TypeResolvers\EnumType\EnumTypeResolverInterface;
use PoP\ComponentModel\TypeResolvers\InterfaceType\InterfaceTypeResolverInterface;
use PoP\ComponentModel\TypeResolvers\ObjectType\ObjectTypeResolverInterface;
use PoP\ComponentModel\TypeResolvers\ScalarType\ScalarTypeResolverInterface;
use PoP\ComponentModel\TypeResolvers\TypeResolverInterface;
use PoP\ComponentModel\TypeResolvers\UnionType\UnionTypeResolverInterface;
use PoP\GraphQLParser\Exception\InvalidRequestException;
use PoP\GraphQLParser\ExtendedSpec\Execution\AbstractExecutableDocument;
use PoP\GraphQLParser\FeedbackItemProviders\GraphQLSpecErrorFeedbackItemProvider;
use PoP\GraphQLParser\Spec\Execution\Context;
use PoP\GraphQLParser\Spec\Parser\Ast\Argument;
use PoP\GraphQLParser\Spec\Parser\Ast\Directive;
use PoP\GraphQLParser\Spec\Parser\Ast\Document;
use PoP\GraphQLParser\Spec\Parser\Ast\FieldInterface;
use PoP\GraphQLParser\Spec\Parser\Ast\Fragment;
use PoP\GraphQLParser\Spec\Parser\Ast\FragmentBondInterface;
use PoP\GraphQLParser\Spec\Parser\Ast\FragmentReference;
use PoP\GraphQLParser\Spec\Parser\Ast\InlineFragment;
use PoP\GraphQLParser\Spec\Parser\Ast\RelationalField;
use PoP\GraphQLParser\Spec\Parser\Ast\Variable;
use PoP\Root\Facades\Instances\InstanceManagerFacade;
use PoP\ComponentModel\Feedback\FeedbackItemResolution;

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

    private ?TypeRegistryInterface $typeRegistry = null;
    private ?OperationDependencyDefinerDirectiveRegistryInterface $operationDependencyDefinerDirectiveRegistry = null;

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
    }

    /**
     * @throws InvalidRequestException
     */
    protected function validate(): void
    {
        parent::validate();
        $this->assertFragmentSpreadTypesExistInSchema();
        $this->assertVariablesAreInputTypes();
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
        foreach ($this->compositeUnionTypeResolvers as $typeResolver) {
            if (
                $this->isTypeResolverForType(
                    $fragmentSpreadType,
                    $typeResolver
                )
            ) {
                return;
            }
        }

        /**
         * The type is neither Union, Object or Interface.
         * Check if it is Enum/Scalar as to determine which validation
         * from the GraphQL spec it belongs to.
         */
        foreach ($this->nonCompositeUnionTypeResolvers as $typeResolver) {
            if (
                $this->isTypeResolverForType(
                    $fragmentSpreadType,
                    $typeResolver
                )
            ) {
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
     * @throws InvalidRequestException
     */
    protected function isTypeResolverForType(
        string $typeName,
        TypeResolverInterface $typeResolver
    ): bool {
        return $typeResolver->getTypeName() === $typeName
            || $typeResolver->getNamespacedTypeName() === $typeName;
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
                $this->assertFragmentSpreadTypeExistsInSchema($inlineFragment->getTypeName(), $inlineFragment);
                $this->assertInlineFragmentSpreadTypesInFieldsOrFragmentsExistInSchema($inlineFragment->getFieldsOrFragmentBonds());
                continue;
            }
        }
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
        foreach ($this->compositeUnionTypeResolvers as $typeResolver) {
            if ($this->isTypeResolverForType($variable->getTypeName(), $typeResolver)) {
                throw new InvalidRequestException(
                    new FeedbackItemResolution(
                        GraphQLSpecErrorFeedbackItemProvider::class,
                        GraphQLSpecErrorFeedbackItemProvider::E_5_8_2,
                        [
                            $variable->getName(),
                            $variable->getTypeName(),
                        ]
                    ),
                    $variable
                );
            }
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

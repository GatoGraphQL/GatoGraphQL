<?php

declare(strict_types=1);

namespace PoP\ComponentModel\ExtendedSpec\Execution;

use PoP\ComponentModel\Registries\TypeRegistryInterface;
use PoP\ComponentModel\TypeResolvers\EnumType\EnumTypeResolverInterface;
use PoP\ComponentModel\TypeResolvers\InterfaceType\InterfaceTypeResolverInterface;
use PoP\ComponentModel\TypeResolvers\ObjectType\ObjectTypeResolverInterface;
use PoP\ComponentModel\TypeResolvers\ScalarType\ScalarTypeResolverInterface;
use PoP\ComponentModel\TypeResolvers\TypeResolverInterface;
use PoP\GraphQLParser\Exception\Parser\InvalidRequestException;
use PoP\GraphQLParser\ExtendedSpec\Execution\ExecutableDocument as UpstreamExecutableDocument;
use PoP\GraphQLParser\FeedbackItemProviders\GraphQLSpecErrorFeedbackItemProvider;
use PoP\GraphQLParser\Spec\Execution\Context;
use PoP\GraphQLParser\Spec\Parser\Ast\Document;
use PoP\GraphQLParser\Spec\Parser\Ast\FieldInterface;
use PoP\GraphQLParser\Spec\Parser\Ast\FragmentBondInterface;
use PoP\GraphQLParser\Spec\Parser\Ast\FragmentReference;
use PoP\GraphQLParser\Spec\Parser\Ast\InlineFragment;
use PoP\GraphQLParser\Spec\Parser\Ast\RelationalField;
use PoP\GraphQLParser\StaticHelpers\LocationHelper;
use PoP\Root\Facades\Instances\InstanceManagerFacade;
use PoP\Root\Feedback\FeedbackItemResolution;

class ExecutableDocument extends UpstreamExecutableDocument
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

    final protected function getTypeRegistry(): TypeRegistryInterface
    {
        return $this->typeRegistry ??= InstanceManagerFacade::getInstance()->getInstance(TypeRegistryInterface::class);
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
    }

    /**
     * @throws InvalidRequestException
     * @see https://spec.graphql.org/draft/#sec-Fragment-Spread-Type-Existence
     */
    protected function assertFragmentSpreadTypesExistInSchema(): void
    {
        foreach ($this->document->getFragments() as $fragment) {
            $this->assertFragmentSpreadTypeExistsInSchema($fragment->getModel());
            $this->assertInlineFragmentSpreadTypesInFieldsOrFragmentsExistInSchema($fragment->getFieldsOrFragmentBonds());
        }
        foreach ($this->document->getOperations() as $operation) {
            $this->assertInlineFragmentSpreadTypesInFieldsOrFragmentsExistInSchema($operation->getFieldsOrFragmentBonds());
        }
    }

    /**
     * @throws InvalidRequestException
     * @see https://spec.graphql.org/draft/#sec-Fragment-Spread-Type-Existence
     */
    protected function assertFragmentSpreadTypeExistsInSchema(string $fragmentSpreadType): void
    {
        foreach ($this->compositeUnionTypeResolvers as $typeResolver) {
            if ($this->isTypeResolverForFragmentSpreadType(
                $fragmentSpreadType,
                $typeResolver
            )) {
                return;
            }
        }

        /**
         * The type is neither Union, Object or Interface.
         * Check if it is Enum/Scalar as to determine which validation
         * from the GraphQL spec it belongs to.
         */
        foreach ($this->nonCompositeUnionTypeResolvers as $typeResolver) {
            if ($this->isTypeResolverForFragmentSpreadType(
                $fragmentSpreadType,
                $typeResolver
            )) {
                throw new InvalidRequestException(
                    new FeedbackItemResolution(
                        GraphQLSpecErrorFeedbackItemProvider::class,
                        GraphQLSpecErrorFeedbackItemProvider::E_5_5_1_3,
                        [
                            $fragmentSpreadType,
                        ]
                    ),
                    LocationHelper::getNonSpecificLocation()
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
            LocationHelper::getNonSpecificLocation()
        );
    }

    /**
     * @throws InvalidRequestException
     * @see https://spec.graphql.org/draft/#sec-Fragment-Spread-Type-Existence
     */
    protected function isTypeResolverForFragmentSpreadType(
        string $fragmentSpreadType,
        TypeResolverInterface $typeResolver
    ): bool {
        return $typeResolver->getTypeName() === $fragmentSpreadType
            || $typeResolver->getNamespacedTypeName() === $fragmentSpreadType;
    }

    /**
     * @param FieldInterface[]|FragmentBondInterface[] $fieldsOrFragmentBonds
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
                $this->assertFragmentSpreadTypeExistsInSchema($inlineFragment->getTypeName());
                $this->assertInlineFragmentSpreadTypesInFieldsOrFragmentsExistInSchema($inlineFragment->getFieldsOrFragmentBonds());
                continue;
            }
        }
    }
}

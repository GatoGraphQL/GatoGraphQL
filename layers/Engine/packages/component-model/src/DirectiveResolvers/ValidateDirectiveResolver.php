<?php

declare(strict_types=1);

namespace PoP\ComponentModel\DirectiveResolvers;

use PoP\ComponentModel\Container\ServiceTags\MandatoryDirectiveServiceTagInterface;
use PoP\ComponentModel\Directives\DirectiveKinds;
use PoP\ComponentModel\Engine\EngineIterationFieldSet;
use PoP\ComponentModel\Feedback\EngineIterationFeedbackStore;
use PoP\ComponentModel\FieldResolvers\ObjectType\ObjectTypeFieldResolverInterface;
use PoP\ComponentModel\QueryResolution\FieldDataAccessProviderInterface;
use PoP\ComponentModel\StaticHelpers\MethodHelpers;
use PoP\ComponentModel\TypeResolvers\ObjectType\ObjectTypeResolverInterface;
use PoP\ComponentModel\TypeResolvers\PipelinePositions;
use PoP\ComponentModel\TypeResolvers\RelationalTypeResolverInterface;
use PoP\ComponentModel\TypeResolvers\UnionType\UnionTypeResolverInterface;
use PoP\ComponentModel\TypeSerialization\TypeSerializationServiceInterface;
use PoP\GraphQLParser\FeedbackItemProviders\GraphQLSpecErrorFeedbackItemProvider;
use PoP\GraphQLParser\Spec\Parser\Ast\FieldInterface;
use PoP\GraphQLParser\Spec\Parser\Ast\RelationalField;
use PoP\Root\Feedback\FeedbackItemResolution;
use SplObjectStorage;

final class ValidateDirectiveResolver extends AbstractGlobalDirectiveResolver implements MandatoryDirectiveServiceTagInterface
{
    private ?TypeSerializationServiceInterface $typeSerializationService = null;

    final public function setTypeSerializationService(TypeSerializationServiceInterface $typeSerializationService): void
    {
        $this->typeSerializationService = $typeSerializationService;
    }
    final protected function getTypeSerializationService(): TypeSerializationServiceInterface
    {
        return $this->typeSerializationService ??= $this->instanceManager->getInstance(TypeSerializationServiceInterface::class);
    }

    public function getDirectiveName(): string
    {
        return 'validate';
    }

    /**
     * This is a system directive
     */
    public function getDirectiveKind(): string
    {
        return DirectiveKinds::SYSTEM;
    }

    /**
     * This directive must be the first one of its group
     */
    public function getPipelinePosition(): string
    {
        return PipelinePositions::AFTER_VALIDATE;
    }

    /**
     * Validate at the schema level first that Fields
     * exist, and RelationalFields are indeed relational
     * in the resolver.
     *
     * @param array<string|int,EngineIterationFieldSet> $idFieldSet
     * @param array<array<string|int,EngineIterationFieldSet>> $succeedingPipelineIDFieldSet
     * @param array<FieldDataAccessProviderInterface> $succeedingPipelineFieldDataAccessProviders
     * @param array<string,array<string|int,SplObjectStorage<FieldInterface,mixed>>> $previouslyResolvedIDFieldValues
     * @param array<string|int,SplObjectStorage<FieldInterface,mixed>> $resolvedIDFieldValues
     */
    public function resolveDirective(
        RelationalTypeResolverInterface $relationalTypeResolver,
        array $idFieldSet,
        FieldDataAccessProviderInterface $fieldDataAccessProvider,
        array $succeedingPipelineDirectiveResolvers,
        array $idObjects,
        array $unionTypeOutputKeyIDs,
        array $previouslyResolvedIDFieldValues,
        array &$succeedingPipelineIDFieldSet,
        array &$succeedingPipelineFieldDataAccessProviders,
        array &$resolvedIDFieldValues,
        array &$messages,
        EngineIterationFeedbackStore $engineIterationFeedbackStore,
    ): void {
        if ($relationalTypeResolver instanceof UnionTypeResolverInterface) {
            /** @var UnionTypeResolverInterface */
            $unionTypeResolver = $relationalTypeResolver;
            /** @var SplObjectStorage<ObjectTypeResolverInterface,FieldInterface[]> */
            $processedObjectTypeResolverFields = new SplObjectStorage();
            foreach ($idFieldSet as $id => $fieldSet) {
                $object = $idObjects[$id] ?? null;
                if ($object === null) {
                    continue;
                }
                $targetObjectTypeResolver = $unionTypeResolver->getTargetObjectTypeResolver($object);
                if ($targetObjectTypeResolver === null) {
                    continue;
                }
                $processedFields = $processedObjectTypeResolverFields[$targetObjectTypeResolver] ?? [];
                foreach ($fieldSet->fields as $field) {
                    if (in_array($field, $processedFields)) {
                        continue;
                    }
                    $processedFields[] = $field;
                    $this->executeValidationForField(
                        $targetObjectTypeResolver,
                        $field,
                        $idFieldSet,
                        $succeedingPipelineIDFieldSet,
                        $resolvedIDFieldValues,
                        $engineIterationFeedbackStore,
                    );
                }
                $processedObjectTypeResolverFields[$targetObjectTypeResolver] = $processedFields;
            }
            return;
        }

        /** @var ObjectTypeResolverInterface */
        $objectTypeResolver = $relationalTypeResolver;
        $fields = MethodHelpers::getFieldsFromIDFieldSet($idFieldSet);
        foreach ($fields as $field) {
            $this->executeValidationForField(
                $objectTypeResolver,
                $field,
                $idFieldSet,
                $succeedingPipelineIDFieldSet,
                $resolvedIDFieldValues,
                $engineIterationFeedbackStore,
            );
        }
    }

    /**
     * @param array<string|int,EngineIterationFieldSet> $idFieldSet
     * @param array<array<string|int,EngineIterationFieldSet>> $succeedingPipelineIDFieldSet
     * @param array<string|int,SplObjectStorage<FieldInterface,mixed>> $resolvedIDFieldValues
     */
    protected function executeValidationForField(
        ObjectTypeResolverInterface $objectTypeResolver,
        FieldInterface $field,
        array $idFieldSet,
        array &$succeedingPipelineIDFieldSet,
        array &$resolvedIDFieldValues,
        EngineIterationFeedbackStore $engineIterationFeedbackStore,
    ): void {
        /**
         * It has already been validated that the field exists
         * when parsing the Field Data
         *
         * @see layers/Engine/packages/component-model/src/TypeResolvers/ObjectType/AbstractObjectTypeResolver.php:doGetObjectTypeResolverObjectFieldData
         *
         * @var ObjectTypeFieldResolverInterface
         */
        $objectTypeFieldResolver = $objectTypeResolver->getExecutableObjectTypeFieldResolverForField($field);

        /**
         * Validate that a RelationalField in the AST is not actually
         * a leaf field in the resolver.
         *
         * Eg: field "id" is built as RelationalField in the AST, but it is
         * not a connection:
         *
         *   ```
         *   {
         *     id {
         *       __typename
         *     }
         *   }
         *   ```
         */
        if (
            $field instanceof RelationalField
            && !($objectTypeFieldResolver->getFieldTypeResolver($objectTypeResolver, $field->getName()) instanceof RelationalTypeResolverInterface)
        ) {
            $this->processSchemaFailure(
                $objectTypeResolver,
                new FeedbackItemResolution(
                    GraphQLSpecErrorFeedbackItemProvider::class,
                    GraphQLSpecErrorFeedbackItemProvider::E_5_3_3,
                    [
                        $field->getOutputKey(),
                        $objectTypeResolver->getMaybeNamespacedTypeName(),
                    ]
                ),
                [$field],
                $idFieldSet,
                $succeedingPipelineIDFieldSet,
                $field,
                $resolvedIDFieldValues,
                $engineIterationFeedbackStore,
            );
        }
    }

    public function getDirectiveDescription(RelationalTypeResolverInterface $relationalTypeResolver): ?string
    {
        return $this->__('Validate the fields before they are resolved. This directive is already included by the engine, since its execution is mandatory', 'component-model');
    }
}

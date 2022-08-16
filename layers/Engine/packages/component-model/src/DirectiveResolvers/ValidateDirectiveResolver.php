<?php

declare(strict_types=1);

namespace PoP\ComponentModel\DirectiveResolvers;

use PoP\ComponentModel\Container\ServiceTags\MandatoryDirectiveServiceTagInterface;
use PoP\ComponentModel\Directives\DirectiveKinds;
use PoP\ComponentModel\Engine\EngineIterationFieldSet;
use PoP\ComponentModel\Feedback\EngineIterationFeedbackStore;
use PoP\ComponentModel\FeedbackItemProviders\ErrorFeedbackItemProvider;
use PoP\ComponentModel\FeedbackItemProviders\FieldResolutionErrorFeedbackItemProvider;
use PoP\ComponentModel\QueryResolution\FieldDataAccessProviderInterface;
use PoP\ComponentModel\StaticHelpers\MethodHelpers;
use PoP\ComponentModel\TypeResolvers\ObjectType\ObjectTypeResolverInterface;
use PoP\ComponentModel\TypeResolvers\PipelinePositions;
use PoP\ComponentModel\TypeResolvers\RelationalTypeResolverInterface;
use PoP\ComponentModel\TypeSerialization\TypeSerializationServiceInterface;
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
        /**
         * Validate at the schema level first that Fields
         * exist, and RelationalFields are indeed relational
         * in the resolver.
         */
        if ($relationalTypeResolver instanceof ObjectTypeResolverInterface) {
            /** @var ObjectTypeResolverInterface */
            $objectTypeResolver = $relationalTypeResolver;
            $fields = MethodHelpers::getFieldsFromIDFieldSet($idFieldSet);
            foreach ($fields as $field) {
                $objectTypeFieldResolver = $objectTypeResolver->getExecutableObjectTypeFieldResolverForField($field);
                if ($objectTypeFieldResolver === null) {
                    $this->processFailure(
                        $relationalTypeResolver,
                        new FeedbackItemResolution(
                            FieldResolutionErrorFeedbackItemProvider::class,
                            FieldResolutionErrorFeedbackItemProvider::E1,
                            [
                                $field->getName(),
                                $objectTypeResolver->getMaybeNamespacedTypeName(),
                            ]
                        ),
                        [$field],
                        $idFieldSet,
                        $succeedingPipelineIDFieldSet,
                        $this->directive,
                        $resolvedIDFieldValues,
                        $engineIterationFeedbackStore,
                    );
                    continue;
                }

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
                if ($field instanceof RelationalField
                    && !($objectTypeFieldResolver->getFieldTypeResolver($objectTypeResolver, $field->getName()) instanceof RelationalTypeResolverInterface)
                ) {
                    $this->processFailure(
                        $relationalTypeResolver,
                        new FeedbackItemResolution(
                            ErrorFeedbackItemProvider::class,
                            ErrorFeedbackItemProvider::E1,
                            [
                                $field->getOutputKey(),
                                $objectTypeResolver->getMaybeNamespacedTypeName(),
                            ]
                        ),
                        [$field],
                        $idFieldSet,
                        $succeedingPipelineIDFieldSet,
                        $this->directive,
                        $resolvedIDFieldValues,
                        $engineIterationFeedbackStore,
                    );
                    continue;
                }
            }
        }
    }

    public function getDirectiveDescription(RelationalTypeResolverInterface $relationalTypeResolver): ?string
    {
        return $this->__('Validate the fields before they are resolved. This directive is already included by the engine, since its execution is mandatory', 'component-model');
    }
}

<?php

declare(strict_types=1);

namespace PoPCMSSchema\CustomPostMutations\FieldResolvers\ObjectType;

use PoPCMSSchema\CustomPostMutations\TypeResolvers\InputObjectType\RootMyCustomPostsFilterInputObjectTypeResolver;
use PoPCMSSchema\CustomPosts\ComponentProcessors\CommonCustomPostFilterInputContainerComponentProcessor;
use PoPCMSSchema\CustomPosts\TypeAPIs\CustomPostTypeAPIInterface;
use PoPCMSSchema\CustomPosts\TypeHelpers\CustomPostUnionTypeHelpers;
use PoPCMSSchema\CustomPosts\TypeResolvers\InputObjectType\CustomPostByOneofInputObjectTypeResolver;
use PoPCMSSchema\CustomPosts\TypeResolvers\InputObjectType\CustomPostPaginationInputObjectTypeResolver;
use PoPCMSSchema\CustomPosts\TypeResolvers\InputObjectType\CustomPostSortInputObjectTypeResolver;
use PoPCMSSchema\SchemaCommons\DataLoading\ReturnTypes;
use PoPCMSSchema\SchemaCommons\Resolvers\WithLimitFieldArgResolverTrait;
use PoPCMSSchema\UserState\Checkpoints\UserLoggedInCheckpoint;
use PoPSchema\SchemaCommons\Constants\QueryOptions;
use PoP\ComponentModel\Checkpoints\CheckpointInterface;
use PoP\ComponentModel\Component\Component;
use PoP\ComponentModel\Feedback\ObjectTypeFieldResolutionFeedbackStore;
use PoP\ComponentModel\FieldResolvers\ObjectType\AbstractQueryableObjectTypeFieldResolver;
use PoP\ComponentModel\QueryResolution\FieldDataAccessorInterface;
use PoP\ComponentModel\Schema\SchemaTypeModifiers;
use PoP\ComponentModel\TypeResolvers\ConcreteTypeResolverInterface;
use PoP\ComponentModel\TypeResolvers\InputTypeResolverInterface;
use PoP\ComponentModel\TypeResolvers\ObjectType\ObjectTypeResolverInterface;
use PoP\ComponentModel\TypeResolvers\ScalarType\IntScalarTypeResolver;
use PoP\Engine\TypeResolvers\ObjectType\RootObjectTypeResolver;
use PoP\GraphQLParser\Spec\Parser\Ast\FieldInterface;
use PoP\Root\App;

class RootQueryableObjectTypeFieldResolver extends AbstractQueryableObjectTypeFieldResolver
{
    use WithLimitFieldArgResolverTrait;

    private ?IntScalarTypeResolver $intScalarTypeResolver = null;
    private ?CustomPostTypeAPIInterface $customPostTypeAPI = null;
    private ?CustomPostByOneofInputObjectTypeResolver $customPostByOneofInputObjectTypeResolver = null;
    private ?RootMyCustomPostsFilterInputObjectTypeResolver $rootMyCustomPostsFilterInputObjectTypeResolver = null;
    private ?CustomPostPaginationInputObjectTypeResolver $customPostPaginationInputObjectTypeResolver = null;
    private ?CustomPostSortInputObjectTypeResolver $customPostSortInputObjectTypeResolver = null;
    private ?UserLoggedInCheckpoint $userLoggedInCheckpoint = null;

    final protected function getIntScalarTypeResolver(): IntScalarTypeResolver
    {
        if ($this->intScalarTypeResolver === null) {
            /** @var IntScalarTypeResolver */
            $intScalarTypeResolver = $this->instanceManager->getInstance(IntScalarTypeResolver::class);
            $this->intScalarTypeResolver = $intScalarTypeResolver;
        }
        return $this->intScalarTypeResolver;
    }
    final protected function getCustomPostTypeAPI(): CustomPostTypeAPIInterface
    {
        if ($this->customPostTypeAPI === null) {
            /** @var CustomPostTypeAPIInterface */
            $customPostTypeAPI = $this->instanceManager->getInstance(CustomPostTypeAPIInterface::class);
            $this->customPostTypeAPI = $customPostTypeAPI;
        }
        return $this->customPostTypeAPI;
    }
    final protected function getCustomPostByOneofInputObjectTypeResolver(): CustomPostByOneofInputObjectTypeResolver
    {
        if ($this->customPostByOneofInputObjectTypeResolver === null) {
            /** @var CustomPostByOneofInputObjectTypeResolver */
            $customPostByOneofInputObjectTypeResolver = $this->instanceManager->getInstance(CustomPostByOneofInputObjectTypeResolver::class);
            $this->customPostByOneofInputObjectTypeResolver = $customPostByOneofInputObjectTypeResolver;
        }
        return $this->customPostByOneofInputObjectTypeResolver;
    }
    final protected function getRootMyCustomPostsFilterInputObjectTypeResolver(): RootMyCustomPostsFilterInputObjectTypeResolver
    {
        if ($this->rootMyCustomPostsFilterInputObjectTypeResolver === null) {
            /** @var RootMyCustomPostsFilterInputObjectTypeResolver */
            $rootMyCustomPostsFilterInputObjectTypeResolver = $this->instanceManager->getInstance(RootMyCustomPostsFilterInputObjectTypeResolver::class);
            $this->rootMyCustomPostsFilterInputObjectTypeResolver = $rootMyCustomPostsFilterInputObjectTypeResolver;
        }
        return $this->rootMyCustomPostsFilterInputObjectTypeResolver;
    }
    final protected function getCustomPostPaginationInputObjectTypeResolver(): CustomPostPaginationInputObjectTypeResolver
    {
        if ($this->customPostPaginationInputObjectTypeResolver === null) {
            /** @var CustomPostPaginationInputObjectTypeResolver */
            $customPostPaginationInputObjectTypeResolver = $this->instanceManager->getInstance(CustomPostPaginationInputObjectTypeResolver::class);
            $this->customPostPaginationInputObjectTypeResolver = $customPostPaginationInputObjectTypeResolver;
        }
        return $this->customPostPaginationInputObjectTypeResolver;
    }
    final protected function getCustomPostSortInputObjectTypeResolver(): CustomPostSortInputObjectTypeResolver
    {
        if ($this->customPostSortInputObjectTypeResolver === null) {
            /** @var CustomPostSortInputObjectTypeResolver */
            $customPostSortInputObjectTypeResolver = $this->instanceManager->getInstance(CustomPostSortInputObjectTypeResolver::class);
            $this->customPostSortInputObjectTypeResolver = $customPostSortInputObjectTypeResolver;
        }
        return $this->customPostSortInputObjectTypeResolver;
    }
    final protected function getUserLoggedInCheckpoint(): UserLoggedInCheckpoint
    {
        if ($this->userLoggedInCheckpoint === null) {
            /** @var UserLoggedInCheckpoint */
            $userLoggedInCheckpoint = $this->instanceManager->getInstance(UserLoggedInCheckpoint::class);
            $this->userLoggedInCheckpoint = $userLoggedInCheckpoint;
        }
        return $this->userLoggedInCheckpoint;
    }

    /**
     * @return array<class-string<ObjectTypeResolverInterface>>
     */
    public function getObjectTypeResolverClassesToAttachTo(): array
    {
        return [
            RootObjectTypeResolver::class,
        ];
    }

    /**
     * @return string[]
     */
    public function getFieldNamesToResolve(): array
    {
        return [
            'myCustomPosts',
            'myCustomPostCount',
            'myCustomPost',
        ];
    }

    public function getFieldTypeResolver(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName): ConcreteTypeResolverInterface
    {
        return match ($fieldName) {
            'myCustomPosts' => CustomPostUnionTypeHelpers::getCustomPostUnionOrTargetObjectTypeResolver(),
            'myCustomPost' => CustomPostUnionTypeHelpers::getCustomPostUnionOrTargetObjectTypeResolver(),
            'myCustomPostCount' => $this->getIntScalarTypeResolver(),
            default => parent::getFieldTypeResolver($objectTypeResolver, $fieldName),
        };
    }

    public function getFieldTypeModifiers(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName): int
    {
        return match ($fieldName) {
            'myCustomPostCount' => SchemaTypeModifiers::NON_NULLABLE,
            'myCustomPosts' => SchemaTypeModifiers::NON_NULLABLE | SchemaTypeModifiers::IS_ARRAY | SchemaTypeModifiers::IS_NON_NULLABLE_ITEMS_IN_ARRAY,
            default => parent::getFieldTypeModifiers($objectTypeResolver, $fieldName),
        };
    }

    public function getFieldDescription(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName): ?string
    {
        return match ($fieldName) {
            'myCustomPosts' => $this->__('Custom posts by the logged-in user', 'custompost-mutations'),
            'myCustomPostCount' => $this->__('Number of custom posts by the logged-in user', 'custompost-mutations'),
            'myCustomPost' => $this->__('Retrieve a single custom post', 'custompost-mutations'),
            default => parent::getFieldDescription($objectTypeResolver, $fieldName),
        };
    }

    public function getFieldFilterInputContainerComponent(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName): ?Component
    {
        return match ($fieldName) {
            'myCustomPost' => new Component(
                CommonCustomPostFilterInputContainerComponentProcessor::class,
                CommonCustomPostFilterInputContainerComponentProcessor::COMPONENT_FILTERINPUTCONTAINER_CUSTOMPOST_BY_STATUS_UNIONTYPE
            ),
            default => parent::getFieldFilterInputContainerComponent($objectTypeResolver, $fieldName),
        };
    }

    /**
     * @return array<string,InputTypeResolverInterface>
     */
    public function getFieldArgNameTypeResolvers(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName): array
    {
        $fieldArgNameTypeResolvers = parent::getFieldArgNameTypeResolvers($objectTypeResolver, $fieldName);
        return match ($fieldName) {
            'myCustomPost' => array_merge(
                $fieldArgNameTypeResolvers,
                [
                    'by' => $this->getCustomPostByOneofInputObjectTypeResolver(),
                ]
            ),
            'myCustomPosts' => array_merge(
                $fieldArgNameTypeResolvers,
                [
                    'filter' => $this->getRootMyCustomPostsFilterInputObjectTypeResolver(),
                    'pagination' => $this->getCustomPostPaginationInputObjectTypeResolver(),
                    'sort' => $this->getCustomPostSortInputObjectTypeResolver(),
                ]
            ),
            'myCustomPostCount' => array_merge(
                $fieldArgNameTypeResolvers,
                [
                    'filter' => $this->getRootMyCustomPostsFilterInputObjectTypeResolver(),
                ]
            ),
            default => $fieldArgNameTypeResolvers,
        };
    }

    public function getFieldArgTypeModifiers(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName, string $fieldArgName): int
    {
        return match ([$fieldName => $fieldArgName]) {
            ['myCustomPost' => 'by'] => SchemaTypeModifiers::MANDATORY,
            default => parent::getFieldArgTypeModifiers($objectTypeResolver, $fieldName, $fieldArgName),
        };
    }

    /**
     * @return array<string,mixed>
     */
    protected function getQuery(
        ObjectTypeResolverInterface $objectTypeResolver,
        object $object,
        FieldDataAccessorInterface $fieldDataAccessor,
    ): array {
        return match ($fieldDataAccessor->getFieldName()) {
            'myCustomPost',
            'myCustomPosts',
            'myCustomPostCount'
                => [
                    'authors' => [App::getState('current-user-id')],
                ],
            default
                => [],
        };
    }

    public function resolveValue(
        ObjectTypeResolverInterface $objectTypeResolver,
        object $object,
        FieldDataAccessorInterface $fieldDataAccessor,
        ObjectTypeFieldResolutionFeedbackStore $objectTypeFieldResolutionFeedbackStore,
    ): mixed {
        $query = array_merge(
            $this->convertFieldArgsToFilteringQueryArgs($objectTypeResolver, $fieldDataAccessor),
            $this->getQuery($objectTypeResolver, $object, $fieldDataAccessor)
        );
        switch ($fieldDataAccessor->getFieldName()) {
            case 'myCustomPostCount':
                return $this->getCustomPostTypeAPI()->getCustomPostCount($query);

            case 'myCustomPosts':
                return $this->getCustomPostTypeAPI()->getCustomPosts($query, [QueryOptions::RETURN_TYPE => ReturnTypes::IDS]);

            case 'myCustomPost':
                if ($customPosts = $this->getCustomPostTypeAPI()->getCustomPosts($query, [QueryOptions::RETURN_TYPE => ReturnTypes::IDS])) {
                    return $customPosts[0];
                }
                return null;
        }

        return parent::resolveValue($objectTypeResolver, $object, $fieldDataAccessor, $objectTypeFieldResolutionFeedbackStore);
    }

    /**
     * Since the return type is known for all the fields in this
     * FieldResolver, there's no need to validate them
     */
    public function validateResolvedFieldType(
        ObjectTypeResolverInterface $objectTypeResolver,
        FieldInterface $field,
    ): bool {
        return false;
    }

    /**
     * @return CheckpointInterface[]
     */
    public function getValidationCheckpoints(
        ObjectTypeResolverInterface $objectTypeResolver,
        FieldDataAccessorInterface $fieldDataAccessor,
        object $object,
    ): array {
        $validationCheckpoints = parent::getValidationCheckpoints(
            $objectTypeResolver,
            $fieldDataAccessor,
            $object,
        );
        $validationCheckpoints[] = $this->getUserLoggedInCheckpoint();
        return $validationCheckpoints;
    }
}

<?php

declare(strict_types=1);

namespace PoPCMSSchema\Comments\FieldResolvers\ObjectType;

use PoPCMSSchema\Comments\ComponentProcessors\FormInputs\FilterInputComponentProcessor;
use PoPCMSSchema\Comments\ComponentProcessors\SingleCommentFilterInputContainerComponentProcessor;
use PoPCMSSchema\Comments\Constants\CommentStatus;
use PoPCMSSchema\Comments\Module;
use PoPCMSSchema\Comments\ModuleConfiguration;
use PoPCMSSchema\Comments\TypeAPIs\CommentTypeAPIInterface;
use PoPCMSSchema\Comments\TypeResolvers\InputObjectType\CommentByOneofInputObjectTypeResolver;
use PoPCMSSchema\Comments\TypeResolvers\InputObjectType\CommentSortInputObjectTypeResolver;
use PoPCMSSchema\Comments\TypeResolvers\InputObjectType\RootCommentPaginationInputObjectTypeResolver;
use PoPCMSSchema\Comments\TypeResolvers\InputObjectType\RootCommentsFilterInputObjectTypeResolver;
use PoPCMSSchema\Comments\TypeResolvers\ObjectType\CommentObjectTypeResolver;
use PoPCMSSchema\CustomPosts\Module as CustomPostsModule;
use PoPCMSSchema\CustomPosts\ModuleConfiguration as CustomPostsModuleConfiguration;
use PoPCMSSchema\SchemaCommons\DataLoading\ReturnTypes;
use PoPCMSSchema\SchemaCommons\Resolvers\WithLimitFieldArgResolverTrait;
use PoPSchema\SchemaCommons\Constants\QueryOptions;
use PoP\ComponentModel\Component\Component;
use PoP\ComponentModel\Feedback\ObjectTypeFieldResolutionFeedbackStore;
use PoP\ComponentModel\FieldResolvers\ObjectType\AbstractQueryableObjectTypeFieldResolver;
use PoP\ComponentModel\FilterInput\FilterInputHelper;
use PoP\ComponentModel\QueryResolution\FieldDataAccessorInterface;
use PoP\ComponentModel\Schema\SchemaTypeModifiers;
use PoP\ComponentModel\TypeResolvers\ConcreteTypeResolverInterface;
use PoP\ComponentModel\TypeResolvers\InputTypeResolverInterface;
use PoP\ComponentModel\TypeResolvers\ObjectType\ObjectTypeResolverInterface;
use PoP\ComponentModel\TypeResolvers\ScalarType\IntScalarTypeResolver;
use PoP\Engine\TypeResolvers\ObjectType\RootObjectTypeResolver;
use PoP\GraphQLParser\Spec\Parser\Ast\FieldInterface;
use PoP\Root\App;

class RootObjectTypeFieldResolver extends AbstractQueryableObjectTypeFieldResolver
{
    use WithLimitFieldArgResolverTrait;

    private ?CommentTypeAPIInterface $commentTypeAPI = null;
    private ?IntScalarTypeResolver $intScalarTypeResolver = null;
    private ?CommentObjectTypeResolver $commentObjectTypeResolver = null;
    private ?CommentByOneofInputObjectTypeResolver $commentByOneofInputObjectTypeResolver = null;
    private ?RootCommentsFilterInputObjectTypeResolver $rootCommentsFilterInputObjectTypeResolver = null;
    private ?RootCommentPaginationInputObjectTypeResolver $rootCommentPaginationInputObjectTypeResolver = null;
    private ?CommentSortInputObjectTypeResolver $commentSortInputObjectTypeResolver = null;

    final protected function getCommentTypeAPI(): CommentTypeAPIInterface
    {
        if ($this->commentTypeAPI === null) {
            /** @var CommentTypeAPIInterface */
            $commentTypeAPI = $this->instanceManager->getInstance(CommentTypeAPIInterface::class);
            $this->commentTypeAPI = $commentTypeAPI;
        }
        return $this->commentTypeAPI;
    }
    final protected function getIntScalarTypeResolver(): IntScalarTypeResolver
    {
        if ($this->intScalarTypeResolver === null) {
            /** @var IntScalarTypeResolver */
            $intScalarTypeResolver = $this->instanceManager->getInstance(IntScalarTypeResolver::class);
            $this->intScalarTypeResolver = $intScalarTypeResolver;
        }
        return $this->intScalarTypeResolver;
    }
    final protected function getCommentObjectTypeResolver(): CommentObjectTypeResolver
    {
        if ($this->commentObjectTypeResolver === null) {
            /** @var CommentObjectTypeResolver */
            $commentObjectTypeResolver = $this->instanceManager->getInstance(CommentObjectTypeResolver::class);
            $this->commentObjectTypeResolver = $commentObjectTypeResolver;
        }
        return $this->commentObjectTypeResolver;
    }
    final protected function getCommentByOneofInputObjectTypeResolver(): CommentByOneofInputObjectTypeResolver
    {
        if ($this->commentByOneofInputObjectTypeResolver === null) {
            /** @var CommentByOneofInputObjectTypeResolver */
            $commentByOneofInputObjectTypeResolver = $this->instanceManager->getInstance(CommentByOneofInputObjectTypeResolver::class);
            $this->commentByOneofInputObjectTypeResolver = $commentByOneofInputObjectTypeResolver;
        }
        return $this->commentByOneofInputObjectTypeResolver;
    }
    final protected function getRootCommentsFilterInputObjectTypeResolver(): RootCommentsFilterInputObjectTypeResolver
    {
        if ($this->rootCommentsFilterInputObjectTypeResolver === null) {
            /** @var RootCommentsFilterInputObjectTypeResolver */
            $rootCommentsFilterInputObjectTypeResolver = $this->instanceManager->getInstance(RootCommentsFilterInputObjectTypeResolver::class);
            $this->rootCommentsFilterInputObjectTypeResolver = $rootCommentsFilterInputObjectTypeResolver;
        }
        return $this->rootCommentsFilterInputObjectTypeResolver;
    }
    final protected function getRootCommentPaginationInputObjectTypeResolver(): RootCommentPaginationInputObjectTypeResolver
    {
        if ($this->rootCommentPaginationInputObjectTypeResolver === null) {
            /** @var RootCommentPaginationInputObjectTypeResolver */
            $rootCommentPaginationInputObjectTypeResolver = $this->instanceManager->getInstance(RootCommentPaginationInputObjectTypeResolver::class);
            $this->rootCommentPaginationInputObjectTypeResolver = $rootCommentPaginationInputObjectTypeResolver;
        }
        return $this->rootCommentPaginationInputObjectTypeResolver;
    }
    final protected function getCommentSortInputObjectTypeResolver(): CommentSortInputObjectTypeResolver
    {
        if ($this->commentSortInputObjectTypeResolver === null) {
            /** @var CommentSortInputObjectTypeResolver */
            $commentSortInputObjectTypeResolver = $this->instanceManager->getInstance(CommentSortInputObjectTypeResolver::class);
            $this->commentSortInputObjectTypeResolver = $commentSortInputObjectTypeResolver;
        }
        return $this->commentSortInputObjectTypeResolver;
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
            'comment',
            'commentCount',
            'comments',
        ];
    }

    public function getFieldTypeResolver(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName): ConcreteTypeResolverInterface
    {
        return match ($fieldName) {
            'commentCount'
                => $this->getIntScalarTypeResolver(),
            'comment',
            'comments'
                => $this->getCommentObjectTypeResolver(),
            default
                => parent::getFieldTypeResolver($objectTypeResolver, $fieldName),
        };
    }

    public function getFieldTypeModifiers(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName): int
    {
        return match ($fieldName) {
            'commentCount' => SchemaTypeModifiers::NON_NULLABLE,
            'comments' => SchemaTypeModifiers::NON_NULLABLE | SchemaTypeModifiers::IS_ARRAY | SchemaTypeModifiers::IS_NON_NULLABLE_ITEMS_IN_ARRAY,
            default => parent::getFieldTypeModifiers($objectTypeResolver, $fieldName),
        };
    }

    public function getFieldDescription(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName): ?string
    {
        return match ($fieldName) {
            'comment' => $this->__('Retrieve a single comment', 'pop-comments'),
            'commentCount' => $this->__('Number of comments on the site', 'pop-comments'),
            'comments' => $this->__('Comments on the site', 'pop-comments'),
            default => parent::getFieldDescription($objectTypeResolver, $fieldName),
        };
    }

    public function getFieldFilterInputContainerComponent(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName): ?Component
    {
        return match ($fieldName) {
            'comment' => new Component(SingleCommentFilterInputContainerComponentProcessor::class, SingleCommentFilterInputContainerComponentProcessor::COMPONENT_FILTERINPUTCONTAINER_COMMENT_STATUS),
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
            'comment' => array_merge(
                $fieldArgNameTypeResolvers,
                [
                    'by' => $this->getCommentByOneofInputObjectTypeResolver(),
                ]
            ),
            'comments' => array_merge(
                $fieldArgNameTypeResolvers,
                [
                    'filter' => $this->getRootCommentsFilterInputObjectTypeResolver(),
                    'pagination' => $this->getRootCommentPaginationInputObjectTypeResolver(),
                    'sort' => $this->getCommentSortInputObjectTypeResolver(),
                ]
            ),
            'commentCount' => array_merge(
                $fieldArgNameTypeResolvers,
                [
                    'filter' => $this->getRootCommentsFilterInputObjectTypeResolver(),
                ]
            ),
            default => $fieldArgNameTypeResolvers,
        };
    }

    public function getFieldArgTypeModifiers(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName, string $fieldArgName): int
    {
        return match ([$fieldName => $fieldArgName]) {
            ['comment' => 'by'] => SchemaTypeModifiers::MANDATORY,
            default => parent::getFieldArgTypeModifiers($objectTypeResolver, $fieldName, $fieldArgName),
        };
    }

    /**
     * @return string[]
     */
    public function getSensitiveFieldArgNames(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName): array
    {
        $sensitiveFieldArgNames = parent::getSensitiveFieldArgNames($objectTypeResolver, $fieldName);
        /** @var ModuleConfiguration */
        $moduleConfiguration = App::getModule(Module::class)->getConfiguration();
        switch ($fieldName) {
            case 'comment':
                if ($moduleConfiguration->treatCommentStatusAsSensitiveData()) {
                    $commentStatusFilterInputName = FilterInputHelper::getFilterInputName(new Component(
                        FilterInputComponentProcessor::class,
                        FilterInputComponentProcessor::COMPONENT_FILTERINPUT_COMMENT_STATUS
                    ));
                    $sensitiveFieldArgNames[] = $commentStatusFilterInputName;
                }
                break;
        }
        return $sensitiveFieldArgNames;
    }

    public function resolveValue(
        ObjectTypeResolverInterface $objectTypeResolver,
        object $object,
        FieldDataAccessorInterface $fieldDataAccessor,
        ObjectTypeFieldResolutionFeedbackStore $objectTypeFieldResolutionFeedbackStore,
    ): mixed {
        $query = $this->convertFieldArgsToFilteringQueryArgs($objectTypeResolver, $fieldDataAccessor);
        /**
         * If "status" is admin and won't be shown, then default to "approve" only
         */
        if (!array_key_exists('status', $query)) {
            $query['status'] = CommentStatus::APPROVE;
        }
        switch ($fieldDataAccessor->getFieldName()) {
            case 'commentCount':
                return $this->getCommentTypeAPI()->getCommentCount($query);

            case 'comments':
                return $this->getCommentTypeAPI()->getComments($query, [QueryOptions::RETURN_TYPE => ReturnTypes::IDS]);

            case 'comment':
                /**
                 * Only from the mapped CPTs, otherwise we may get an error when
                 * the custom post to which the comment was added, is not accessible
                 * via field `Comment.customPost`:
                 *
                 *   ```
                 *   comments {
                 *     customPost {
                 *       id
                 *     }
                 *   }
                 *   ```
                 */
                /** @var CustomPostsModuleConfiguration */
                $moduleConfiguration = App::getModule(CustomPostsModule::class)->getConfiguration();
                $query['custompost-types'] = $moduleConfiguration->getQueryableCustomPostTypes();
                if ($comments = $this->getCommentTypeAPI()->getComments($query, [QueryOptions::RETURN_TYPE => ReturnTypes::IDS])) {
                    return $comments[0];
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
}

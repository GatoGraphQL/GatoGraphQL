<?php

declare(strict_types=1);

namespace PoPCMSSchema\Comments\FieldResolvers\ObjectType;

use PoP\ComponentModel\Feedback\ObjectTypeFieldResolutionFeedbackStore;
use DateTime;
use PoP\ComponentModel\FieldResolvers\ObjectType\AbstractQueryableObjectTypeFieldResolver;
use PoP\ComponentModel\Schema\SchemaTypeModifiers;
use PoP\ComponentModel\TypeResolvers\ConcreteTypeResolverInterface;
use PoP\ComponentModel\TypeResolvers\ObjectType\ObjectTypeResolverInterface;
use PoPCMSSchema\SchemaCommons\Formatters\DateFormatterInterface;
use PoP\ComponentModel\TypeResolvers\ScalarType\BooleanScalarTypeResolver;
use PoP\ComponentModel\TypeResolvers\ScalarType\IDScalarTypeResolver;
use PoP\ComponentModel\TypeResolvers\ScalarType\IntScalarTypeResolver;
use PoP\ComponentModel\TypeResolvers\ScalarType\StringScalarTypeResolver;
use PoPCMSSchema\Comments\TypeAPIs\CommentTypeAPIInterface;
use PoPCMSSchema\Comments\TypeResolvers\EnumType\CommentStatusEnumTypeResolver;
use PoPCMSSchema\Comments\TypeResolvers\InputObjectType\CommentResponsePaginationInputObjectTypeResolver;
use PoPCMSSchema\Comments\TypeResolvers\InputObjectType\CommentResponsesFilterInputObjectTypeResolver;
use PoPCMSSchema\Comments\TypeResolvers\InputObjectType\CommentSortInputObjectTypeResolver;
use PoPCMSSchema\Comments\TypeResolvers\ObjectType\CommentObjectTypeResolver;
use PoPCMSSchema\CustomPosts\TypeHelpers\CustomPostUnionTypeHelpers;
use PoPSchema\SchemaCommons\Constants\QueryOptions;
use PoPCMSSchema\SchemaCommons\DataLoading\ReturnTypes;
use PoPCMSSchema\SchemaCommons\ModuleProcessors\CommonFilterInputContainerModuleProcessor;
use PoPCMSSchema\SchemaCommons\Resolvers\WithLimitFieldArgResolverTrait;
use PoPSchema\SchemaCommons\TypeResolvers\ScalarType\DateTimeScalarTypeResolver;
use PoPSchema\SchemaCommons\TypeResolvers\ScalarType\EmailScalarTypeResolver;
use PoPSchema\SchemaCommons\TypeResolvers\ScalarType\HTMLScalarTypeResolver;
use PoPSchema\SchemaCommons\TypeResolvers\ScalarType\URLScalarTypeResolver;

class CommentObjectTypeFieldResolver extends AbstractQueryableObjectTypeFieldResolver
{
    use WithLimitFieldArgResolverTrait;

    private ?CommentTypeAPIInterface $commentTypeAPI = null;
    private ?StringScalarTypeResolver $stringScalarTypeResolver = null;
    private ?HTMLScalarTypeResolver $htmlScalarTypeResolver = null;
    private ?URLScalarTypeResolver $urlScalarTypeResolver = null;
    private ?EmailScalarTypeResolver $emailScalarTypeResolver = null;
    private ?IDScalarTypeResolver $idScalarTypeResolver = null;
    private ?BooleanScalarTypeResolver $booleanScalarTypeResolver = null;
    private ?DateTimeScalarTypeResolver $dateTimeScalarTypeResolver = null;
    private ?IntScalarTypeResolver $intScalarTypeResolver = null;
    private ?CommentObjectTypeResolver $commentObjectTypeResolver = null;
    private ?CommentStatusEnumTypeResolver $commentStatusEnumTypeResolver = null;
    private ?DateFormatterInterface $dateFormatter = null;
    private ?CommentResponsesFilterInputObjectTypeResolver $commentResponsesFilterInputObjectTypeResolver = null;
    private ?CommentResponsePaginationInputObjectTypeResolver $commentResponsePaginationInputObjectTypeResolver = null;
    private ?CommentSortInputObjectTypeResolver $commentSortInputObjectTypeResolver = null;

    final public function setCommentTypeAPI(CommentTypeAPIInterface $commentTypeAPI): void
    {
        $this->commentTypeAPI = $commentTypeAPI;
    }
    final protected function getCommentTypeAPI(): CommentTypeAPIInterface
    {
        return $this->commentTypeAPI ??= $this->instanceManager->getInstance(CommentTypeAPIInterface::class);
    }
    final public function setStringScalarTypeResolver(StringScalarTypeResolver $stringScalarTypeResolver): void
    {
        $this->stringScalarTypeResolver = $stringScalarTypeResolver;
    }
    final protected function getStringScalarTypeResolver(): StringScalarTypeResolver
    {
        return $this->stringScalarTypeResolver ??= $this->instanceManager->getInstance(StringScalarTypeResolver::class);
    }
    final public function setHTMLScalarTypeResolver(HTMLScalarTypeResolver $htmlScalarTypeResolver): void
    {
        $this->htmlScalarTypeResolver = $htmlScalarTypeResolver;
    }
    final protected function getHTMLScalarTypeResolver(): HTMLScalarTypeResolver
    {
        return $this->htmlScalarTypeResolver ??= $this->instanceManager->getInstance(HTMLScalarTypeResolver::class);
    }
    final public function setURLScalarTypeResolver(URLScalarTypeResolver $urlScalarTypeResolver): void
    {
        $this->urlScalarTypeResolver = $urlScalarTypeResolver;
    }
    final protected function getURLScalarTypeResolver(): URLScalarTypeResolver
    {
        return $this->urlScalarTypeResolver ??= $this->instanceManager->getInstance(URLScalarTypeResolver::class);
    }
    final public function setEmailScalarTypeResolver(EmailScalarTypeResolver $emailScalarTypeResolver): void
    {
        $this->emailScalarTypeResolver = $emailScalarTypeResolver;
    }
    final protected function getEmailScalarTypeResolver(): EmailScalarTypeResolver
    {
        return $this->emailScalarTypeResolver ??= $this->instanceManager->getInstance(EmailScalarTypeResolver::class);
    }
    final public function setIDScalarTypeResolver(IDScalarTypeResolver $idScalarTypeResolver): void
    {
        $this->idScalarTypeResolver = $idScalarTypeResolver;
    }
    final protected function getIDScalarTypeResolver(): IDScalarTypeResolver
    {
        return $this->idScalarTypeResolver ??= $this->instanceManager->getInstance(IDScalarTypeResolver::class);
    }
    final public function setBooleanScalarTypeResolver(BooleanScalarTypeResolver $booleanScalarTypeResolver): void
    {
        $this->booleanScalarTypeResolver = $booleanScalarTypeResolver;
    }
    final protected function getBooleanScalarTypeResolver(): BooleanScalarTypeResolver
    {
        return $this->booleanScalarTypeResolver ??= $this->instanceManager->getInstance(BooleanScalarTypeResolver::class);
    }
    final public function setDateTimeScalarTypeResolver(DateTimeScalarTypeResolver $dateTimeScalarTypeResolver): void
    {
        $this->dateTimeScalarTypeResolver = $dateTimeScalarTypeResolver;
    }
    final protected function getDateTimeScalarTypeResolver(): DateTimeScalarTypeResolver
    {
        return $this->dateTimeScalarTypeResolver ??= $this->instanceManager->getInstance(DateTimeScalarTypeResolver::class);
    }
    final public function setIntScalarTypeResolver(IntScalarTypeResolver $intScalarTypeResolver): void
    {
        $this->intScalarTypeResolver = $intScalarTypeResolver;
    }
    final protected function getIntScalarTypeResolver(): IntScalarTypeResolver
    {
        return $this->intScalarTypeResolver ??= $this->instanceManager->getInstance(IntScalarTypeResolver::class);
    }
    final public function setCommentObjectTypeResolver(CommentObjectTypeResolver $commentObjectTypeResolver): void
    {
        $this->commentObjectTypeResolver = $commentObjectTypeResolver;
    }
    final protected function getCommentObjectTypeResolver(): CommentObjectTypeResolver
    {
        return $this->commentObjectTypeResolver ??= $this->instanceManager->getInstance(CommentObjectTypeResolver::class);
    }
    final public function setCommentStatusEnumTypeResolver(CommentStatusEnumTypeResolver $commentStatusEnumTypeResolver): void
    {
        $this->commentStatusEnumTypeResolver = $commentStatusEnumTypeResolver;
    }
    final protected function getCommentStatusEnumTypeResolver(): CommentStatusEnumTypeResolver
    {
        return $this->commentStatusEnumTypeResolver ??= $this->instanceManager->getInstance(CommentStatusEnumTypeResolver::class);
    }
    final public function setDateFormatter(DateFormatterInterface $dateFormatter): void
    {
        $this->dateFormatter = $dateFormatter;
    }
    final protected function getDateFormatter(): DateFormatterInterface
    {
        return $this->dateFormatter ??= $this->instanceManager->getInstance(DateFormatterInterface::class);
    }
    final public function setCommentResponsesFilterInputObjectTypeResolver(CommentResponsesFilterInputObjectTypeResolver $commentResponsesFilterInputObjectTypeResolver): void
    {
        $this->commentResponsesFilterInputObjectTypeResolver = $commentResponsesFilterInputObjectTypeResolver;
    }
    final protected function getCommentResponsesFilterInputObjectTypeResolver(): CommentResponsesFilterInputObjectTypeResolver
    {
        return $this->commentResponsesFilterInputObjectTypeResolver ??= $this->instanceManager->getInstance(CommentResponsesFilterInputObjectTypeResolver::class);
    }
    final public function setCommentResponsePaginationInputObjectTypeResolver(CommentResponsePaginationInputObjectTypeResolver $commentResponsePaginationInputObjectTypeResolver): void
    {
        $this->commentResponsePaginationInputObjectTypeResolver = $commentResponsePaginationInputObjectTypeResolver;
    }
    final protected function getCommentResponsePaginationInputObjectTypeResolver(): CommentResponsePaginationInputObjectTypeResolver
    {
        return $this->commentResponsePaginationInputObjectTypeResolver ??= $this->instanceManager->getInstance(CommentResponsePaginationInputObjectTypeResolver::class);
    }
    final public function setCommentSortInputObjectTypeResolver(CommentSortInputObjectTypeResolver $commentSortInputObjectTypeResolver): void
    {
        $this->commentSortInputObjectTypeResolver = $commentSortInputObjectTypeResolver;
    }
    final protected function getCommentSortInputObjectTypeResolver(): CommentSortInputObjectTypeResolver
    {
        return $this->commentSortInputObjectTypeResolver ??= $this->instanceManager->getInstance(CommentSortInputObjectTypeResolver::class);
    }

    public function getObjectTypeResolverClassesToAttachTo(): array
    {
        return [
            CommentObjectTypeResolver::class,
        ];
    }

    public function getFieldNamesToResolve(): array
    {
        return [
            'content',
            'rawContent',
            'authorName',
            'authorURL',
            'authorEmail',
            'customPost',
            'customPostID',
            'approved',
            'type',
            'status',
            'parent',
            'date',
            'dateStr',
            'responses',
            'responseCount',
        ];
    }

    public function getFieldTypeResolver(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName): ConcreteTypeResolverInterface
    {
        return match ($fieldName) {
            'rawContent',
            'authorName',
            'type',
            'dateStr'
                => $this->getStringScalarTypeResolver(),
            'content'
                => $this->getHTMLScalarTypeResolver(),
            'authorURL'
                => $this->getURLScalarTypeResolver(),
            'authorEmail'
                => $this->getEmailScalarTypeResolver(),
            'customPostID'
                => $this->getIDScalarTypeResolver(),
            'approved'
                => $this->getBooleanScalarTypeResolver(),
            'date'
                => $this->getDateTimeScalarTypeResolver(),
            'responseCount'
                => $this->getIntScalarTypeResolver(),
            'customPost'
                => CustomPostUnionTypeHelpers::getCustomPostUnionOrTargetObjectTypeResolver(),
            'parent',
            'responses'
                => $this->getCommentObjectTypeResolver(),
            'status'
                => $this->getCommentStatusEnumTypeResolver(),
            default
                => parent::getFieldTypeResolver($objectTypeResolver, $fieldName),
        };
    }

    public function getFieldTypeModifiers(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName): int
    {
        return match ($fieldName) {
            'content',
            'rawContent',
            'customPost',
            'customPostID',
            'approved',
            'type',
            'status',
            'date',
            'dateStr',
            'responseCount'
                => SchemaTypeModifiers::NON_NULLABLE,
            'responses'
                => SchemaTypeModifiers::NON_NULLABLE | SchemaTypeModifiers::IS_ARRAY | SchemaTypeModifiers::IS_NON_NULLABLE_ITEMS_IN_ARRAY,
            default
                => parent::getFieldTypeModifiers($objectTypeResolver, $fieldName),
        };
    }

    public function getFieldDescription(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName): ?string
    {
        return match ($fieldName) {
            'content' => $this->__('Comment\'s content, in HTML format', 'pop-comments'),
            'rawContent' => $this->__('Comment\'s content, in raw format', 'pop-comments'),
            'authorName' => $this->__('Comment author\'s name', 'pop-comments'),
            'authorURL' => $this->__('Comment author\'s URL', 'pop-comments'),
            'authorEmail' => $this->__('Comment author\'s email', 'pop-comments'),
            'customPost' => $this->__('Custom post to which the comment was added', 'pop-comments'),
            'customPostID' => $this->__('ID of the custom post to which the comment was added', 'pop-comments'),
            'approved' => $this->__('Is the comment approved?', 'pop-comments'),
            'type' => $this->__('Type of comment', 'pop-comments'),
            'status' => $this->__('Status of the comment', 'pop-comments'),
            'parent' => $this->__('Parent comment (if this comment is a response to another one)', 'pop-comments'),
            'date' => $this->__('Date when the comment was added', 'pop-comments'),
            'dateStr' => $this->__('Date when the comment was added, in String format', 'pop-comments'),
            'responses' => $this->__('Responses to the comment', 'pop-comments'),
            'responseCount' => $this->__('Number of responses to the comment', 'pop-comments'),
            default => parent::getFieldDescription($objectTypeResolver, $fieldName),
        };
    }

    public function getFieldFilterInputContainerModule(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName): ?array
    {
        return match ($fieldName) {
            'date' => [CommonFilterInputContainerModuleProcessor::class, CommonFilterInputContainerModuleProcessor::MODULE_FILTERINPUTCONTAINER_GMTDATE],
            'dateStr' => [CommonFilterInputContainerModuleProcessor::class, CommonFilterInputContainerModuleProcessor::MODULE_FILTERINPUTCONTAINER_GMTDATE_AS_STRING],
            default => parent::getFieldFilterInputContainerModule($objectTypeResolver, $fieldName),
        };
    }

    public function getFieldArgNameTypeResolvers(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName): array
    {
        $fieldArgNameTypeResolvers = parent::getFieldArgNameTypeResolvers($objectTypeResolver, $fieldName);
        return match ($fieldName) {
            'responses' => array_merge(
                $fieldArgNameTypeResolvers,
                [
                    'filter' => $this->getCommentResponsesFilterInputObjectTypeResolver(),
                    'pagination' => $this->getCommentResponsePaginationInputObjectTypeResolver(),
                    'sort' => $this->getCommentSortInputObjectTypeResolver(),
                ]
            ),
            'responseCount' => array_merge(
                $fieldArgNameTypeResolvers,
                [
                    'filter' => $this->getCommentResponsesFilterInputObjectTypeResolver(),
                ]
            ),
            default => $fieldArgNameTypeResolvers,
        };
    }

    /**
     * @param array<string, mixed> $fieldArgs
     * @param array<string, mixed> $variables
     * @param array<string, mixed> $expressions
     * @param array<string, mixed> $options
     */
    public function resolveValue(
        ObjectTypeResolverInterface $objectTypeResolver,
        object $object,
        string $fieldName,
        array $fieldArgs,
        array $variables,
        array $expressions,
        ObjectTypeFieldResolutionFeedbackStore $objectTypeFieldResolutionFeedbackStore,
        array $options = []
    ): mixed {
        $comment = $object;
        switch ($fieldName) {
            case 'content':
                return $this->getCommentTypeAPI()->getCommentContent($comment);

            case 'rawContent':
                return $this->getCommentTypeAPI()->getCommentRawContent($comment);

            case 'authorName':
                return $this->getCommentTypeAPI()->getCommentAuthorName($comment);

            case 'authorURL':
                return $this->getCommentTypeAPI()->getCommentAuthorURL($comment);

            case 'authorEmail':
                return $this->getCommentTypeAPI()->getCommentAuthorEmail($comment);

            case 'customPost':
            case 'customPostID':
                return $this->getCommentTypeAPI()->getCommentPostId($comment);

            case 'approved':
                return $this->getCommentTypeAPI()->isCommentApproved($comment);

            case 'type':
                return $this->getCommentTypeAPI()->getCommentType($comment);

            case 'status':
                return $this->getCommentTypeAPI()->getCommentStatus($comment);

            case 'parent':
                return $this->getCommentTypeAPI()->getCommentParent($comment);

            case 'date':
                return new DateTime($this->getCommentTypeAPI()->getCommentDate($comment, $fieldArgs['gmt']));

            case 'dateStr':
                return $this->getDateFormatter()->format(
                    $fieldArgs['format'],
                    $this->getCommentTypeAPI()->getCommentDate($comment, $fieldArgs['gmt'])
                );
        }

        $query = array_merge(
            $this->convertFieldArgsToFilteringQueryArgs($objectTypeResolver, $fieldName, $fieldArgs),
            [
                'parent-id' => $objectTypeResolver->getID($comment),
            ]
        );
        switch ($fieldName) {
            case 'responses':
                return $this->getCommentTypeAPI()->getComments($query, [QueryOptions::RETURN_TYPE => ReturnTypes::IDS]);

            case 'responseCount':
                return $this->getCommentTypeAPI()->getCommentCount($query);
        }

        return parent::resolveValue($objectTypeResolver, $object, $fieldName, $fieldArgs, $variables, $expressions, $objectTypeFieldResolutionFeedbackStore, $options);
    }
}

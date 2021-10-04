<?php

declare(strict_types=1);

namespace PoPSchema\Comments\FieldResolvers\ObjectType;

use PoP\ComponentModel\FieldResolvers\ObjectType\AbstractQueryableObjectTypeFieldResolver;
use PoP\ComponentModel\FilterInput\FilterInputHelper;
use PoP\ComponentModel\Schema\SchemaTypeModifiers;
use PoP\ComponentModel\TypeResolvers\ConcreteTypeResolverInterface;
use PoP\ComponentModel\TypeResolvers\ObjectType\ObjectTypeResolverInterface;
use PoP\Engine\Formatters\DateFormatterInterface;
use PoP\Engine\TypeResolvers\ScalarType\BooleanScalarTypeResolver;
use PoP\Engine\TypeResolvers\ScalarType\IDScalarTypeResolver;
use PoP\Engine\TypeResolvers\ScalarType\IntScalarTypeResolver;
use PoP\Engine\TypeResolvers\ScalarType\StringScalarTypeResolver;
use PoPSchema\Comments\ComponentConfiguration;
use PoPSchema\Comments\ModuleProcessors\CommentFilterInputContainerModuleProcessor;
use PoPSchema\Comments\TypeAPIs\CommentTypeAPIInterface;
use PoPSchema\Comments\TypeResolvers\EnumType\CommentStatusEnumTypeResolver;
use PoPSchema\Comments\TypeResolvers\ObjectType\CommentObjectTypeResolver;
use PoPSchema\CustomPosts\TypeHelpers\CustomPostUnionTypeHelpers;
use PoPSchema\SchemaCommons\Constants\QueryOptions;
use PoPSchema\SchemaCommons\DataLoading\ReturnTypes;
use PoPSchema\SchemaCommons\FormInputs\OrderFormInput;
use PoPSchema\SchemaCommons\ModuleProcessors\CommonFilterInputContainerModuleProcessor;
use PoPSchema\SchemaCommons\ModuleProcessors\FormInputs\CommonFilterInputModuleProcessor;
use PoPSchema\SchemaCommons\Resolvers\WithLimitFieldArgResolverTrait;
use PoPSchema\SchemaCommons\TypeResolvers\ScalarType\DateScalarTypeResolver;
use PoPSchema\SchemaCommons\TypeResolvers\ScalarType\EmailScalarTypeResolver;
use PoPSchema\SchemaCommons\TypeResolvers\ScalarType\URLScalarTypeResolver;
use Symfony\Contracts\Service\Attribute\Required;

class CommentObjectTypeFieldResolver extends AbstractQueryableObjectTypeFieldResolver
{
    use WithLimitFieldArgResolverTrait;

    protected CommentTypeAPIInterface $commentTypeAPI;
    protected StringScalarTypeResolver $stringScalarTypeResolver;
    protected URLScalarTypeResolver $urlScalarTypeResolver;
    protected EmailScalarTypeResolver $emailScalarTypeResolver;
    protected IDScalarTypeResolver $idScalarTypeResolver;
    protected BooleanScalarTypeResolver $booleanScalarTypeResolver;
    protected DateScalarTypeResolver $dateScalarTypeResolver;
    protected IntScalarTypeResolver $intScalarTypeResolver;
    protected CommentObjectTypeResolver $commentObjectTypeResolver;
    protected CommentStatusEnumTypeResolver $commentStatusEnumTypeResolver;
    protected DateFormatterInterface $dateFormatter;

    #[Required]
    final public function autowireCommentObjectTypeFieldResolver(
        CommentTypeAPIInterface $commentTypeAPI,
        StringScalarTypeResolver $stringScalarTypeResolver,
        URLScalarTypeResolver $urlScalarTypeResolver,
        EmailScalarTypeResolver $emailScalarTypeResolver,
        IDScalarTypeResolver $idScalarTypeResolver,
        BooleanScalarTypeResolver $booleanScalarTypeResolver,
        DateScalarTypeResolver $dateScalarTypeResolver,
        IntScalarTypeResolver $intScalarTypeResolver,
        CommentObjectTypeResolver $commentObjectTypeResolver,
        CommentStatusEnumTypeResolver $commentStatusEnumTypeResolver,
        DateFormatterInterface $dateFormatter,
    ): void {
        $this->commentTypeAPI = $commentTypeAPI;
        $this->stringScalarTypeResolver = $stringScalarTypeResolver;
        $this->urlScalarTypeResolver = $urlScalarTypeResolver;
        $this->emailScalarTypeResolver = $emailScalarTypeResolver;
        $this->idScalarTypeResolver = $idScalarTypeResolver;
        $this->booleanScalarTypeResolver = $booleanScalarTypeResolver;
        $this->dateScalarTypeResolver = $dateScalarTypeResolver;
        $this->intScalarTypeResolver = $intScalarTypeResolver;
        $this->commentObjectTypeResolver = $commentObjectTypeResolver;
        $this->commentStatusEnumTypeResolver = $commentStatusEnumTypeResolver;
        $this->dateFormatter = $dateFormatter;
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
            'responses',
            'responseCount',
            'responsesForAdmin',
            'responseCountForAdmin',
        ];
    }

    public function getFieldTypeResolver(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName): ConcreteTypeResolverInterface
    {
        return match ($fieldName) {
            'content',
            'authorName',
            'type'
                => $this->stringScalarTypeResolver,
            'authorURL'
                => $this->urlScalarTypeResolver,
            'authorEmail'
                => $this->emailScalarTypeResolver,
            'customPostID'
                => $this->idScalarTypeResolver,
            'approved'
                => $this->booleanScalarTypeResolver,
            'date'
                => $this->dateScalarTypeResolver,
            'responseCount',
            'responseCountForAdmin'
                => $this->intScalarTypeResolver,
            'customPost'
                => CustomPostUnionTypeHelpers::getCustomPostUnionOrTargetObjectTypeResolver(),
            'parent',
            'responses',
            'responsesForAdmin'
                => $this->commentObjectTypeResolver,
            'status'
                => $this->commentStatusEnumTypeResolver,
            default
                => parent::getFieldTypeResolver($objectTypeResolver, $fieldName),
        };
    }

    public function getFieldTypeModifiers(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName): int
    {
        return match ($fieldName) {
            'content',
            'customPost',
            'customPostID',
            'approved',
            'type',
            'status',
            'date',
            'responseCount',
            'responseCountForAdmin'
                => SchemaTypeModifiers::NON_NULLABLE,
            'responses',
            'responsesForAdmin'
                => SchemaTypeModifiers::NON_NULLABLE | SchemaTypeModifiers::IS_ARRAY,
            default
                => parent::getFieldTypeModifiers($objectTypeResolver, $fieldName),
        };
    }

    public function getFieldDescription(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName): ?string
    {
        return match ($fieldName) {
            'content' => $this->translationAPI->__('Comment\'s content', 'pop-comments'),
            'authorName' => $this->translationAPI->__('Comment author\'s name', 'pop-comments'),
            'authorURL' => $this->translationAPI->__('Comment author\'s URL', 'pop-comments'),
            'authorEmail' => $this->translationAPI->__('Comment author\'s email', 'pop-comments'),
            'customPost' => $this->translationAPI->__('Custom post to which the comment was added', 'pop-comments'),
            'customPostID' => $this->translationAPI->__('ID of the custom post to which the comment was added', 'pop-comments'),
            'approved' => $this->translationAPI->__('Is the comment approved?', 'pop-comments'),
            'type' => $this->translationAPI->__('Type of comment', 'pop-comments'),
            'status' => $this->translationAPI->__('Status of the comment', 'pop-comments'),
            'parent' => $this->translationAPI->__('Parent comment (if this comment is a response to another one)', 'pop-comments'),
            'date' => $this->translationAPI->__('Date when the comment was added', 'pop-comments'),
            'responses' => $this->translationAPI->__('Responses to the comment', 'pop-comments'),
            'responseCount' => $this->translationAPI->__('Number of responses to the comment', 'pop-comments'),
            'responsesForAdmin' => $this->translationAPI->__('[Unrestricted] Responses to the comment', 'pop-comments'),
            'responseCountForAdmin' => $this->translationAPI->__('[Unrestricted] Number of responses to the comment', 'pop-comments'),
            default => parent::getFieldDescription($objectTypeResolver, $fieldName),
        };
    }

    public function getFieldFilterInputContainerModule(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName): ?array
    {
        return match ($fieldName) {
            'responses' => [CommentFilterInputContainerModuleProcessor::class, CommentFilterInputContainerModuleProcessor::MODULE_FILTERINPUTCONTAINER_RESPONSES],
            'responseCount' => [CommentFilterInputContainerModuleProcessor::class, CommentFilterInputContainerModuleProcessor::MODULE_FILTERINPUTCONTAINER_RESPONSECOUNT],
            'responsesForAdmin' => [CommentFilterInputContainerModuleProcessor::class, CommentFilterInputContainerModuleProcessor::MODULE_FILTERINPUTCONTAINER_ADMINRESPONSES],
            'responseCountForAdmin' => [CommentFilterInputContainerModuleProcessor::class, CommentFilterInputContainerModuleProcessor::MODULE_FILTERINPUTCONTAINER_ADMINRESPONSECOUNT],
            'date' => [CommonFilterInputContainerModuleProcessor::class, CommonFilterInputContainerModuleProcessor::MODULE_FILTERINPUTCONTAINER_GMTDATE_AS_STRING],
            default => parent::getFieldFilterInputContainerModule($objectTypeResolver, $fieldName),
        };
    }

    public function getFieldArgDefaultValue(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName, string $fieldArgName): mixed
    {
        switch ($fieldName) {
            case 'responses':
            case 'responsesForAdmin':
                $limitFilterInputName = FilterInputHelper::getFilterInputName([
                    CommonFilterInputModuleProcessor::class,
                    CommonFilterInputModuleProcessor::MODULE_FILTERINPUT_LIMIT
                ]);
                if ($fieldArgName === $limitFilterInputName) {
                    return ComponentConfiguration::getCustomPostCommentOrCommentResponseListDefaultLimit();
                }
                // Order by descending date
                $orderFilterInputName = FilterInputHelper::getFilterInputName([
                    CommonFilterInputModuleProcessor::class,
                    CommonFilterInputModuleProcessor::MODULE_FILTERINPUT_ORDER
                ]);
                if ($fieldArgName === $orderFilterInputName) {
                    $orderBy = $this->nameResolver->getName('popcms:dbcolumn:orderby:comments:date');
                    $order = 'DESC';
                    return $orderBy . OrderFormInput::SEPARATOR . $order;
                }
                break;
        }
        return parent::getFieldArgDefaultValue($objectTypeResolver, $fieldName, $fieldArgName);
    }

    /**
     * Validate the constraints for a field argument
     *
     * @return string[] Error messages
     */
    public function validateFieldArgument(
        ObjectTypeResolverInterface $objectTypeResolver,
        string $fieldName,
        string $fieldArgName,
        mixed $fieldArgValue
    ): array {
        $errors = parent::validateFieldArgument(
            $objectTypeResolver,
            $fieldName,
            $fieldArgName,
            $fieldArgValue,
        );

        // Check the "limit" fieldArg
        switch ($fieldName) {
            case 'responses':
            case 'responsesForAdmin':
                if (
                    $maybeError = $this->maybeValidateLimitFieldArgument(
                        ComponentConfiguration::getCommentListMaxLimit(),
                        $fieldName,
                        $fieldArgName,
                        $fieldArgValue
                    )
                ) {
                    $errors[] = $maybeError;
                }
                break;
        }
        return $errors;
    }

    /**
     * @param array<string, mixed> $fieldArgs
     * @param array<string, mixed>|null $variables
     * @param array<string, mixed>|null $expressions
     * @param array<string, mixed> $options
     */
    public function resolveValue(
        ObjectTypeResolverInterface $objectTypeResolver,
        object $object,
        string $fieldName,
        array $fieldArgs = [],
        ?array $variables = null,
        ?array $expressions = null,
        array $options = []
    ): mixed {
        $comment = $object;
        switch ($fieldName) {
            case 'content':
                return $this->commentTypeAPI->getCommentContent($comment);

            case 'authorName':
                return $this->commentTypeAPI->getCommentAuthorName($comment);

            case 'authorURL':
                return $this->commentTypeAPI->getCommentAuthorURL($comment);

            case 'authorEmail':
                return $this->commentTypeAPI->getCommentAuthorEmail($comment);

            case 'customPost':
            case 'customPostID':
                return $this->commentTypeAPI->getCommentPostId($comment);

            case 'approved':
                return $this->commentTypeAPI->isCommentApproved($comment);

            case 'type':
                return $this->commentTypeAPI->getCommentType($comment);

            case 'status':
                return $this->commentTypeAPI->getCommentStatus($comment);

            case 'parent':
                return $this->commentTypeAPI->getCommentParent($comment);

            case 'date':
                return $this->dateFormatter->format(
                    $fieldArgs['format'],
                    $this->commentTypeAPI->getCommentDate($comment, $fieldArgs['gmt'])
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
            case 'responsesForAdmin':
                return $this->commentTypeAPI->getComments($query, [QueryOptions::RETURN_TYPE => ReturnTypes::IDS]);

            case 'responseCount':
            case 'responseCountForAdmin':
                return $this->commentTypeAPI->getCommentCount($query);
        }

        return parent::resolveValue($objectTypeResolver, $object, $fieldName, $fieldArgs, $variables, $expressions, $options);
    }
}

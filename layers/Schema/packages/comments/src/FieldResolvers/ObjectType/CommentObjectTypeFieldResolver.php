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

class CommentObjectTypeFieldResolver extends AbstractQueryableObjectTypeFieldResolver
{
    use WithLimitFieldArgResolverTrait;

    private ?CommentTypeAPIInterface $commentTypeAPI = null;
    private ?StringScalarTypeResolver $stringScalarTypeResolver = null;
    private ?URLScalarTypeResolver $urlScalarTypeResolver = null;
    private ?EmailScalarTypeResolver $emailScalarTypeResolver = null;
    private ?IDScalarTypeResolver $idScalarTypeResolver = null;
    private ?BooleanScalarTypeResolver $booleanScalarTypeResolver = null;
    private ?DateScalarTypeResolver $dateScalarTypeResolver = null;
    private ?IntScalarTypeResolver $intScalarTypeResolver = null;
    private ?CommentObjectTypeResolver $commentObjectTypeResolver = null;
    private ?CommentStatusEnumTypeResolver $commentStatusEnumTypeResolver = null;
    private ?DateFormatterInterface $dateFormatter = null;

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
    final public function setDateScalarTypeResolver(DateScalarTypeResolver $dateScalarTypeResolver): void
    {
        $this->dateScalarTypeResolver = $dateScalarTypeResolver;
    }
    final protected function getDateScalarTypeResolver(): DateScalarTypeResolver
    {
        return $this->dateScalarTypeResolver ??= $this->instanceManager->getInstance(DateScalarTypeResolver::class);
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
                => $this->getStringScalarTypeResolver(),
            'authorURL'
                => $this->getUrlScalarTypeResolver(),
            'authorEmail'
                => $this->getEmailScalarTypeResolver(),
            'customPostID'
                => $this->getIDScalarTypeResolver(),
            'approved'
                => $this->getBooleanScalarTypeResolver(),
            'date'
                => $this->getDateScalarTypeResolver(),
            'responseCount',
            'responseCountForAdmin'
                => $this->getIntScalarTypeResolver(),
            'customPost'
                => CustomPostUnionTypeHelpers::getCustomPostUnionOrTargetObjectTypeResolver(),
            'parent',
            'responses',
            'responsesForAdmin'
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
            'content' => $this->getTranslationAPI()->__('Comment\'s content', 'pop-comments'),
            'authorName' => $this->getTranslationAPI()->__('Comment author\'s name', 'pop-comments'),
            'authorURL' => $this->getTranslationAPI()->__('Comment author\'s URL', 'pop-comments'),
            'authorEmail' => $this->getTranslationAPI()->__('Comment author\'s email', 'pop-comments'),
            'customPost' => $this->getTranslationAPI()->__('Custom post to which the comment was added', 'pop-comments'),
            'customPostID' => $this->getTranslationAPI()->__('ID of the custom post to which the comment was added', 'pop-comments'),
            'approved' => $this->getTranslationAPI()->__('Is the comment approved?', 'pop-comments'),
            'type' => $this->getTranslationAPI()->__('Type of comment', 'pop-comments'),
            'status' => $this->getTranslationAPI()->__('Status of the comment', 'pop-comments'),
            'parent' => $this->getTranslationAPI()->__('Parent comment (if this comment is a response to another one)', 'pop-comments'),
            'date' => $this->getTranslationAPI()->__('Date when the comment was added', 'pop-comments'),
            'responses' => $this->getTranslationAPI()->__('Responses to the comment', 'pop-comments'),
            'responseCount' => $this->getTranslationAPI()->__('Number of responses to the comment', 'pop-comments'),
            'responsesForAdmin' => $this->getTranslationAPI()->__('[Unrestricted] Responses to the comment', 'pop-comments'),
            'responseCountForAdmin' => $this->getTranslationAPI()->__('[Unrestricted] Number of responses to the comment', 'pop-comments'),
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
                    $orderBy = $this->getNameResolver()->getName('popcms:dbcolumn:orderby:comments:date');
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
    public function validateFieldArgValue(
        ObjectTypeResolverInterface $objectTypeResolver,
        string $fieldName,
        string $fieldArgName,
        mixed $fieldArgValue
    ): array {
        $errors = parent::validateFieldArgValue(
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
        array $fieldArgs,
        ?array $variables = null,
        ?array $expressions = null,
        array $options = []
    ): mixed {
        $comment = $object;
        switch ($fieldName) {
            case 'content':
                return $this->getCommentTypeAPI()->getCommentContent($comment);

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
            case 'responsesForAdmin':
                return $this->getCommentTypeAPI()->getComments($query, [QueryOptions::RETURN_TYPE => ReturnTypes::IDS]);

            case 'responseCount':
            case 'responseCountForAdmin':
                return $this->getCommentTypeAPI()->getCommentCount($query);
        }

        return parent::resolveValue($objectTypeResolver, $object, $fieldName, $fieldArgs, $variables, $expressions, $options);
    }
}

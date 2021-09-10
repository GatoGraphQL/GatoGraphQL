<?php

declare(strict_types=1);

namespace PoPSchema\Comments\FieldResolvers;

use PoP\ComponentModel\FieldResolvers\AbstractQueryableFieldResolver;
use PoP\ComponentModel\FieldResolvers\EnumTypeFieldSchemaDefinitionResolverTrait;
use PoP\ComponentModel\FilterInput\FilterInputHelper;
use PoP\ComponentModel\HelperServices\SemverHelperServiceInterface;
use PoP\ComponentModel\Instances\InstanceManagerInterface;
use PoP\ComponentModel\Schema\FieldQueryInterpreterInterface;
use PoP\ComponentModel\Schema\SchemaDefinition;
use PoP\ComponentModel\Schema\SchemaTypeModifiers;
use PoP\ComponentModel\TypeResolvers\ObjectType\ObjectTypeResolverInterface;
use PoP\Engine\CMS\CMSServiceInterface;
use PoP\Engine\Facades\Formatters\DateFormatterFacade;
use PoP\Hooks\HooksAPIInterface;
use PoP\LooseContracts\NameResolverInterface;
use PoP\Translation\TranslationAPIInterface;
use PoPSchema\Comments\ComponentConfiguration;
use PoPSchema\Comments\Enums\CommentStatusEnum;
use PoPSchema\Comments\ModuleProcessors\CommentFilterInputContainerModuleProcessor;
use PoPSchema\Comments\TypeAPIs\CommentTypeAPIInterface;
use PoPSchema\Comments\TypeResolvers\ObjectType\CommentTypeResolver;
use PoPSchema\CustomPosts\TypeHelpers\CustomPostUnionTypeHelpers;
use PoPSchema\CustomPosts\TypeResolvers\UnionType\CustomPostUnionTypeResolver;
use PoPSchema\SchemaCommons\Constants\QueryOptions;
use PoPSchema\SchemaCommons\DataLoading\ReturnTypes;
use PoPSchema\SchemaCommons\FormInputs\OrderFormInput;
use PoPSchema\SchemaCommons\ModuleProcessors\CommonFilterInputContainerModuleProcessor;
use PoPSchema\SchemaCommons\ModuleProcessors\FormInputs\CommonFilterInputModuleProcessor;
use PoPSchema\SchemaCommons\Resolvers\WithLimitFieldArgResolverTrait;

class CommentFieldResolver extends AbstractQueryableFieldResolver
{
    use EnumTypeFieldSchemaDefinitionResolverTrait;
    use WithLimitFieldArgResolverTrait;

    public function __construct(
        TranslationAPIInterface $translationAPI,
        HooksAPIInterface $hooksAPI,
        InstanceManagerInterface $instanceManager,
        FieldQueryInterpreterInterface $fieldQueryInterpreter,
        NameResolverInterface $nameResolver,
        CMSServiceInterface $cmsService,
        SemverHelperServiceInterface $semverHelperService,
        protected CommentTypeAPIInterface $commentTypeAPI,
    ) {
        parent::__construct(
            $translationAPI,
            $hooksAPI,
            $instanceManager,
            $fieldQueryInterpreter,
            $nameResolver,
            $cmsService,
            $semverHelperService,
        );
    }

    public function getObjectTypeResolverClassesToAttachTo(): array
    {
        return [
            CommentTypeResolver::class,
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

    public function getSchemaFieldType(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName): string
    {
        $types = [
            'content' => SchemaDefinition::TYPE_STRING,
            'authorName' => SchemaDefinition::TYPE_STRING,
            'authorURL' => SchemaDefinition::TYPE_URL,
            'authorEmail' => SchemaDefinition::TYPE_EMAIL,
            'customPostID' => SchemaDefinition::TYPE_ID,
            'approved' => SchemaDefinition::TYPE_BOOL,
            'type' => SchemaDefinition::TYPE_STRING,
            'status' => SchemaDefinition::TYPE_ENUM,
            'date' => SchemaDefinition::TYPE_DATE,
            'responseCount' => SchemaDefinition::TYPE_INT,
            'responseCountForAdmin' => SchemaDefinition::TYPE_INT,
        ];
        return $types[$fieldName] ?? parent::getSchemaFieldType($objectTypeResolver, $fieldName);
    }

    public function getSchemaFieldTypeModifiers(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName): ?int
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
                => parent::getSchemaFieldTypeModifiers($objectTypeResolver, $fieldName),
        };
    }

    public function getSchemaFieldDescription(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName): ?string
    {
        $descriptions = [
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
        ];
        return $descriptions[$fieldName] ?? parent::getSchemaFieldDescription($objectTypeResolver, $fieldName);
    }

    public function getFieldDataFilteringModule(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName): ?array
    {
        return match ($fieldName) {
            'responses' => [CommentFilterInputContainerModuleProcessor::class, CommentFilterInputContainerModuleProcessor::MODULE_FILTERINPUTCONTAINER_RESPONSES],
            'responseCount' => [CommentFilterInputContainerModuleProcessor::class, CommentFilterInputContainerModuleProcessor::MODULE_FILTERINPUTCONTAINER_RESPONSECOUNT],
            'responsesForAdmin' => [CommentFilterInputContainerModuleProcessor::class, CommentFilterInputContainerModuleProcessor::MODULE_FILTERINPUTCONTAINER_ADMINRESPONSES],
            'responseCountForAdmin' => [CommentFilterInputContainerModuleProcessor::class, CommentFilterInputContainerModuleProcessor::MODULE_FILTERINPUTCONTAINER_ADMINRESPONSECOUNT],
            'date' => [CommonFilterInputContainerModuleProcessor::class, CommonFilterInputContainerModuleProcessor::MODULE_FILTERINPUTCONTAINER_GMTDATE_AS_STRING],
            default => parent::getFieldDataFilteringModule($objectTypeResolver, $fieldName),
        };
    }

    protected function getFieldDataFilteringDefaultValues(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName): array
    {
        switch ($fieldName) {
            case 'responses':
            case 'responsesForAdmin':
                $limitFilterInputName = FilterInputHelper::getFilterInputName([
                    CommonFilterInputModuleProcessor::class,
                    CommonFilterInputModuleProcessor::MODULE_FILTERINPUT_LIMIT
                ]);
                // Order by descending date
                $orderFilterInputName = FilterInputHelper::getFilterInputName([
                    CommonFilterInputModuleProcessor::class,
                    CommonFilterInputModuleProcessor::MODULE_FILTERINPUT_ORDER
                ]);
                $orderBy = $this->nameResolver->getName('popcms:dbcolumn:orderby:comments:date');
                $order = 'DESC';
                return [
                    $limitFilterInputName => ComponentConfiguration::getCustomPostCommentOrCommentResponseListDefaultLimit(),
                    $orderFilterInputName => $orderBy . OrderFormInput::SEPARATOR . $order,
                ];
        }
        return parent::getFieldDataFilteringDefaultValues($objectTypeResolver, $fieldName);
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

    protected function getSchemaDefinitionEnumName(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName): ?string
    {
        switch ($fieldName) {
            case 'status':
                /**
                 * @var CommentStatusEnum
                 */
                $commentStatusEnum = $this->instanceManager->getInstance(CommentStatusEnum::class);
                return $commentStatusEnum->getName();
        }
        return null;
    }

    protected function getSchemaDefinitionEnumValues(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName): ?array
    {
        switch ($fieldName) {
            case 'status':
                /**
                 * @var CommentStatusEnum
                 */
                $commentStatusEnum = $this->instanceManager->getInstance(CommentStatusEnum::class);
                return $commentStatusEnum->getValues();
        }
        return null;
    }

    protected function getSchemaDefinitionEnumValueDescriptions(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName): ?array
    {
        switch ($fieldName) {
            case 'status':
                /**
                 * @var CommentStatusEnum
                 */
                $commentStatusEnum = $this->instanceManager->getInstance(CommentStatusEnum::class);
                return $commentStatusEnum->getDescriptions();
        }
        return null;
    }

    /**
     * @param array<string, mixed> $fieldArgs
     * @param array<string, mixed>|null $variables
     * @param array<string, mixed>|null $expressions
     * @param array<string, mixed> $options
     */
    public function resolveValue(
        ObjectTypeResolverInterface $objectTypeResolver,
        object $resultItem,
        string $fieldName,
        array $fieldArgs = [],
        ?array $variables = null,
        ?array $expressions = null,
        array $options = []
    ): mixed {
        $comment = $resultItem;
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
                $dateFormatter = DateFormatterFacade::getInstance();
                return $dateFormatter->format(
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

        return parent::resolveValue($objectTypeResolver, $resultItem, $fieldName, $fieldArgs, $variables, $expressions, $options);
    }

    public function getFieldTypeResolverClass(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName): ?string
    {
        switch ($fieldName) {
            case 'customPost':
                return CustomPostUnionTypeHelpers::getCustomPostUnionOrTargetObjectTypeResolverClass(CustomPostUnionTypeResolver::class);

            case 'parent':
            case 'responses':
            case 'responsesForAdmin':
                return CommentTypeResolver::class;
        }

        return parent::getFieldTypeResolverClass($objectTypeResolver, $fieldName);
    }
}

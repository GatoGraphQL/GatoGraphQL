<?php

declare(strict_types=1);

namespace PoPSchema\Comments\FieldResolvers\InterfaceType;

use PoP\ComponentModel\FieldResolvers\InterfaceType\AbstractQueryableSchemaInterfaceTypeFieldResolver;
use PoP\ComponentModel\FilterInput\FilterInputHelper;
use PoP\ComponentModel\Schema\SchemaDefinition;
use PoP\ComponentModel\Schema\SchemaTypeModifiers;
use PoPSchema\Comments\ComponentConfiguration;
use PoPSchema\Comments\ModuleProcessors\CommentFilterInputContainerModuleProcessor;
use PoPSchema\Comments\TypeResolvers\InterfaceType\CommentableInterfaceTypeResolver;
use PoPSchema\Comments\TypeResolvers\ObjectType\CommentObjectTypeResolver;
use PoPSchema\SchemaCommons\FormInputs\OrderFormInput;
use PoPSchema\SchemaCommons\ModuleProcessors\FormInputs\CommonFilterInputModuleProcessor;
use PoPSchema\SchemaCommons\Resolvers\WithLimitFieldArgResolverTrait;

class CommentableInterfaceTypeFieldResolver extends AbstractQueryableSchemaInterfaceTypeFieldResolver
{
    use WithLimitFieldArgResolverTrait;

    public function getInterfaceTypeResolverClassesToAttachTo(): array
    {
        return [
            CommentableInterfaceTypeResolver::class,
        ];
    }

    public function getFieldNamesToImplement(): array
    {
        return [
            'areCommentsOpen',
            'hasComments',
            'commentCount',
            'comments',
            'commentCountForAdmin',
            'commentsForAdmin',
        ];
    }

    public function getSchemaFieldType(string $fieldName): string
    {
        $types = [
            'areCommentsOpen' => SchemaDefinition::TYPE_BOOL,
            'hasComments' => SchemaDefinition::TYPE_BOOL,
            'commentCount' => SchemaDefinition::TYPE_INT,
            'commentCountForAdmin' => SchemaDefinition::TYPE_INT,
        ];
        return $types[$fieldName] ?? parent::getSchemaFieldType($fieldName);
    }

    public function getSchemaFieldTypeModifiers(string $fieldName): ?int
    {
        return match ($fieldName) {
            'areCommentsOpen',
            'hasComments',
            'commentCount',
            'commentCountForAdmin'
                => SchemaTypeModifiers::NON_NULLABLE,
            'comments',
            'commentsForAdmin'
                => SchemaTypeModifiers::NON_NULLABLE | SchemaTypeModifiers::IS_ARRAY,
            default
                => parent::getSchemaFieldTypeModifiers($fieldName),
        };
    }

    public function getSchemaFieldDescription(string $fieldName): ?string
    {
        $descriptions = [
            'areCommentsOpen' => $this->translationAPI->__('Are comments open to be added to the custom post', 'pop-comments'),
            'hasComments' => $this->translationAPI->__('Does the custom post have comments?', 'pop-comments'),
            'commentCount' => $this->translationAPI->__('Number of comments added to the custom post', 'pop-comments'),
            'comments' => $this->translationAPI->__('Comments added to the custom post', 'pop-comments'),
            'commentCountForAdmin' => $this->translationAPI->__('[Unrestricted] Number of comments added to the custom post', 'pop-comments'),
            'commentsForAdmin' => $this->translationAPI->__('[Unrestricted] Comments added to the custom post', 'pop-comments'),
        ];
        return $descriptions[$fieldName] ?? parent::getSchemaFieldDescription($fieldName);
    }

    public function getFieldDataFilteringModule(string $fieldName): ?array
    {
        return match ($fieldName) {
            'comments' => [
                CommentFilterInputContainerModuleProcessor::class,
                CommentFilterInputContainerModuleProcessor::MODULE_FILTERINPUTCONTAINER_CUSTOMPOST_COMMENTS
            ],
            'commentCount' => [
                CommentFilterInputContainerModuleProcessor::class,
                CommentFilterInputContainerModuleProcessor::MODULE_FILTERINPUTCONTAINER_CUSTOMPOST_COMMENTCOUNT
            ],
            'commentsForAdmin' => [
                CommentFilterInputContainerModuleProcessor::class,
                CommentFilterInputContainerModuleProcessor::MODULE_FILTERINPUTCONTAINER_CUSTOMPOST_ADMINCOMMENTS
            ],
            'commentCountForAdmin' => [
                CommentFilterInputContainerModuleProcessor::class,
                CommentFilterInputContainerModuleProcessor::MODULE_FILTERINPUTCONTAINER_CUSTOMPOST_ADMINCOMMENTCOUNT
            ],
            default => parent::getFieldDataFilteringModule($fieldName),
        };
    }

    protected function getFieldDataFilteringDefaultValues(string $fieldName): array
    {
        $parentIDFilterInputName = FilterInputHelper::getFilterInputName([
            CommonFilterInputModuleProcessor::class,
            CommonFilterInputModuleProcessor::MODULE_FILTERINPUT_PARENT_ID
        ]);
        $filterInputNameDefaultValues = [
            // By default retrieve the top level comments (with ID => 0)
            $parentIDFilterInputName => 0,
        ];
        switch ($fieldName) {
            case 'comments':
            case 'commentsForAdmin':
                $limitFilterInputName = FilterInputHelper::getFilterInputName([
                    CommonFilterInputModuleProcessor::class,
                    CommonFilterInputModuleProcessor::MODULE_FILTERINPUT_LIMIT
                ]);
                $orderFilterInputName = FilterInputHelper::getFilterInputName([
                    CommonFilterInputModuleProcessor::class,
                    CommonFilterInputModuleProcessor::MODULE_FILTERINPUT_ORDER
                ]);
                // Order by descending date
                $orderBy = $this->nameResolver->getName('popcms:dbcolumn:orderby:comments:date');
                $order = 'DESC';
                return array_merge(
                    $filterInputNameDefaultValues,
                    [
                        $limitFilterInputName => ComponentConfiguration::getCustomPostCommentOrCommentResponseListDefaultLimit(),
                        $orderFilterInputName => $orderBy . OrderFormInput::SEPARATOR . $order,
                    ]
                );
            case 'commentCount':
            case 'commentCountForAdmin':
                return $filterInputNameDefaultValues;
        }
        return parent::getFieldDataFilteringDefaultValues($fieldName);
    }

    /**
     * Validate the constraints for a field argument
     *
     * @return string[] Error messages
     */
    public function validateFieldArgument(
        string $fieldName,
        string $fieldArgName,
        mixed $fieldArgValue
    ): array {
        $errors = parent::validateFieldArgument(
            $fieldName,
            $fieldArgName,
            $fieldArgValue,
        );

        // Check the "limit" fieldArg
        switch ($fieldName) {
            case 'comments':
            case 'commentsForAdmin':
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

    public function getFieldTypeResolverClass(string $fieldName): ?string
    {
        switch ($fieldName) {
            case 'comments':
            case 'commentsForAdmin':
                return CommentObjectTypeResolver::class;
        }

        return parent::getFieldTypeResolverClass($fieldName);
    }
}

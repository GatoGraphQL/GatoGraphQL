<?php

declare(strict_types=1);

namespace PoPSchema\Comments\FieldInterfaceResolvers;

use PoP\ComponentModel\FieldInterfaceResolvers\AbstractQueryableSchemaFieldInterfaceResolver;
use PoP\ComponentModel\Schema\SchemaDefinition;
use PoP\ComponentModel\Schema\SchemaTypeModifiers;
use PoPSchema\Comments\ComponentConfiguration;
use PoPSchema\Comments\ModuleProcessors\CommentFilterInputContainerModuleProcessor;
use PoPSchema\SchemaCommons\FormInputs\OrderFormInput;
use PoPSchema\SchemaCommons\ModuleProcessors\FormInputs\CommonFilterInputModuleProcessor;
use PoP\ComponentModel\FilterInput\FilterInputHelper;

class CommentableFieldInterfaceResolver extends AbstractQueryableSchemaFieldInterfaceResolver
{
    public function getInterfaceName(): string
    {
        return 'Commentable';
    }

    public function getSchemaInterfaceDescription(): ?string
    {
        return $this->translationAPI->__('The entity can receive comments', 'comments');
    }

    public function getFieldNamesToImplement(): array
    {
        return [
            'areCommentsOpen',
            'hasComments',
            'commentCount',
            'comments',
            'unrestrictedCommentCount',
            'unrestrictedComments',
        ];
    }

    public function getSchemaFieldType(string $fieldName): string
    {
        $types = [
            'areCommentsOpen' => SchemaDefinition::TYPE_BOOL,
            'hasComments' => SchemaDefinition::TYPE_BOOL,
            'commentCount' => SchemaDefinition::TYPE_INT,
            'comments' => SchemaDefinition::TYPE_ID,
            'unrestrictedCommentCount' => SchemaDefinition::TYPE_INT,
            'unrestrictedComments' => SchemaDefinition::TYPE_ID,
        ];
        return $types[$fieldName] ?? parent::getSchemaFieldType($fieldName);
    }

    public function getSchemaFieldTypeModifiers(string $fieldName): ?int
    {
        return match ($fieldName) {
            'areCommentsOpen',
            'hasComments',
            'commentCount',
            'unrestrictedCommentCount'
                => SchemaTypeModifiers::NON_NULLABLE,
            'comments',
            'unrestrictedComments'
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
            'unrestrictedCommentCount' => $this->translationAPI->__('[Unrestricted] Number of comments added to the custom post', 'pop-comments'),
            'unrestrictedComments' => $this->translationAPI->__('[Unrestricted] Comments added to the custom post', 'pop-comments'),
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
            'unrestrictedComments' => [
                CommentFilterInputContainerModuleProcessor::class,
                CommentFilterInputContainerModuleProcessor::MODULE_FILTERINPUTCONTAINER_CUSTOMPOST_ADMINCOMMENTS
            ],
            'unrestrictedCommentCount' => [
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
            case 'unrestrictedComments':
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
            case 'unrestrictedCommentCount':
                return $filterInputNameDefaultValues;
        }
        return parent::getFieldDataFilteringDefaultValues($fieldName);
    }
}

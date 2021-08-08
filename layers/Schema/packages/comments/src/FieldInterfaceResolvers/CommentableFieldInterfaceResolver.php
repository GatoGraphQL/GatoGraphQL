<?php

declare(strict_types=1);

namespace PoPSchema\Comments\FieldInterfaceResolvers;

use PoP\ComponentModel\FieldInterfaceResolvers\AbstractQueryableSchemaFieldInterfaceResolver;
use PoP\ComponentModel\Schema\SchemaDefinition;
use PoP\ComponentModel\Schema\SchemaTypeModifiers;
use PoPSchema\Comments\ModuleProcessors\CommentFilterInputContainerModuleProcessor;

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
            'commentCount',
            'hasComments',
            'comments',
        ];
    }

    public function getSchemaFieldType(string $fieldName): string
    {
        $types = [
            'areCommentsOpen' => SchemaDefinition::TYPE_BOOL,
            'commentCount' => SchemaDefinition::TYPE_INT,
            'hasComments' => SchemaDefinition::TYPE_BOOL,
            'comments' => SchemaDefinition::TYPE_ID,
        ];
        return $types[$fieldName] ?? parent::getSchemaFieldType($fieldName);
    }

    public function getSchemaFieldTypeModifiers(string $fieldName): ?int
    {
        return match ($fieldName) {
            'areCommentsOpen',
            'commentCount',
            'hasComments'
                => SchemaTypeModifiers::NON_NULLABLE,
            'comments'
                => SchemaTypeModifiers::NON_NULLABLE | SchemaTypeModifiers::IS_ARRAY,
            default
                => parent::getSchemaFieldTypeModifiers($fieldName),
        };
    }

    public function getSchemaFieldDescription(string $fieldName): ?string
    {
        $descriptions = [
            'areCommentsOpen' => $this->translationAPI->__('Are comments open to be added to the custom post', 'pop-comments'),
            'commentCount' => $this->translationAPI->__('Number of comments added to the custom post', 'pop-comments'),
            'hasComments' => $this->translationAPI->__('Does the custom post have comments?', 'pop-comments'),
            'comments' => $this->translationAPI->__('Comments added to the custom post', 'pop-comments'),
        ];
        return $descriptions[$fieldName] ?? parent::getSchemaFieldDescription($fieldName);
    }

    protected function getFieldDataFilteringModule(string $fieldName): ?array
    {
        return match ($fieldName) {
            'comments' => [
                CommentFilterInputContainerModuleProcessor::class,
                CommentFilterInputContainerModuleProcessor::MODULE_FILTERINNER_COMMENTS
            ],
            default => parent::getFieldDataFilteringModule($fieldName),
        };
    }

    public function getSchemaFieldArgs(string $fieldName): array
    {
        $schemaFieldArgs = parent::getSchemaFieldArgs($fieldName);
        switch ($fieldName) {
            case 'comments':
                // Retrieve all the schema definitions for the filter inputs
                $filterDataloadingModule = [
                    CommentFilterInputContainerModuleProcessor::class,
                    CommentFilterInputContainerModuleProcessor::MODULE_FILTERINNER_COMMENTS
                ];
                return array_merge(
                    $schemaFieldArgs,
                    $this->getFilterSchemaDefinitionItems($filterDataloadingModule)
                );
        }
        return $schemaFieldArgs;
    }
}

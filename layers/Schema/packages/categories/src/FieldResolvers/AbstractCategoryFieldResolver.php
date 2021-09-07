<?php

declare(strict_types=1);

namespace PoPSchema\Categories\FieldResolvers;

use PoP\ComponentModel\FieldInterfaceResolvers\FieldInterfaceSchemaDefinitionResolverInterface;
use PoP\ComponentModel\FieldResolvers\AbstractDBDataFieldResolver;
use PoP\ComponentModel\FieldResolvers\FieldSchemaDefinitionResolverInterface;
use PoP\ComponentModel\Schema\SchemaDefinition;
use PoP\ComponentModel\Schema\SchemaTypeModifiers;
use PoP\ComponentModel\TypeResolvers\RelationalTypeResolverInterface;
use PoPSchema\Categories\ComponentContracts\CategoryAPIRequestedContractTrait;
use PoPSchema\QueriedObject\FieldInterfaceResolvers\QueryableFieldInterfaceResolver;

abstract class AbstractCategoryFieldResolver extends AbstractDBDataFieldResolver
{
    use CategoryAPIRequestedContractTrait;

    public function getImplementedFieldInterfaceResolverClasses(): array
    {
        return [
            QueryableFieldInterfaceResolver::class,
        ];
    }

    public function getFieldNamesToResolve(): array
    {
        return [
            'url',
            'urlPath',
            'slug',
            'name',
            'description',
            'count',
            'parentCategory',
        ];
    }

    /**
     * Get the Schema Definition from the Interface
     */
    protected function getFieldInterfaceSchemaDefinitionResolverClass(
        RelationalTypeResolverInterface $relationalTypeResolver,
        string $fieldName
    ): ?string {
        return match ($fieldName) {
            'url',
            'urlPath',
            'slug'
                => QueryableFieldInterfaceResolver::class,
            default
                => parent::getFieldInterfaceSchemaDefinitionResolverClass($relationalTypeResolver, $fieldName),
        };
    }

    public function getSchemaFieldType(RelationalTypeResolverInterface $relationalTypeResolver, string $fieldName): string
    {
        $types = [
            'name' => SchemaDefinition::TYPE_STRING,
            'description' => SchemaDefinition::TYPE_STRING,
            'count' => SchemaDefinition::TYPE_INT,
        ];
        return $types[$fieldName] ?? parent::getSchemaFieldType($relationalTypeResolver, $fieldName);
    }

    public function getSchemaFieldTypeModifiers(RelationalTypeResolverInterface $relationalTypeResolver, string $fieldName): ?int
    {
        return match ($fieldName) {
            'name',
            'count'
                => SchemaTypeModifiers::NON_NULLABLE,
            default
                => parent::getSchemaFieldTypeModifiers($relationalTypeResolver, $fieldName),
        };
    }

    public function getSchemaFieldDescription(RelationalTypeResolverInterface $relationalTypeResolver, string $fieldName): ?string
    {
        $descriptions = [
            'url' => $this->translationAPI->__('Category URL', 'pop-categories'),
            'urlPath' => $this->translationAPI->__('Category URL path', 'pop-categories'),
            'slug' => $this->translationAPI->__('Category slug', 'pop-categories'),
            'name' => $this->translationAPI->__('Category', 'pop-categories'),
            'description' => $this->translationAPI->__('Category description', 'pop-categories'),
            'parentCategory' => $this->translationAPI->__('Parent category (if this category is a child of another one)', 'pop-categories'),
            'count' => $this->translationAPI->__('Number of custom posts containing this category', 'pop-categories'),
        ];
        return $descriptions[$fieldName] ?? parent::getSchemaFieldDescription($relationalTypeResolver, $fieldName);
    }

    /**
     * @param array<string, mixed> $fieldArgs
     * @param array<string, mixed>|null $variables
     * @param array<string, mixed>|null $expressions
     * @param array<string, mixed> $options
     */
    public function resolveValue(
        RelationalTypeResolverInterface $relationalTypeResolver,
        object $resultItem,
        string $fieldName,
        array $fieldArgs = [],
        ?array $variables = null,
        ?array $expressions = null,
        array $options = []
    ): mixed {
        $categoryTypeAPI = $this->getCategoryTypeAPI();
        $category = $resultItem;
        switch ($fieldName) {
            case 'url':
                return $categoryTypeAPI->getCategoryURL($relationalTypeResolver->getID($category));

            case 'urlPath':
                return $categoryTypeAPI->getCategoryURLPath($relationalTypeResolver->getID($category));

            case 'name':
                return $categoryTypeAPI->getCategoryName($relationalTypeResolver->getID($category));

            case 'slug':
                return $categoryTypeAPI->getCategorySlug($category);

            case 'description':
                return $categoryTypeAPI->getCategoryDescription($category);

            case 'parentCategory':
                return $categoryTypeAPI->getCategoryParentID($relationalTypeResolver->getID($category));

            case 'count':
                return $categoryTypeAPI->getCategoryItemCount($category);
        }

        return parent::resolveValue($relationalTypeResolver, $resultItem, $fieldName, $fieldArgs, $variables, $expressions, $options);
    }

    public function getFieldTypeResolverClass(RelationalTypeResolverInterface $relationalTypeResolver, string $fieldName): ?string
    {
        switch ($fieldName) {
            case 'parentCategory':
                return $this->getCategoryTypeResolverClass();
        }

        return parent::getFieldTypeResolverClass($relationalTypeResolver, $fieldName);
    }
}

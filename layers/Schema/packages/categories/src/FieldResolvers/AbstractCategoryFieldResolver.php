<?php

declare(strict_types=1);

namespace PoPSchema\Categories\FieldResolvers;

use PoP\ComponentModel\Schema\SchemaDefinition;
use PoP\ComponentModel\TypeResolvers\TypeResolverInterface;
use PoP\ComponentModel\FieldResolvers\AbstractDBDataFieldResolver;
use PoPSchema\QueriedObject\FieldInterfaceResolvers\QueryableFieldInterfaceResolver;
use PoPSchema\Categories\ComponentContracts\CategoryAPIRequestedContractTrait;

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
            'name',
            'slug',
            'description',
            'parent',
            'count',
        ];
    }

    public function getSchemaFieldType(TypeResolverInterface $typeResolver, string $fieldName): string
    {
        $types = [
            'url' => SchemaDefinition::TYPE_URL,
            'name' => SchemaDefinition::TYPE_STRING,
            'slug' => SchemaDefinition::TYPE_STRING,
            'description' => SchemaDefinition::TYPE_STRING,
            'parent' => SchemaDefinition::TYPE_ID,
            'count' => SchemaDefinition::TYPE_INT,
        ];
        return $types[$fieldName] ?? parent::getSchemaFieldType($typeResolver, $fieldName);
    }

    public function getSchemaFieldDescription(TypeResolverInterface $typeResolver, string $fieldName): ?string
    {
        $descriptions = [
            'url' => $this->translationAPI->__('Category URL', 'pop-categories'),
            'name' => $this->translationAPI->__('Category', 'pop-categories'),
            'slug' => $this->translationAPI->__('Category slug', 'pop-categories'),
            'description' => $this->translationAPI->__('Category description', 'pop-categories'),
            'parent' => $this->translationAPI->__('Parent category (if this category is a child of another one)', 'pop-categories'),
            'count' => $this->translationAPI->__('Number of custom posts containing this category', 'pop-categories'),
        ];
        return $descriptions[$fieldName] ?? parent::getSchemaFieldDescription($typeResolver, $fieldName);
    }

    /**
     * @param array<string, mixed> $fieldArgs
     * @param array<string, mixed>|null $variables
     * @param array<string, mixed>|null $expressions
     * @param array<string, mixed> $options
     */
    public function resolveValue(
        TypeResolverInterface $typeResolver,
        object $resultItem,
        string $fieldName,
        array $fieldArgs = [],
        ?array $variables = null,
        ?array $expressions = null,
        array $options = []
    ): mixed {
        $categoryTypeAPI = $this->getTypeAPI();
        $category = $resultItem;
        switch ($fieldName) {
            case 'url':
                return $categoryTypeAPI->getCategoryURL($typeResolver->getID($category));

            case 'name':
                return $categoryTypeAPI->getCategoryName($typeResolver->getID($category));

            case 'slug':
                return $categoryTypeAPI->getCategorySlug($category);

            case 'description':
                return $categoryTypeAPI->getCategoryDescription($category);

            case 'parent':
                return $categoryTypeAPI->getCategoryParentID($typeResolver->getID($category));

            case 'count':
                return $categoryTypeAPI->getCategoryItemCount($category);
        }

        return parent::resolveValue($typeResolver, $resultItem, $fieldName, $fieldArgs, $variables, $expressions, $options);
    }

    public function resolveFieldTypeResolverClass(TypeResolverInterface $typeResolver, string $fieldName): ?string
    {
        switch ($fieldName) {
            case 'parent':
                return $this->getTypeResolverClass();
        }

        return parent::resolveFieldTypeResolverClass($typeResolver, $fieldName);
    }
}

<?php

declare(strict_types=1);

namespace PoPSchema\CustomPostCategoryMutations\FieldResolvers;

use PoP\ComponentModel\FieldResolvers\AbstractDBDataFieldResolver;
use PoP\ComponentModel\Schema\SchemaDefinition;
use PoP\ComponentModel\Schema\SchemaTypeModifiers;
use PoP\ComponentModel\TypeResolvers\RelationalTypeResolverInterface;
use PoP\ComponentModel\TypeResolvers\TypeResolverInterface;
use PoPSchema\CustomPostCategoryMutations\MutationResolvers\MutationInputProperties;

abstract class AbstractCustomPostFieldResolver extends AbstractDBDataFieldResolver
{
    use SetCategoriesOnCustomPostFieldResolverTrait;

    public function getClassesToAttachTo(): array
    {
        return [
            $this->getCustomPostTypeResolverClass(),
        ];
    }

    public function getFieldNamesToResolve(): array
    {
        return [
            'setCategories',
        ];
    }

    public function getSchemaFieldDescription(RelationalTypeResolverInterface $relationalTypeResolver, string $fieldName): ?string
    {
        $descriptions = [
            'setCategories' => sprintf(
                $this->translationAPI->__('Set categories on the %s', 'custompost-category-mutations'),
                $this->getEntityName()
            )
        ];
        return $descriptions[$fieldName] ?? parent::getSchemaFieldDescription($relationalTypeResolver, $fieldName);
    }

    public function getSchemaFieldType(RelationalTypeResolverInterface $relationalTypeResolver, string $fieldName): string
    {
        $types = [
            'setCategories' => SchemaDefinition::TYPE_ID,
        ];
        return $types[$fieldName] ?? parent::getSchemaFieldType($relationalTypeResolver, $fieldName);
    }

    public function getSchemaFieldTypeModifiers(RelationalTypeResolverInterface $relationalTypeResolver, string $fieldName): ?int
    {
        $nonNullableFieldNames = [
            'setCategories',
        ];
        if (in_array($fieldName, $nonNullableFieldNames)) {
            return SchemaTypeModifiers::NON_NULLABLE;
        }
        return parent::getSchemaFieldTypeModifiers($relationalTypeResolver, $fieldName);
    }

    public function getSchemaFieldArgs(RelationalTypeResolverInterface $relationalTypeResolver, string $fieldName): array
    {
        switch ($fieldName) {
            case 'setCategories':
                $categoryTypeResolverClass = $this->getCategoryTypeResolverClass();
                /** @var TypeResolverInterface */
                $categoryTypeResolver = $this->instanceManager->getInstance($categoryTypeResolverClass);
                return [
                    [
                        SchemaDefinition::ARGNAME_NAME => MutationInputProperties::CATEGORY_IDS,
                        SchemaDefinition::ARGNAME_TYPE => SchemaDefinition::TYPE_ID,
                        SchemaDefinition::ARGNAME_IS_ARRAY => true,
                        SchemaDefinition::ARGNAME_DESCRIPTION => sprintf(
                            $this->translationAPI->__('The IDs of the categories to set, of type \'%s\'', 'custompost-category-mutations'),
                            $categoryTypeResolver->getTypeName()
                        ),
                        SchemaDefinition::ARGNAME_MANDATORY => true,
                    ],
                    [
                        SchemaDefinition::ARGNAME_NAME => MutationInputProperties::APPEND,
                        SchemaDefinition::ARGNAME_TYPE => SchemaDefinition::TYPE_BOOL,
                        SchemaDefinition::ARGNAME_DESCRIPTION => $this->translationAPI->__('Append the categories to the existing ones?', 'custompost-category-mutations'),
                        SchemaDefinition::ARGNAME_DEFAULT_VALUE => false,
                    ],
                ];
        }
        return parent::getSchemaFieldArgs($relationalTypeResolver, $fieldName);
    }

    /**
     * Validated the mutation on the resultItem because the ID
     * is obtained from the same object, so it's not originally
     * present in $form_data
     */
    public function validateMutationOnResultItem(
        RelationalTypeResolverInterface $relationalTypeResolver,
        string $fieldName
    ): bool {
        switch ($fieldName) {
            case 'setCategories':
                return true;
        }
        return parent::validateMutationOnResultItem($relationalTypeResolver, $fieldName);
    }

    protected function getFieldArgsToExecuteMutation(
        array $fieldArgs,
        RelationalTypeResolverInterface $relationalTypeResolver,
        object $resultItem,
        string $fieldName
    ): array {
        $fieldArgs = parent::getFieldArgsToExecuteMutation(
            $fieldArgs,
            $relationalTypeResolver,
            $resultItem,
            $fieldName
        );
        $customPost = $resultItem;
        switch ($fieldName) {
            case 'setCategories':
                $fieldArgs[MutationInputProperties::CUSTOMPOST_ID] = $relationalTypeResolver->getID($customPost);
                break;
        }

        return $fieldArgs;
    }

    public function resolveFieldMutationResolverClass(RelationalTypeResolverInterface $relationalTypeResolver, string $fieldName): ?string
    {
        switch ($fieldName) {
            case 'setCategories':
                return $this->getTypeMutationResolverClass();
        }

        return parent::resolveFieldMutationResolverClass($relationalTypeResolver, $fieldName);
    }

    public function resolveFieldTypeResolverClass(RelationalTypeResolverInterface $relationalTypeResolver, string $fieldName): ?string
    {
        switch ($fieldName) {
            case 'setCategories':
                return $this->getCustomPostTypeResolverClass();
        }

        return parent::resolveFieldTypeResolverClass($relationalTypeResolver, $fieldName);
    }
}

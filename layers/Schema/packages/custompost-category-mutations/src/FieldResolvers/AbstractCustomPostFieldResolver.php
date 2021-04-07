<?php

declare(strict_types=1);

namespace PoPSchema\CustomPostCategoryMutations\FieldResolvers;

use PoP\ComponentModel\Facades\Instances\InstanceManagerFacade;
use PoP\ComponentModel\FieldResolvers\AbstractDBDataFieldResolver;
use PoP\ComponentModel\Schema\SchemaDefinition;
use PoP\ComponentModel\Schema\TypeCastingHelpers;
use PoP\ComponentModel\TypeResolvers\TypeResolverInterface;
use PoP\Translation\Facades\TranslationAPIFacade;
use PoPSchema\CustomPostCategoryMutations\MutationResolvers\MutationInputProperties;

abstract class AbstractCustomPostFieldResolver extends AbstractDBDataFieldResolver
{
    use SetCategoriesOnCustomPostFieldResolverTrait;

    public function getClassesToAttachTo(): array
    {
        return [
            $this->getTypeResolverClass(),
        ];
    }

    public function getFieldNamesToResolve(): array
    {
        return [
            'setCategories',
        ];
    }

    public function getSchemaFieldDescription(TypeResolverInterface $typeResolver, string $fieldName): ?string
    {
        $translationAPI = TranslationAPIFacade::getInstance();
        $descriptions = [
            'setCategories' => sprintf(
                $translationAPI->__('Set categories on the %s', 'custompost-category-mutations'),
                $this->getEntityName()
            )
        ];
        return $descriptions[$fieldName] ?? parent::getSchemaFieldDescription($typeResolver, $fieldName);
    }

    public function getSchemaFieldType(TypeResolverInterface $typeResolver, string $fieldName): ?string
    {
        $types = [
            'setCategories' => SchemaDefinition::TYPE_ID,
        ];
        return $types[$fieldName] ?? parent::getSchemaFieldType($typeResolver, $fieldName);
    }

    public function isSchemaFieldResponseNonNullable(TypeResolverInterface $typeResolver, string $fieldName): bool
    {
        $nonNullableFieldNames = [
            'setCategories',
        ];
        if (in_array($fieldName, $nonNullableFieldNames)) {
            return true;
        }
        return parent::isSchemaFieldResponseNonNullable($typeResolver, $fieldName);
    }

    public function getSchemaFieldArgs(TypeResolverInterface $typeResolver, string $fieldName): array
    {
        $translationAPI = TranslationAPIFacade::getInstance();
        switch ($fieldName) {
            case 'setCategories':
                $instanceManager = InstanceManagerFacade::getInstance();
                $categoryTypeResolverClass = $this->getCategoryTypeResolverClass();
                /** @var TypeResolverInterface */
                $categoryTypeResolver = $instanceManager->getInstance($categoryTypeResolverClass);
                return [
                    [
                        SchemaDefinition::ARGNAME_NAME => MutationInputProperties::CATEGORY_IDS,
                        SchemaDefinition::ARGNAME_TYPE => TypeCastingHelpers::makeArray(SchemaDefinition::TYPE_ID),
                        SchemaDefinition::ARGNAME_DESCRIPTION => sprintf(
                            $translationAPI->__('The IDs of the categories to set, of type \'%s\'', 'custompost-category-mutations'),
                            $categoryTypeResolver->getTypeName()
                        ),
                        SchemaDefinition::ARGNAME_MANDATORY => true,
                    ],
                    [
                        SchemaDefinition::ARGNAME_NAME => MutationInputProperties::APPEND,
                        SchemaDefinition::ARGNAME_TYPE => SchemaDefinition::TYPE_BOOL,
                        SchemaDefinition::ARGNAME_DESCRIPTION => $translationAPI->__('Append the categories to the existing ones?', 'custompost-category-mutations'),
                        SchemaDefinition::ARGNAME_DEFAULT_VALUE => false,
                    ],
                ];
        }
        return parent::getSchemaFieldArgs($typeResolver, $fieldName);
    }

    /**
     * Validated the mutation on the resultItem because the ID
     * is obtained from the same object, so it's not originally
     * present in $form_data
     */
    public function validateMutationOnResultItem(
        TypeResolverInterface $typeResolver,
        string $fieldName
    ): bool {
        switch ($fieldName) {
            case 'setCategories':
                return true;
        }
        return parent::validateMutationOnResultItem($typeResolver, $fieldName);
    }

    protected function getFieldArgsToExecuteMutation(
        array $fieldArgs,
        TypeResolverInterface $typeResolver,
        object $resultItem,
        string $fieldName
    ): array {
        $fieldArgs = parent::getFieldArgsToExecuteMutation(
            $fieldArgs,
            $typeResolver,
            $resultItem,
            $fieldName
        );
        $customPost = $resultItem;
        switch ($fieldName) {
            case 'setCategories':
                $fieldArgs[MutationInputProperties::CUSTOMPOST_ID] = $typeResolver->getID($customPost);
                break;
        }

        return $fieldArgs;
    }

    public function resolveFieldMutationResolverClass(TypeResolverInterface $typeResolver, string $fieldName): ?string
    {
        switch ($fieldName) {
            case 'setCategories':
                return $this->getTypeMutationResolverClass();
        }

        return parent::resolveFieldMutationResolverClass($typeResolver, $fieldName);
    }

    public function resolveFieldTypeResolverClass(TypeResolverInterface $typeResolver, string $fieldName): ?string
    {
        switch ($fieldName) {
            case 'setCategories':
                return $this->getTypeResolverClass();
        }

        return parent::resolveFieldTypeResolverClass($typeResolver, $fieldName);
    }
}

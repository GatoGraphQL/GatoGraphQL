<?php

declare(strict_types=1);

namespace PoPSchema\CustomPostTagMutations\FieldResolvers;

use PoP\ComponentModel\FieldResolvers\AbstractDBDataFieldResolver;
use PoP\ComponentModel\Schema\SchemaDefinition;
use PoP\ComponentModel\Schema\TypeCastingHelpers;
use PoP\ComponentModel\TypeResolvers\TypeResolverInterface;
use PoPSchema\CustomPostTagMutations\MutationResolvers\MutationInputProperties;

abstract class AbstractCustomPostFieldResolver extends AbstractDBDataFieldResolver
{
    use SetTagsOnCustomPostFieldResolverTrait;

    public function getClassesToAttachTo(): array
    {
        return [
            $this->getTypeResolverClass(),
        ];
    }

    public function getFieldNamesToResolve(): array
    {
        return [
            'setTags',
        ];
    }

    public function getSchemaFieldDescription(TypeResolverInterface $typeResolver, string $fieldName): ?string
    {
        $descriptions = [
            'setTags' => sprintf(
                $this->translationAPI->__('Set tags on the %s', 'custompost-tag-mutations'),
                $this->getEntityName()
            )
        ];
        return $descriptions[$fieldName] ?? parent::getSchemaFieldDescription($typeResolver, $fieldName);
    }

    public function getSchemaFieldType(TypeResolverInterface $typeResolver, string $fieldName): string
    {
        $types = [
            'setTags' => SchemaDefinition::TYPE_ID,
        ];
        return $types[$fieldName] ?? parent::getSchemaFieldType($typeResolver, $fieldName);
    }

    public function isSchemaFieldResponseNonNullable(TypeResolverInterface $typeResolver, string $fieldName): bool
    {
        $nonNullableFieldNames = [
            'setTags',
        ];
        if (in_array($fieldName, $nonNullableFieldNames)) {
            return true;
        }
        return parent::isSchemaFieldResponseNonNullable($typeResolver, $fieldName);
    }

    public function getSchemaFieldArgs(TypeResolverInterface $typeResolver, string $fieldName): array
    {
        switch ($fieldName) {
            case 'setTags':
                return [
                    [
                        SchemaDefinition::ARGNAME_NAME => MutationInputProperties::TAGS,
                        SchemaDefinition::ARGNAME_TYPE => TypeCastingHelpers::makeArray(SchemaDefinition::TYPE_STRING),
                        SchemaDefinition::ARGNAME_DESCRIPTION => $this->translationAPI->__('The tags to set', 'custompost-tag-mutations'),
                        SchemaDefinition::ARGNAME_MANDATORY => true,
                    ],
                    [
                        SchemaDefinition::ARGNAME_NAME => MutationInputProperties::APPEND,
                        SchemaDefinition::ARGNAME_TYPE => SchemaDefinition::TYPE_BOOL,
                        SchemaDefinition::ARGNAME_DESCRIPTION => $this->translationAPI->__('Append the tags to the existing ones?', 'custompost-tag-mutations'),
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
            case 'setTags':
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
            case 'setTags':
                $fieldArgs[MutationInputProperties::CUSTOMPOST_ID] = $typeResolver->getID($customPost);
                break;
        }

        return $fieldArgs;
    }

    public function resolveFieldMutationResolverClass(TypeResolverInterface $typeResolver, string $fieldName): ?string
    {
        switch ($fieldName) {
            case 'setTags':
                return $this->getTypeMutationResolverClass();
        }

        return parent::resolveFieldMutationResolverClass($typeResolver, $fieldName);
    }

    public function resolveFieldTypeResolverClass(TypeResolverInterface $typeResolver, string $fieldName): ?string
    {
        switch ($fieldName) {
            case 'setTags':
                return $this->getTypeResolverClass();
        }

        return parent::resolveFieldTypeResolverClass($typeResolver, $fieldName);
    }
}

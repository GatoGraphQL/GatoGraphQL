<?php

declare(strict_types=1);

namespace PoPSchema\CustomPostTagMutations\FieldResolvers\ObjectType;

use PoP\ComponentModel\FieldResolvers\ObjectType\AbstractObjectTypeFieldResolver;
use PoP\ComponentModel\MutationResolvers\MutationResolverInterface;
use PoP\ComponentModel\Schema\SchemaDefinition;
use PoP\ComponentModel\Schema\SchemaTypeModifiers;
use PoP\ComponentModel\TypeResolvers\ConcreteTypeResolverInterface;
use PoP\ComponentModel\TypeResolvers\ObjectType\ObjectTypeResolverInterface;
use PoPSchema\CustomPostTagMutations\MutationResolvers\MutationInputProperties;

abstract class AbstractCustomPostObjectTypeFieldResolver extends AbstractObjectTypeFieldResolver implements SetTagsOnCustomPostObjectTypeFieldResolverInterface
{
    use SetTagsOnCustomPostObjectTypeFieldResolverTrait;

    public function getObjectTypeResolverClassesToAttachTo(): array
    {
        return [
            get_class($this->getCustomPostTypeResolver()),
        ];
    }

    public function getFieldNamesToResolve(): array
    {
        return [
            'setTags',
        ];
    }

    public function getSchemaFieldDescription(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName): ?string
    {
        $descriptions = [
            'setTags' => sprintf(
                $this->translationAPI->__('Set tags on the %s', 'custompost-tag-mutations'),
                $this->getEntityName()
            )
        ];
        return $descriptions[$fieldName] ?? parent::getSchemaFieldDescription($objectTypeResolver, $fieldName);
    }

    public function getSchemaFieldTypeModifiers(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName): ?int
    {
        $nonNullableFieldNames = [
            'setTags',
        ];
        if (in_array($fieldName, $nonNullableFieldNames)) {
            return SchemaTypeModifiers::NON_NULLABLE;
        }
        return parent::getSchemaFieldTypeModifiers($objectTypeResolver, $fieldName);
    }

    public function getSchemaFieldArgs(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName): array
    {
        switch ($fieldName) {
            case 'setTags':
                return [
                    [
                        SchemaDefinition::ARGNAME_NAME => MutationInputProperties::TAGS,
                        SchemaDefinition::ARGNAME_TYPE => SchemaDefinition::TYPE_STRING,
                        SchemaDefinition::ARGNAME_IS_ARRAY => true,
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
        return parent::getSchemaFieldArgs($objectTypeResolver, $fieldName);
    }

    /**
     * Validated the mutation on the object because the ID
     * is obtained from the same object, so it's not originally
     * present in $form_data
     */
    public function validateMutationOnObject(
        ObjectTypeResolverInterface $objectTypeResolver,
        string $fieldName
    ): bool {
        switch ($fieldName) {
            case 'setTags':
                return true;
        }
        return parent::validateMutationOnObject($objectTypeResolver, $fieldName);
    }

    protected function getFieldArgsToExecuteMutation(
        array $fieldArgs,
        ObjectTypeResolverInterface $objectTypeResolver,
        object $object,
        string $fieldName
    ): array {
        $fieldArgs = parent::getFieldArgsToExecuteMutation(
            $fieldArgs,
            $objectTypeResolver,
            $object,
            $fieldName
        );
        $customPost = $object;
        switch ($fieldName) {
            case 'setTags':
                $fieldArgs[MutationInputProperties::CUSTOMPOST_ID] = $objectTypeResolver->getID($customPost);
                break;
        }

        return $fieldArgs;
    }

    public function getFieldMutationResolver(
        ObjectTypeResolverInterface $objectTypeResolver,
        string $fieldName
    ): ?MutationResolverInterface {
        switch ($fieldName) {
            case 'setTags':
                return $this->getSetTagsMutationResolver();
        }

        return parent::getFieldMutationResolver($objectTypeResolver, $fieldName);
    }

    public function getFieldTypeResolver(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName): ConcreteTypeResolverInterface
    {
        switch ($fieldName) {
            case 'setTags':
                return $this->getCustomPostTypeResolver();
        }

        return parent::getFieldTypeResolver($objectTypeResolver, $fieldName);
    }
}

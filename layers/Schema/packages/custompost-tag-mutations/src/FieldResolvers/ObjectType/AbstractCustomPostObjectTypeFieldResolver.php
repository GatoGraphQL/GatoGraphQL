<?php

declare(strict_types=1);

namespace PoPSchema\CustomPostTagMutations\FieldResolvers\ObjectType;

use PoP\ComponentModel\FieldResolvers\ObjectType\AbstractObjectTypeFieldResolver;
use PoP\ComponentModel\MutationResolvers\MutationResolverInterface;
use PoP\ComponentModel\Schema\SchemaTypeModifiers;
use PoP\ComponentModel\TypeResolvers\ConcreteTypeResolverInterface;
use PoP\ComponentModel\TypeResolvers\ObjectType\ObjectTypeResolverInterface;
use PoP\Engine\TypeResolvers\ScalarType\BooleanScalarTypeResolver;
use PoP\Engine\TypeResolvers\ScalarType\StringScalarTypeResolver;
use PoPSchema\CustomPostTagMutations\MutationResolvers\MutationInputProperties;
use Symfony\Contracts\Service\Attribute\Required;

abstract class AbstractCustomPostObjectTypeFieldResolver extends AbstractObjectTypeFieldResolver implements SetTagsOnCustomPostObjectTypeFieldResolverInterface
{
    use SetTagsOnCustomPostObjectTypeFieldResolverTrait;
    
    protected StringScalarTypeResolver $stringScalarTypeResolver;
    protected BooleanScalarTypeResolver $booleanScalarTypeResolver;

    #[Required]
    public function autowireAbstractCustomPostObjectTypeFieldResolver(
        StringScalarTypeResolver $stringScalarTypeResolver,
        BooleanScalarTypeResolver $booleanScalarTypeResolver,
    ): void {
        $this->stringScalarTypeResolver = $stringScalarTypeResolver;
        $this->booleanScalarTypeResolver = $booleanScalarTypeResolver;
    }

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
        return match ($fieldName) {
            'setTags' => sprintf(
                $this->translationAPI->__('Set tags on the %s', 'custompost-tag-mutations'),
                $this->getEntityName()
            ),
            default => parent::getSchemaFieldDescription($objectTypeResolver, $fieldName),
        };
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

    public function getSchemaFieldArgNameResolvers(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName): array
    {
        return match ($fieldName) {
            'setTags' => [
                MutationInputProperties::TAGS => $this->stringScalarTypeResolver,
                MutationInputProperties::APPEND => $this->booleanScalarTypeResolver,
            ],
            default => parent::getSchemaFieldArgNameResolvers($objectTypeResolver, $fieldName),
        };
    }
    
    public function getSchemaFieldArgDescription(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName, string $fieldArgName): ?string
    {
        return match ([$fieldName => $fieldArgName]) {
            ['setTags' => MutationInputProperties::TAGS] => $this->translationAPI->__('The tags to set', 'custompost-tag-mutations'),
            ['setTags' => MutationInputProperties::APPEND] => $this->translationAPI->__('Append the tags to the existing ones?', 'custompost-tag-mutations'),
            default => parent::getSchemaFieldArgDescription($objectTypeResolver, $fieldName, $fieldArgName),
        };
    }
    
    public function getSchemaFieldArgDefaultValue(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName, string $fieldArgName): mixed
    {
        return match ([$fieldName => $fieldArgName]) {
            ['setTags' => MutationInputProperties::APPEND] => false,
            default => parent::getSchemaFieldArgDefaultValue($objectTypeResolver, $fieldName, $fieldArgName),
        };
    }
    
    public function getSchemaFieldArgTypeModifiers(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName, string $fieldArgName): ?int
    {
        return match ([$fieldName => $fieldArgName]) {
            ['setTags' => MutationInputProperties::TAGS] => SchemaTypeModifiers::IS_ARRAY | SchemaTypeModifiers::MANDATORY,
            default => parent::getSchemaFieldArgTypeModifiers($objectTypeResolver, $fieldName, $fieldArgName),
        };
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

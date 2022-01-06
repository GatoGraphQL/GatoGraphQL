<?php

declare(strict_types=1);

namespace PoPSchema\CustomPostCategoryMutations\FieldResolvers\ObjectType;

use PoP\ComponentModel\FieldResolvers\ObjectType\AbstractObjectTypeFieldResolver;
use PoP\ComponentModel\MutationResolvers\MutationResolverInterface;
use PoP\ComponentModel\Schema\SchemaTypeModifiers;
use PoP\ComponentModel\TypeResolvers\ConcreteTypeResolverInterface;
use PoP\ComponentModel\TypeResolvers\ObjectType\ObjectTypeResolverInterface;
use PoPSchema\CustomPostCategoryMutations\MutationResolvers\MutationInputProperties;

abstract class AbstractCustomPostObjectTypeFieldResolver extends AbstractObjectTypeFieldResolver implements SetCategoriesOnCustomPostObjectTypeFieldResolverInterface
{
    use SetCategoriesOnCustomPostObjectTypeFieldResolverTrait;

    public function getObjectTypeResolverClassesToAttachTo(): array
    {
        return [
            get_class($this->getCustomPostObjectTypeResolver()),
        ];
    }

    public function getFieldNamesToResolve(): array
    {
        return [
            'setCategories',
        ];
    }

    public function getFieldDescription(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName): ?string
    {
        return match ($fieldName) {
            'setCategories' => sprintf(
                $this->__('Set categories on the %s', 'custompost-category-mutations'),
                $this->getEntityName()
            ),
            default => parent::getFieldDescription($objectTypeResolver, $fieldName),
        };
    }

    public function getFieldTypeModifiers(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName): int
    {
        return match ($fieldName) {
            'setCategories' => SchemaTypeModifiers::NON_NULLABLE,
            default => parent::getFieldTypeModifiers($objectTypeResolver, $fieldName),
        };
    }

    public function getFieldArgNameTypeResolvers(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName): array
    {
        return match ($fieldName) {
            'setCategories' => [
                'input' => $this->getCustomPostSetCategoriesFilterInputObjectTypeResolver(),
            ],
            default => parent::getFieldArgNameTypeResolvers($objectTypeResolver, $fieldName),
        };
    }

    public function getFieldArgTypeModifiers(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName, string $fieldArgName): int
    {
        return match ([$fieldName => $fieldArgName]) {
            ['setCategories' => 'input'] => SchemaTypeModifiers::MANDATORY,
            default => parent::getFieldArgTypeModifiers($objectTypeResolver, $fieldName, $fieldArgName),
        };
    }

    /**
     * Validated the mutation on the object because the ID
     * is obtained from the same object, so it's not originally
     * present in $form_data
     */
    public function validateMutationOnObject(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName): bool
    {
        return match ($fieldName) {
            'setCategories' => true,
            default => parent::validateMutationOnObject($objectTypeResolver, $fieldName),
        };
    }

    protected function getMutationFieldArgsForObject(
        array $mutationFieldArgs,
        ObjectTypeResolverInterface $objectTypeResolver,
        object $object,
        string $fieldName
    ): array {
        $mutationFieldArgs = parent::getMutationFieldArgsForObject(
            $mutationFieldArgs,
            $objectTypeResolver,
            $object,
            $fieldName
        );
        $customPost = $object;
        switch ($fieldName) {
            case 'setCategories':
                $mutationFieldArgs[MutationInputProperties::CUSTOMPOST_ID] = $objectTypeResolver->getID($customPost);
                break;
        }

        return $mutationFieldArgs;
    }

    public function getFieldMutationResolver(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName): ?MutationResolverInterface
    {
        return match ($fieldName) {
            'setCategories' => $this->getSetCategoriesMutationResolver(),
            default => parent::getFieldMutationResolver($objectTypeResolver, $fieldName),
        };
    }

    public function getFieldTypeResolver(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName): ConcreteTypeResolverInterface
    {
        return match ($fieldName) {
            'setCategories' => $this->getCustomPostObjectTypeResolver(),
            default => parent::getFieldTypeResolver($objectTypeResolver, $fieldName),
        };
    }
}

<?php

declare(strict_types=1);

namespace PoPCMSSchema\CustomPostCategoryMutations\FieldResolvers\ObjectType;

use PoP\ComponentModel\FieldResolvers\ObjectType\AbstractObjectTypeFieldResolver;
use PoP\ComponentModel\MutationResolvers\MutationResolverInterface;
use PoP\ComponentModel\Schema\SchemaTypeModifiers;
use PoP\ComponentModel\TypeResolvers\ConcreteTypeResolverInterface;
use PoP\ComponentModel\TypeResolvers\ObjectType\ObjectTypeResolverInterface;
use PoP\GraphQLParser\Spec\Parser\Ast\ArgumentValue\Literal;
use PoP\GraphQLParser\Spec\Parser\Ast\FieldInterface;
use PoPCMSSchema\CustomPostCategoryMutations\MutationResolvers\MutationInputProperties;

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
     * present in $withArgumentsAST
     */
    public function validateMutationOnObject(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName): bool
    {
        return match ($fieldName) {
            'setCategories' => true,
            default => parent::validateMutationOnObject($objectTypeResolver, $fieldName),
        };
    }

    protected function getMutationFieldForObject(
        FieldInterface $mutationField,
        ObjectTypeResolverInterface $objectTypeResolver,
        object $object,
        string $fieldName,
    ): FieldInterface {
        $mutationField = parent::getMutationFieldForObject(
            $mutationField,
            $objectTypeResolver,
            $object,
            $fieldName
        );
        $customPost = $object;
        switch ($fieldName) {
            case 'setCategories':
                $mutationField->addArgument(new \PoP\GraphQLParser\Spec\Parser\Ast\Argument(MutationInputProperties::CUSTOMPOST_ID, new Literal($objectTypeResolver->getID($customPost), \PoP\GraphQLParser\StaticHelpers\LocationHelper::getNonSpecificLocation()), \PoP\GraphQLParser\StaticHelpers\LocationHelper::getNonSpecificLocation()));
                break;
        }

        return $mutationField;
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

<?php

declare(strict_types=1);

namespace PoPSchema\CustomPostCategoryMutations\FieldResolvers\ObjectType;

use PoP\ComponentModel\FieldResolvers\ObjectType\AbstractQueryableObjectTypeFieldResolver;
use PoP\ComponentModel\MutationResolvers\MutationResolverInterface;
use PoP\ComponentModel\Schema\SchemaTypeModifiers;
use PoP\ComponentModel\TypeResolvers\ConcreteTypeResolverInterface;
use PoP\ComponentModel\TypeResolvers\ObjectType\ObjectTypeResolverInterface;
use PoP\Engine\ComponentConfiguration as EngineComponentConfiguration;
use PoP\Engine\TypeResolvers\ObjectType\RootObjectTypeResolver;
use PoP\Engine\TypeResolvers\ScalarType\BooleanScalarTypeResolver;
use PoP\Engine\TypeResolvers\ScalarType\IDScalarTypeResolver;
use PoPSchema\CustomPostCategoryMutations\MutationResolvers\MutationInputProperties;
use Symfony\Contracts\Service\Attribute\Required;

abstract class AbstractRootObjectTypeFieldResolver extends AbstractQueryableObjectTypeFieldResolver implements SetCategoriesOnCustomPostObjectTypeFieldResolverInterface
{
    use SetCategoriesOnCustomPostObjectTypeFieldResolverTrait;

    protected BooleanScalarTypeResolver $booleanScalarTypeResolver;
    protected IDScalarTypeResolver $idScalarTypeResolver;
    
    #[Required]
    public function autowireAbstractRootObjectTypeFieldResolver(
        BooleanScalarTypeResolver $booleanScalarTypeResolver,
        IDScalarTypeResolver $idScalarTypeResolver,
    ): void {
        $this->booleanScalarTypeResolver = $booleanScalarTypeResolver;
        $this->idScalarTypeResolver = $idScalarTypeResolver;
    }

    public function getObjectTypeResolverClassesToAttachTo(): array
    {
        return [
            RootObjectTypeResolver::class,
        ];
    }

    public function getFieldNamesToResolve(): array
    {
        if (EngineComponentConfiguration::disableRedundantRootTypeMutationFields()) {
            return [];
        }
        return [
            $this->getSetCategoriesFieldName(),
        ];
    }

    abstract protected function getSetCategoriesFieldName(): string;

    public function getSchemaFieldDescription(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName): ?string
    {
        return match ($fieldName) {
            $this->getSetCategoriesFieldName() => sprintf(
                $this->translationAPI->__('Set categories on a %s', 'custompost-category-mutations'),
                $this->getEntityName()
            ),
            default => parent::getSchemaFieldDescription($objectTypeResolver, $fieldName),
        };
    }

    public function getSchemaFieldArgNameResolvers(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName): array
    {
        return match ($fieldName) {
            $this->getSetCategoriesFieldName() => [
                MutationInputProperties::CUSTOMPOST_ID => $this->idScalarTypeResolver,
                MutationInputProperties::CATEGORY_IDS => $this->idScalarTypeResolver,
                MutationInputProperties::APPEND => $this->booleanScalarTypeResolver,
            ],
            default => parent::getSchemaFieldArgNameResolvers($objectTypeResolver, $fieldName),
        };
    }
    
    public function getSchemaFieldArgDescription(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName, string $fieldArgName): ?string
    {
        $setCategoriesFieldName = $this->getSetCategoriesFieldName();
        return match ([$fieldName => $fieldArgName]) {
            [$setCategoriesFieldName => MutationInputProperties::CUSTOMPOST_ID] => sprintf(
                $this->translationAPI->__('The ID of the %s', 'custompost-category-mutations'),
                $this->getEntityName()
            ),
            [$setCategoriesFieldName => MutationInputProperties::CATEGORY_IDS] => sprintf(
                $this->translationAPI->__('The IDs of the categories to set, of type \'%s\'', 'custompost-category-mutations'),
                $this->getCategoryTypeResolver()->getTypeOutputName()
            ),
            [$setCategoriesFieldName => MutationInputProperties::APPEND] => $this->translationAPI->__('Append the categories to the existing ones?', 'custompost-category-mutations'),
            default => parent::getSchemaFieldArgDescription($objectTypeResolver, $fieldName, $fieldArgName),
        };
    }
    
    public function getSchemaFieldArgDefaultValue(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName, string $fieldArgName): mixed
    {
        return match ([$fieldName => $fieldArgName]) {
            [$this->getSetCategoriesFieldName() => MutationInputProperties::APPEND] => false,
            default => parent::getSchemaFieldArgDefaultValue($objectTypeResolver, $fieldName, $fieldArgName),
        };
    }
    
    public function getSchemaFieldArgTypeModifiers(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName, string $fieldArgName): ?int
    {
        $setCategoriesFieldName = $this->getSetCategoriesFieldName();
        return match ([$fieldName => $fieldArgName]) {
            [$setCategoriesFieldName => MutationInputProperties::CUSTOMPOST_ID] => SchemaTypeModifiers::MANDATORY,
            [$setCategoriesFieldName => MutationInputProperties::CATEGORY_IDS] => SchemaTypeModifiers::IS_ARRAY | SchemaTypeModifiers::MANDATORY,
            default => parent::getSchemaFieldArgTypeModifiers($objectTypeResolver, $fieldName, $fieldArgName),
        };
    }

    public function getFieldMutationResolver(
        ObjectTypeResolverInterface $objectTypeResolver,
        string $fieldName
    ): ?MutationResolverInterface {
        switch ($fieldName) {
            case $this->getSetCategoriesFieldName():
                return $this->getSetCategoriesMutationResolver();
        }

        return parent::getFieldMutationResolver($objectTypeResolver, $fieldName);
    }

    public function getFieldTypeResolver(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName): ConcreteTypeResolverInterface
    {
        switch ($fieldName) {
            case $this->getSetCategoriesFieldName():
                return $this->getCustomPostTypeResolver();
        }

        return parent::getFieldTypeResolver($objectTypeResolver, $fieldName);
    }
}

<?php

declare(strict_types=1);

namespace PoPSchema\CustomPostTagMutations\FieldResolvers\ObjectType;

use PoP\ComponentModel\FieldResolvers\ObjectType\AbstractQueryableObjectTypeFieldResolver;
use PoP\ComponentModel\MutationResolvers\MutationResolverInterface;
use PoP\ComponentModel\Schema\SchemaTypeModifiers;
use PoP\ComponentModel\TypeResolvers\ConcreteTypeResolverInterface;
use PoP\ComponentModel\TypeResolvers\ObjectType\ObjectTypeResolverInterface;
use PoP\Engine\ComponentConfiguration as EngineComponentConfiguration;
use PoP\Engine\TypeResolvers\ObjectType\RootObjectTypeResolver;
use PoP\Engine\TypeResolvers\ScalarType\BooleanScalarTypeResolver;
use PoP\Engine\TypeResolvers\ScalarType\IDScalarTypeResolver;
use PoP\Engine\TypeResolvers\ScalarType\StringScalarTypeResolver;
use PoPSchema\CustomPostTagMutations\MutationResolvers\MutationInputProperties;
use Symfony\Contracts\Service\Attribute\Required;

abstract class AbstractRootObjectTypeFieldResolver extends AbstractQueryableObjectTypeFieldResolver implements SetTagsOnCustomPostObjectTypeFieldResolverInterface
{
    use SetTagsOnCustomPostObjectTypeFieldResolverTrait;

    protected StringScalarTypeResolver $stringScalarTypeResolver;
    protected BooleanScalarTypeResolver $booleanScalarTypeResolver;
    protected IDScalarTypeResolver $idScalarTypeResolver;

    #[Required]
    final public function autowireAbstractRootObjectTypeFieldResolver(
        StringScalarTypeResolver $stringScalarTypeResolver,
        BooleanScalarTypeResolver $booleanScalarTypeResolver,
        IDScalarTypeResolver $idScalarTypeResolver,
    ): void {
        $this->stringScalarTypeResolver = $stringScalarTypeResolver;
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
            $this->getSetTagsFieldName(),
        ];
    }

    abstract protected function getSetTagsFieldName(): string;

    public function getFieldDescription(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName): ?string
    {
        return match ($fieldName) {
            $this->getSetTagsFieldName() => sprintf(
                $this->translationAPI->__('Set tags on a %s', 'custompost-tag-mutations'),
                $this->getEntityName()
            ),
            default => parent::getFieldDescription($objectTypeResolver, $fieldName),
        };
    }

    public function getFieldArgNameResolvers(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName): array
    {
        return match ($fieldName) {
            $this->getSetTagsFieldName() => [
                MutationInputProperties::CUSTOMPOST_ID => $this->idScalarTypeResolver,
                MutationInputProperties::TAGS => $this->stringScalarTypeResolver,
                MutationInputProperties::APPEND => $this->booleanScalarTypeResolver,
            ],
            default => parent::getFieldArgNameResolvers($objectTypeResolver, $fieldName),
        };
    }

    public function getFieldArgDescription(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName, string $fieldArgName): ?string
    {
        $setTagsFieldName = $this->getSetTagsFieldName();
        return match ([$fieldName => $fieldArgName]) {
            [$setTagsFieldName => MutationInputProperties::CUSTOMPOST_ID] => sprintf(
                $this->translationAPI->__('The ID of the %s', 'custompost-tag-mutations'),
                $this->getEntityName()
            ),
            [$setTagsFieldName => MutationInputProperties::TAGS] => $this->translationAPI->__('The tags to set', 'custompost-tag-mutations'),
            [$setTagsFieldName => MutationInputProperties::APPEND] => $this->translationAPI->__('Append the tags to the existing ones?', 'custompost-tag-mutations'),
            default => parent::getFieldArgDescription($objectTypeResolver, $fieldName, $fieldArgName),
        };
    }

    public function getFieldArgDefaultValue(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName, string $fieldArgName): mixed
    {
        return match ([$fieldName => $fieldArgName]) {
            [$this->getSetTagsFieldName() => MutationInputProperties::APPEND] => false,
            default => parent::getFieldArgDefaultValue($objectTypeResolver, $fieldName, $fieldArgName),
        };
    }

    public function getFieldArgTypeModifiers(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName, string $fieldArgName): int
    {
        $setTagsFieldName = $this->getSetTagsFieldName();
        return match ([$fieldName => $fieldArgName]) {
            [$setTagsFieldName => MutationInputProperties::CUSTOMPOST_ID] => SchemaTypeModifiers::MANDATORY,
            [$setTagsFieldName => MutationInputProperties::TAGS] => SchemaTypeModifiers::IS_ARRAY | SchemaTypeModifiers::MANDATORY,
            default => parent::getFieldArgTypeModifiers($objectTypeResolver, $fieldName, $fieldArgName),
        };
    }

    public function getFieldMutationResolver(
        ObjectTypeResolverInterface $objectTypeResolver,
        string $fieldName
    ): ?MutationResolverInterface {
        switch ($fieldName) {
            case $this->getSetTagsFieldName():
                return $this->getSetTagsMutationResolver();
        }

        return parent::getFieldMutationResolver($objectTypeResolver, $fieldName);
    }

    public function getFieldTypeResolver(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName): ConcreteTypeResolverInterface
    {
        switch ($fieldName) {
            case $this->getSetTagsFieldName():
                return $this->getCustomPostObjectTypeResolver();
        }

        return parent::getFieldTypeResolver($objectTypeResolver, $fieldName);
    }
}

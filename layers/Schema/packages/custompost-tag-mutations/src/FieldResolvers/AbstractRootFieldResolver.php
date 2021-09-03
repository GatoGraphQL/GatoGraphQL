<?php

declare(strict_types=1);

namespace PoPSchema\CustomPostTagMutations\FieldResolvers;

use PoP\ComponentModel\FieldResolvers\AbstractQueryableFieldResolver;
use PoP\ComponentModel\Schema\SchemaDefinition;
use PoP\ComponentModel\TypeResolvers\RelationalTypeResolverInterface;
use PoP\Engine\ComponentConfiguration as EngineComponentConfiguration;
use PoP\Engine\TypeResolvers\RootTypeResolver;
use PoPSchema\CustomPostTagMutations\MutationResolvers\MutationInputProperties;

abstract class AbstractRootFieldResolver extends AbstractQueryableFieldResolver
{
    use SetTagsOnCustomPostFieldResolverTrait;

    public function getClassesToAttachTo(): array
    {
        return array(RootTypeResolver::class);
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

    public function getSchemaFieldDescription(RelationalTypeResolverInterface $relationalTypeResolver, string $fieldName): ?string
    {
        $descriptions = [
            $this->getSetTagsFieldName() => sprintf(
                $this->translationAPI->__('Set tags on a %s', 'custompost-tag-mutations'),
                $this->getEntityName()
            ),
        ];
        return $descriptions[$fieldName] ?? parent::getSchemaFieldDescription($relationalTypeResolver, $fieldName);
    }

    public function getSchemaFieldType(RelationalTypeResolverInterface $relationalTypeResolver, string $fieldName): string
    {
        $types = [
            $this->getSetTagsFieldName() => SchemaDefinition::TYPE_ID,
        ];
        return $types[$fieldName] ?? parent::getSchemaFieldType($relationalTypeResolver, $fieldName);
    }

    public function getSchemaFieldArgs(RelationalTypeResolverInterface $relationalTypeResolver, string $fieldName): array
    {
        switch ($fieldName) {
            case $this->getSetTagsFieldName():
                return [
                    [
                        SchemaDefinition::ARGNAME_NAME => MutationInputProperties::CUSTOMPOST_ID,
                        SchemaDefinition::ARGNAME_TYPE => SchemaDefinition::TYPE_ID,
                        SchemaDefinition::ARGNAME_DESCRIPTION => sprintf(
                            $this->translationAPI->__('The ID of the %s', 'custompost-tag-mutations'),
                            $this->getEntityName()
                        ),
                        SchemaDefinition::ARGNAME_MANDATORY => true,
                    ],
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
        return parent::getSchemaFieldArgs($relationalTypeResolver, $fieldName);
    }

    public function resolveFieldMutationResolverClass(RelationalTypeResolverInterface $relationalTypeResolver, string $fieldName): ?string
    {
        switch ($fieldName) {
            case $this->getSetTagsFieldName():
                return $this->getTypeMutationResolverClass();
        }

        return parent::resolveFieldMutationResolverClass($relationalTypeResolver, $fieldName);
    }

    public function resolveFieldTypeResolverClass(RelationalTypeResolverInterface $relationalTypeResolver, string $fieldName): ?string
    {
        switch ($fieldName) {
            case $this->getSetTagsFieldName():
                return $this->getCustomPostTypeResolverClass();
        }

        return parent::resolveFieldTypeResolverClass($relationalTypeResolver, $fieldName);
    }
}

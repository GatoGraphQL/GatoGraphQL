<?php

declare(strict_types=1);

namespace PoPWPSchema\Meta\TypeResolvers\InputObjectType;

use PoP\ComponentModel\TypeResolvers\InputObjectType\AbstractQueryableInputObjectTypeResolver;
use PoP\Engine\TypeResolvers\ScalarType\StringScalarTypeResolver;
use PoPWPSchema\Meta\TypeResolvers\EnumType\MetaQueryValueTypeEnumTypeResolver;
use PoPWPSchema\SchemaCommons\Constants\MetaQueryValueTypes;
use PoPWPSchema\SchemaCommons\TypeResolvers\EnumType\RelationEnumTypeResolver;
use stdClass;

class MetaQueryInputObjectTypeResolver extends AbstractQueryableInputObjectTypeResolver
{
    private ?MetaQueryValueTypeEnumTypeResolver $metaQueryValueTypesEnumTypeResolver = null;
    private ?MetaQueryCompareByOneofInputObjectTypeResolver $metaQueryCompareByOneofInputObjectTypeResolver = null;
    private ?StringScalarTypeResolver $stringScalarTypeResolver = null;
    private ?RelationEnumTypeResolver $relationEnumTypeResolver = null;

    final public function setMetaQueryValueTypesEnumTypeResolver(MetaQueryValueTypeEnumTypeResolver $metaQueryValueTypesEnumTypeResolver): void
    {
        $this->metaQueryValueTypesEnumTypeResolver = $metaQueryValueTypesEnumTypeResolver;
    }
    final protected function getMetaQueryValueTypesEnumTypeResolver(): MetaQueryValueTypeEnumTypeResolver
    {
        return $this->metaQueryValueTypesEnumTypeResolver ??= $this->instanceManager->getInstance(MetaQueryValueTypeEnumTypeResolver::class);
    }
    final public function setMetaQueryCompareByOneofInputObjectTypeResolver(MetaQueryCompareByOneofInputObjectTypeResolver $metaQueryCompareByOneofInputObjectTypeResolver): void
    {
        $this->metaQueryCompareByOneofInputObjectTypeResolver = $metaQueryCompareByOneofInputObjectTypeResolver;
    }
    final protected function getMetaQueryCompareByOneofInputObjectTypeResolver(): MetaQueryCompareByOneofInputObjectTypeResolver
    {
        return $this->metaQueryCompareByOneofInputObjectTypeResolver ??= $this->instanceManager->getInstance(MetaQueryCompareByOneofInputObjectTypeResolver::class);
    }
    final public function setStringScalarTypeResolver(StringScalarTypeResolver $stringScalarTypeResolver): void
    {
        $this->stringScalarTypeResolver = $stringScalarTypeResolver;
    }
    final protected function getStringScalarTypeResolver(): StringScalarTypeResolver
    {
        return $this->stringScalarTypeResolver ??= $this->instanceManager->getInstance(StringScalarTypeResolver::class);
    }
    final public function setRelationEnumTypeResolver(RelationEnumTypeResolver $relationEnumTypeResolver): void
    {
        $this->relationEnumTypeResolver = $relationEnumTypeResolver;
    }
    final protected function getRelationEnumTypeResolver(): RelationEnumTypeResolver
    {
        return $this->relationEnumTypeResolver ??= $this->instanceManager->getInstance(RelationEnumTypeResolver::class);
    }

    public function getTypeName(): string
    {
        return 'MetaQueryInput';
    }

    public function getTypeDescription(): ?string
    {
        return $this->getTranslationAPI()->__('Filter by meta key and value. See: https://developer.wordpress.org/reference/classes/wp_meta_query/', 'meta');
    }

    public function getInputFieldNameTypeResolvers(): array
    {
        return [
            'key' => $this->getStringScalarTypeResolver(),
            'type' => $this->getMetaQueryValueTypesEnumTypeResolver(),
            'compareBy' => $this->getMetaQueryCompareByOneofInputObjectTypeResolver(),
            'relation' => $this->getRelationEnumTypeResolver(),
        ];
    }

    public function getInputFieldDescription(string $inputFieldName): ?string
    {
        return match ($inputFieldName) {
            'key' => $this->getTranslationAPI()->__('Custom field key', 'meta'),
            'type' => $this->getTranslationAPI()->__('Custom field type', 'meta'),
            'compareBy' => $this->getTranslationAPI()->__('Value and operator to compare against', 'meta'),
            'relation' => $this->getTranslationAPI()->__('OR or AND, how the sub-arrays should be compared. Default: AND. Only the value from the first sub-array will be used', 'meta'),
            default => parent::getInputFieldDescription($inputFieldName),
        };
    }

    public function getInputFieldDefaultValue(string $inputFieldName): mixed
    {
        return match ($inputFieldName) {
            'type' => MetaQueryValueTypes::CHAR,
            default => parent::getInputFieldDescription($inputFieldName),
        };
    }

    /**
     * Integrate parameters into the "date_query" WP_Query arg
     *
     * @see https://developer.wordpress.org/reference/classes/wp_query/#date-parameters
     *
     * @param array<string, mixed> $query
     * @param stdClass|stdClass[]|array<stdClass[]> $inputValue
     */
    public function integrateInputValueToFilteringQueryArgs(array &$query, stdClass|array $inputValue): void
    {
        // @todo
    }
}

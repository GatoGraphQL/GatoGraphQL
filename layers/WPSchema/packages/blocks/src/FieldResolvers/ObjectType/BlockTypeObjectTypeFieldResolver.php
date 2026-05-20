<?php

declare(strict_types=1);

namespace PoPWPSchema\Blocks\FieldResolvers\ObjectType;

use PoP\ComponentModel\Feedback\ObjectTypeFieldResolutionFeedbackStore;
use PoP\ComponentModel\FieldResolvers\ObjectType\AbstractQueryableObjectTypeFieldResolver;
use PoP\ComponentModel\QueryResolution\FieldDataAccessorInterface;
use PoP\ComponentModel\Schema\SchemaTypeModifiers;
use PoP\ComponentModel\TypeResolvers\ConcreteTypeResolverInterface;
use PoP\ComponentModel\TypeResolvers\InputTypeResolverInterface;
use PoP\ComponentModel\TypeResolvers\ObjectType\ObjectTypeResolverInterface;
use PoP\ComponentModel\TypeResolvers\ScalarType\BooleanScalarTypeResolver;
use PoP\ComponentModel\TypeResolvers\ScalarType\StringScalarTypeResolver;
use PoP\Engine\TypeResolvers\ScalarType\JSONObjectScalarTypeResolver;
use PoP\GraphQLParser\Spec\Parser\Ast\FieldInterface;
use PoPWPSchema\Blocks\ObjectModels\BlockType;
use PoPWPSchema\Blocks\ObjectModels\BlockTypeAttribute;
use PoPWPSchema\Blocks\TypeResolvers\InputObjectType\BlockTypeAttributesFilterInputObjectTypeResolver;
use PoPWPSchema\Blocks\TypeResolvers\ObjectType\BlockTypeAttributeObjectTypeResolver;
use PoPWPSchema\Blocks\TypeResolvers\ObjectType\BlockTypeObjectTypeResolver;

class BlockTypeObjectTypeFieldResolver extends AbstractQueryableObjectTypeFieldResolver
{
    private ?StringScalarTypeResolver $stringScalarTypeResolver = null;
    private ?BooleanScalarTypeResolver $booleanScalarTypeResolver = null;
    private ?JSONObjectScalarTypeResolver $jsonObjectScalarTypeResolver = null;
    private ?BlockTypeAttributeObjectTypeResolver $blockTypeAttributeObjectTypeResolver = null;
    private ?BlockTypeAttributesFilterInputObjectTypeResolver $blockTypeAttributesFilterInputObjectTypeResolver = null;

    final protected function getStringScalarTypeResolver(): StringScalarTypeResolver
    {
        if ($this->stringScalarTypeResolver === null) {
            /** @var StringScalarTypeResolver */
            $stringScalarTypeResolver = $this->instanceManager->getInstance(StringScalarTypeResolver::class);
            $this->stringScalarTypeResolver = $stringScalarTypeResolver;
        }
        return $this->stringScalarTypeResolver;
    }
    final protected function getBooleanScalarTypeResolver(): BooleanScalarTypeResolver
    {
        if ($this->booleanScalarTypeResolver === null) {
            /** @var BooleanScalarTypeResolver */
            $booleanScalarTypeResolver = $this->instanceManager->getInstance(BooleanScalarTypeResolver::class);
            $this->booleanScalarTypeResolver = $booleanScalarTypeResolver;
        }
        return $this->booleanScalarTypeResolver;
    }
    final protected function getJSONObjectScalarTypeResolver(): JSONObjectScalarTypeResolver
    {
        if ($this->jsonObjectScalarTypeResolver === null) {
            /** @var JSONObjectScalarTypeResolver */
            $jsonObjectScalarTypeResolver = $this->instanceManager->getInstance(JSONObjectScalarTypeResolver::class);
            $this->jsonObjectScalarTypeResolver = $jsonObjectScalarTypeResolver;
        }
        return $this->jsonObjectScalarTypeResolver;
    }
    final protected function getBlockTypeAttributeObjectTypeResolver(): BlockTypeAttributeObjectTypeResolver
    {
        if ($this->blockTypeAttributeObjectTypeResolver === null) {
            /** @var BlockTypeAttributeObjectTypeResolver */
            $blockTypeAttributeObjectTypeResolver = $this->instanceManager->getInstance(BlockTypeAttributeObjectTypeResolver::class);
            $this->blockTypeAttributeObjectTypeResolver = $blockTypeAttributeObjectTypeResolver;
        }
        return $this->blockTypeAttributeObjectTypeResolver;
    }
    final protected function getBlockTypeAttributesFilterInputObjectTypeResolver(): BlockTypeAttributesFilterInputObjectTypeResolver
    {
        if ($this->blockTypeAttributesFilterInputObjectTypeResolver === null) {
            /** @var BlockTypeAttributesFilterInputObjectTypeResolver */
            $blockTypeAttributesFilterInputObjectTypeResolver = $this->instanceManager->getInstance(BlockTypeAttributesFilterInputObjectTypeResolver::class);
            $this->blockTypeAttributesFilterInputObjectTypeResolver = $blockTypeAttributesFilterInputObjectTypeResolver;
        }
        return $this->blockTypeAttributesFilterInputObjectTypeResolver;
    }

    /**
     * @return array<class-string<ObjectTypeResolverInterface>>
     */
    public function getObjectTypeResolverClassesToAttachTo(): array
    {
        return [
            BlockTypeObjectTypeResolver::class,
        ];
    }

    /**
     * @return string[]
     */
    public function getFieldNamesToResolve(): array
    {
        return [
            'name',
            'supports',
            'hasRenderCallback',
            'attributes',
            'attributeNames',
        ];
    }

    public function getFieldDescription(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName): ?string
    {
        return match ($fieldName) {
            'name' => $this->__('Block name (e.g. "core/paragraph")', 'blocks'),
            'supports' => $this->__('Block "supports" configuration as registered in `block.json`, returned as a JSON object', 'blocks'),
            'hasRenderCallback' => $this->__('Whether this block has a registered `render_callback` (i.e. is a dynamic/PHP-rendered block)', 'blocks'),
            'attributes' => $this->__('The block\'s registered attributes', 'blocks'),
            'attributeNames' => $this->__('The names of the block\'s registered attributes', 'blocks'),
            default => parent::getFieldDescription($objectTypeResolver, $fieldName),
        };
    }

    public function getFieldTypeResolver(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName): ConcreteTypeResolverInterface
    {
        return match ($fieldName) {
            'name',
            'attributeNames'
                => $this->getStringScalarTypeResolver(),
            'supports' => $this->getJSONObjectScalarTypeResolver(),
            'hasRenderCallback' => $this->getBooleanScalarTypeResolver(),
            'attributes' => $this->getBlockTypeAttributeObjectTypeResolver(),
            default => parent::getFieldTypeResolver($objectTypeResolver, $fieldName),
        };
    }

    public function getFieldTypeModifiers(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName): int
    {
        return match ($fieldName) {
            'name',
            'supports',
            'hasRenderCallback'
                => SchemaTypeModifiers::NON_NULLABLE,
            'attributes',
            'attributeNames'
                => SchemaTypeModifiers::NON_NULLABLE | SchemaTypeModifiers::IS_ARRAY | SchemaTypeModifiers::IS_NON_NULLABLE_ITEMS_IN_ARRAY,
            default
                => parent::getFieldTypeModifiers($objectTypeResolver, $fieldName),
        };
    }

    /**
     * @return array<string,InputTypeResolverInterface>
     */
    public function getFieldArgNameTypeResolvers(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName): array
    {
        $fieldArgNameTypeResolvers = parent::getFieldArgNameTypeResolvers($objectTypeResolver, $fieldName);
        return match ($fieldName) {
            'attributes',
            'attributeNames'
                => array_merge(
                    $fieldArgNameTypeResolvers,
                    [
                        'filter' => $this->getBlockTypeAttributesFilterInputObjectTypeResolver(),
                    ],
                ),
            default => $fieldArgNameTypeResolvers,
        };
    }

    public function resolveValue(
        ObjectTypeResolverInterface $objectTypeResolver,
        object $object,
        FieldDataAccessorInterface $fieldDataAccessor,
        ObjectTypeFieldResolutionFeedbackStore $objectTypeFieldResolutionFeedbackStore,
    ): mixed {
        /** @var BlockType $blockType */
        $blockType = $object;
        switch ($fieldDataAccessor->getFieldName()) {
            case 'name':
                return $blockType->getName();

            case 'supports':
                return (object) $blockType->getSupports();

            case 'hasRenderCallback':
                return $blockType->hasRenderCallback();

            case 'attributes':
                $attributes = $this->getFilteredAttributes($blockType, $objectTypeResolver, $fieldDataAccessor);
                /**
                 * BlockTypeAttribute is a Transient Object: instantiating it
                 * auto-registers it in the Object Dictionary, so the DataLoader
                 * can later resolve it by ID without re-parsing the registry.
                 */
                $ids = [];
                foreach ($attributes as $attributeName => $schema) {
                    $attribute = new BlockTypeAttribute($blockType, (string) $attributeName, $schema);
                    $ids[] = $attribute->getID();
                }
                return $ids;

            case 'attributeNames':
                $attributes = $this->getFilteredAttributes($blockType, $objectTypeResolver, $fieldDataAccessor);
                return array_map(
                    static fn (int|string $attributeName): string => (string) $attributeName,
                    array_keys($attributes),
                );
        }

        return parent::resolveValue($objectTypeResolver, $object, $fieldDataAccessor, $objectTypeFieldResolutionFeedbackStore);
    }

    /**
     * Apply the `filter` input arg shared by `attributes` and `attributeNames`
     * against the BlockType's raw attribute schemas.
     *
     * @return array<string,array<string,mixed>>
     */
    protected function getFilteredAttributes(
        BlockType $blockType,
        ObjectTypeResolverInterface $objectTypeResolver,
        FieldDataAccessorInterface $fieldDataAccessor,
    ): array {
        $query = $this->convertFieldArgsToFilteringQueryArgs($objectTypeResolver, $fieldDataAccessor);
        $attributes = $blockType->getAttributes();

        /** @var string[]|null */
        $expectedFieldTypes = $query['fieldTypes'] ?? null;
        if ($expectedFieldTypes !== null && $expectedFieldTypes !== []) {
            $attributes = array_filter(
                $attributes,
                static function (array $schema) use ($expectedFieldTypes): bool {
                    $type = $schema['type'] ?? null;
                    if (is_array($type)) {
                        return array_intersect($expectedFieldTypes, $type) !== [];
                    }
                    return in_array($type, $expectedFieldTypes, true);
                },
            );
        }

        if (array_key_exists('autoGenerateControl', $query)) {
            $expected = (bool) $query['autoGenerateControl'];
            $attributes = array_filter(
                $attributes,
                static fn (array $schema): bool => !empty($schema['autoGenerateControl']) === $expected,
            );
        }

        if (array_key_exists('hasEnum', $query)) {
            $expected = (bool) $query['hasEnum'];
            $attributes = array_filter(
                $attributes,
                static function (array $schema) use ($expected): bool {
                    $enum = $schema['enum'] ?? null;
                    $hasEnum = is_array($enum) && $enum !== [];
                    return $hasEnum === $expected;
                },
            );
        }

        return $attributes;
    }

    /**
     * Since the return type is known for all the fields in this
     * FieldResolver, there's no need to validate them
     */
    public function validateResolvedFieldType(
        ObjectTypeResolverInterface $objectTypeResolver,
        FieldInterface $field,
    ): bool {
        return false;
    }
}

<?php

declare(strict_types=1);

namespace PoPWPSchema\Blocks\FieldResolvers\ObjectType;

use PoP\ComponentModel\Feedback\ObjectTypeFieldResolutionFeedbackStore;
use PoP\ComponentModel\FieldResolvers\ObjectType\AbstractObjectTypeFieldResolver;
use PoP\ComponentModel\QueryResolution\FieldDataAccessorInterface;
use PoP\ComponentModel\Schema\SchemaTypeModifiers;
use PoP\ComponentModel\TypeResolvers\ConcreteTypeResolverInterface;
use PoP\ComponentModel\TypeResolvers\ObjectType\ObjectTypeResolverInterface;
use PoP\ComponentModel\TypeResolvers\ScalarType\BooleanScalarTypeResolver;
use PoP\ComponentModel\TypeResolvers\ScalarType\StringScalarTypeResolver;
use PoP\Engine\TypeResolvers\ScalarType\JSONObjectScalarTypeResolver;
use PoP\GraphQLParser\Spec\Parser\Ast\FieldInterface;
use PoPWPSchema\Blocks\ObjectModels\BlockTypeAttribute;
use PoPWPSchema\Blocks\TypeResolvers\ObjectType\BlockTypeAttributeObjectTypeResolver;
use PoPWPSchema\Blocks\TypeResolvers\ScalarType\BlockTypeAttributeFieldTypeEnumStringScalarTypeResolver;

class BlockTypeAttributeObjectTypeFieldResolver extends AbstractObjectTypeFieldResolver
{
    private ?StringScalarTypeResolver $stringScalarTypeResolver = null;
    private ?BooleanScalarTypeResolver $booleanScalarTypeResolver = null;
    private ?JSONObjectScalarTypeResolver $jsonObjectScalarTypeResolver = null;
    private ?BlockTypeAttributeFieldTypeEnumStringScalarTypeResolver $blockTypeAttributeFieldTypeEnumStringScalarTypeResolver = null;

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
    final protected function getBlockTypeAttributeFieldTypeEnumStringScalarTypeResolver(): BlockTypeAttributeFieldTypeEnumStringScalarTypeResolver
    {
        if ($this->blockTypeAttributeFieldTypeEnumStringScalarTypeResolver === null) {
            /** @var BlockTypeAttributeFieldTypeEnumStringScalarTypeResolver */
            $blockTypeAttributeFieldTypeEnumStringScalarTypeResolver = $this->instanceManager->getInstance(BlockTypeAttributeFieldTypeEnumStringScalarTypeResolver::class);
            $this->blockTypeAttributeFieldTypeEnumStringScalarTypeResolver = $blockTypeAttributeFieldTypeEnumStringScalarTypeResolver;
        }
        return $this->blockTypeAttributeFieldTypeEnumStringScalarTypeResolver;
    }

    /**
     * @return array<class-string<ObjectTypeResolverInterface>>
     */
    public function getObjectTypeResolverClassesToAttachTo(): array
    {
        return [
            BlockTypeAttributeObjectTypeResolver::class,
        ];
    }

    /**
     * @return string[]
     */
    public function getFieldNamesToResolve(): array
    {
        return [
            'name',
            'blockTypeName',
            'fieldType',
            'label',
            'default',
            'enum',
            'source',
            'role',
            'autoGenerateControl',
            'schema',
        ];
    }

    public function getFieldDescription(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName): ?string
    {
        return match ($fieldName) {
            'name' => $this->__('Attribute name', 'blocks'),
            'blockTypeName' => $this->__('Name of the block type owning this attribute', 'blocks'),
            'fieldType' => $this->__('JSON-Schema "type" of the attribute (e.g. "string", "boolean"). Returns the first type if it\'s a union.', 'blocks'),
            'label' => $this->__('Human-readable label for the attribute (used by auto-generated controls in WP 7.0+)', 'blocks'),
            'default' => $this->__('Default value of the attribute', 'blocks'),
            'enum' => $this->__('Allowed values, if the attribute is restricted to an enum', 'blocks'),
            'source' => $this->__('Where the attribute value is parsed from (e.g. "attribute", "html", "text"); null when stored in block-comment delimiters', 'blocks'),
            'role' => $this->__('Attribute role (e.g. "local", "content")', 'blocks'),
            'autoGenerateControl' => $this->__('Whether WordPress auto-generates an editor control for this attribute (PHP-only blocks, WP 7.0+)', 'blocks'),
            'schema' => $this->__('Full attribute schema as a JSON object', 'blocks'),
            default => parent::getFieldDescription($objectTypeResolver, $fieldName),
        };
    }

    public function getFieldTypeResolver(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName): ConcreteTypeResolverInterface
    {
        return match ($fieldName) {
            'name',
            'blockTypeName',
            'label',
            'source',
            'role'
                => $this->getStringScalarTypeResolver(),
            'autoGenerateControl'
                => $this->getBooleanScalarTypeResolver(),
            'fieldType'
                => $this->getBlockTypeAttributeFieldTypeEnumStringScalarTypeResolver(),
            'default',
            'enum',
            'schema'
                => $this->getJSONObjectScalarTypeResolver(),
            default
                => parent::getFieldTypeResolver($objectTypeResolver, $fieldName),
        };
    }

    public function getFieldTypeModifiers(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName): int
    {
        return match ($fieldName) {
            'name',
            'blockTypeName',
            'autoGenerateControl',
            'schema'
                => SchemaTypeModifiers::NON_NULLABLE,
            'enum'
                => SchemaTypeModifiers::IS_ARRAY,
            default
                => parent::getFieldTypeModifiers($objectTypeResolver, $fieldName),
        };
    }

    public function resolveValue(
        ObjectTypeResolverInterface $objectTypeResolver,
        object $object,
        FieldDataAccessorInterface $fieldDataAccessor,
        ObjectTypeFieldResolutionFeedbackStore $objectTypeFieldResolutionFeedbackStore,
    ): mixed {
        /** @var BlockTypeAttribute $attribute */
        $attribute = $object;
        $schema = $attribute->getSchema();
        switch ($fieldDataAccessor->getFieldName()) {
            case 'name':
                return $attribute->getAttributeName();

            case 'blockTypeName':
                return $attribute->getBlockTypeName();

            case 'fieldType':
                $type = $schema['type'] ?? null;
                if (is_array($type)) {
                    return $type[0] ?? null;
                }
                return $type;

            case 'label':
                return $schema['label'] ?? null;

            case 'default':
                return $schema['default'] ?? null;

            case 'enum':
                return $schema['enum'] ?? null;

            case 'source':
                return $schema['source'] ?? null;

            case 'role':
                return $schema['role'] ?? null;

            case 'autoGenerateControl':
                return !empty($schema['autoGenerateControl']);

            case 'schema':
                return (object) $schema;
        }

        return parent::resolveValue($objectTypeResolver, $object, $fieldDataAccessor, $objectTypeFieldResolutionFeedbackStore);
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

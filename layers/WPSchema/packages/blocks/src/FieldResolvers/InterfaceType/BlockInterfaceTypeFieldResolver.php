<?php

declare(strict_types=1);

namespace PoPWPSchema\Blocks\FieldResolvers\InterfaceType;

use PoPSchema\SchemaCommons\TypeResolvers\ScalarType\HTMLScalarTypeResolver;
use PoPWPSchema\Blocks\TypeHelpers\BlockUnionTypeHelpers;
use PoPWPSchema\Blocks\TypeResolvers\InterfaceType\BlockInterfaceTypeResolver;
use PoP\ComponentModel\FieldResolvers\InterfaceType\AbstractInterfaceTypeFieldResolver;
use PoP\ComponentModel\Schema\SchemaTypeModifiers;
use PoP\ComponentModel\TypeResolvers\ConcreteTypeResolverInterface;
use PoP\ComponentModel\TypeResolvers\InterfaceType\InterfaceTypeResolverInterface;
use PoP\ComponentModel\TypeResolvers\ScalarType\StringScalarTypeResolver;
use PoP\Engine\TypeResolvers\ScalarType\JSONObjectScalarTypeResolver;

class BlockInterfaceTypeFieldResolver extends AbstractInterfaceTypeFieldResolver
{
    private ?JSONObjectScalarTypeResolver $jsonObjectScalarTypeResolver = null;
    private ?StringScalarTypeResolver $stringScalarTypeResolver = null;
    private ?HTMLScalarTypeResolver $htmlScalarTypeResolver = null;

    final protected function getJSONObjectScalarTypeResolver(): JSONObjectScalarTypeResolver
    {
        if ($this->jsonObjectScalarTypeResolver === null) {
            /** @var JSONObjectScalarTypeResolver */
            $jsonObjectScalarTypeResolver = $this->instanceManager->getInstance(JSONObjectScalarTypeResolver::class);
            $this->jsonObjectScalarTypeResolver = $jsonObjectScalarTypeResolver;
        }
        return $this->jsonObjectScalarTypeResolver;
    }
    final protected function getStringScalarTypeResolver(): StringScalarTypeResolver
    {
        if ($this->stringScalarTypeResolver === null) {
            /** @var StringScalarTypeResolver */
            $stringScalarTypeResolver = $this->instanceManager->getInstance(StringScalarTypeResolver::class);
            $this->stringScalarTypeResolver = $stringScalarTypeResolver;
        }
        return $this->stringScalarTypeResolver;
    }
    final protected function getHTMLScalarTypeResolver(): HTMLScalarTypeResolver
    {
        if ($this->htmlScalarTypeResolver === null) {
            /** @var HTMLScalarTypeResolver */
            $htmlScalarTypeResolver = $this->instanceManager->getInstance(HTMLScalarTypeResolver::class);
            $this->htmlScalarTypeResolver = $htmlScalarTypeResolver;
        }
        return $this->htmlScalarTypeResolver;
    }

    /**
     * @return array<class-string<InterfaceTypeResolverInterface>>
     */
    public function getInterfaceTypeResolverClassesToAttachTo(): array
    {
        return [
            BlockInterfaceTypeResolver::class,
        ];
    }

    /**
     * @return string[]
     */
    public function getFieldNamesToImplement(): array
    {
        return [
            'name',
            'attributes',
            'innerBlocks',
            // 'innerHTML',
            'contentSource',
        ];
    }

    public function getFieldTypeResolver(string $fieldName): ConcreteTypeResolverInterface
    {
        return match ($fieldName) {
            'name' => $this->getStringScalarTypeResolver(),
            'attributes' => $this->getJSONObjectScalarTypeResolver(),
            'innerBlocks' => BlockUnionTypeHelpers::getBlockUnionOrTargetObjectTypeResolver(),
            // 'innerHTML' => $this->getHTMLScalarTypeResolver(),
            'contentSource' => $this->getHTMLScalarTypeResolver(),
            default => parent::getFieldTypeResolver($fieldName),
        };
    }

    public function getFieldTypeModifiers(string $fieldName): int
    {
        return match ($fieldName) {
            'name',
            // 'innerHTML',
            'contentSource'
                => SchemaTypeModifiers::NON_NULLABLE,
            'innerBlocks'
                => SchemaTypeModifiers::IS_ARRAY | SchemaTypeModifiers::IS_NON_NULLABLE_ITEMS_IN_ARRAY,
            default
                => parent::getFieldTypeModifiers($fieldName),
        };
    }

    public function getFieldDescription(string $fieldName): ?string
    {
        return match ($fieldName) {
            'name' => $this->__('Block name', 'blocks'),
            'attributes' => $this->__('Block attributes, parsed to the type declared in their block.json schema', 'blocks'),
            'innerBlocks' => $this->__('Block\'s inner blocks (if suitable)', 'blocks'),
            // 'innerHTML' => $this->__('Block\'s inner HTML code', 'blocks'),
            'contentSource' => $this->__('Block\'s whole HTML code, including the block comment delimiters', 'blocks'),
            default => parent::getFieldDescription($fieldName),
        };
    }
}

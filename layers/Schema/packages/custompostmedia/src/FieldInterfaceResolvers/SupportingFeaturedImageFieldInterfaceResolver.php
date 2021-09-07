<?php

declare(strict_types=1);

namespace PoPSchema\CustomPostMedia\FieldInterfaceResolvers;

use PoP\ComponentModel\FieldInterfaceResolvers\AbstractFieldInterfaceResolver;
use PoP\ComponentModel\Schema\SchemaDefinition;
use PoP\ComponentModel\Schema\SchemaTypeModifiers;
use PoPSchema\Media\TypeResolvers\Object\MediaTypeResolver;

class SupportingFeaturedImageFieldInterfaceResolver extends AbstractFieldInterfaceResolver
{
    public function getInterfaceName(): string
    {
        return 'SupportingFeaturedImage';
    }

    public function getSchemaInterfaceDescription(): ?string
    {
        return $this->translationAPI->__('Fields concerning an entity\'s featured image', 'custompostmedia');
    }

    public function getFieldNamesToImplement(): array
    {
        return [
            'hasFeaturedImage',
            'featuredImage',
        ];
    }

    public function getSchemaFieldType(string $fieldName): string
    {
        $types = [
            'hasFeaturedImage' => SchemaDefinition::TYPE_BOOL,
            'featuredImage' => SchemaDefinition::TYPE_ID,
        ];
        return $types[$fieldName] ?? parent::getSchemaFieldType($fieldName);
    }

    public function getSchemaFieldTypeModifiers(string $fieldName): ?int
    {
        $nonNullableFieldNames = [
            'hasFeaturedImage',
        ];
        if (in_array($fieldName, $nonNullableFieldNames)) {
            return SchemaTypeModifiers::NON_NULLABLE;
        }
        return parent::getSchemaFieldTypeModifiers($fieldName);
    }

    public function getSchemaFieldDescription(string $fieldName): ?string
    {
        $descriptions = [
            'hasFeaturedImage' => $this->translationAPI->__('Does the custom post have a featured image?', 'custompostmedia'),
            'featuredImage' => $this->translationAPI->__('Featured image from the custom post', 'custompostmedia'),
        ];
        return $descriptions[$fieldName] ?? parent::getSchemaFieldDescription($fieldName);
    }

    public function getFieldTypeResolverClass(string $fieldName): ?string
    {
        switch ($fieldName) {
            case 'featuredImage':
                return MediaTypeResolver::class;
        }

        return parent::getFieldTypeResolverClass($fieldName);
    }
}

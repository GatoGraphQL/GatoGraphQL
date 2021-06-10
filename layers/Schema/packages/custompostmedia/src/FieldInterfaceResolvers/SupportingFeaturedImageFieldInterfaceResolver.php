<?php

declare(strict_types=1);

namespace PoPSchema\CustomPostMedia\FieldInterfaceResolvers;

use PoPSchema\Media\TypeResolvers\MediaTypeResolver;
use PoP\ComponentModel\Schema\SchemaDefinition;
use PoP\ComponentModel\FieldInterfaceResolvers\AbstractSchemaFieldInterfaceResolver;

class SupportingFeaturedImageFieldInterfaceResolver extends AbstractSchemaFieldInterfaceResolver
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

    public function isSchemaFieldResponseNonNullable(string $fieldName): bool
    {
        $nonNullableFieldNames = [
            'hasFeaturedImage',
        ];
        if (in_array($fieldName, $nonNullableFieldNames)) {
            return true;
        }
        return parent::isSchemaFieldResponseNonNullable($fieldName);
    }

    public function getSchemaFieldDescription(string $fieldName): ?string
    {
        $descriptions = [
            'hasFeaturedImage' => $this->translationAPI->__('Does the custom post have a featured image?', 'custompostmedia'),
            'featuredImage' => $this->translationAPI->__('Featured image from the custom post', 'custompostmedia'),
        ];
        return $descriptions[$fieldName] ?? parent::getSchemaFieldDescription($fieldName);
    }

    /**
     * This function is not called by the engine, to generate the schema.
     * Instead, the resolver is obtained from the fieldResolver.
     * To make sure that all fieldResolvers implementing the same interface
     * return the expected type for the field, they can obtain it from the
     * interface through this function.
     */
    public function getFieldTypeResolverClass(string $fieldName): ?string
    {
        switch ($fieldName) {
            case 'featuredImage':
                return MediaTypeResolver::class;
        }

        return parent::getFieldTypeResolverClass($fieldName);
    }
}

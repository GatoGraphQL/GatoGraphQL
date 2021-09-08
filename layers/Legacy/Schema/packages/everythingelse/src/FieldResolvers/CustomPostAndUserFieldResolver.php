<?php

declare(strict_types=1);

namespace PoPSchema\Locations\FieldResolvers;

use PoP\ComponentModel\FieldResolvers\AbstractDBDataFieldResolver;
use PoP\ComponentModel\Misc\GeneralUtils;
use PoP\ComponentModel\Schema\SchemaDefinition;
use PoP\ComponentModel\Schema\SchemaTypeModifiers;
use PoP\ComponentModel\TypeResolvers\RelationalTypeResolverInterface;
use PoPSchema\CustomPosts\TypeResolvers\Object\AbstractCustomPostTypeResolver;
use PoPSchema\Locations\TypeResolvers\Object\LocationTypeResolver;
use PoPSchema\Users\TypeResolvers\Object\UserTypeResolver;

class CustomPostAndUserFieldResolver extends AbstractDBDataFieldResolver
{
    public function getClassesToAttachTo(): array
    {
        return [
            AbstractCustomPostTypeResolver::class,
            UserTypeResolver::class,
        ];
    }

    public function getFieldNamesToResolve(): array
    {
        return [
            'hasLocation',
            'location',
        ];
    }

    public function getSchemaFieldType(RelationalTypeResolverInterface $relationalTypeResolver, string $fieldName): string
    {
        return match ($fieldName) {
            'hasLocation' => SchemaDefinition::TYPE_BOOL,
            default => parent::getSchemaFieldType($relationalTypeResolver, $fieldName),
        };
    }

    public function getSchemaFieldTypeModifiers(RelationalTypeResolverInterface $relationalTypeResolver, string $fieldName): ?int
    {
        $nonNullableFieldNames = [
            'hasLocation',
        ];
        if (in_array($fieldName, $nonNullableFieldNames)) {
            return SchemaTypeModifiers::NON_NULLABLE;
        }
        return parent::getSchemaFieldTypeModifiers($relationalTypeResolver, $fieldName);
    }

    public function getSchemaFieldDescription(RelationalTypeResolverInterface $relationalTypeResolver, string $fieldName): ?string
    {
        $descriptions = [
            'hasLocation' => $this->translationAPI->__('Does the object have location?', 'pop-locations'),
            'location' => $this->translationAPI->__('Object\'s location', 'pop-locations'),
        ];
        return $descriptions[$fieldName] ?? parent::getSchemaFieldDescription($relationalTypeResolver, $fieldName);
    }

    /**
     * @param array<string, mixed> $fieldArgs
     * @param array<string, mixed>|null $variables
     * @param array<string, mixed>|null $expressions
     * @param array<string, mixed> $options
     */
    public function resolveValue(
        RelationalTypeResolverInterface $relationalTypeResolver,
        object $resultItem,
        string $fieldName,
        array $fieldArgs = [],
        ?array $variables = null,
        ?array $expressions = null,
        array $options = []
    ): mixed {
        switch ($fieldName) {
            case 'hasLocation':
                $locations = $relationalTypeResolver->resolveValue($resultItem, 'locations', $variables, $expressions, $options);
                if (GeneralUtils::isError($locations)) {
                    return $locations;
                }
                return !empty($locations);

            case 'location':
                $locations = $relationalTypeResolver->resolveValue($resultItem, 'locations', $variables, $expressions, $options);
                if (GeneralUtils::isError($locations)) {
                    return $locations;
                } elseif ($locations) {
                    return $locations[0];
                }
                return null;
        }

        return parent::resolveValue($relationalTypeResolver, $resultItem, $fieldName, $fieldArgs, $variables, $expressions, $options);
    }

    public function getFieldTypeResolverClass(RelationalTypeResolverInterface $relationalTypeResolver, string $fieldName): ?string
    {
        switch ($fieldName) {
            case 'location':
                return LocationTypeResolver::class;
        }

        return parent::getFieldTypeResolverClass($relationalTypeResolver, $fieldName);
    }
}

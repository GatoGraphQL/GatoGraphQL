<?php

declare(strict_types=1);

namespace PoPSchema\QueriedObject\FieldResolvers\InterfaceType;

use PoPSchema\SchemaCommons\TypeResolvers\ScalarType\URLScalarTypeResolver;
use PoP\Engine\TypeResolvers\ScalarType\StringScalarTypeResolver;
use PoP\ComponentModel\FieldResolvers\InterfaceType\AbstractInterfaceTypeFieldResolver;
use PoP\ComponentModel\Schema\SchemaDefinition;
use PoP\ComponentModel\Schema\SchemaTypeModifiers;
use PoPSchema\QueriedObject\TypeResolvers\InterfaceType\QueryableInterfaceTypeResolver;

class QueryableInterfaceTypeFieldResolver extends AbstractInterfaceTypeFieldResolver
{
    public function getInterfaceTypeResolverClassesToAttachTo(): array
    {
        return [
            QueryableInterfaceTypeResolver::class,
        ];
    }

    public function getFieldNamesToImplement(): array
    {
        return [
            'url',
            'urlPath',
            'slug',
        ];
    }

    public function getFieldTypeResolverClass(string $fieldName): string
    {
        $types = [
            'url' => URLScalarTypeResolver::class,
            'urlPath' => StringScalarTypeResolver::class,
            'slug' => StringScalarTypeResolver::class,
        ];
        return $types[$fieldName] ?? parent::getFieldTypeResolverClass($fieldName);
    }

    public function getSchemaFieldTypeModifiers(string $fieldName): ?int
    {
        return match ($fieldName) {
            'url',
            'urlPath',
            'slug'
                => SchemaTypeModifiers::NON_NULLABLE,
            default
                => parent::getSchemaFieldTypeModifiers($fieldName),
        };
    }

    public function getSchemaFieldDescription(string $fieldName): ?string
    {
        $descriptions = [
            'url' => $this->translationAPI->__('URL to query the object', 'queriedobject'),
            'urlPath' => $this->translationAPI->__('URL path to query the object', 'queriedobject'),
            'slug' => $this->translationAPI->__('URL\'s slug', 'queriedobject'),
        ];
        return $descriptions[$fieldName] ?? parent::getSchemaFieldDescription($fieldName);
    }
}

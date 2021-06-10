<?php

declare(strict_types=1);

namespace PoPSchema\QueriedObject\FieldInterfaceResolvers;

use PoP\ComponentModel\Schema\SchemaDefinition;
use PoP\ComponentModel\FieldInterfaceResolvers\AbstractSchemaFieldInterfaceResolver;

class QueryableFieldInterfaceResolver extends AbstractSchemaFieldInterfaceResolver
{
    public function getInterfaceName(): string
    {
        return 'Queryable';
    }

    public function getSchemaInterfaceDescription(): ?string
    {
        return $this->translationAPI->__('Entities that can be queried through an URL', 'queriedobject');
    }

    public function getFieldNamesToImplement(): array
    {
        return [
            'url',
            'slug',
        ];
    }

    public function getSchemaFieldType(string $fieldName): string
    {
        $types = [
            'url' => SchemaDefinition::TYPE_URL,
            'slug' => SchemaDefinition::TYPE_STRING,
        ];
        return $types[$fieldName] ?? parent::getSchemaFieldType($fieldName);
    }

    public function getSchemaFieldDescription(string $fieldName): ?string
    {
        $descriptions = [
            'url' => $this->translationAPI->__('URL to query the object', 'queriedobject'),
            'slug' => $this->translationAPI->__('URL\'s slug', 'queriedobject'),
        ];
        return $descriptions[$fieldName] ?? parent::getSchemaFieldDescription($fieldName);
    }
}

<?php

declare(strict_types=1);

namespace PoPSchema\Users\FieldInterfaceResolvers;

use PoP\ComponentModel\FieldInterfaceResolvers\AbstractSchemaFieldInterfaceResolver;
use PoP\ComponentModel\Schema\SchemaDefinition;
use PoP\ComponentModel\Schema\SchemaTypeModifiers;
use PoPSchema\Users\TypeResolvers\UserTypeResolver;

class WithAuthorFieldInterfaceResolver extends AbstractSchemaFieldInterfaceResolver
{
    public function getInterfaceName(): string
    {
        return 'WithAuthor';
    }

    public function getSchemaInterfaceDescription(): ?string
    {
        return $this->translationAPI->__('Entities that have an author', 'queriedobject');
    }

    public function getFieldNamesToImplement(): array
    {
        return [
            'author',
        ];
    }

    public function getSchemaFieldType(string $fieldName): string
    {
        $types = [
            'author' => SchemaDefinition::TYPE_ID,
        ];
        return $types[$fieldName] ?? parent::getSchemaFieldType($fieldName);
    }

    public function getSchemaFieldTypeModifiers(string $fieldName): ?int
    {
        switch ($fieldName) {
            case 'author':
                return SchemaTypeModifiers::NON_NULLABLE;
        }
        return parent::getSchemaFieldTypeModifiers($fieldName);
    }

    public function getSchemaFieldDescription(string $fieldName): ?string
    {
        $descriptions = [
            'author' => $this->translationAPI->__('The entity\'s author', 'queriedobject'),
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
            case 'author':
                return UserTypeResolver::class;
        }

        return parent::getFieldTypeResolverClass($fieldName);
    }
}

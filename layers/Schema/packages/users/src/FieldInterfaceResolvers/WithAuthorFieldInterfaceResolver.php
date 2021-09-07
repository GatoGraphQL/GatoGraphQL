<?php

declare(strict_types=1);

namespace PoPSchema\Users\FieldInterfaceResolvers;

use PoP\ComponentModel\FieldInterfaceResolvers\AbstractFieldInterfaceResolver;
use PoP\ComponentModel\Schema\SchemaTypeModifiers;
use PoPSchema\Users\TypeResolvers\Object\UserTypeResolver;

class WithAuthorFieldInterfaceResolver extends AbstractFieldInterfaceResolver
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

    public function getFieldTypeResolverClass(string $fieldName): ?string
    {
        switch ($fieldName) {
            case 'author':
                return UserTypeResolver::class;
        }

        return parent::getFieldTypeResolverClass($fieldName);
    }
}

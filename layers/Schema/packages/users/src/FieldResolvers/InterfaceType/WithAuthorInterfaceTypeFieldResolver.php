<?php

declare(strict_types=1);

namespace PoPSchema\Users\FieldResolvers\InterfaceType;

use PoP\ComponentModel\TypeResolvers\ConcreteTypeResolverInterface;
use PoP\ComponentModel\FieldResolvers\InterfaceType\AbstractInterfaceTypeFieldResolver;
use PoP\ComponentModel\Schema\SchemaTypeModifiers;
use PoPSchema\Users\TypeResolvers\InterfaceType\WithAuthorInterfaceTypeResolver;
use PoPSchema\Users\TypeResolvers\ObjectType\UserObjectTypeResolver;

class WithAuthorInterfaceTypeFieldResolver extends AbstractInterfaceTypeFieldResolver
{
    public function getInterfaceTypeResolverClassesToAttachTo(): array
    {
        return [
            WithAuthorInterfaceTypeResolver::class,
        ];
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

    public function getFieldTypeResolver(string $fieldName): ConcreteTypeResolverInterface
    {
        switch ($fieldName) {
            case 'author':
                return $this->instanceManager->getInstance(UserObjectTypeResolver::class);
        }

        return parent::getFieldTypeResolver($fieldName);
    }
}

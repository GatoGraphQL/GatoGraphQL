<?php

declare(strict_types=1);

namespace PoPSchema\QueriedObject\FieldResolvers\InterfaceType;

use PoP\ComponentModel\FieldResolvers\InterfaceType\AbstractInterfaceTypeFieldResolver;
use PoP\ComponentModel\Schema\SchemaTypeModifiers;
use PoP\ComponentModel\TypeResolvers\ConcreteTypeResolverInterface;
use PoP\ComponentModel\TypeResolvers\ScalarType\StringScalarTypeResolver;
use PoPSchema\QueriedObject\TypeResolvers\InterfaceType\QueryableInterfaceTypeResolver;
use PoPSchema\SchemaCommons\TypeResolvers\ScalarType\URLAbsolutePathScalarTypeResolver;
use PoPSchema\SchemaCommons\TypeResolvers\ScalarType\URLScalarTypeResolver;

class QueryableInterfaceTypeFieldResolver extends AbstractInterfaceTypeFieldResolver
{
    private ?URLScalarTypeResolver $urlScalarTypeResolver = null;
    private ?URLAbsolutePathScalarTypeResolver $urlAbsolutePathScalarTypeResolver = null;
    private ?StringScalarTypeResolver $stringScalarTypeResolver = null;

    final public function setURLScalarTypeResolver(URLScalarTypeResolver $urlScalarTypeResolver): void
    {
        $this->urlScalarTypeResolver = $urlScalarTypeResolver;
    }
    final protected function getURLScalarTypeResolver(): URLScalarTypeResolver
    {
        return $this->urlScalarTypeResolver ??= $this->instanceManager->getInstance(URLScalarTypeResolver::class);
    }
    final public function setURLAbsolutePathScalarTypeResolver(URLAbsolutePathScalarTypeResolver $urlAbsolutePathScalarTypeResolver): void
    {
        $this->urlAbsolutePathScalarTypeResolver = $urlAbsolutePathScalarTypeResolver;
    }
    final protected function getURLAbsolutePathScalarTypeResolver(): URLAbsolutePathScalarTypeResolver
    {
        return $this->urlAbsolutePathScalarTypeResolver ??= $this->instanceManager->getInstance(URLAbsolutePathScalarTypeResolver::class);
    }
    final public function setStringScalarTypeResolver(StringScalarTypeResolver $stringScalarTypeResolver): void
    {
        $this->stringScalarTypeResolver = $stringScalarTypeResolver;
    }
    final protected function getStringScalarTypeResolver(): StringScalarTypeResolver
    {
        return $this->stringScalarTypeResolver ??= $this->instanceManager->getInstance(StringScalarTypeResolver::class);
    }

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
            'urlAbsolutePath',
            'slug',
        ];
    }

    public function getFieldTypeResolver(string $fieldName): ConcreteTypeResolverInterface
    {
        return match ($fieldName) {
            'url' => $this->getURLScalarTypeResolver(),
            'urlAbsolutePath' => $this->getURLAbsolutePathScalarTypeResolver(),
            'slug' => $this->getStringScalarTypeResolver(),
            default => parent::getFieldTypeResolver($fieldName),
        };
    }

    public function getFieldTypeModifiers(string $fieldName): int
    {
        return match ($fieldName) {
            'url',
            'urlAbsolutePath',
            'slug'
                => SchemaTypeModifiers::NON_NULLABLE,
            default
                => parent::getFieldTypeModifiers($fieldName),
        };
    }

    public function getFieldDescription(string $fieldName): ?string
    {
        return match ($fieldName) {
            'url' => $this->__('URL to query the object', 'queriedobject'),
            'urlAbsolutePath' => $this->__('URL path to query the object', 'queriedobject'),
            'slug' => $this->__('URL\'s slug', 'queriedobject'),
            default => parent::getFieldDescription($fieldName),
        };
    }
}

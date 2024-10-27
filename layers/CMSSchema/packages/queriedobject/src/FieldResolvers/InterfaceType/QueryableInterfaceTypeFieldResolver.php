<?php

declare(strict_types=1);

namespace PoPCMSSchema\QueriedObject\FieldResolvers\InterfaceType;

use PoP\ComponentModel\TypeResolvers\InterfaceType\InterfaceTypeResolverInterface;
use PoP\ComponentModel\FieldResolvers\InterfaceType\AbstractInterfaceTypeFieldResolver;
use PoP\ComponentModel\Schema\SchemaTypeModifiers;
use PoP\ComponentModel\TypeResolvers\ConcreteTypeResolverInterface;
use PoP\ComponentModel\TypeResolvers\ScalarType\StringScalarTypeResolver;
use PoPCMSSchema\QueriedObject\TypeResolvers\InterfaceType\QueryableInterfaceTypeResolver;
use PoPSchema\SchemaCommons\TypeResolvers\ScalarType\URLAbsolutePathScalarTypeResolver;
use PoPSchema\SchemaCommons\TypeResolvers\ScalarType\URLScalarTypeResolver;

class QueryableInterfaceTypeFieldResolver extends AbstractInterfaceTypeFieldResolver
{
    private ?URLScalarTypeResolver $urlScalarTypeResolver = null;
    private ?URLAbsolutePathScalarTypeResolver $urlAbsolutePathScalarTypeResolver = null;
    private ?StringScalarTypeResolver $stringScalarTypeResolver = null;

    final protected function getURLScalarTypeResolver(): URLScalarTypeResolver
    {
        if ($this->urlScalarTypeResolver === null) {
            /** @var URLScalarTypeResolver */
            $urlScalarTypeResolver = $this->instanceManager->getInstance(URLScalarTypeResolver::class);
            $this->urlScalarTypeResolver = $urlScalarTypeResolver;
        }
        return $this->urlScalarTypeResolver;
    }
    final protected function getURLAbsolutePathScalarTypeResolver(): URLAbsolutePathScalarTypeResolver
    {
        if ($this->urlAbsolutePathScalarTypeResolver === null) {
            /** @var URLAbsolutePathScalarTypeResolver */
            $urlAbsolutePathScalarTypeResolver = $this->instanceManager->getInstance(URLAbsolutePathScalarTypeResolver::class);
            $this->urlAbsolutePathScalarTypeResolver = $urlAbsolutePathScalarTypeResolver;
        }
        return $this->urlAbsolutePathScalarTypeResolver;
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

    /**
     * @return array<class-string<InterfaceTypeResolverInterface>>
     */
    public function getInterfaceTypeResolverClassesToAttachTo(): array
    {
        return [
            QueryableInterfaceTypeResolver::class,
        ];
    }

    /**
     * @return string[]
     */
    public function getFieldNamesToImplement(): array
    {
        return [
            'url',
            'urlPath',
            'slug',
        ];
    }

    public function getFieldTypeResolver(string $fieldName): ConcreteTypeResolverInterface
    {
        return match ($fieldName) {
            'url' => $this->getURLScalarTypeResolver(),
            'urlPath' => $this->getURLAbsolutePathScalarTypeResolver(),
            'slug' => $this->getStringScalarTypeResolver(),
            default => parent::getFieldTypeResolver($fieldName),
        };
    }

    public function getFieldTypeModifiers(string $fieldName): int
    {
        return match ($fieldName) {
            'url',
            'urlPath',
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
            'urlPath' => $this->__('URL path to query the object', 'queriedobject'),
            'slug' => $this->__('URL\'s slug', 'queriedobject'),
            default => parent::getFieldDescription($fieldName),
        };
    }
}

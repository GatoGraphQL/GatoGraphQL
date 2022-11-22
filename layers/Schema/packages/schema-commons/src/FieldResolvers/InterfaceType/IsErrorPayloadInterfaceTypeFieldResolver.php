<?php

declare(strict_types=1);

namespace PoPSchema\SchemaCommons\FieldResolvers\InterfaceType;

use PoPSchema\SchemaCommons\TypeResolvers\InterfaceType\IsErrorPayloadInterfaceTypeResolver;
use PoPSchema\SchemaCommons\TypeResolvers\ScalarType\StringOrIntScalarTypeResolver;
use PoP\ComponentModel\FieldResolvers\InterfaceType\AbstractInterfaceTypeFieldResolver;
use PoP\ComponentModel\Schema\SchemaTypeModifiers;
use PoP\ComponentModel\TypeResolvers\ConcreteTypeResolverInterface;
use PoP\ComponentModel\TypeResolvers\InterfaceType\InterfaceTypeResolverInterface;
use PoP\ComponentModel\TypeResolvers\ScalarType\StringScalarTypeResolver;
use PoP\Engine\TypeResolvers\ScalarType\JSONObjectScalarTypeResolver;

class IsErrorPayloadInterfaceTypeFieldResolver extends AbstractInterfaceTypeFieldResolver
{
    private ?StringOrIntScalarTypeResolver $stringOrIntScalarTypeResolver = null;
    private ?StringScalarTypeResolver $stringScalarTypeResolver = null;
    private ?JSONObjectScalarTypeResolver $jsonObjectScalarTypeResolver = null;

    final public function setStringOrIntScalarTypeResolver(StringOrIntScalarTypeResolver $stringOrIntScalarTypeResolver): void
    {
        $this->stringOrIntScalarTypeResolver = $stringOrIntScalarTypeResolver;
    }
    final protected function getStringOrIntScalarTypeResolver(): StringOrIntScalarTypeResolver
    {
        /** @var StringOrIntScalarTypeResolver */
        return $this->stringOrIntScalarTypeResolver ??= $this->instanceManager->getInstance(StringOrIntScalarTypeResolver::class);
    }
    final public function setStringScalarTypeResolver(StringScalarTypeResolver $stringScalarTypeResolver): void
    {
        $this->stringScalarTypeResolver = $stringScalarTypeResolver;
    }
    final protected function getStringScalarTypeResolver(): StringScalarTypeResolver
    {
        /** @var StringScalarTypeResolver */
        return $this->stringScalarTypeResolver ??= $this->instanceManager->getInstance(StringScalarTypeResolver::class);
    }
    final public function setJSONObjectScalarTypeResolver(JSONObjectScalarTypeResolver $jsonObjectScalarTypeResolver): void
    {
        $this->jsonObjectScalarTypeResolver = $jsonObjectScalarTypeResolver;
    }
    final protected function getJSONObjectScalarTypeResolver(): JSONObjectScalarTypeResolver
    {
        /** @var JSONObjectScalarTypeResolver */
        return $this->jsonObjectScalarTypeResolver ??= $this->instanceManager->getInstance(JSONObjectScalarTypeResolver::class);
    }
    
    /**
     * @return array<class-string<InterfaceTypeResolverInterface>>
     */
    public function getInterfaceTypeResolverClassesToAttachTo(): array
    {
        return [
            IsErrorPayloadInterfaceTypeResolver::class,
        ];
    }

    /**
     * @return string[]
     */
    public function getFieldNamesToImplement(): array
    {
        return [
            'message',
            'code',
            'data',
        ];
    }

    public function getFieldTypeResolver(string $fieldName): ConcreteTypeResolverInterface
    {
        return match ($fieldName) {
            'message' => $this->getStringScalarTypeResolver(),
            'code' => $this->getStringOrIntScalarTypeResolver(),
            'data' => $this->getJSONObjectScalarTypeResolver(),
            default
                => parent::getFieldTypeResolver($fieldName),
        };
    }

    public function getFieldTypeModifiers(string $fieldName): int
    {
        return match ($fieldName) {
            'message' => SchemaTypeModifiers::NON_NULLABLE,
            default => parent::getFieldTypeModifiers($fieldName),
        };
    }

    public function getFieldDescription(string $fieldName): ?string
    {
        return match ($fieldName) {
            'message' => $this->__('Error message', 'schema-commons'),
            'code' => $this->__('Error code', 'schema-commons'),
            'data' => $this->__('Error data', 'schema-commons'),
            default => parent::getFieldDescription($fieldName),
        };
    }
}

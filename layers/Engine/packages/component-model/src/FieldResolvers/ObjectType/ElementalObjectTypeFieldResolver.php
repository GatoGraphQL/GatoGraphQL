<?php

declare(strict_types=1);

namespace PoP\ComponentModel\FieldResolvers\ObjectType;

use PoP\ComponentModel\FieldResolvers\InterfaceType\ElementalInterfaceTypeFieldResolver;
use PoP\ComponentModel\Schema\SchemaTypeModifiers;
use PoP\ComponentModel\TypeResolvers\ConcreteTypeResolverInterface;
use PoP\ComponentModel\TypeResolvers\ObjectType\AbstractObjectTypeResolver;
use PoP\ComponentModel\TypeResolvers\ObjectType\ObjectTypeResolverInterface;
use Symfony\Contracts\Service\Attribute\Required;

class ElementalObjectTypeFieldResolver extends AbstractObjectTypeFieldResolver
{
    protected ElementalInterfaceTypeFieldResolver $elementalInterfaceTypeFieldResolver;

    #[Required]
    public function autowireElementalObjectTypeFieldResolver(
        ElementalInterfaceTypeFieldResolver $elementalInterfaceTypeFieldResolver,
    ): void {
        $this->elementalInterfaceTypeFieldResolver = $elementalInterfaceTypeFieldResolver;
    }

    public function getObjectTypeResolverClassesToAttachTo(): array
    {
        return [
            AbstractObjectTypeResolver::class,
        ];
    }

    public function getImplementedInterfaceTypeFieldResolvers(): array
    {
        return [
            $this->elementalInterfaceTypeFieldResolver,
        ];
    }

    public function getFieldNamesToResolve(): array
    {
        return [
            'id',
            'self',
        ];
    }

    public function getSchemaFieldTypeModifiers(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName): ?int
    {
        switch ($fieldName) {
            case 'self':
                return SchemaTypeModifiers::NON_NULLABLE;
        }
        return parent::getSchemaFieldTypeModifiers($objectTypeResolver, $fieldName);
    }

    public function getSchemaFieldDescription(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName): ?string
    {
        return match ($fieldName) {
            'self' => $this->translationAPI->__('The same object', 'pop-component-model'),
            default => parent::getSchemaFieldDescription($objectTypeResolver, $fieldName),
        };
    }

    /**
     * @param array<string, mixed> $fieldArgs
     * @param array<string, mixed>|null $variables
     * @param array<string, mixed>|null $expressions
     * @param array<string, mixed> $options
     */
    public function resolveValue(
        ObjectTypeResolverInterface $objectTypeResolver,
        object $object,
        string $fieldName,
        array $fieldArgs = [],
        ?array $variables = null,
        ?array $expressions = null,
        array $options = []
    ): mixed {
        switch ($fieldName) {
            case 'id':
            case 'self':
                return $objectTypeResolver->getID($object);
        }

        return parent::resolveValue($objectTypeResolver, $object, $fieldName, $fieldArgs, $variables, $expressions, $options);
    }

    public function getFieldTypeResolver(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName): ConcreteTypeResolverInterface
    {
        switch ($fieldName) {
            case 'self':
                return $objectTypeResolver;
        }
        return parent::getFieldTypeResolver($objectTypeResolver, $fieldName);
    }
}

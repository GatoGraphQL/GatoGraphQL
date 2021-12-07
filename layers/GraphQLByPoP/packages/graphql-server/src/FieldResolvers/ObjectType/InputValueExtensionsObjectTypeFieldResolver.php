<?php

declare(strict_types=1);

namespace GraphQLByPoP\GraphQLServer\FieldResolvers\ObjectType;

use GraphQLByPoP\GraphQLServer\ObjectModels\InputValueExtensions;
use GraphQLByPoP\GraphQLServer\TypeResolvers\ObjectType\InputValueExtensionsObjectTypeResolver;
use PoP\ComponentModel\FieldResolvers\ObjectType\AbstractObjectTypeFieldResolver;
use PoP\ComponentModel\Schema\SchemaTypeModifiers;
use PoP\ComponentModel\TypeResolvers\ConcreteTypeResolverInterface;
use PoP\ComponentModel\TypeResolvers\ObjectType\ObjectTypeResolverInterface;
use PoP\Engine\TypeResolvers\ScalarType\BooleanScalarTypeResolver;

class InputValueExtensionsObjectTypeFieldResolver extends AbstractObjectTypeFieldResolver
{
    private ?BooleanScalarTypeResolver $booleanScalarTypeResolver = null;

    final public function setBooleanScalarTypeResolver(BooleanScalarTypeResolver $booleanScalarTypeResolver): void
    {
        $this->booleanScalarTypeResolver = $booleanScalarTypeResolver;
    }
    final protected function getBooleanScalarTypeResolver(): BooleanScalarTypeResolver
    {
        return $this->booleanScalarTypeResolver ??= $this->instanceManager->getInstance(BooleanScalarTypeResolver::class);
    }

    public function getObjectTypeResolverClassesToAttachTo(): array
    {
        return [
            InputValueExtensionsObjectTypeResolver::class,
        ];
    }

    public function getFieldNamesToResolve(): array
    {
        return [
            'isAdminElement',
        ];
    }

    public function getFieldDescription(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName): ?string
    {
        return match ($fieldName) {
            'isAdminElement' => $this->getTranslationAPI()->__('Is this element considered an \'admin\' element in the schema? (If so, it is only exposed in the schema when \'Expose admin elements\' is enabled)', 'graphql-server'),
            default => parent::getFieldDescription($objectTypeResolver, $fieldName),
        };
    }

    public function getFieldTypeModifiers(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName): int
    {
        return match ($fieldName) {
            'isAdminElement' => SchemaTypeModifiers::NON_NULLABLE,
            default => parent::getFieldTypeModifiers($objectTypeResolver, $fieldName),
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
        array $fieldArgs,
        ?array $variables = null,
        ?array $expressions = null,
        array $options = []
    ): mixed {
        /** @var InputValueExtensions */
        $inputValueExtensions = $object;
        return match ($fieldName) {
            'isAdminElement' => $inputValueExtensions->isAdminElement(),
            default => parent::resolveValue($objectTypeResolver, $object, $fieldName, $fieldArgs, $variables, $expressions, $options),
        };
    }

    public function getFieldTypeResolver(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName): ConcreteTypeResolverInterface
    {
        return match ($fieldName) {
            'isAdminElement' => $this->getBooleanScalarTypeResolver(),
            default => parent::getFieldTypeResolver($objectTypeResolver, $fieldName),
        };
    }
}

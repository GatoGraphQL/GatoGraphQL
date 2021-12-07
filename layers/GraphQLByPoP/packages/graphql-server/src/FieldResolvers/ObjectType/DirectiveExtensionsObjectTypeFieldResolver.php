<?php

declare(strict_types=1);

namespace GraphQLByPoP\GraphQLServer\FieldResolvers\ObjectType;

use GraphQLByPoP\GraphQLServer\ObjectModels\DirectiveExtensions;
use GraphQLByPoP\GraphQLServer\TypeResolvers\ObjectType\DirectiveExtensionsObjectTypeResolver;
use PoP\ComponentModel\FieldResolvers\ObjectType\AbstractObjectTypeFieldResolver;
use PoP\ComponentModel\Schema\SchemaTypeModifiers;
use PoP\ComponentModel\TypeResolvers\ConcreteTypeResolverInterface;
use PoP\ComponentModel\TypeResolvers\ObjectType\ObjectTypeResolverInterface;
use PoP\Engine\TypeResolvers\ScalarType\StringScalarTypeResolver;

class DirectiveExtensionsObjectTypeFieldResolver extends AbstractObjectTypeFieldResolver
{
    private ?StringScalarTypeResolver $stringScalarTypeResolver = null;

    final public function setStringScalarTypeResolver(StringScalarTypeResolver $stringScalarTypeResolver): void
    {
        $this->stringScalarTypeResolver = $stringScalarTypeResolver;
    }
    final protected function getStringScalarTypeResolver(): StringScalarTypeResolver
    {
        return $this->stringScalarTypeResolver ??= $this->instanceManager->getInstance(StringScalarTypeResolver::class);
    }

    public function getObjectTypeResolverClassesToAttachTo(): array
    {
        return [
            DirectiveExtensionsObjectTypeResolver::class,
        ];
    }

    public function getFieldNamesToResolve(): array
    {
        return [
            'version',
        ];
    }

    public function getFieldDescription(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName): ?string
    {
        return match ($fieldName) {
            'version' => $this->getTranslationAPI()->__('The version of the directive, if any', 'graphql-server'),
            default => parent::getFieldDescription($objectTypeResolver, $fieldName),
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
        /** @var DirectiveExtensions */
        $directiveExtensions = $object;
        return match ($fieldName) {
            'version' => $directiveExtensions->getVersion(),
            default => parent::resolveValue($objectTypeResolver, $object, $fieldName, $fieldArgs, $variables, $expressions, $options),
        };
    }

    public function getFieldTypeResolver(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName): ConcreteTypeResolverInterface
    {
        return match ($fieldName) {
            'version' => $this->getStringScalarTypeResolver(),
            default => parent::getFieldTypeResolver($objectTypeResolver, $fieldName),
        };
    }
}

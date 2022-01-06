<?php

declare(strict_types=1);

namespace PoPSchema\Users\FieldResolvers\ObjectType;

use PoP\ComponentModel\Schema\SchemaTypeModifiers;
use PoP\ComponentModel\TypeResolvers\ConcreteTypeResolverInterface;
use PoP\ComponentModel\TypeResolvers\ObjectType\ObjectTypeResolverInterface;
use PoP\Engine\TypeResolvers\ObjectType\RootObjectTypeResolver;
use PoP\Engine\TypeResolvers\ScalarType\StringScalarTypeResolver;
use PoPSchema\SchemaCommons\Constants\QueryOptions;
use PoPSchema\SchemaCommons\DataLoading\ReturnTypes;
use PoPSchema\Users\TypeResolvers\InputObjectType\UserByInputObjectTypeResolver;

class RootUserObjectTypeFieldResolver extends AbstractUserObjectTypeFieldResolver
{
    private ?StringScalarTypeResolver $stringScalarTypeResolver = null;
    private ?UserByInputObjectTypeResolver $userByInputObjectTypeResolver = null;

    final public function setStringScalarTypeResolver(StringScalarTypeResolver $stringScalarTypeResolver): void
    {
        $this->stringScalarTypeResolver = $stringScalarTypeResolver;
    }
    final protected function getStringScalarTypeResolver(): StringScalarTypeResolver
    {
        return $this->stringScalarTypeResolver ??= $this->instanceManager->getInstance(StringScalarTypeResolver::class);
    }
    final public function setUserByInputObjectTypeResolver(UserByInputObjectTypeResolver $userByInputObjectTypeResolver): void
    {
        $this->userByInputObjectTypeResolver = $userByInputObjectTypeResolver;
    }
    final protected function getUserByInputObjectTypeResolver(): UserByInputObjectTypeResolver
    {
        return $this->userByInputObjectTypeResolver ??= $this->instanceManager->getInstance(UserByInputObjectTypeResolver::class);
    }

    public function getObjectTypeResolverClassesToAttachTo(): array
    {
        return [
            RootObjectTypeResolver::class,
        ];
    }

    public function getFieldNamesToResolve(): array
    {
        return array_merge(
            parent::getFieldNamesToResolve(),
            [
                'user',
            ]
        );
    }

    public function getFieldDescription(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName): ?string
    {
        return match ($fieldName) {
            'user' => $this->__('Retrieve a single user', 'pop-users'),
            'users' => $this->__('Retrieve a list of users', 'pop-users'),
            default => parent::getFieldDescription($objectTypeResolver, $fieldName),
        };
    }

    public function getFieldArgNameTypeResolvers(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName): array
    {
        $fieldArgNameTypeResolvers = parent::getFieldArgNameTypeResolvers($objectTypeResolver, $fieldName);
        return match ($fieldName) {
            'user' => array_merge(
                $fieldArgNameTypeResolvers,
                [
                    'by' => $this->getUserByInputObjectTypeResolver(),
                ]
            ),
            default => $fieldArgNameTypeResolvers,
        };
    }

    public function getFieldArgTypeModifiers(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName, string $fieldArgName): int
    {
        return match ([$fieldName => $fieldArgName]) {
            ['user' => 'by'] => SchemaTypeModifiers::MANDATORY,
            default => parent::getFieldArgTypeModifiers($objectTypeResolver, $fieldName, $fieldArgName),
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
        $query = $this->convertFieldArgsToFilteringQueryArgs($objectTypeResolver, $fieldName, $fieldArgs);
        switch ($fieldName) {
            case 'user':
                if ($users = $this->getUserTypeAPI()->getUsers($query, [QueryOptions::RETURN_TYPE => ReturnTypes::IDS])) {
                    return $users[0];
                }
                return null;
        }

        return parent::resolveValue($objectTypeResolver, $object, $fieldName, $fieldArgs, $variables, $expressions, $options);
    }

    public function getFieldTypeResolver(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName): ConcreteTypeResolverInterface
    {
        return match ($fieldName) {
            'user' => $this->getUserObjectTypeResolver(),
            default => parent::getFieldTypeResolver($objectTypeResolver, $fieldName),
        };
    }
}

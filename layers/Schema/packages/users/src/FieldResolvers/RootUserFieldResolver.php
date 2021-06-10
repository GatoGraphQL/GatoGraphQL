<?php

declare(strict_types=1);

namespace PoPSchema\Users\FieldResolvers;

use PoP\ComponentModel\Schema\SchemaDefinition;
use PoP\ComponentModel\TypeResolvers\TypeResolverInterface;
use PoP\Engine\TypeResolvers\RootTypeResolver;
use PoPSchema\SchemaCommons\DataLoading\ReturnTypes;
use PoPSchema\Users\FieldResolvers\AbstractUserFieldResolver;
use PoPSchema\Users\TypeResolvers\UserTypeResolver;

class RootUserFieldResolver extends AbstractUserFieldResolver
{
    public function getClassesToAttachTo(): array
    {
        return array(RootTypeResolver::class);
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

    public function getSchemaFieldDescription(TypeResolverInterface $typeResolver, string $fieldName): ?string
    {
        $descriptions = [
            'user' => $this->translationAPI->__('User with a specific ID', 'pop-users'),
            'users' => $this->translationAPI->__('Users in the current site', 'pop-users'),
        ];
        return $descriptions[$fieldName] ?? parent::getSchemaFieldDescription($typeResolver, $fieldName);
    }

    public function getSchemaFieldType(TypeResolverInterface $typeResolver, string $fieldName): string
    {
        $types = [
            'user' => SchemaDefinition::TYPE_ID,
        ];
        return $types[$fieldName] ?? parent::getSchemaFieldType($typeResolver, $fieldName);
    }

    public function getSchemaFieldArgs(TypeResolverInterface $typeResolver, string $fieldName): array
    {
        $schemaFieldArgs = parent::getSchemaFieldArgs($typeResolver, $fieldName);
        switch ($fieldName) {
            case 'user':
                return array_merge(
                    $schemaFieldArgs,
                    [
                        [
                            SchemaDefinition::ARGNAME_NAME => 'id',
                            SchemaDefinition::ARGNAME_TYPE => SchemaDefinition::TYPE_ID,
                            SchemaDefinition::ARGNAME_DESCRIPTION => $this->translationAPI->__('The user ID', 'pop-users'),
                            SchemaDefinition::ARGNAME_MANDATORY => true,
                        ],
                    ]
                );
        }
        return $schemaFieldArgs;
    }

    /**
     * @param array<string, mixed> $fieldArgs
     * @param array<string, mixed>|null $variables
     * @param array<string, mixed>|null $expressions
     * @param array<string, mixed> $options
     */
    public function resolveValue(
        TypeResolverInterface $typeResolver,
        object $resultItem,
        string $fieldName,
        array $fieldArgs = [],
        ?array $variables = null,
        ?array $expressions = null,
        array $options = []
    ): mixed {
        switch ($fieldName) {
            case 'user':
                $query = [
                    'include' => [$fieldArgs['id']],
                ];
                $options = [
                    'return-type' => ReturnTypes::IDS,
                ];
                if ($users = $this->userTypeAPI->getUsers($query, $options)) {
                    return $users[0];
                }
                return null;
        }

        return parent::resolveValue($typeResolver, $resultItem, $fieldName, $fieldArgs, $variables, $expressions, $options);
    }

    public function resolveFieldTypeResolverClass(TypeResolverInterface $typeResolver, string $fieldName): ?string
    {
        switch ($fieldName) {
            case 'user':
                return UserTypeResolver::class;
        }

        return parent::resolveFieldTypeResolverClass($typeResolver, $fieldName);
    }
}

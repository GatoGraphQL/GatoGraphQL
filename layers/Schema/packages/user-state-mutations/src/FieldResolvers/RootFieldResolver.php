<?php

declare(strict_types=1);

namespace PoPSchema\UserStateMutations\FieldResolvers;

use PoP\Engine\TypeResolvers\RootTypeResolver;
use PoP\ComponentModel\Schema\SchemaDefinition;
use PoPSchema\Users\TypeResolvers\UserTypeResolver;
use PoP\ComponentModel\TypeResolvers\TypeResolverInterface;
use PoP\ComponentModel\FieldResolvers\AbstractQueryableFieldResolver;
use PoPSchema\UserStateMutations\MutationResolvers\LoginMutationResolver;
use PoPSchema\UserStateMutations\MutationResolvers\LogoutMutationResolver;
use PoPSchema\UserStateMutations\MutationResolvers\MutationInputProperties;

class RootFieldResolver extends AbstractQueryableFieldResolver
{
    public function getClassesToAttachTo(): array
    {
        return array(RootTypeResolver::class);
    }

    public function getFieldNamesToResolve(): array
    {
        return [
            'loginUser',
            'logoutUser',
        ];
    }

    public function getSchemaFieldDescription(TypeResolverInterface $typeResolver, string $fieldName): ?string
    {
        $descriptions = [
            'loginUser' => $this->translationAPI->__('Log the user in', 'user-state-mutations'),
            'logoutUser' => $this->translationAPI->__('Log the user out', 'user-state-mutations'),
        ];
        return $descriptions[$fieldName] ?? parent::getSchemaFieldDescription($typeResolver, $fieldName);
    }

    public function getSchemaFieldType(TypeResolverInterface $typeResolver, string $fieldName): string
    {
        $types = [
            'loginUser' => SchemaDefinition::TYPE_ID,
            'logoutUser' => SchemaDefinition::TYPE_ID,
        ];
        return $types[$fieldName] ?? parent::getSchemaFieldType($typeResolver, $fieldName);
    }

    public function getSchemaFieldArgs(TypeResolverInterface $typeResolver, string $fieldName): array
    {
        switch ($fieldName) {
            case 'loginUser':
                return [
                    [
                        SchemaDefinition::ARGNAME_NAME => MutationInputProperties::USERNAME_OR_EMAIL,
                        SchemaDefinition::ARGNAME_TYPE => SchemaDefinition::TYPE_STRING,
                        SchemaDefinition::ARGNAME_DESCRIPTION => $this->translationAPI->__('The username or email', 'user-state-mutations'),
                        SchemaDefinition::ARGNAME_MANDATORY => true,
                    ],
                    [
                        SchemaDefinition::ARGNAME_NAME => MutationInputProperties::PASSWORD,
                        SchemaDefinition::ARGNAME_TYPE => SchemaDefinition::TYPE_STRING,
                        SchemaDefinition::ARGNAME_DESCRIPTION => $this->translationAPI->__('The password', 'user-state-mutations'),
                        SchemaDefinition::ARGNAME_MANDATORY => true,
                    ],
                ];
        }
        return parent::getSchemaFieldArgs($typeResolver, $fieldName);
    }

    public function resolveFieldMutationResolverClass(TypeResolverInterface $typeResolver, string $fieldName): ?string
    {
        switch ($fieldName) {
            case 'loginUser':
                return LoginMutationResolver::class;
            case 'logoutUser':
                return LogoutMutationResolver::class;
        }

        return parent::resolveFieldMutationResolverClass($typeResolver, $fieldName);
    }

    public function resolveFieldTypeResolverClass(TypeResolverInterface $typeResolver, string $fieldName): ?string
    {
        switch ($fieldName) {
            case 'loginUser':
            case 'logoutUser':
                return UserTypeResolver::class;
        }

        return parent::resolveFieldTypeResolverClass($typeResolver, $fieldName);
    }
}

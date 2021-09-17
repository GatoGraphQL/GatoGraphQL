<?php

declare(strict_types=1);

namespace PoPSchema\UserStateMutations\FieldResolvers\ObjectType;

use PoP\ComponentModel\TypeResolvers\ConcreteTypeResolverInterface;
use PoP\Engine\TypeResolvers\ObjectType\RootObjectTypeResolver;
use PoP\ComponentModel\Schema\SchemaDefinition;
use PoPSchema\Users\TypeResolvers\ObjectType\UserObjectTypeResolver;
use PoP\ComponentModel\TypeResolvers\ObjectType\ObjectTypeResolverInterface;
use PoP\ComponentModel\FieldResolvers\ObjectType\AbstractQueryableObjectTypeFieldResolver;
use PoPSchema\UserStateMutations\MutationResolvers\LoginMutationResolver;
use PoPSchema\UserStateMutations\MutationResolvers\LogoutMutationResolver;
use PoPSchema\UserStateMutations\MutationResolvers\MutationInputProperties;

class RootObjectTypeFieldResolver extends AbstractQueryableObjectTypeFieldResolver
{
    public function __construct(
        \PoP\Translation\TranslationAPIInterface $translationAPI,
        \PoP\Hooks\HooksAPIInterface $hooksAPI,
        \PoP\ComponentModel\Instances\InstanceManagerInterface $instanceManager,
        \PoP\ComponentModel\Schema\FieldQueryInterpreterInterface $fieldQueryInterpreter,
        \PoP\LooseContracts\NameResolverInterface $nameResolver,
        \PoP\Engine\CMS\CMSServiceInterface $cmsService,
        \PoP\ComponentModel\HelperServices\SemverHelperServiceInterface $semverHelperService,
    ) {
        parent::__construct(
            $translationAPI,
            $hooksAPI,
            $instanceManager,
            $fieldQueryInterpreter,
            $nameResolver,
            $cmsService,
            $semverHelperService,
        );
    }

    public function getObjectTypeResolverClassesToAttachTo(): array
    {
        return [
            RootObjectTypeResolver::class,
        ];
    }

    public function getFieldNamesToResolve(): array
    {
        return [
            'loginUser',
            'logoutUser',
        ];
    }

    public function getSchemaFieldDescription(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName): ?string
    {
        $descriptions = [
            'loginUser' => $this->translationAPI->__('Log the user in', 'user-state-mutations'),
            'logoutUser' => $this->translationAPI->__('Log the user out', 'user-state-mutations'),
        ];
        return $descriptions[$fieldName] ?? parent::getSchemaFieldDescription($objectTypeResolver, $fieldName);
    }

    public function getSchemaFieldArgs(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName): array
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
        return parent::getSchemaFieldArgs($objectTypeResolver, $fieldName);
    }

    public function getFieldMutationResolverClass(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName): ?string
    {
        switch ($fieldName) {
            case 'loginUser':
                return LoginMutationResolver::class;
            case 'logoutUser':
                return LogoutMutationResolver::class;
        }

        return parent::getFieldMutationResolverClass($objectTypeResolver, $fieldName);
    }

    public function getFieldTypeResolver(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName): ConcreteTypeResolverInterface
    {
        switch ($fieldName) {
            case 'loginUser':
            case 'logoutUser':
                return $this->instanceManager->getInstance(UserObjectTypeResolver::class);
        }

        return parent::getFieldTypeResolver($objectTypeResolver, $fieldName);
    }
}

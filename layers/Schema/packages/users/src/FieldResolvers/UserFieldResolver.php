<?php

declare(strict_types=1);

namespace PoPSchema\Users\FieldResolvers;

use PoPSchema\Users\TypeResolvers\UserTypeResolver;
use PoP\ComponentModel\Schema\SchemaDefinition;
use PoP\Translation\Facades\TranslationAPIFacade;
use PoP\ComponentModel\TypeResolvers\TypeResolverInterface;
use PoP\ComponentModel\FieldResolvers\AbstractDBDataFieldResolver;
use PoPSchema\QueriedObject\FieldInterfaceResolvers\QueryableFieldInterfaceResolver;

class UserFieldResolver extends AbstractDBDataFieldResolver
{
    public function getClassesToAttachTo(): array
    {
        return array(UserTypeResolver::class);
    }

    public static function getImplementedInterfaceClasses(): array
    {
        return [
            QueryableFieldInterfaceResolver::class,
        ];
    }

    public static function getFieldNamesToResolve(): array
    {
        return [
            'username',
            'userNicename',
            'nicename',
            'name',
            'displayName',
            'firstname',
            'lastname',
            'email',
            'url',
            'slug',
            'description',
            'websiteURL',
        ];
    }

    public function getSchemaFieldType(TypeResolverInterface $typeResolver, string $fieldName): ?string
    {
        $types = [
            'username' => SchemaDefinition::TYPE_STRING,
            'userNicename' => SchemaDefinition::TYPE_STRING,
            'nicename' => SchemaDefinition::TYPE_STRING,
            'name' => SchemaDefinition::TYPE_STRING,
            'displayName' => SchemaDefinition::TYPE_STRING,
            'firstname' => SchemaDefinition::TYPE_STRING,
            'lastname' => SchemaDefinition::TYPE_STRING,
            'email' => SchemaDefinition::TYPE_EMAIL,
            'url' => SchemaDefinition::TYPE_URL,
            'slug' => SchemaDefinition::TYPE_STRING,
            'description' => SchemaDefinition::TYPE_STRING,
            'websiteURL' => SchemaDefinition::TYPE_URL,
        ];
        return $types[$fieldName] ?? parent::getSchemaFieldType($typeResolver, $fieldName);
    }

    public function getSchemaFieldDescription(TypeResolverInterface $typeResolver, string $fieldName): ?string
    {
        $translationAPI = TranslationAPIFacade::getInstance();
        $descriptions = [
            'username' => $translationAPI->__('User\'s username handle', 'pop-users'),
            'userNicename' => $translationAPI->__('User\'s nice name', 'pop-users'),
            'nicename' => $translationAPI->__('User\'s nice name', 'pop-users'),
            'name' => $translationAPI->__('Name of the user', 'pop-users'),
            'displayName' => $translationAPI->__('Name of the user as displayed on the website', 'pop-users'),
            'firstname' => $translationAPI->__('User\'s first name', 'pop-users'),
            'lastname' => $translationAPI->__('User\'s last name', 'pop-users'),
            'email' => $translationAPI->__('User\'s email', 'pop-users'),
            'url' => $translationAPI->__('URL of the user\'s profile in the website', 'pop-users'),
            'slug' => $translationAPI->__('Slug of the URL of the user\'s profile in the website', 'pop-users'),
            'description' => $translationAPI->__('Description of the user', 'pop-users'),
            'websiteURL' => $translationAPI->__('User\'s own website\'s URL', 'pop-users'),
        ];
        return $descriptions[$fieldName] ?? parent::getSchemaFieldDescription($typeResolver, $fieldName);
    }

    /**
     * @param array<string, mixed> $fieldArgs
     * @param array<string, mixed>|null $variables
     * @param array<string, mixed>|null $expressions
     * @param array<string, mixed> $options
     * @return mixed
     */
    public function resolveValue(
        TypeResolverInterface $typeResolver,
        object $resultItem,
        string $fieldName,
        array $fieldArgs = [],
        ?array $variables = null,
        ?array $expressions = null,
        array $options = []
    ) {
        $cmsusersresolver = \PoPSchema\Users\ObjectPropertyResolverFactory::getInstance();
        $cmsusersapi = \PoPSchema\Users\FunctionAPIFactory::getInstance();
        $user = $resultItem;
        switch ($fieldName) {
            case 'username':
                return $cmsusersresolver->getUserLogin($user);

            case 'userNicename':
            case 'nicename':
                return $cmsusersresolver->getUserNicename($user);

            case 'name':
            case 'displayName':
                return $cmsusersresolver->getUserDisplayName($user);

            case 'firstname':
                return $cmsusersresolver->getUserFirstname($user);

            case 'lastname':
                return $cmsusersresolver->getUserLastname($user);

            case 'email':
                return $cmsusersresolver->getUserEmail($user);

            case 'url':
                return $cmsusersapi->getUserURL($typeResolver->getID($user));

            case 'slug':
                return $cmsusersresolver->getUserSlug($user);

            case 'description':
                return $cmsusersresolver->getUserDescription($user);

            case 'websiteURL':
                return $cmsusersresolver->getUserWebsiteUrl($user);
        }

        return parent::resolveValue($typeResolver, $resultItem, $fieldName, $fieldArgs, $variables, $expressions, $options);
    }
}

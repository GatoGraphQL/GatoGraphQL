<?php

declare(strict_types=1);

namespace PoPSchema\Users\FieldResolvers;

use PoP\ComponentModel\FieldResolvers\AbstractDBDataFieldResolver;
use PoP\ComponentModel\HelperServices\SemverHelperServiceInterface;
use PoP\ComponentModel\Instances\InstanceManagerInterface;
use PoP\ComponentModel\Schema\FieldQueryInterpreterInterface;
use PoP\ComponentModel\Schema\SchemaDefinition;
use PoP\ComponentModel\Schema\SchemaTypeModifiers;
use PoP\ComponentModel\TypeResolvers\RelationalTypeResolverInterface;
use PoP\Engine\CMS\CMSServiceInterface;
use PoP\Hooks\HooksAPIInterface;
use PoP\LooseContracts\NameResolverInterface;
use PoP\Translation\TranslationAPIInterface;
use PoPSchema\QueriedObject\FieldInterfaceResolvers\QueryableFieldInterfaceResolver;
use PoPSchema\Users\TypeAPIs\UserTypeAPIInterface;
use PoPSchema\Users\TypeResolvers\UserTypeResolver;

class UserFieldResolver extends AbstractDBDataFieldResolver
{
    public function __construct(
        TranslationAPIInterface $translationAPI,
        HooksAPIInterface $hooksAPI,
        InstanceManagerInterface $instanceManager,
        FieldQueryInterpreterInterface $fieldQueryInterpreter,
        NameResolverInterface $nameResolver,
        CMSServiceInterface $cmsService,
        SemverHelperServiceInterface $semverHelperService,
        protected UserTypeAPIInterface $userTypeAPI,
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

    public function getClassesToAttachTo(): array
    {
        return array(UserTypeResolver::class);
    }

    public function getImplementedFieldInterfaceResolverClasses(): array
    {
        return [
            QueryableFieldInterfaceResolver::class,
        ];
    }

    public function getFieldNamesToResolve(): array
    {
        return [
            'username',
            'name',
            'displayName',
            'firstName',
            'lastName',
            'email',
            'url',
            'urlPath',
            'slug',
            'description',
            'websiteURL',
        ];
    }

    public function getSchemaFieldType(RelationalTypeResolverInterface $relationalTypeResolver, string $fieldName): string
    {
        $types = [
            'username' => SchemaDefinition::TYPE_STRING,
            'name' => SchemaDefinition::TYPE_STRING,
            'displayName' => SchemaDefinition::TYPE_STRING,
            'firstName' => SchemaDefinition::TYPE_STRING,
            'lastName' => SchemaDefinition::TYPE_STRING,
            'email' => SchemaDefinition::TYPE_EMAIL,
            'url' => SchemaDefinition::TYPE_URL,
            'urlPath' => SchemaDefinition::TYPE_STRING,
            'slug' => SchemaDefinition::TYPE_STRING,
            'description' => SchemaDefinition::TYPE_STRING,
            'websiteURL' => SchemaDefinition::TYPE_URL,
        ];
        return $types[$fieldName] ?? parent::getSchemaFieldType($relationalTypeResolver, $fieldName);
    }

    public function getSchemaFieldTypeModifiers(RelationalTypeResolverInterface $relationalTypeResolver, string $fieldName): ?int
    {
        return match ($fieldName) {
            'username',
            'name',
            'displayName',
            'url',
            'urlPath',
            'slug'
                => SchemaTypeModifiers::NON_NULLABLE,
            default
                => parent::getSchemaFieldTypeModifiers($relationalTypeResolver, $fieldName),
        };
    }

    public function getSchemaFieldDescription(RelationalTypeResolverInterface $relationalTypeResolver, string $fieldName): ?string
    {
        $descriptions = [
            'username' => $this->translationAPI->__('User\'s username handle', 'pop-users'),
            'name' => $this->translationAPI->__('Name of the user', 'pop-users'),
            'displayName' => $this->translationAPI->__('Name of the user as displayed on the website', 'pop-users'),
            'firstName' => $this->translationAPI->__('User\'s first name', 'pop-users'),
            'lastName' => $this->translationAPI->__('User\'s last name', 'pop-users'),
            'email' => $this->translationAPI->__('User\'s email', 'pop-users'),
            'url' => $this->translationAPI->__('URL of the user\'s profile in the website', 'pop-users'),
            'urlPath' => $this->translationAPI->__('URL path of the user\'s profile in the website', 'pop-users'),
            'slug' => $this->translationAPI->__('Slug of the URL of the user\'s profile in the website', 'pop-users'),
            'description' => $this->translationAPI->__('Description of the user', 'pop-users'),
            'websiteURL' => $this->translationAPI->__('User\'s own website\'s URL', 'pop-users'),
        ];
        return $descriptions[$fieldName] ?? parent::getSchemaFieldDescription($relationalTypeResolver, $fieldName);
    }

    /**
     * @param array<string, mixed> $fieldArgs
     * @param array<string, mixed>|null $variables
     * @param array<string, mixed>|null $expressions
     * @param array<string, mixed> $options
     */
    public function resolveValue(
        RelationalTypeResolverInterface $relationalTypeResolver,
        object $resultItem,
        string $fieldName,
        array $fieldArgs = [],
        ?array $variables = null,
        ?array $expressions = null,
        array $options = []
    ): mixed {
        $user = $resultItem;
        switch ($fieldName) {
            case 'username':
                return $this->userTypeAPI->getUserLogin($user);

            case 'name':
            case 'displayName':
                return $this->userTypeAPI->getUserDisplayName($user);

            case 'firstName':
                return $this->userTypeAPI->getUserFirstname($user);

            case 'lastName':
                return $this->userTypeAPI->getUserLastname($user);

            case 'email':
                return $this->userTypeAPI->getUserEmail($user);

            case 'url':
                return $this->userTypeAPI->getUserURL($relationalTypeResolver->getID($user));

            case 'urlPath':
                return $this->userTypeAPI->getUserURLPath($relationalTypeResolver->getID($user));

            case 'slug':
                return $this->userTypeAPI->getUserSlug($user);

            case 'description':
                return $this->userTypeAPI->getUserDescription($user);

            case 'websiteURL':
                return $this->userTypeAPI->getUserWebsiteUrl($user);
        }

        return parent::resolveValue($relationalTypeResolver, $resultItem, $fieldName, $fieldArgs, $variables, $expressions, $options);
    }
}

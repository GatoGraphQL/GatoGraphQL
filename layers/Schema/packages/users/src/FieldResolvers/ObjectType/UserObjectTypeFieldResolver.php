<?php

declare(strict_types=1);

namespace PoPSchema\Users\FieldResolvers\ObjectType;

use PoP\ComponentModel\FieldResolvers\ObjectType\AbstractObjectTypeFieldResolver;
use PoP\ComponentModel\Schema\SchemaTypeModifiers;
use PoP\ComponentModel\TypeResolvers\ConcreteTypeResolverInterface;
use PoP\ComponentModel\TypeResolvers\ObjectType\ObjectTypeResolverInterface;
use PoP\Engine\TypeResolvers\ScalarType\StringScalarTypeResolver;
use PoPSchema\QueriedObject\FieldResolvers\InterfaceType\QueryableInterfaceTypeFieldResolver;
use PoPSchema\SchemaCommons\TypeResolvers\ScalarType\EmailScalarTypeResolver;
use PoPSchema\SchemaCommons\TypeResolvers\ScalarType\URLScalarTypeResolver;
use PoPSchema\Users\TypeAPIs\UserTypeAPIInterface;
use PoPSchema\Users\TypeResolvers\ObjectType\UserObjectTypeResolver;
use Symfony\Contracts\Service\Attribute\Required;

class UserObjectTypeFieldResolver extends AbstractObjectTypeFieldResolver
{
    protected UserTypeAPIInterface $userTypeAPI;
    protected EmailScalarTypeResolver $emailScalarTypeResolver;
    protected StringScalarTypeResolver $stringScalarTypeResolver;
    protected URLScalarTypeResolver $urlScalarTypeResolver;
    protected QueryableInterfaceTypeFieldResolver $queryableInterfaceTypeFieldResolver;

    #[Required]
    public function autowireUserObjectTypeFieldResolver(
        UserTypeAPIInterface $userTypeAPI,
        EmailScalarTypeResolver $emailScalarTypeResolver,
        StringScalarTypeResolver $stringScalarTypeResolver,
        URLScalarTypeResolver $urlScalarTypeResolver,
        QueryableInterfaceTypeFieldResolver $queryableInterfaceTypeFieldResolver,
    ): void {
        $this->userTypeAPI = $userTypeAPI;
        $this->emailScalarTypeResolver = $emailScalarTypeResolver;
        $this->stringScalarTypeResolver = $stringScalarTypeResolver;
        $this->urlScalarTypeResolver = $urlScalarTypeResolver;
        $this->queryableInterfaceTypeFieldResolver = $queryableInterfaceTypeFieldResolver;
    }

    public function getObjectTypeResolverClassesToAttachTo(): array
    {
        return [
            UserObjectTypeResolver::class,
        ];
    }

    public function getImplementedInterfaceTypeFieldResolvers(): array
    {
        return [
            $this->queryableInterfaceTypeFieldResolver,
        ];
    }

    public function getFieldNamesToResolve(): array
    {
        return [
            'url',
            'urlPath',
            'slug',
            'username',
            'name',
            'displayName',
            'firstName',
            'lastName',
            'email',
            'description',
            'websiteURL',
        ];
    }

    public function getFieldTypeResolver(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName): ConcreteTypeResolverInterface
    {
        return match ($fieldName) {
            'username' => $this->stringScalarTypeResolver,
            'name' => $this->stringScalarTypeResolver,
            'displayName' => $this->stringScalarTypeResolver,
            'firstName' => $this->stringScalarTypeResolver,
            'lastName' => $this->stringScalarTypeResolver,
            'email' => $this->emailScalarTypeResolver,
            'description' => $this->stringScalarTypeResolver,
            'websiteURL' => $this->urlScalarTypeResolver,
            default => parent::getFieldTypeResolver($objectTypeResolver, $fieldName),
        };
    }

    public function getFieldTypeModifiers(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName): int
    {
        return match ($fieldName) {
            'username',
            'name',
            'displayName'
                => SchemaTypeModifiers::NON_NULLABLE,
            default
                => parent::getFieldTypeModifiers($objectTypeResolver, $fieldName),
        };
    }

    public function getFieldDescription(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName): ?string
    {
        return match ($fieldName) {
            'url' => $this->translationAPI->__('URL of the user\'s profile in the website', 'pop-users'),
            'urlPath' => $this->translationAPI->__('URL path of the user\'s profile in the website', 'pop-users'),
            'slug' => $this->translationAPI->__('Slug of the URL of the user\'s profile in the website', 'pop-users'),
            'username' => $this->translationAPI->__('User\'s username handle', 'pop-users'),
            'name' => $this->translationAPI->__('Name of the user', 'pop-users'),
            'displayName' => $this->translationAPI->__('Name of the user as displayed on the website', 'pop-users'),
            'firstName' => $this->translationAPI->__('User\'s first name', 'pop-users'),
            'lastName' => $this->translationAPI->__('User\'s last name', 'pop-users'),
            'email' => $this->translationAPI->__('User\'s email', 'pop-users'),
            'description' => $this->translationAPI->__('Description of the user', 'pop-users'),
            'websiteURL' => $this->translationAPI->__('User\'s own website\'s URL', 'pop-users'),
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
        array $fieldArgs = [],
        ?array $variables = null,
        ?array $expressions = null,
        array $options = []
    ): mixed {
        $user = $object;
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
                return $this->userTypeAPI->getUserURL($objectTypeResolver->getID($user));

            case 'urlPath':
                return $this->userTypeAPI->getUserURLPath($objectTypeResolver->getID($user));

            case 'slug':
                return $this->userTypeAPI->getUserSlug($user);

            case 'description':
                return $this->userTypeAPI->getUserDescription($user);

            case 'websiteURL':
                return $this->userTypeAPI->getUserWebsiteUrl($user);
        }

        return parent::resolveValue($objectTypeResolver, $object, $fieldName, $fieldArgs, $variables, $expressions, $options);
    }
}

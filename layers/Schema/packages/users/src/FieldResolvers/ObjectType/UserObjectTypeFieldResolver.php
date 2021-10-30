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
    private ?UserTypeAPIInterface $userTypeAPI = null;
    private ?EmailScalarTypeResolver $emailScalarTypeResolver = null;
    private ?StringScalarTypeResolver $stringScalarTypeResolver = null;
    private ?URLScalarTypeResolver $urlScalarTypeResolver = null;
    private ?QueryableInterfaceTypeFieldResolver $queryableInterfaceTypeFieldResolver = null;

    final public function setUserTypeAPI(UserTypeAPIInterface $userTypeAPI): void
    {
        $this->userTypeAPI = $userTypeAPI;
    }
    final protected function getUserTypeAPI(): UserTypeAPIInterface
    {
        return $this->userTypeAPI ??= $this->instanceManager->getInstance(UserTypeAPIInterface::class);
    }
    final public function setEmailScalarTypeResolver(EmailScalarTypeResolver $emailScalarTypeResolver): void
    {
        $this->emailScalarTypeResolver = $emailScalarTypeResolver;
    }
    final protected function getEmailScalarTypeResolver(): EmailScalarTypeResolver
    {
        return $this->emailScalarTypeResolver ??= $this->instanceManager->getInstance(EmailScalarTypeResolver::class);
    }
    final public function setStringScalarTypeResolver(StringScalarTypeResolver $stringScalarTypeResolver): void
    {
        $this->stringScalarTypeResolver = $stringScalarTypeResolver;
    }
    final protected function getStringScalarTypeResolver(): StringScalarTypeResolver
    {
        return $this->stringScalarTypeResolver ??= $this->instanceManager->getInstance(StringScalarTypeResolver::class);
    }
    final public function setURLScalarTypeResolver(URLScalarTypeResolver $urlScalarTypeResolver): void
    {
        $this->urlScalarTypeResolver = $urlScalarTypeResolver;
    }
    final protected function getURLScalarTypeResolver(): URLScalarTypeResolver
    {
        return $this->urlScalarTypeResolver ??= $this->instanceManager->getInstance(URLScalarTypeResolver::class);
    }
    final public function setQueryableInterfaceTypeFieldResolver(QueryableInterfaceTypeFieldResolver $queryableInterfaceTypeFieldResolver): void
    {
        $this->queryableInterfaceTypeFieldResolver = $queryableInterfaceTypeFieldResolver;
    }
    final protected function getQueryableInterfaceTypeFieldResolver(): QueryableInterfaceTypeFieldResolver
    {
        return $this->queryableInterfaceTypeFieldResolver ??= $this->instanceManager->getInstance(QueryableInterfaceTypeFieldResolver::class);
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
            $this->getQueryableInterfaceTypeFieldResolver(),
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
            'username' => $this->getStringScalarTypeResolver(),
            'name' => $this->getStringScalarTypeResolver(),
            'displayName' => $this->getStringScalarTypeResolver(),
            'firstName' => $this->getStringScalarTypeResolver(),
            'lastName' => $this->getStringScalarTypeResolver(),
            'email' => $this->getEmailScalarTypeResolver(),
            'description' => $this->getStringScalarTypeResolver(),
            'websiteURL' => $this->getUrlScalarTypeResolver(),
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
            'url' => $this->getTranslationAPI()->__('URL of the user\'s profile in the website', 'pop-users'),
            'urlPath' => $this->getTranslationAPI()->__('URL path of the user\'s profile in the website', 'pop-users'),
            'slug' => $this->getTranslationAPI()->__('Slug of the URL of the user\'s profile in the website', 'pop-users'),
            'username' => $this->getTranslationAPI()->__('User\'s username handle', 'pop-users'),
            'name' => $this->getTranslationAPI()->__('Name of the user', 'pop-users'),
            'displayName' => $this->getTranslationAPI()->__('Name of the user as displayed on the website', 'pop-users'),
            'firstName' => $this->getTranslationAPI()->__('User\'s first name', 'pop-users'),
            'lastName' => $this->getTranslationAPI()->__('User\'s last name', 'pop-users'),
            'email' => $this->getTranslationAPI()->__('User\'s email', 'pop-users'),
            'description' => $this->getTranslationAPI()->__('Description of the user', 'pop-users'),
            'websiteURL' => $this->getTranslationAPI()->__('User\'s own website\'s URL', 'pop-users'),
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
                return $this->getUserTypeAPI()->getUserLogin($user);

            case 'name':
            case 'displayName':
                return $this->getUserTypeAPI()->getUserDisplayName($user);

            case 'firstName':
                return $this->getUserTypeAPI()->getUserFirstname($user);

            case 'lastName':
                return $this->getUserTypeAPI()->getUserLastname($user);

            case 'email':
                return $this->getUserTypeAPI()->getUserEmail($user);

            case 'url':
                return $this->getUserTypeAPI()->getUserURL($objectTypeResolver->getID($user));

            case 'urlPath':
                return $this->getUserTypeAPI()->getUserURLPath($objectTypeResolver->getID($user));

            case 'slug':
                return $this->getUserTypeAPI()->getUserSlug($user);

            case 'description':
                return $this->getUserTypeAPI()->getUserDescription($user);

            case 'websiteURL':
                return $this->getUserTypeAPI()->getUserWebsiteUrl($user);
        }

        return parent::resolveValue($objectTypeResolver, $object, $fieldName, $fieldArgs, $variables, $expressions, $options);
    }
}

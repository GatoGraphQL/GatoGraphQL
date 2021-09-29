<?php

declare(strict_types=1);

namespace PoPWPSchema\Users\FieldResolvers\ObjectType;

use Symfony\Contracts\Service\Attribute\Required;
use PoP\ComponentModel\FieldResolvers\ObjectType\AbstractQueryableObjectTypeFieldResolver;
use PoP\ComponentModel\Schema\SchemaTypeModifiers;
use PoP\ComponentModel\TypeResolvers\ConcreteTypeResolverInterface;
use PoP\ComponentModel\TypeResolvers\ObjectType\ObjectTypeResolverInterface;
use PoP\Engine\Formatters\DateFormatterInterface;
use PoP\Engine\TypeResolvers\ScalarType\StringScalarTypeResolver;
use PoPSchema\SchemaCommons\ModuleProcessors\CommonFilterInputContainerModuleProcessor;
use PoPSchema\Users\TypeResolvers\ObjectType\UserObjectTypeResolver;
use WP_User;

class UserObjectTypeFieldResolver extends AbstractQueryableObjectTypeFieldResolver
{
    protected DateFormatterInterface $dateFormatter;
    protected StringScalarTypeResolver $stringScalarTypeResolver;

    #[Required]
    public function autowireUserObjectTypeFieldResolver(
        DateFormatterInterface $dateFormatter,
        StringScalarTypeResolver $stringScalarTypeResolver,
    ): void {
        $this->dateFormatter = $dateFormatter;
        $this->stringScalarTypeResolver = $stringScalarTypeResolver;
    }

    public function getObjectTypeResolverClassesToAttachTo(): array
    {
        return [
            UserObjectTypeResolver::class,
        ];
    }

    public function getFieldNamesToResolve(): array
    {
        return [
            'nicename',
            'nickname',
            'locale',
            'registeredDate',
        ];
    }

    public function getFieldTypeResolver(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName): ConcreteTypeResolverInterface
    {
        return match ($fieldName) {
            'nicename' => $this->stringScalarTypeResolver,
            'nickname' => $this->stringScalarTypeResolver,
            'locale' => $this->stringScalarTypeResolver,
            'registeredDate' => $this->stringScalarTypeResolver,
            default => parent::getFieldTypeResolver($objectTypeResolver, $fieldName),
        };
    }

    public function getSchemaFieldTypeModifiers(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName): ?int
    {
        return match ($fieldName) {
            'nicename',
            'nickname',
            'locale',
            'registeredDate'
                => SchemaTypeModifiers::NON_NULLABLE,
            default
                => parent::getSchemaFieldTypeModifiers($objectTypeResolver, $fieldName),
        };
    }

    public function getSchemaFieldDescription(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName): ?string
    {
        return match ($fieldName) {
            'nicename' => $this->translationAPI->__('User\'s nicename', 'pop-users'),
            'nickname' => $this->translationAPI->__('User\'s nickname', 'pop-users'),
            'locale' => $this->translationAPI->__('Retrieves the locale of a user', 'pop-users'),
            'registeredDate' => $this->translationAPI->__('The date the user registerd on the site', 'pop-users'),
            default => parent::getSchemaFieldDescription($objectTypeResolver, $fieldName),
        };
    }

    public function getFieldFilterInputContainerModule(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName): ?array
    {
        return match ($fieldName) {
            'registeredDate' => [CommonFilterInputContainerModuleProcessor::class, CommonFilterInputContainerModuleProcessor::MODULE_FILTERINPUTCONTAINER_DATE_AS_STRING],
            default => parent::getFieldFilterInputContainerModule($objectTypeResolver, $fieldName),
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
        /** @var WP_User */
        $user = $object;
        switch ($fieldName) {
            case 'nicename':
                return $user->user_nicename;
            case 'nickname':
                return $user->nickname;
            case 'locale':
                return \get_user_locale($user);
            case 'registeredDate':
                return $this->dateFormatter->format(
                    $fieldArgs['format'],
                    $user->user_registered
                );
        }

        return parent::resolveValue($objectTypeResolver, $object, $fieldName, $fieldArgs, $variables, $expressions, $options);
    }
}

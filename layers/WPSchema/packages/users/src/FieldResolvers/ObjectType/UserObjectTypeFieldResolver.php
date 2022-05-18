<?php

declare(strict_types=1);

namespace PoPWPSchema\Users\FieldResolvers\ObjectType;

use PoP\ComponentModel\Feedback\ObjectTypeFieldResolutionFeedbackStore;
use DateTime;
use PoP\ComponentModel\FieldResolvers\ObjectType\AbstractQueryableObjectTypeFieldResolver;
use PoP\ComponentModel\Schema\SchemaTypeModifiers;
use PoP\ComponentModel\TypeResolvers\ConcreteTypeResolverInterface;
use PoP\ComponentModel\TypeResolvers\ObjectType\ObjectTypeResolverInterface;
use PoPCMSSchema\SchemaCommons\Formatters\DateFormatterInterface;
use PoP\ComponentModel\TypeResolvers\ScalarType\StringScalarTypeResolver;
use PoPCMSSchema\SchemaCommons\ComponentProcessors\CommonFilterInputContainerComponentProcessor;
use PoPSchema\SchemaCommons\TypeResolvers\ScalarType\DateTimeScalarTypeResolver;
use PoPCMSSchema\Users\TypeResolvers\ObjectType\UserObjectTypeResolver;
use WP_User;

class UserObjectTypeFieldResolver extends AbstractQueryableObjectTypeFieldResolver
{
    private ?DateFormatterInterface $dateFormatter = null;
    private ?StringScalarTypeResolver $stringScalarTypeResolver = null;
    private ?DateTimeScalarTypeResolver $dateTimeScalarTypeResolver = null;

    final public function setDateFormatter(DateFormatterInterface $dateFormatter): void
    {
        $this->dateFormatter = $dateFormatter;
    }
    final protected function getDateFormatter(): DateFormatterInterface
    {
        return $this->dateFormatter ??= $this->instanceManager->getInstance(DateFormatterInterface::class);
    }
    final public function setStringScalarTypeResolver(StringScalarTypeResolver $stringScalarTypeResolver): void
    {
        $this->stringScalarTypeResolver = $stringScalarTypeResolver;
    }
    final protected function getStringScalarTypeResolver(): StringScalarTypeResolver
    {
        return $this->stringScalarTypeResolver ??= $this->instanceManager->getInstance(StringScalarTypeResolver::class);
    }
    final public function setDateTimeScalarTypeResolver(DateTimeScalarTypeResolver $dateTimeScalarTypeResolver): void
    {
        $this->dateTimeScalarTypeResolver = $dateTimeScalarTypeResolver;
    }
    final protected function getDateTimeScalarTypeResolver(): DateTimeScalarTypeResolver
    {
        return $this->dateTimeScalarTypeResolver ??= $this->instanceManager->getInstance(DateTimeScalarTypeResolver::class);
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
            'registeredDateStr',
        ];
    }

    public function getFieldTypeResolver(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName): ConcreteTypeResolverInterface
    {
        return match ($fieldName) {
            'nicename' => $this->getStringScalarTypeResolver(),
            'nickname' => $this->getStringScalarTypeResolver(),
            'locale' => $this->getStringScalarTypeResolver(),
            'registeredDate' => $this->getDateTimeScalarTypeResolver(),
            'registeredDateStr' => $this->getStringScalarTypeResolver(),
            default => parent::getFieldTypeResolver($objectTypeResolver, $fieldName),
        };
    }

    public function getFieldTypeModifiers(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName): int
    {
        return match ($fieldName) {
            'nicename',
            'nickname',
            'locale',
            'registeredDate',
            'registeredDateStr'
                => SchemaTypeModifiers::NON_NULLABLE,
            default
                => parent::getFieldTypeModifiers($objectTypeResolver, $fieldName),
        };
    }

    public function getFieldDescription(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName): ?string
    {
        return match ($fieldName) {
            'nicename' => $this->__('User\'s nicename', 'pop-users'),
            'nickname' => $this->__('User\'s nickname', 'pop-users'),
            'locale' => $this->__('Retrieves the locale of a user', 'pop-users'),
            'registeredDate' => $this->__('The date the user registerd on the site', 'pop-users'),
            'registeredDateStr' => $this->__('The date the user registerd on the site, in String format', 'pop-users'),
            default => parent::getFieldDescription($objectTypeResolver, $fieldName),
        };
    }

    public function getFieldFilterInputContainerModule(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName): ?array
    {
        return match ($fieldName) {
            'registeredDateStr' => [CommonFilterInputContainerComponentProcessor::class, CommonFilterInputContainerComponentProcessor::MODULE_FILTERINPUTCONTAINER_DATE_AS_STRING],
            default => parent::getFieldFilterInputContainerModule($objectTypeResolver, $fieldName),
        };
    }

    /**
     * @param array<string, mixed> $fieldArgs
     * @param array<string, mixed> $variables
     * @param array<string, mixed> $expressions
     * @param array<string, mixed> $options
     */
    public function resolveValue(
        ObjectTypeResolverInterface $objectTypeResolver,
        object $object,
        string $fieldName,
        array $fieldArgs,
        array $variables,
        array $expressions,
        ObjectTypeFieldResolutionFeedbackStore $objectTypeFieldResolutionFeedbackStore,
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
                return new DateTime($user->user_registered);
            case 'registeredDateStr':
                return $this->getDateFormatter()->format(
                    $fieldArgs['format'],
                    $user->user_registered
                );
        }

        return parent::resolveValue($objectTypeResolver, $object, $fieldName, $fieldArgs, $variables, $expressions, $objectTypeFieldResolutionFeedbackStore, $options);
    }
}

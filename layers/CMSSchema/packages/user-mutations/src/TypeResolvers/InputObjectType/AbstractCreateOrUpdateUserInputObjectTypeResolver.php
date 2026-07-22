<?php

declare(strict_types=1);

namespace PoPCMSSchema\UserMutations\TypeResolvers\InputObjectType;

use PoPCMSSchema\UserMutations\Constants\MutationInputProperties;
use PoPSchema\SchemaCommons\TypeResolvers\ScalarType\DateTimeScalarTypeResolver;
use PoPSchema\SchemaCommons\TypeResolvers\ScalarType\EmailScalarTypeResolver;
use PoPSchema\SchemaCommons\TypeResolvers\ScalarType\URLScalarTypeResolver;
use PoP\ComponentModel\Schema\SchemaTypeModifiers;
use PoP\ComponentModel\TypeResolvers\InputObjectType\AbstractInputObjectTypeResolver;
use PoP\ComponentModel\TypeResolvers\InputTypeResolverInterface;
use PoP\ComponentModel\TypeResolvers\ScalarType\BooleanScalarTypeResolver;
use PoP\ComponentModel\TypeResolvers\ScalarType\IDScalarTypeResolver;
use PoP\ComponentModel\TypeResolvers\ScalarType\StringScalarTypeResolver;

abstract class AbstractCreateOrUpdateUserInputObjectTypeResolver extends AbstractInputObjectTypeResolver implements CreateUserInputObjectTypeResolverInterface
{
    private ?IDScalarTypeResolver $idScalarTypeResolver = null;
    private ?StringScalarTypeResolver $stringScalarTypeResolver = null;
    private ?EmailScalarTypeResolver $emailScalarTypeResolver = null;
    private ?URLScalarTypeResolver $urlScalarTypeResolver = null;
    private ?DateTimeScalarTypeResolver $dateTimeScalarTypeResolver = null;
    private ?BooleanScalarTypeResolver $booleanScalarTypeResolver = null;

    final protected function getIDScalarTypeResolver(): IDScalarTypeResolver
    {
        if ($this->idScalarTypeResolver === null) {
            /** @var IDScalarTypeResolver */
            $idScalarTypeResolver = $this->instanceManager->getInstance(IDScalarTypeResolver::class);
            $this->idScalarTypeResolver = $idScalarTypeResolver;
        }
        return $this->idScalarTypeResolver;
    }
    final protected function getStringScalarTypeResolver(): StringScalarTypeResolver
    {
        if ($this->stringScalarTypeResolver === null) {
            /** @var StringScalarTypeResolver */
            $stringScalarTypeResolver = $this->instanceManager->getInstance(StringScalarTypeResolver::class);
            $this->stringScalarTypeResolver = $stringScalarTypeResolver;
        }
        return $this->stringScalarTypeResolver;
    }
    final protected function getEmailScalarTypeResolver(): EmailScalarTypeResolver
    {
        if ($this->emailScalarTypeResolver === null) {
            /** @var EmailScalarTypeResolver */
            $emailScalarTypeResolver = $this->instanceManager->getInstance(EmailScalarTypeResolver::class);
            $this->emailScalarTypeResolver = $emailScalarTypeResolver;
        }
        return $this->emailScalarTypeResolver;
    }
    final protected function getURLScalarTypeResolver(): URLScalarTypeResolver
    {
        if ($this->urlScalarTypeResolver === null) {
            /** @var URLScalarTypeResolver */
            $urlScalarTypeResolver = $this->instanceManager->getInstance(URLScalarTypeResolver::class);
            $this->urlScalarTypeResolver = $urlScalarTypeResolver;
        }
        return $this->urlScalarTypeResolver;
    }
    final protected function getDateTimeScalarTypeResolver(): DateTimeScalarTypeResolver
    {
        if ($this->dateTimeScalarTypeResolver === null) {
            /** @var DateTimeScalarTypeResolver */
            $dateTimeScalarTypeResolver = $this->instanceManager->getInstance(DateTimeScalarTypeResolver::class);
            $this->dateTimeScalarTypeResolver = $dateTimeScalarTypeResolver;
        }
        return $this->dateTimeScalarTypeResolver;
    }
    final protected function getBooleanScalarTypeResolver(): BooleanScalarTypeResolver
    {
        if ($this->booleanScalarTypeResolver === null) {
            /** @var BooleanScalarTypeResolver */
            $booleanScalarTypeResolver = $this->instanceManager->getInstance(BooleanScalarTypeResolver::class);
            $this->booleanScalarTypeResolver = $booleanScalarTypeResolver;
        }
        return $this->booleanScalarTypeResolver;
    }

    /**
     * @return array<string,InputTypeResolverInterface>
     */
    public function getInputFieldNameTypeResolvers(): array
    {
        return array_merge(
            $this->addUserInputField() ? [
                MutationInputProperties::ID => $this->getIDScalarTypeResolver(),
            ] : [],
            $this->addUsernameInputField() ? [
                MutationInputProperties::USERNAME => $this->getStringScalarTypeResolver(),
            ] : [],
            [
                MutationInputProperties::EMAIL => $this->getEmailScalarTypeResolver(),
                MutationInputProperties::PASSWORD => $this->getStringScalarTypeResolver(),
                MutationInputProperties::ROLES => $this->getStringScalarTypeResolver(),
                MutationInputProperties::FIRST_NAME => $this->getStringScalarTypeResolver(),
                MutationInputProperties::LAST_NAME => $this->getStringScalarTypeResolver(),
                MutationInputProperties::NICKNAME => $this->getStringScalarTypeResolver(),
                MutationInputProperties::DISPLAY_NAME => $this->getStringScalarTypeResolver(),
                MutationInputProperties::SLUG => $this->getStringScalarTypeResolver(),
                MutationInputProperties::WEBSITE_URL => $this->getURLScalarTypeResolver(),
                MutationInputProperties::DESCRIPTION => $this->getStringScalarTypeResolver(),
                MutationInputProperties::LOCALE => $this->getStringScalarTypeResolver(),
                MutationInputProperties::REGISTERED_DATE => $this->getDateTimeScalarTypeResolver(),
            ],
            $this->addUsernameInputField() ? [
                MutationInputProperties::SEND_EMAIL_NOTIFICATION => $this->getBooleanScalarTypeResolver(),
            ] : [],
        );
    }

    abstract protected function addUserInputField(): bool;

    abstract protected function addUsernameInputField(): bool;

    public function getInputFieldDescription(string $inputFieldName): ?string
    {
        return match ($inputFieldName) {
            MutationInputProperties::ID => $this->__('The ID of the user to update', 'gatographql'),
            MutationInputProperties::USERNAME => $this->__('The username (login). It cannot be changed once the user is created', 'gatographql'),
            MutationInputProperties::EMAIL => $this->__('The user\'s email address', 'gatographql'),
            MutationInputProperties::PASSWORD => $this->__('The user\'s password. When creating a user, if not provided a random password is generated', 'gatographql'),
            MutationInputProperties::ROLES => $this->__('The roles assigned to the user', 'gatographql'),
            MutationInputProperties::FIRST_NAME => $this->__('The user\'s first name', 'gatographql'),
            MutationInputProperties::LAST_NAME => $this->__('The user\'s last name', 'gatographql'),
            MutationInputProperties::NICKNAME => $this->__('The user\'s nickname', 'gatographql'),
            MutationInputProperties::DISPLAY_NAME => $this->__('The name to display for the user on the site', 'gatographql'),
            MutationInputProperties::SLUG => $this->__('The URL-friendly slug (nicename) for the user', 'gatographql'),
            MutationInputProperties::WEBSITE_URL => $this->__('The user\'s website URL', 'gatographql'),
            MutationInputProperties::DESCRIPTION => $this->__('The user\'s biographical description', 'gatographql'),
            MutationInputProperties::LOCALE => $this->__('The user\'s locale', 'gatographql'),
            MutationInputProperties::REGISTERED_DATE => $this->__('The date the user registered', 'gatographql'),
            MutationInputProperties::SEND_EMAIL_NOTIFICATION => $this->__('Whether to send the new user an email about their account', 'gatographql'),
            default => parent::getInputFieldDescription($inputFieldName),
        };
    }

    public function getInputFieldTypeModifiers(string $inputFieldName): int
    {
        return match ($inputFieldName) {
            MutationInputProperties::ID,
            MutationInputProperties::USERNAME
                => SchemaTypeModifiers::MANDATORY,
            MutationInputProperties::EMAIL
                => $this->addUsernameInputField()
                    ? SchemaTypeModifiers::MANDATORY
                    : SchemaTypeModifiers::NONE,
            MutationInputProperties::ROLES
                => SchemaTypeModifiers::IS_ARRAY | SchemaTypeModifiers::IS_NON_NULLABLE_ITEMS_IN_ARRAY,
            default
                => parent::getInputFieldTypeModifiers($inputFieldName),
        };
    }

    public function getInputFieldDefaultValue(string $inputFieldName): mixed
    {
        return match ($inputFieldName) {
            MutationInputProperties::SEND_EMAIL_NOTIFICATION => false,
            default => parent::getInputFieldDefaultValue($inputFieldName),
        };
    }
}

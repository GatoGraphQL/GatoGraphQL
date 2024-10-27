<?php

declare(strict_types=1);

namespace PoPCMSSchema\Users\TypeResolvers\InputObjectType;

use PoP\ComponentModel\TypeResolvers\InputTypeResolverInterface;
use PoP\ComponentModel\FilterInputs\FilterInputInterface;
use PoP\ComponentModel\TypeResolvers\InputObjectType\AbstractOneofQueryableInputObjectTypeResolver;
use PoP\ComponentModel\TypeResolvers\ScalarType\IDScalarTypeResolver;
use PoP\ComponentModel\TypeResolvers\ScalarType\StringScalarTypeResolver;
use PoP\Root\App;
use PoPCMSSchema\SchemaCommons\FilterInputs\IncludeFilterInput;
use PoPCMSSchema\Users\Constants\InputProperties;
use PoPCMSSchema\Users\FilterInputs\EmailFilterInput;
use PoPCMSSchema\Users\FilterInputs\UsernameFilterInput;
use PoPCMSSchema\Users\Module;
use PoPCMSSchema\Users\ModuleConfiguration;
use PoPSchema\SchemaCommons\TypeResolvers\ScalarType\EmailScalarTypeResolver;

class UserByOneofInputObjectTypeResolver extends AbstractOneofQueryableInputObjectTypeResolver
{
    private ?IDScalarTypeResolver $idScalarTypeResolver = null;
    private ?StringScalarTypeResolver $stringScalarTypeResolver = null;
    private ?EmailScalarTypeResolver $emailScalarTypeResolver = null;
    private ?IncludeFilterInput $includeFilterInput = null;
    private ?UsernameFilterInput $usernameFilterInput = null;
    private ?EmailFilterInput $emailFilterInput = null;

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
    final protected function getIncludeFilterInput(): IncludeFilterInput
    {
        if ($this->includeFilterInput === null) {
            /** @var IncludeFilterInput */
            $includeFilterInput = $this->instanceManager->getInstance(IncludeFilterInput::class);
            $this->includeFilterInput = $includeFilterInput;
        }
        return $this->includeFilterInput;
    }
    final protected function getUsernameFilterInput(): UsernameFilterInput
    {
        if ($this->usernameFilterInput === null) {
            /** @var UsernameFilterInput */
            $usernameFilterInput = $this->instanceManager->getInstance(UsernameFilterInput::class);
            $this->usernameFilterInput = $usernameFilterInput;
        }
        return $this->usernameFilterInput;
    }
    final protected function getEmailFilterInput(): EmailFilterInput
    {
        if ($this->emailFilterInput === null) {
            /** @var EmailFilterInput */
            $emailFilterInput = $this->instanceManager->getInstance(EmailFilterInput::class);
            $this->emailFilterInput = $emailFilterInput;
        }
        return $this->emailFilterInput;
    }

    public function getTypeName(): string
    {
        return 'UserByInput';
    }

    public function getTypeDescription(): ?string
    {
        return $this->__('Oneof input to specify the property and data to fetch a user', 'users');
    }

    /**
     * @return array<string,InputTypeResolverInterface>
     */
    public function getInputFieldNameTypeResolvers(): array
    {
        return [
            InputProperties::ID => $this->getIDScalarTypeResolver(),
            InputProperties::USERNAME => $this->getStringScalarTypeResolver(),
            InputProperties::EMAIL => $this->getEmailScalarTypeResolver(),
        ];
    }

    /**
     * @return string[]
     */
    public function getSensitiveInputFieldNames(): array
    {
        $sensitiveInputFieldNames = parent::getSensitiveInputFieldNames();
        /** @var ModuleConfiguration */
        $moduleConfiguration = App::getModule(Module::class)->getConfiguration();
        if ($moduleConfiguration->treatUserEmailAsSensitiveData()) {
            $sensitiveInputFieldNames[] = InputProperties::EMAIL;
        }
        return $sensitiveInputFieldNames;
    }

    public function getInputFieldDescription(string $inputFieldName): ?string
    {
        return match ($inputFieldName) {
            InputProperties::ID => $this->__('Query by user ID', 'users'),
            InputProperties::USERNAME => $this->__('Query by username', 'users'),
            InputProperties::EMAIL => $this->__('Query by email', 'users'),
            default => parent::getInputFieldDescription($inputFieldName),
        };
    }

    public function getInputFieldFilterInput(string $inputFieldName): ?FilterInputInterface
    {
        return match ($inputFieldName) {
            InputProperties::ID => $this->getIncludeFilterInput(),
            InputProperties::USERNAME => $this->getUsernameFilterInput(),
            InputProperties::EMAIL => $this->getEmailFilterInput(),
            default => parent::getInputFieldFilterInput($inputFieldName),
        };
    }
}

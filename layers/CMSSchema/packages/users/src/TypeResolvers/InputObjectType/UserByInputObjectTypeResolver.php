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
use PoPCMSSchema\Users\FilterInputs\EmailFilterInput;
use PoPCMSSchema\Users\FilterInputs\UsernameFilterInput;
use PoPCMSSchema\Users\Module;
use PoPCMSSchema\Users\ModuleConfiguration;
use PoPSchema\SchemaCommons\TypeResolvers\ScalarType\EmailScalarTypeResolver;

class UserByInputObjectTypeResolver extends AbstractOneofQueryableInputObjectTypeResolver
{
    private ?IDScalarTypeResolver $idScalarTypeResolver = null;
    private ?StringScalarTypeResolver $stringScalarTypeResolver = null;
    private ?EmailScalarTypeResolver $emailScalarTypeResolver = null;
    private ?IncludeFilterInput $includeFilterInput = null;
    private ?UsernameFilterInput $usernameFilterInput = null;
    private ?EmailFilterInput $emailFilterInput = null;

    final public function setIDScalarTypeResolver(IDScalarTypeResolver $idScalarTypeResolver): void
    {
        $this->idScalarTypeResolver = $idScalarTypeResolver;
    }
    final protected function getIDScalarTypeResolver(): IDScalarTypeResolver
    {
        return $this->idScalarTypeResolver ??= $this->instanceManager->getInstance(IDScalarTypeResolver::class);
    }
    final public function setStringScalarTypeResolver(StringScalarTypeResolver $stringScalarTypeResolver): void
    {
        $this->stringScalarTypeResolver = $stringScalarTypeResolver;
    }
    final protected function getStringScalarTypeResolver(): StringScalarTypeResolver
    {
        return $this->stringScalarTypeResolver ??= $this->instanceManager->getInstance(StringScalarTypeResolver::class);
    }
    final public function setEmailScalarTypeResolver(EmailScalarTypeResolver $emailScalarTypeResolver): void
    {
        $this->emailScalarTypeResolver = $emailScalarTypeResolver;
    }
    final protected function getEmailScalarTypeResolver(): EmailScalarTypeResolver
    {
        return $this->emailScalarTypeResolver ??= $this->instanceManager->getInstance(EmailScalarTypeResolver::class);
    }
    final public function setIncludeFilterInput(IncludeFilterInput $includeFilterInput): void
    {
        $this->includeFilterInput = $includeFilterInput;
    }
    final protected function getIncludeFilterInput(): IncludeFilterInput
    {
        return $this->includeFilterInput ??= $this->instanceManager->getInstance(IncludeFilterInput::class);
    }
    final public function setUsernameFilterInput(UsernameFilterInput $usernameFilterInput): void
    {
        $this->usernameFilterInput = $usernameFilterInput;
    }
    final protected function getUsernameFilterInput(): UsernameFilterInput
    {
        return $this->usernameFilterInput ??= $this->instanceManager->getInstance(UsernameFilterInput::class);
    }
    final public function setEmailFilterInput(EmailFilterInput $emailFilterInput): void
    {
        $this->emailFilterInput = $emailFilterInput;
    }
    final protected function getEmailFilterInput(): EmailFilterInput
    {
        return $this->emailFilterInput ??= $this->instanceManager->getInstance(EmailFilterInput::class);
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
     * @return array<string, InputTypeResolverInterface>
     */
    public function getInputFieldNameTypeResolvers(): array
    {
        return [
            'id' => $this->getIDScalarTypeResolver(),
            'username' => $this->getStringScalarTypeResolver(),
            'email' => $this->getEmailScalarTypeResolver(),
        ];
    }

    /**
     * @return string[]
     */
    public function getAdminInputFieldNames(): array
    {
        $adminInputFieldNames = parent::getAdminInputFieldNames();
        /** @var ModuleConfiguration */
        $moduleConfiguration = App::getModule(Module::class)->getConfiguration();
        if ($moduleConfiguration->treatUserEmailAsAdminData()) {
            $adminInputFieldNames[] = 'email';
        }
        return $adminInputFieldNames;
    }

    public function getInputFieldDescription(string $inputFieldName): ?string
    {
        return match ($inputFieldName) {
            'id' => $this->__('Query by user ID', 'users'),
            'username' => $this->__('Query by username', 'users'),
            'email' => $this->__('Query by email', 'users'),
            default => parent::getInputFieldDescription($inputFieldName),
        };
    }

    public function getInputFieldFilterInput(string $inputFieldName): ?FilterInputInterface
    {
        return match ($inputFieldName) {
            'id' => $this->getIncludeFilterInput(),
            'username' => $this->getUsernameFilterInput(),
            'email' => $this->getEmailFilterInput(),
            default => parent::getInputFieldFilterInput($inputFieldName),
        };
    }
}

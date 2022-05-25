<?php

declare(strict_types=1);

namespace PoPCMSSchema\Users\TypeResolvers\InputObjectType;

use PoP\ComponentModel\FilterInputProcessors\FilterInputProcessorInterface;
use PoP\ComponentModel\TypeResolvers\InputObjectType\AbstractOneofQueryableInputObjectTypeResolver;
use PoP\ComponentModel\TypeResolvers\ScalarType\IDScalarTypeResolver;
use PoP\ComponentModel\TypeResolvers\ScalarType\StringScalarTypeResolver;
use PoP\Root\App;
use PoPCMSSchema\SchemaCommons\FilterInputProcessors\IncludeFilterInputProcessor;
use PoPCMSSchema\Users\FilterInputProcessors\EmailFilterInputProcessor;
use PoPCMSSchema\Users\FilterInputProcessors\UsernameFilterInputProcessor;
use PoPCMSSchema\Users\Module;
use PoPCMSSchema\Users\ModuleConfiguration;
use PoPSchema\SchemaCommons\TypeResolvers\ScalarType\EmailScalarTypeResolver;

class UserByInputObjectTypeResolver extends AbstractOneofQueryableInputObjectTypeResolver
{
    private ?IDScalarTypeResolver $idScalarTypeResolver = null;
    private ?StringScalarTypeResolver $stringScalarTypeResolver = null;
    private ?EmailScalarTypeResolver $emailScalarTypeResolver = null;
    private ?IncludeFilterInputProcessor $includeFilterInputProcessor = null;
    private ?UsernameFilterInputProcessor $usernameFilterInputProcessor = null;
    private ?EmailFilterInputProcessor $emailFilterInputProcessor = null;

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
    final public function setIncludeFilterInputProcessor(IncludeFilterInputProcessor $includeFilterInputProcessor): void
    {
        $this->includeFilterInputProcessor = $includeFilterInputProcessor;
    }
    final protected function getIncludeFilterInputProcessor(): IncludeFilterInputProcessor
    {
        return $this->includeFilterInputProcessor ??= $this->instanceManager->getInstance(IncludeFilterInputProcessor::class);
    }
    final public function setUsernameFilterInputProcessor(UsernameFilterInputProcessor $usernameFilterInputProcessor): void
    {
        $this->usernameFilterInputProcessor = $usernameFilterInputProcessor;
    }
    final protected function getUsernameFilterInputProcessor(): UsernameFilterInputProcessor
    {
        return $this->usernameFilterInputProcessor ??= $this->instanceManager->getInstance(UsernameFilterInputProcessor::class);
    }
    final public function setEmailFilterInputProcessor(EmailFilterInputProcessor $emailFilterInputProcessor): void
    {
        $this->emailFilterInputProcessor = $emailFilterInputProcessor;
    }
    final protected function getEmailFilterInputProcessor(): EmailFilterInputProcessor
    {
        return $this->emailFilterInputProcessor ??= $this->instanceManager->getInstance(EmailFilterInputProcessor::class);
    }

    public function getTypeName(): string
    {
        return 'UserByInput';
    }

    public function getTypeDescription(): ?string
    {
        return $this->__('Oneof input to specify the property and data to fetch a user', 'users');
    }

    public function getInputFieldNameTypeResolvers(): array
    {
        return [
            'id' => $this->getIDScalarTypeResolver(),
            'username' => $this->getStringScalarTypeResolver(),
            'email' => $this->getEmailScalarTypeResolver(),
        ];
    }

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

    public function getInputFieldFilterInput(string $inputFieldName): ?FilterInputProcessorInterface
    {
        return match ($inputFieldName) {
            'id' => $this->getIncludeFilterInputProcessor(),
            'username' => $this->getUsernameFilterInputProcessor(),
            'email' => $this->getEmailFilterInputProcessor(),
            default => parent::getInputFieldFilterInput($inputFieldName),
        };
    }
}

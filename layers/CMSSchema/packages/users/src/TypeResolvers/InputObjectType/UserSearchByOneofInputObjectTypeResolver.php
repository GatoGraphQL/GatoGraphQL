<?php

declare(strict_types=1);

namespace PoPCMSSchema\Users\TypeResolvers\InputObjectType;

use PoP\ComponentModel\TypeResolvers\InputTypeResolverInterface;
use PoP\ComponentModel\FilterInputs\FilterInputInterface;
use PoP\ComponentModel\Schema\SchemaTypeModifiers;
use PoP\ComponentModel\TypeResolvers\InputObjectType\AbstractOneofQueryableInputObjectTypeResolver;
use PoP\ComponentModel\TypeResolvers\ScalarType\StringScalarTypeResolver;
use PoP\Root\App;
use PoPCMSSchema\SchemaCommons\FilterInputs\SearchFilterInput;
use PoPCMSSchema\Users\FilterInputs\EmailOrEmailsFilterInput;
use PoPCMSSchema\Users\Module;
use PoPCMSSchema\Users\ModuleConfiguration;
use PoPSchema\SchemaCommons\TypeResolvers\ScalarType\EmailScalarTypeResolver;

class UserSearchByOneofInputObjectTypeResolver extends AbstractOneofQueryableInputObjectTypeResolver
{
    private ?StringScalarTypeResolver $stringScalarTypeResolver = null;
    private ?EmailScalarTypeResolver $emailScalarTypeResolver = null;
    private ?SearchFilterInput $searchFilterInput = null;
    private ?EmailOrEmailsFilterInput $emailOrEmailsFilterInput = null;

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
    final protected function getSearchFilterInput(): SearchFilterInput
    {
        if ($this->searchFilterInput === null) {
            /** @var SearchFilterInput */
            $searchFilterInput = $this->instanceManager->getInstance(SearchFilterInput::class);
            $this->searchFilterInput = $searchFilterInput;
        }
        return $this->searchFilterInput;
    }
    final protected function getEmailOrEmailsFilterInput(): EmailOrEmailsFilterInput
    {
        if ($this->emailOrEmailsFilterInput === null) {
            /** @var EmailOrEmailsFilterInput */
            $emailOrEmailsFilterInput = $this->instanceManager->getInstance(EmailOrEmailsFilterInput::class);
            $this->emailOrEmailsFilterInput = $emailOrEmailsFilterInput;
        }
        return $this->emailOrEmailsFilterInput;
    }

    public function getTypeName(): string
    {
        return 'UserSearchByInput';
    }

    public function getTypeDescription(): ?string
    {
        return $this->__('Oneof input to specify the property and data to search users', 'users');
    }

    protected function isOneInputValueMandatory(): bool
    {
        return false;
    }

    /**
     * @return array<string,InputTypeResolverInterface>
     */
    public function getInputFieldNameTypeResolvers(): array
    {
        return [
            'name' => $this->getStringScalarTypeResolver(),
            'emails' => $this->getEmailScalarTypeResolver(),
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
            $sensitiveInputFieldNames[] = 'emails';
        }
        return $sensitiveInputFieldNames;
    }

    public function getInputFieldDescription(string $inputFieldName): ?string
    {
        return match ($inputFieldName) {
            'name' => $this->__('Search by name', 'users'),
            'emails' => $this->__('Search by email(s)', 'users'),
            default => parent::getInputFieldDescription($inputFieldName),
        };
    }

    public function getInputFieldTypeModifiers(string $inputFieldName): int
    {
        return match ($inputFieldName) {
            'emails' => SchemaTypeModifiers::IS_ARRAY | SchemaTypeModifiers::IS_NON_NULLABLE_ITEMS_IN_ARRAY,
            default => parent::getInputFieldTypeModifiers($inputFieldName)
        };
    }

    public function getInputFieldFilterInput(string $inputFieldName): ?FilterInputInterface
    {
        return match ($inputFieldName) {
            'name' => $this->getSearchFilterInput(),
            'emails' => $this->getEmailOrEmailsFilterInput(),
            default => parent::getInputFieldFilterInput($inputFieldName),
        };
    }
}

<?php

declare(strict_types=1);

namespace PoPSchema\Users\TypeResolvers\InputObjectType;

use PoP\Root\Managers\ComponentManager;
use PoP\ComponentModel\Schema\SchemaTypeModifiers;
use PoP\ComponentModel\TypeResolvers\InputObjectType\AbstractOneofQueryableInputObjectTypeResolver;
use PoP\Engine\TypeResolvers\ScalarType\StringScalarTypeResolver;
use PoPSchema\SchemaCommons\FilterInputProcessors\FilterInputProcessor as SchemaCommonsFilterInputProcessor;
use PoPSchema\SchemaCommons\TypeResolvers\ScalarType\EmailScalarTypeResolver;
use PoPSchema\Users\Component;
use PoPSchema\Users\ComponentConfiguration;
use PoPSchema\Users\FilterInputProcessors\FilterInputProcessor;

class UserSearchByInputObjectTypeResolver extends AbstractOneofQueryableInputObjectTypeResolver
{
    private ?StringScalarTypeResolver $stringScalarTypeResolver = null;
    private ?EmailScalarTypeResolver $emailScalarTypeResolver = null;

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

    public function getTypeName(): string
    {
        return 'UserSearchByInput';
    }

    public function getTypeDescription(): ?string
    {
        return $this->getTranslationAPI()->__('Oneof input to specify the property and data to search users', 'users');
    }

    protected function isOneInputValueMandatory(): bool
    {
        return false;
    }

    public function getInputFieldNameTypeResolvers(): array
    {
        return [
            'name' => $this->getStringScalarTypeResolver(),
            'emails' => $this->getEmailScalarTypeResolver(),
        ];
    }

    public function getAdminInputFieldNames(): array
    {
        $adminInputFieldNames = parent::getAdminInputFieldNames();
        /** @var ComponentConfiguration */
        $componentConfiguration = ComponentManager::getComponent(Component::class)->getConfiguration();
        if ($componentConfiguration->treatUserEmailAsAdminData()) {
            $adminInputFieldNames[] = 'emails';
        }
        return $adminInputFieldNames;
    }

    public function getInputFieldDescription(string $inputFieldName): ?string
    {
        return match ($inputFieldName) {
            'name' => $this->getTranslationAPI()->__('Search by name', 'users'),
            'emails' => $this->getTranslationAPI()->__('Search by email(s)', 'users'),
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

    public function getInputFieldFilterInput(string $inputFieldName): ?array
    {
        return match ($inputFieldName) {
            'name' => [SchemaCommonsFilterInputProcessor::class, SchemaCommonsFilterInputProcessor::FILTERINPUT_SEARCH],
            'emails' => [FilterInputProcessor::class, FilterInputProcessor::FILTERINPUT_EMAIL_OR_EMAILS],
            default => parent::getInputFieldFilterInput($inputFieldName),
        };
    }
}

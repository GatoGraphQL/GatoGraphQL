<?php

declare(strict_types=1);

namespace PoPSchema\Users\TypeResolvers\InputObjectType;

use PoP\ComponentModel\Schema\SchemaTypeModifiers;
use PoP\ComponentModel\TypeResolvers\InputObjectType\AbstractOneofQueryableInputObjectTypeResolver;
use PoP\Engine\TypeResolvers\ScalarType\StringScalarTypeResolver;
use PoPSchema\SchemaCommons\FilterInputProcessors\FilterInputProcessor as SchemaCommonsFilterInputProcessor;
use PoPSchema\Users\ComponentConfiguration;
use PoPSchema\Users\FilterInputProcessors\FilterInputProcessor;

class UserSearchByInputObjectTypeResolver extends AbstractOneofQueryableInputObjectTypeResolver
{
    private ?StringScalarTypeResolver $stringScalarTypeResolver = null;

    final public function setStringScalarTypeResolver(StringScalarTypeResolver $stringScalarTypeResolver): void
    {
        $this->stringScalarTypeResolver = $stringScalarTypeResolver;
    }
    final protected function getStringScalarTypeResolver(): StringScalarTypeResolver
    {
        return $this->stringScalarTypeResolver ??= $this->instanceManager->getInstance(StringScalarTypeResolver::class);
    }

    public function getTypeName(): string
    {
        return 'UserSearchByInput';
    }

    public function getTypeDescription(): ?string
    {
        return $this->getTranslationAPI()->__('Oneof input to specify the property and data to search users', 'users');
    }

    public function getInputFieldNameTypeResolvers(): array
    {
        return [
            'name' => $this->getStringScalarTypeResolver(),
            'emails' => $this->getStringScalarTypeResolver(),
        ];
    }

    public function getAdminInputFieldNames(): array
    {
        $adminInputFieldNames = parent::getAdminInputFieldNames();
        if (ComponentConfiguration::treatUserEmailAsAdminData()) {
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

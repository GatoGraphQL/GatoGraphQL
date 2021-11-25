<?php

declare(strict_types=1);

namespace PoPSchema\Users\TypeResolvers\InputObjectType;

use PoP\ComponentModel\Schema\SchemaTypeModifiers;
use PoPSchema\SchemaCommons\TypeResolvers\InputObjectType\AbstractObjectsFilterInputObjectTypeResolver;
use PoPSchema\Users\ComponentConfiguration;
use PoPSchema\Users\FilterInputProcessors\FilterInputProcessor;

abstract class AbstractUsersFilterInputObjectTypeResolver extends AbstractObjectsFilterInputObjectTypeResolver
{
    public function getTypeDescription(): ?string
    {
        return $this->getTranslationAPI()->__('Input to filter users', 'users');
    }

    public function getAdminInputFieldNames(): array
    {
        $adminInputFieldNames = parent::getAdminInputFieldNames();
        if ($this->treatUserEmailAsAdminData()) {
            $adminInputFieldNames[] = 'emails';
        }
        return $adminInputFieldNames;
    }

    protected function treatUserEmailAsAdminData(): bool
    {
        return ComponentConfiguration::treatUserEmailAsAdminData();
    }

    public function getInputFieldNameTypeResolvers(): array
    {
        return array_merge(
            parent::getInputFieldNameTypeResolvers(),
            [
                'name' => $this->getStringScalarTypeResolver(),
                'emails' => $this->getStringScalarTypeResolver(),
            ]
        );
    }

    public function getInputFieldDescription(string $inputFieldName): ?string
    {
        return match ($inputFieldName) {
            'name' => $this->getTranslationAPI()->__('Search for custom posts containing the given string', 'customposts'),
            'emails' => $this->getTranslationAPI()->__('Custom post emails', 'customposts'),
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
            'name' => [FilterInputProcessor::class, FilterInputProcessor::FILTERINPUT_NAME],
            'emails' => [FilterInputProcessor::class, FilterInputProcessor::FILTERINPUT_EMAIL_OR_EMAILS],
            default => parent::getInputFieldFilterInput($inputFieldName),
        };
    }
}

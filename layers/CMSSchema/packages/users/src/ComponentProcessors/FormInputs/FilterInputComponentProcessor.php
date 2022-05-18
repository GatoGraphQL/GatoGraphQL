<?php

declare(strict_types=1);

namespace PoPCMSSchema\Users\ComponentProcessors\FormInputs;

use PoP\ComponentModel\ComponentProcessors\AbstractFilterInputComponentProcessor;
use PoP\ComponentModel\ComponentProcessors\DataloadQueryArgsFilterInputComponentProcessorInterface;
use PoP\ComponentModel\Schema\SchemaTypeModifiers;
use PoP\ComponentModel\TypeResolvers\InputTypeResolverInterface;
use PoP\ComponentModel\TypeResolvers\ScalarType\StringScalarTypeResolver;
use PoPSchema\SchemaCommons\TypeResolvers\ScalarType\EmailScalarTypeResolver;
use PoPCMSSchema\Users\FilterInputProcessors\FilterInputProcessor;

class FilterInputComponentProcessor extends AbstractFilterInputComponentProcessor implements DataloadQueryArgsFilterInputComponentProcessorInterface
{
    public final const MODULE_FILTERINPUT_NAME = 'filterinput-name';
    public final const MODULE_FILTERINPUT_EMAILS = 'filterinput-emails';

    private ?EmailScalarTypeResolver $emailScalarTypeResolver = null;
    private ?StringScalarTypeResolver $stringScalarTypeResolver = null;

    final public function setEmailScalarTypeResolver(EmailScalarTypeResolver $emailScalarTypeResolver): void
    {
        $this->emailScalarTypeResolver = $emailScalarTypeResolver;
    }
    final protected function getEmailScalarTypeResolver(): EmailScalarTypeResolver
    {
        return $this->emailScalarTypeResolver ??= $this->instanceManager->getInstance(EmailScalarTypeResolver::class);
    }
    final public function setStringScalarTypeResolver(StringScalarTypeResolver $stringScalarTypeResolver): void
    {
        $this->stringScalarTypeResolver = $stringScalarTypeResolver;
    }
    final protected function getStringScalarTypeResolver(): StringScalarTypeResolver
    {
        return $this->stringScalarTypeResolver ??= $this->instanceManager->getInstance(StringScalarTypeResolver::class);
    }

    public function getComponentsToProcess(): array
    {
        return array(
            [self::class, self::MODULE_FILTERINPUT_NAME],
            [self::class, self::MODULE_FILTERINPUT_EMAILS],
        );
    }

    public function getFilterInput(array $component): ?array
    {
        $filterInputs = [
            self::MODULE_FILTERINPUT_NAME => [FilterInputProcessor::class, FilterInputProcessor::FILTERINPUT_NAME],
            self::MODULE_FILTERINPUT_EMAILS => [FilterInputProcessor::class, FilterInputProcessor::FILTERINPUT_EMAIL_OR_EMAILS],
        ];
        return $filterInputs[$component[1]] ?? null;
    }

    public function getName(array $component): string
    {
        switch ($component[1]) {
            case self::MODULE_FILTERINPUT_NAME:
            case self::MODULE_FILTERINPUT_EMAILS:
            // Add a nice name, so that the URL params when filtering make sense
                $names = array(
                    self::MODULE_FILTERINPUT_NAME => 'nombre',
                    self::MODULE_FILTERINPUT_EMAILS => 'emails',
                );
                return $names[$component[1]];
        }

        return parent::getName($component);
    }

    public function getFilterInputTypeResolver(array $component): InputTypeResolverInterface
    {
        return match ($component[1]) {
            self::MODULE_FILTERINPUT_NAME => $this->getStringScalarTypeResolver(),
            self::MODULE_FILTERINPUT_EMAILS => $this->getEmailScalarTypeResolver(),
            default => $this->getDefaultSchemaFilterInputTypeResolver(),
        };
    }

    public function getFilterInputTypeModifiers(array $component): int
    {
        return match ($component[1]) {
            self::MODULE_FILTERINPUT_EMAILS => SchemaTypeModifiers::IS_ARRAY | SchemaTypeModifiers::IS_NON_NULLABLE_ITEMS_IN_ARRAY,
            default => SchemaTypeModifiers::NONE,
        };
    }

    public function getFilterInputDescription(array $component): ?string
    {
        return match ($component[1]) {
            self::MODULE_FILTERINPUT_NAME => $this->__('Search users whose name contains this string', 'pop-users'),
            self::MODULE_FILTERINPUT_EMAILS => $this->__('Search users with any of the provided emails', 'pop-users'),
            default => null,
        };
    }
}

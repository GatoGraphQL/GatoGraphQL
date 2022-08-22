<?php

declare(strict_types=1);

namespace PoPCMSSchema\Users\ComponentProcessors\FormInputs;

use PoP\ComponentModel\Component\Component;
use PoP\ComponentModel\ComponentProcessors\AbstractFilterInputComponentProcessor;
use PoP\ComponentModel\ComponentProcessors\DataloadQueryArgsFilterInputComponentProcessorInterface;
use PoP\ComponentModel\FilterInputs\FilterInputInterface;
use PoP\ComponentModel\Schema\SchemaTypeModifiers;
use PoP\ComponentModel\TypeResolvers\InputTypeResolverInterface;
use PoP\ComponentModel\TypeResolvers\ScalarType\StringScalarTypeResolver;
use PoPCMSSchema\Users\FilterInputs\EmailOrEmailsFilterInput;
use PoPCMSSchema\Users\FilterInputs\NameFilterInput;
use PoPSchema\SchemaCommons\TypeResolvers\ScalarType\EmailScalarTypeResolver;

class FilterInputComponentProcessor extends AbstractFilterInputComponentProcessor implements DataloadQueryArgsFilterInputComponentProcessorInterface
{
    public final const COMPONENT_FILTERINPUT_NAME = 'filterinput-name';
    public final const COMPONENT_FILTERINPUT_EMAILS = 'filterinput-emails';

    private ?EmailScalarTypeResolver $emailScalarTypeResolver = null;
    private ?StringScalarTypeResolver $stringScalarTypeResolver = null;
    private ?NameFilterInput $nameFilterInput = null;
    private ?EmailOrEmailsFilterInput $emailOrEmailsFilterInput = null;

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
    final public function setNameFilterInput(NameFilterInput $nameFilterInput): void
    {
        $this->nameFilterInput = $nameFilterInput;
    }
    final protected function getNameFilterInput(): NameFilterInput
    {
        return $this->nameFilterInput ??= $this->instanceManager->getInstance(NameFilterInput::class);
    }
    final public function setEmailOrEmailsFilterInput(EmailOrEmailsFilterInput $emailOrEmailsFilterInput): void
    {
        $this->emailOrEmailsFilterInput = $emailOrEmailsFilterInput;
    }
    final protected function getEmailOrEmailsFilterInput(): EmailOrEmailsFilterInput
    {
        return $this->emailOrEmailsFilterInput ??= $this->instanceManager->getInstance(EmailOrEmailsFilterInput::class);
    }

    /**
     * @return string[]
     */
    public function getComponentNamesToProcess(): array
    {
        return array(
            self::COMPONENT_FILTERINPUT_NAME,
            self::COMPONENT_FILTERINPUT_EMAILS,
        );
    }

    public function getFilterInput(Component $component): ?FilterInputInterface
    {
        return match ($component->name) {
            self::COMPONENT_FILTERINPUT_NAME => $this->getNameFilterInput(),
            self::COMPONENT_FILTERINPUT_EMAILS => $this->getEmailOrEmailsFilterInput(),
            default => null,
        };
    }

    public function getName(Component $component): string
    {
        switch ($component->name) {
            case self::COMPONENT_FILTERINPUT_NAME:
            case self::COMPONENT_FILTERINPUT_EMAILS:
            // Add a nice name, so that the URL params when filtering make sense
                $names = array(
                    self::COMPONENT_FILTERINPUT_NAME => 'nombre',
                    self::COMPONENT_FILTERINPUT_EMAILS => 'emails',
                );
                return $names[$component->name];
        }

        return parent::getName($component);
    }

    public function getFilterInputTypeResolver(Component $component): InputTypeResolverInterface
    {
        return match ($component->name) {
            self::COMPONENT_FILTERINPUT_NAME => $this->getStringScalarTypeResolver(),
            self::COMPONENT_FILTERINPUT_EMAILS => $this->getEmailScalarTypeResolver(),
            default => $this->getDefaultSchemaFilterInputTypeResolver(),
        };
    }

    public function getFilterInputTypeModifiers(Component $component): int
    {
        return match ($component->name) {
            self::COMPONENT_FILTERINPUT_EMAILS => SchemaTypeModifiers::IS_ARRAY | SchemaTypeModifiers::IS_NON_NULLABLE_ITEMS_IN_ARRAY,
            default => SchemaTypeModifiers::NONE,
        };
    }

    public function getFilterInputDescription(Component $component): ?string
    {
        return match ($component->name) {
            self::COMPONENT_FILTERINPUT_NAME => $this->__('Search users whose name contains this string', 'pop-users'),
            self::COMPONENT_FILTERINPUT_EMAILS => $this->__('Search users with any of the provided emails', 'pop-users'),
            default => null,
        };
    }
}

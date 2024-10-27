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

    final protected function getEmailScalarTypeResolver(): EmailScalarTypeResolver
    {
        if ($this->emailScalarTypeResolver === null) {
            /** @var EmailScalarTypeResolver */
            $emailScalarTypeResolver = $this->instanceManager->getInstance(EmailScalarTypeResolver::class);
            $this->emailScalarTypeResolver = $emailScalarTypeResolver;
        }
        return $this->emailScalarTypeResolver;
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
    final protected function getNameFilterInput(): NameFilterInput
    {
        if ($this->nameFilterInput === null) {
            /** @var NameFilterInput */
            $nameFilterInput = $this->instanceManager->getInstance(NameFilterInput::class);
            $this->nameFilterInput = $nameFilterInput;
        }
        return $this->nameFilterInput;
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

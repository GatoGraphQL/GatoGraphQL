<?php

declare(strict_types=1);

namespace PoPCMSSchema\Users\ComponentProcessors\FormInputs;

use PoP\ComponentModel\ComponentProcessors\AbstractFilterInputComponentProcessor;
use PoP\ComponentModel\ComponentProcessors\DataloadQueryArgsFilterInputComponentProcessorInterface;
use PoP\ComponentModel\FilterInputProcessors\FilterInputProcessorInterface;
use PoP\ComponentModel\Schema\SchemaTypeModifiers;
use PoP\ComponentModel\TypeResolvers\InputTypeResolverInterface;
use PoP\ComponentModel\TypeResolvers\ScalarType\StringScalarTypeResolver;
use PoPCMSSchema\Users\FilterInputProcessors\EmailOrEmailsFilterInputProcessor;
use PoPCMSSchema\Users\FilterInputProcessors\NameFilterInputProcessor;
use PoPSchema\SchemaCommons\TypeResolvers\ScalarType\EmailScalarTypeResolver;

class FilterInputComponentProcessor extends AbstractFilterInputComponentProcessor implements DataloadQueryArgsFilterInputComponentProcessorInterface
{
    public final const COMPONENT_FILTERINPUT_NAME = 'filterinput-name';
    public final const COMPONENT_FILTERINPUT_EMAILS = 'filterinput-emails';

    private ?EmailScalarTypeResolver $emailScalarTypeResolver = null;
    private ?StringScalarTypeResolver $stringScalarTypeResolver = null;
    private ?NameFilterInputProcessor $nameFilterInputProcessor = null;
    private ?EmailOrEmailsFilterInputProcessor $emailOrEmailsFilterInputProcessor = null;

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
    final public function setNameFilterInputProcessor(NameFilterInputProcessor $nameFilterInputProcessor): void
    {
        $this->nameFilterInputProcessor = $nameFilterInputProcessor;
    }
    final protected function getNameFilterInputProcessor(): NameFilterInputProcessor
    {
        return $this->nameFilterInputProcessor ??= $this->instanceManager->getInstance(NameFilterInputProcessor::class);
    }
    final public function setEmailOrEmailsFilterInputProcessor(EmailOrEmailsFilterInputProcessor $emailOrEmailsFilterInputProcessor): void
    {
        $this->emailOrEmailsFilterInputProcessor = $emailOrEmailsFilterInputProcessor;
    }
    final protected function getEmailOrEmailsFilterInputProcessor(): EmailOrEmailsFilterInputProcessor
    {
        return $this->emailOrEmailsFilterInputProcessor ??= $this->instanceManager->getInstance(EmailOrEmailsFilterInputProcessor::class);
    }

    public function getComponentsToProcess(): array
    {
        return array(
            [self::class, self::COMPONENT_FILTERINPUT_NAME],
            [self::class, self::COMPONENT_FILTERINPUT_EMAILS],
        );
    }

    public function getFilterInput(array $component): ?FilterInputProcessorInterface
    {
        $filterInputs = [
            self::COMPONENT_FILTERINPUT_NAME => $this->getNameFilterInputProcessor(),
            self::COMPONENT_FILTERINPUT_EMAILS => $this->getEmailOrEmailsFilterInputProcessor(),
        ];
        return $filterInputs[$component[1]] ?? null;
    }

    public function getName(array $component): string
    {
        switch ($component[1]) {
            case self::COMPONENT_FILTERINPUT_NAME:
            case self::COMPONENT_FILTERINPUT_EMAILS:
            // Add a nice name, so that the URL params when filtering make sense
                $names = array(
                    self::COMPONENT_FILTERINPUT_NAME => 'nombre',
                    self::COMPONENT_FILTERINPUT_EMAILS => 'emails',
                );
                return $names[$component[1]];
        }

        return parent::getName($component);
    }

    public function getFilterInputTypeResolver(array $component): InputTypeResolverInterface
    {
        return match ($component[1]) {
            self::COMPONENT_FILTERINPUT_NAME => $this->getStringScalarTypeResolver(),
            self::COMPONENT_FILTERINPUT_EMAILS => $this->getEmailScalarTypeResolver(),
            default => $this->getDefaultSchemaFilterInputTypeResolver(),
        };
    }

    public function getFilterInputTypeModifiers(array $component): int
    {
        return match ($component[1]) {
            self::COMPONENT_FILTERINPUT_EMAILS => SchemaTypeModifiers::IS_ARRAY | SchemaTypeModifiers::IS_NON_NULLABLE_ITEMS_IN_ARRAY,
            default => SchemaTypeModifiers::NONE,
        };
    }

    public function getFilterInputDescription(array $component): ?string
    {
        return match ($component[1]) {
            self::COMPONENT_FILTERINPUT_NAME => $this->__('Search users whose name contains this string', 'pop-users'),
            self::COMPONENT_FILTERINPUT_EMAILS => $this->__('Search users with any of the provided emails', 'pop-users'),
            default => null,
        };
    }
}

<?php

declare(strict_types=1);

namespace PoPSchema\Users\ModuleProcessors\FormInputs;

use PoP\ComponentModel\ModuleProcessors\AbstractFormInputModuleProcessor;
use PoP\ComponentModel\ModuleProcessors\DataloadQueryArgsFilterInputModuleProcessorInterface;
use PoP\ComponentModel\ModuleProcessors\DataloadQueryArgsSchemaFilterInputModuleProcessorInterface;
use PoP\ComponentModel\ModuleProcessors\DataloadQueryArgsSchemaFilterInputModuleProcessorTrait;
use PoP\ComponentModel\Schema\SchemaTypeModifiers;
use PoP\ComponentModel\TypeResolvers\InputTypeResolverInterface;
use PoP\Engine\TypeResolvers\ScalarType\StringScalarTypeResolver;
use PoPSchema\SchemaCommons\TypeResolvers\ScalarType\EmailScalarTypeResolver;
use PoPSchema\Users\FilterInputProcessors\FilterInputProcessor;
use Symfony\Contracts\Service\Attribute\Required;

class FilterInputModuleProcessor extends AbstractFormInputModuleProcessor implements DataloadQueryArgsFilterInputModuleProcessorInterface, DataloadQueryArgsSchemaFilterInputModuleProcessorInterface
{
    use DataloadQueryArgsSchemaFilterInputModuleProcessorTrait;

    public const MODULE_FILTERINPUT_NAME = 'filterinput-name';
    public const MODULE_FILTERINPUT_EMAILS = 'filterinput-emails';

    protected EmailScalarTypeResolver $emailScalarTypeResolver;
    protected StringScalarTypeResolver $stringScalarTypeResolver;

    #[Required]
    final public function autowireFilterInputModuleProcessor(
        EmailScalarTypeResolver $emailScalarTypeResolver,
        StringScalarTypeResolver $stringScalarTypeResolver,
    ): void {
        $this->emailScalarTypeResolver = $emailScalarTypeResolver;
        $this->stringScalarTypeResolver = $stringScalarTypeResolver;
    }

    public function getModulesToProcess(): array
    {
        return array(
            [self::class, self::MODULE_FILTERINPUT_NAME],
            [self::class, self::MODULE_FILTERINPUT_EMAILS],
        );
    }

    public function getFilterInput(array $module): ?array
    {
        $filterInputs = [
            self::MODULE_FILTERINPUT_NAME => [FilterInputProcessor::class, FilterInputProcessor::FILTERINPUT_NAME],
            self::MODULE_FILTERINPUT_EMAILS => [FilterInputProcessor::class, FilterInputProcessor::FILTERINPUT_EMAILS],
        ];
        return $filterInputs[$module[1]] ?? null;
    }

    public function getName(array $module): string
    {
        switch ($module[1]) {
            case self::MODULE_FILTERINPUT_NAME:
            case self::MODULE_FILTERINPUT_EMAILS:
            // Add a nice name, so that the URL params when filtering make sense
                $names = array(
                    self::MODULE_FILTERINPUT_NAME => 'nombre',
                    self::MODULE_FILTERINPUT_EMAILS => 'emails',
                );
                return $names[$module[1]];
        }

        return parent::getName($module);
    }

    public function getFilterInputTypeResolver(array $module): InputTypeResolverInterface
    {
        return match ($module[1]) {
            self::MODULE_FILTERINPUT_NAME => $this->stringScalarTypeResolver,
            self::MODULE_FILTERINPUT_EMAILS => $this->emailScalarTypeResolver,
            default => $this->getDefaultSchemaFilterInputTypeResolver(),
        };
    }

    public function getFilterInputTypeModifiers(array $module): int
    {
        return match ($module[1]) {
            self::MODULE_FILTERINPUT_EMAILS => SchemaTypeModifiers::IS_ARRAY | SchemaTypeModifiers::IS_NON_NULLABLE_ITEMS_IN_ARRAY,
            default => SchemaTypeModifiers::NONE,
        };
    }

    public function getFilterInputDescription(array $module): ?string
    {
        return match ($module[1]) {
            self::MODULE_FILTERINPUT_NAME => $this->translationAPI->__('Search users whose name contains this string', 'pop-users'),
            self::MODULE_FILTERINPUT_EMAILS => $this->translationAPI->__('Search users with any of the provided emails', 'pop-users'),
            default => null,
        };
    }
}

<?php

declare(strict_types=1);

namespace PoPCMSSchema\CustomPosts\ModuleProcessors\FormInputs;

use PoP\ComponentModel\FormInputs\FormMultipleInput;
use PoP\ComponentModel\ModuleProcessors\AbstractFilterInputModuleProcessor;
use PoP\ComponentModel\ModuleProcessors\DataloadQueryArgsFilterInputModuleProcessorInterface;
use PoP\ComponentModel\Schema\SchemaTypeModifiers;
use PoP\ComponentModel\TypeResolvers\InputTypeResolverInterface;
use PoPCMSSchema\CustomPosts\FilterInputProcessors\FilterInputProcessor;
use PoPCMSSchema\CustomPosts\TypeResolvers\EnumType\CustomPostEnumTypeResolver;
use PoPCMSSchema\CustomPosts\TypeResolvers\EnumType\FilterCustomPostStatusEnumTypeResolver;
use PoPCMSSchema\CustomPosts\Enums\CustomPostStatus;

class FilterInputModuleProcessor extends AbstractFilterInputModuleProcessor implements DataloadQueryArgsFilterInputModuleProcessorInterface
{
    public const MODULE_FILTERINPUT_CUSTOMPOSTSTATUS = 'filterinput-custompoststatus';
    public const MODULE_FILTERINPUT_UNIONCUSTOMPOSTTYPES = 'filterinput-unioncustomposttypes';

    private ?FilterCustomPostStatusEnumTypeResolver $filterCustomPostStatusEnumTypeResolver = null;
    private ?CustomPostEnumTypeResolver $customPostEnumTypeResolver = null;

    final public function setFilterCustomPostStatusEnumTypeResolver(FilterCustomPostStatusEnumTypeResolver $filterCustomPostStatusEnumTypeResolver): void
    {
        $this->filterCustomPostStatusEnumTypeResolver = $filterCustomPostStatusEnumTypeResolver;
    }
    final protected function getFilterCustomPostStatusEnumTypeResolver(): FilterCustomPostStatusEnumTypeResolver
    {
        return $this->filterCustomPostStatusEnumTypeResolver ??= $this->instanceManager->getInstance(FilterCustomPostStatusEnumTypeResolver::class);
    }
    final public function setCustomPostEnumTypeResolver(CustomPostEnumTypeResolver $customPostEnumTypeResolver): void
    {
        $this->customPostEnumTypeResolver = $customPostEnumTypeResolver;
    }
    final protected function getCustomPostEnumTypeResolver(): CustomPostEnumTypeResolver
    {
        return $this->customPostEnumTypeResolver ??= $this->instanceManager->getInstance(CustomPostEnumTypeResolver::class);
    }

    public function getModulesToProcess(): array
    {
        return array(
            [self::class, self::MODULE_FILTERINPUT_CUSTOMPOSTSTATUS],
            [self::class, self::MODULE_FILTERINPUT_UNIONCUSTOMPOSTTYPES],
        );
    }

    public function getFilterInput(array $module): ?array
    {
        $filterInputs = [
            self::MODULE_FILTERINPUT_CUSTOMPOSTSTATUS => [FilterInputProcessor::class, FilterInputProcessor::FILTERINPUT_CUSTOMPOSTSTATUS],
            self::MODULE_FILTERINPUT_UNIONCUSTOMPOSTTYPES => [FilterInputProcessor::class, FilterInputProcessor::FILTERINPUT_UNIONCUSTOMPOSTTYPES],
        ];
        return $filterInputs[$module[1]] ?? null;
    }

    public function getInputClass(array $module): string
    {
        switch ($module[1]) {
            case self::MODULE_FILTERINPUT_CUSTOMPOSTSTATUS:
            case self::MODULE_FILTERINPUT_UNIONCUSTOMPOSTTYPES:
                return FormMultipleInput::class;
        }

        return parent::getInputClass($module);
    }
    public function getName(array $module): string
    {
        switch ($module[1]) {
            case self::MODULE_FILTERINPUT_CUSTOMPOSTSTATUS:
            case self::MODULE_FILTERINPUT_UNIONCUSTOMPOSTTYPES:
                // Add a nice name, so that the URL params when filtering make sense
                $names = array(
                    self::MODULE_FILTERINPUT_CUSTOMPOSTSTATUS => 'status',
                    self::MODULE_FILTERINPUT_UNIONCUSTOMPOSTTYPES => 'customPostTypes',
                );
                return $names[$module[1]];
        }

        return parent::getName($module);
    }

    public function getFilterInputTypeResolver(array $module): InputTypeResolverInterface
    {
        return match ($module[1]) {
            self::MODULE_FILTERINPUT_CUSTOMPOSTSTATUS => $this->getFilterCustomPostStatusEnumTypeResolver(),
            self::MODULE_FILTERINPUT_UNIONCUSTOMPOSTTYPES => $this->getCustomPostEnumTypeResolver(),
            default => $this->getDefaultSchemaFilterInputTypeResolver(),
        };
    }

    public function getFilterInputTypeModifiers(array $module): int
    {
        return match ($module[1]) {
            self::MODULE_FILTERINPUT_CUSTOMPOSTSTATUS,
            self::MODULE_FILTERINPUT_UNIONCUSTOMPOSTTYPES
                => SchemaTypeModifiers::IS_ARRAY | SchemaTypeModifiers::IS_NON_NULLABLE_ITEMS_IN_ARRAY,
            default
                => SchemaTypeModifiers::NONE,
        };
    }

    public function getFilterInputDefaultValue(array $module): mixed
    {
        return match ($module[1]) {
            self::MODULE_FILTERINPUT_CUSTOMPOSTSTATUS => [
                CustomPostStatus::PUBLISH,
            ],
            self::MODULE_FILTERINPUT_UNIONCUSTOMPOSTTYPES => $this->getCustomPostEnumTypeResolver()->getConsolidatedEnumValues(),
            default => null,
        };
    }

    public function getFilterInputDescription(array $module): ?string
    {
        return match ($module[1]) {
            self::MODULE_FILTERINPUT_CUSTOMPOSTSTATUS => $this->__('Custom Post Status', 'customposts'),
            self::MODULE_FILTERINPUT_UNIONCUSTOMPOSTTYPES => $this->__('Return results from Union of the Custom Post Types', 'customposts'),
            default => null,
        };
    }
}

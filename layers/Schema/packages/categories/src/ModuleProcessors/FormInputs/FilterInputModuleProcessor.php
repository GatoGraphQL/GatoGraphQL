<?php

declare(strict_types=1);

namespace PoPSchema\Categories\ModuleProcessors\FormInputs;

use PoP\ComponentModel\FormInputs\FormMultipleInput;
use PoP\ComponentModel\ModuleProcessors\AbstractFilterInputModuleProcessor;
use PoP\ComponentModel\ModuleProcessors\DataloadQueryArgsFilterInputModuleProcessorInterface;
use PoP\ComponentModel\Schema\SchemaTypeModifiers;
use PoP\ComponentModel\TypeResolvers\InputTypeResolverInterface;
use PoP\Engine\TypeResolvers\ScalarType\IDScalarTypeResolver;
use PoPSchema\Categories\FilterInputProcessors\FilterInputProcessor;

class FilterInputModuleProcessor extends AbstractFilterInputModuleProcessor implements DataloadQueryArgsFilterInputModuleProcessorInterface
{
    public const MODULE_FILTERINPUT_CATEGORY_IDS = 'filterinput-category-ids';

    private ?IDScalarTypeResolver $idScalarTypeResolver = null;

    final public function setIDScalarTypeResolver(IDScalarTypeResolver $idScalarTypeResolver): void
    {
        $this->idScalarTypeResolver = $idScalarTypeResolver;
    }
    final protected function getIDScalarTypeResolver(): IDScalarTypeResolver
    {
        return $this->idScalarTypeResolver ??= $this->instanceManager->getInstance(IDScalarTypeResolver::class);
    }

    public function getModulesToProcess(): array
    {
        return array(
            [self::class, self::MODULE_FILTERINPUT_CATEGORY_IDS],
        );
    }

    public function getFilterInput(array $module): ?array
    {
        $filterInputs = [
            self::MODULE_FILTERINPUT_CATEGORY_IDS => [FilterInputProcessor::class, FilterInputProcessor::FILTERINPUT_CATEGORY_IDS],
        ];
        return $filterInputs[$module[1]] ?? null;
    }

    public function getInputClass(array $module): string
    {
        switch ($module[1]) {
            case self::MODULE_FILTERINPUT_CATEGORY_IDS:
                return FormMultipleInput::class;
        }

        return parent::getInputClass($module);
    }

    public function getName(array $module): string
    {
        return match ($module[1]) {
            self::MODULE_FILTERINPUT_CATEGORY_IDS => 'categoryIDs',
            default => parent::getName($module),
        };
    }

    public function getFilterInputTypeResolver(array $module): InputTypeResolverInterface
    {
        return match ($module[1]) {
            self::MODULE_FILTERINPUT_CATEGORY_IDS => $this->getIDScalarTypeResolver(),
            default => $this->getDefaultSchemaFilterInputTypeResolver(),
        };
    }

    public function getFilterInputTypeModifiers(array $module): int
    {
        return match ($module[1]) {
            self::MODULE_FILTERINPUT_CATEGORY_IDS => SchemaTypeModifiers::IS_ARRAY | SchemaTypeModifiers::IS_NON_NULLABLE_ITEMS_IN_ARRAY,
            default => SchemaTypeModifiers::NONE,
        };
    }

    public function getFilterInputDescription(array $module): ?string
    {
        return match ($module[1]) {
            self::MODULE_FILTERINPUT_CATEGORY_IDS => $this->__('Limit results to elements with the given ids', 'categories'),
            default => null,
        };
    }
}

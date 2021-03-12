<?php

declare(strict_types=1);

namespace PoPSchema\Categories\ModuleProcessors;

use PoP\Translation\Facades\TranslationAPIFacade;
use PoP\ComponentModel\Schema\SchemaDefinition;
use PoP\ComponentModel\ModuleProcessors\DataloadQueryArgsSchemaFilterInputModuleProcessorTrait;
use PoP\ComponentModel\ModuleProcessors\DataloadQueryArgsFilterInputModuleProcessorInterface;
use PoP\ComponentModel\ModuleProcessors\DataloadQueryArgsSchemaFilterInputModuleProcessorInterface;
use PoP\ComponentModel\ModuleProcessors\AbstractFormInputModuleProcessor;
use PoPSchema\SchemaCommons\FilterInputProcessors\FilterInputProcessor;

class FilterInputModuleProcessor extends AbstractFormInputModuleProcessor implements DataloadQueryArgsFilterInputModuleProcessorInterface, DataloadQueryArgsSchemaFilterInputModuleProcessorInterface
{
    use DataloadQueryArgsSchemaFilterInputModuleProcessorTrait;

    public const MODULE_FILTERINPUT_SEARCH = 'filterinput-search';

    public function getModulesToProcess(): array
    {
        return array(
            [self::class, self::MODULE_FILTERINPUT_SEARCH],
        );
    }

    public function getFilterInput(array $module): ?array
    {
        $filterInputs = [
            self::MODULE_FILTERINPUT_SEARCH => [FilterInputProcessor::class, FilterInputProcessor::FILTERINPUT_SEARCH],
        ];
        return $filterInputs[$module[1]] ?? null;
    }

    public function getName(array $module)
    {
        switch ($module[1]) {
            case self::MODULE_FILTERINPUT_SEARCH:
                // Add a nice name, so that the URL params when filtering make sense
                $names = array(
                    self::MODULE_FILTERINPUT_SEARCH => 'searchfor',
                );
                return $names[$module[1]];
        }

        return parent::getName($module);
    }

    public function getSchemaFilterInputType(array $module): ?string
    {
        $types = [
            self::MODULE_FILTERINPUT_SEARCH => SchemaDefinition::TYPE_STRING,
        ];
        return $types[$module[1]] ?? null;
    }

    public function getSchemaFilterInputDescription(array $module): ?string
    {
        $translationAPI = TranslationAPIFacade::getInstance();
        $descriptions = [
            self::MODULE_FILTERINPUT_SEARCH => $translationAPI->__('Search for categories containing the given string', 'pop-categories'),
        ];
        return $descriptions[$module[1]] ?? null;
    }
}




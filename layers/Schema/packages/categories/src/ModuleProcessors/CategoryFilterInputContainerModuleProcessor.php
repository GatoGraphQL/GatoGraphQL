<?php

declare(strict_types=1);

namespace PoPSchema\Categories\ModuleProcessors;

use PoPSchema\SchemaCommons\ModuleProcessors\AbstractFilterInputContainerModuleProcessor;
use PoPSchema\SchemaCommons\ModuleProcessors\FormInputs\CommonFilterInputModuleProcessor;
use PoPSchema\Taxonomies\ModuleProcessors\FormInputs\FilterInputModuleProcessor;

class CategoryFilterInputContainerModuleProcessor extends AbstractFilterInputContainerModuleProcessor
{
    public const HOOK_FILTER_INPUTS = __CLASS__ . ':filter-inputs';

    public const MODULE_FILTERINPUTCONTAINER_CATEGORIES = 'filterinputcontainer-categories';
    public const MODULE_FILTERINPUTCONTAINER_CATEGORYCOUNT = 'filterinputcontainer-categorycount';
    public const MODULE_FILTERINPUTCONTAINER_CHILDCATEGORIES = 'filterinputcontainer-childcategories';
    public const MODULE_FILTERINPUTCONTAINER_CHILDCATEGORYCOUNT = 'filterinputcontainer-childcategorycount';

    public function getModulesToProcess(): array
    {
        return array(
            [self::class, self::MODULE_FILTERINPUTCONTAINER_CATEGORIES],
            [self::class, self::MODULE_FILTERINPUTCONTAINER_CATEGORYCOUNT],
            [self::class, self::MODULE_FILTERINPUTCONTAINER_CHILDCATEGORIES],
            [self::class, self::MODULE_FILTERINPUTCONTAINER_CHILDCATEGORYCOUNT],
        );
    }

    public function getFilterInputModules(array $module): array
    {
        $filterInputModules = match ($module[1]) {
            self::MODULE_FILTERINPUTCONTAINER_CATEGORIES,
            self::MODULE_FILTERINPUTCONTAINER_CHILDCATEGORIES => [
                [CommonFilterInputModuleProcessor::class, CommonFilterInputModuleProcessor::MODULE_FILTERINPUT_SEARCH],
                [CommonFilterInputModuleProcessor::class, CommonFilterInputModuleProcessor::MODULE_FILTERINPUT_ORDER],
                [CommonFilterInputModuleProcessor::class, CommonFilterInputModuleProcessor::MODULE_FILTERINPUT_LIMIT],
                [CommonFilterInputModuleProcessor::class, CommonFilterInputModuleProcessor::MODULE_FILTERINPUT_OFFSET],
                [CommonFilterInputModuleProcessor::class, CommonFilterInputModuleProcessor::MODULE_FILTERINPUT_IDS],
                [CommonFilterInputModuleProcessor::class, CommonFilterInputModuleProcessor::MODULE_FILTERINPUT_ID],
                [FilterInputModuleProcessor::class, FilterInputModuleProcessor::MODULE_FILTERINPUT_SLUGS],
            ],
            self::MODULE_FILTERINPUTCONTAINER_CATEGORYCOUNT,
            self::MODULE_FILTERINPUTCONTAINER_CHILDCATEGORYCOUNT => [
                [CommonFilterInputModuleProcessor::class, CommonFilterInputModuleProcessor::MODULE_FILTERINPUT_SEARCH],
                [CommonFilterInputModuleProcessor::class, CommonFilterInputModuleProcessor::MODULE_FILTERINPUT_IDS],
                [CommonFilterInputModuleProcessor::class, CommonFilterInputModuleProcessor::MODULE_FILTERINPUT_ID],
                [FilterInputModuleProcessor::class, FilterInputModuleProcessor::MODULE_FILTERINPUT_SLUGS],
            ],
            default => [],
        };
        if (
            in_array($module[1], [
            self::MODULE_FILTERINPUTCONTAINER_CATEGORIES,
            self::MODULE_FILTERINPUTCONTAINER_CATEGORYCOUNT,
            ])
        ) {
            $filterInputModules[] = [CommonFilterInputModuleProcessor::class, CommonFilterInputModuleProcessor::MODULE_FILTERINPUT_PARENT_ID];
        }
        return $filterInputModules;
    }

    /**
     * @return string[]
     */
    protected function getFilterInputHookNames(): array
    {
        return [
            ...parent::getFilterInputHookNames(),
            self::HOOK_FILTER_INPUTS,
        ];
    }
}

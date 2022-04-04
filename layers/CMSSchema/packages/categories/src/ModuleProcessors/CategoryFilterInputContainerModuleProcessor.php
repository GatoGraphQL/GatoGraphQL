<?php

declare(strict_types=1);

namespace PoPCMSSchema\Categories\ModuleProcessors;

use PoPCMSSchema\SchemaCommons\ModuleProcessors\AbstractFilterInputContainerModuleProcessor;
use PoPCMSSchema\SchemaCommons\ModuleProcessors\FormInputs\CommonFilterInputModuleProcessor;

class CategoryFilterInputContainerModuleProcessor extends AbstractFilterInputContainerModuleProcessor
{
    public const HOOK_FILTER_INPUTS = __CLASS__ . ':filter-inputs';

    public final const MODULE_FILTERINPUTCONTAINER_CATEGORIES = 'filterinputcontainer-categories';
    public final const MODULE_FILTERINPUTCONTAINER_CATEGORYCOUNT = 'filterinputcontainer-categorycount';
    public final const MODULE_FILTERINPUTCONTAINER_CHILDCATEGORIES = 'filterinputcontainer-childcategories';
    public final const MODULE_FILTERINPUTCONTAINER_CHILDCATEGORYCOUNT = 'filterinputcontainer-childcategorycount';

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
        $categoryFilterInputModules = [
            ...$this->getIDFilterInputModules(),
            [CommonFilterInputModuleProcessor::class, CommonFilterInputModuleProcessor::MODULE_FILTERINPUT_SEARCH],
            [CommonFilterInputModuleProcessor::class, CommonFilterInputModuleProcessor::MODULE_FILTERINPUT_SLUGS],
        ];
        $topLevelCategoryFilterInputModules = [
            [CommonFilterInputModuleProcessor::class, CommonFilterInputModuleProcessor::MODULE_FILTERINPUT_PARENT_ID],
        ];
        $paginationFilterInputModules = $this->getPaginationFilterInputModules();
        return match ($module[1]) {
            self::MODULE_FILTERINPUTCONTAINER_CATEGORIES => [
                ...$categoryFilterInputModules,
                ...$topLevelCategoryFilterInputModules,
                ...$paginationFilterInputModules,
            ],
            self::MODULE_FILTERINPUTCONTAINER_CHILDCATEGORIES => [
                ...$categoryFilterInputModules,
                ...$paginationFilterInputModules,
            ],
            self::MODULE_FILTERINPUTCONTAINER_CATEGORYCOUNT => [
                ...$categoryFilterInputModules,
                ...$topLevelCategoryFilterInputModules,
            ],
            self::MODULE_FILTERINPUTCONTAINER_CHILDCATEGORYCOUNT => $categoryFilterInputModules,
            default => [],
        };
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

<?php

declare(strict_types=1);

namespace PoPSchema\Pages\ModuleProcessors;

use PoPSchema\CustomPosts\ModuleProcessors\CustomPostFilterInputContainerModuleProcessor;
use PoPSchema\SchemaCommons\ModuleProcessors\FormInputs\CommonFilterInputModuleProcessor;

class PageFilterInputContainerModuleProcessor extends CustomPostFilterInputContainerModuleProcessor
{
    public const HOOK_FILTER_INPUTS = __CLASS__ . ':filter-inputs';

    public const MODULE_FILTERINNER_PAGELISTLIST = 'filterinner-pagelist';
    public const MODULE_FILTERINNER_PAGELISTCOUNT = 'filterinner-pagecount';
    public const MODULE_FILTERINNER_ADMINPAGELISTLIST = 'filterinner-adminpagelist';
    public const MODULE_FILTERINNER_ADMINPAGELISTCOUNT = 'filterinner-adminpagecount';

    public function getModulesToProcess(): array
    {
        return array(
            [self::class, self::MODULE_FILTERINNER_PAGELISTLIST],
            [self::class, self::MODULE_FILTERINNER_PAGELISTCOUNT],
            [self::class, self::MODULE_FILTERINNER_ADMINPAGELISTLIST],
            [self::class, self::MODULE_FILTERINNER_ADMINPAGELISTCOUNT],
        );
    }

    public function getFilterInputModules(array $module): array
    {
        // Get the original config from above
        $targetModule = match ($module[1]) {
            self::MODULE_FILTERINNER_PAGELISTLIST => [self::class, self::MODULE_FILTERINNER_CUSTOMPOSTLISTLIST],
            self::MODULE_FILTERINNER_PAGELISTCOUNT => [self::class, self::MODULE_FILTERINNER_CUSTOMPOSTLISTCOUNT],
            self::MODULE_FILTERINNER_ADMINPAGELISTLIST => [self::class, self::MODULE_FILTERINNER_ADMINCUSTOMPOSTLISTLIST],
            self::MODULE_FILTERINNER_ADMINPAGELISTCOUNT => [self::class, self::MODULE_FILTERINNER_ADMINCUSTOMPOSTLISTCOUNT],
            default => null,
        };
        $filterInputModules = parent::getFilterInputModules($targetModule);
        // Add the parentIDs and parentID filterInputs
        $filterInputModules[] = [CommonFilterInputModuleProcessor::class, CommonFilterInputModuleProcessor::MODULE_FILTERINPUT_PARENT_IDS];
        $filterInputModules[] = [CommonFilterInputModuleProcessor::class, CommonFilterInputModuleProcessor::MODULE_FILTERINPUT_PARENT_ID];
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

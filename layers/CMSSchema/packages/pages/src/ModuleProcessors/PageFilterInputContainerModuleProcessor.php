<?php

declare(strict_types=1);

namespace PoPSchema\Pages\ModuleProcessors;

use PoPSchema\CustomPosts\ModuleProcessors\CustomPostFilterInputContainerModuleProcessor;
use PoPCMSSchema\SchemaCommons\ModuleProcessors\FormInputs\CommonFilterInputModuleProcessor;

class PageFilterInputContainerModuleProcessor extends CustomPostFilterInputContainerModuleProcessor
{
    public const HOOK_FILTER_INPUTS = __CLASS__ . ':filter-inputs';

    public const MODULE_FILTERINPUTCONTAINER_PAGELISTLIST = 'filterinputcontainer-pagelist';
    public const MODULE_FILTERINPUTCONTAINER_PAGELISTCOUNT = 'filterinputcontainer-pagecount';
    public const MODULE_FILTERINPUTCONTAINER_ADMINPAGELISTLIST = 'filterinputcontainer-adminpagelist';
    public const MODULE_FILTERINPUTCONTAINER_ADMINPAGELISTCOUNT = 'filterinputcontainer-adminpagecount';

    public function getModulesToProcess(): array
    {
        return array(
            [self::class, self::MODULE_FILTERINPUTCONTAINER_PAGELISTLIST],
            [self::class, self::MODULE_FILTERINPUTCONTAINER_PAGELISTCOUNT],
            [self::class, self::MODULE_FILTERINPUTCONTAINER_ADMINPAGELISTLIST],
            [self::class, self::MODULE_FILTERINPUTCONTAINER_ADMINPAGELISTCOUNT],
        );
    }

    public function getFilterInputModules(array $module): array
    {
        // Get the original config from above
        $targetModule = match ($module[1]) {
            self::MODULE_FILTERINPUTCONTAINER_PAGELISTLIST => [parent::class, parent::MODULE_FILTERINPUTCONTAINER_CUSTOMPOSTLISTLIST],
            self::MODULE_FILTERINPUTCONTAINER_PAGELISTCOUNT => [parent::class, parent::MODULE_FILTERINPUTCONTAINER_CUSTOMPOSTLISTCOUNT],
            self::MODULE_FILTERINPUTCONTAINER_ADMINPAGELISTLIST => [parent::class, parent::MODULE_FILTERINPUTCONTAINER_ADMINCUSTOMPOSTLISTLIST],
            self::MODULE_FILTERINPUTCONTAINER_ADMINPAGELISTCOUNT => [parent::class, parent::MODULE_FILTERINPUTCONTAINER_ADMINCUSTOMPOSTLISTCOUNT],
            default => null,
        };
        $filterInputModules = parent::getFilterInputModules($targetModule);
        // Add the parentIDs and parentID filterInputs
        $filterInputModules[] = [CommonFilterInputModuleProcessor::class, CommonFilterInputModuleProcessor::MODULE_FILTERINPUT_PARENT_IDS];
        $filterInputModules[] = [CommonFilterInputModuleProcessor::class, CommonFilterInputModuleProcessor::MODULE_FILTERINPUT_PARENT_ID];
        $filterInputModules[] = [CommonFilterInputModuleProcessor::class, CommonFilterInputModuleProcessor::MODULE_FILTERINPUT_EXCLUDE_PARENT_IDS];
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

<?php

declare(strict_types=1);

namespace PoPSchema\CustomPosts\ModuleProcessors;

use PoP\Hooks\Facades\HooksAPIFacade;
use PoP\ComponentModel\ModuleProcessors\AbstractModuleProcessor;
use PoPSchema\SchemaCommons\ModuleProcessors\FormInputs\CommonFilterInputs;
use PoPSchema\SchemaCommons\ModuleProcessors\FormInputs\CommonFilterMultipleInputs;
use PoPSchema\CustomPosts\ModuleProcessors\FormInputs\FilterInputs;

class CustomPostFilterInnerModuleProcessor extends AbstractModuleProcessor
{
    public const MODULE_FILTERINNER_UNIONCUSTOMPOSTLIST = 'filterinner-unioncustompostlist';
    public const MODULE_FILTERINNER_UNIONCUSTOMPOSTCOUNT = 'filterinner-unioncustompostcount';
    public const MODULE_FILTERINNER_CUSTOMPOSTLISTLIST = 'filterinner-custompostlist';
    public const MODULE_FILTERINNER_CUSTOMPOSTLISTCOUNT = 'filterinner-custompostcount';

    public function getModulesToProcess(): array
    {
        return array(
            [self::class, self::MODULE_FILTERINNER_UNIONCUSTOMPOSTLIST],
            [self::class, self::MODULE_FILTERINNER_UNIONCUSTOMPOSTCOUNT],
            [self::class, self::MODULE_FILTERINNER_CUSTOMPOSTLISTLIST],
            [self::class, self::MODULE_FILTERINNER_CUSTOMPOSTLISTCOUNT],
        );
    }

    public function getSubmodules(array $module): array
    {
        $ret = parent::getSubmodules($module);

        switch ($module[1]) {
            case self::MODULE_FILTERINNER_UNIONCUSTOMPOSTLIST:
            case self::MODULE_FILTERINNER_CUSTOMPOSTLISTLIST:
                $inputmodules = [
                    [CommonFilterInputs::class, CommonFilterInputs::MODULE_FILTERINPUT_SEARCH],
                    [CommonFilterInputs::class, CommonFilterInputs::MODULE_FILTERINPUT_ORDER],
                    [CommonFilterInputs::class, CommonFilterInputs::MODULE_FILTERINPUT_LIMIT],
                    [CommonFilterInputs::class, CommonFilterInputs::MODULE_FILTERINPUT_OFFSET],
                    [CommonFilterMultipleInputs::class, CommonFilterMultipleInputs::MODULE_FILTERINPUT_DATES],
                    [CommonFilterInputs::class, CommonFilterInputs::MODULE_FILTERINPUT_IDS],
                    [CommonFilterInputs::class, CommonFilterInputs::MODULE_FILTERINPUT_ID],
                ];
                break;
            case self::MODULE_FILTERINNER_UNIONCUSTOMPOSTCOUNT:
            case self::MODULE_FILTERINNER_CUSTOMPOSTLISTCOUNT:
                $inputmodules = [
                    [CommonFilterInputs::class, CommonFilterInputs::MODULE_FILTERINPUT_SEARCH],
                    [CommonFilterMultipleInputs::class, CommonFilterMultipleInputs::MODULE_FILTERINPUT_DATES],
                    [CommonFilterInputs::class, CommonFilterInputs::MODULE_FILTERINPUT_IDS],
                    [CommonFilterInputs::class, CommonFilterInputs::MODULE_FILTERINPUT_ID],
                ];
                break;
        }
        // Fields "customPosts" and "customPostCount" also have the "postTypes" filter
        if (
            in_array($module[1], [
            self::MODULE_FILTERINNER_UNIONCUSTOMPOSTLIST,
            self::MODULE_FILTERINNER_UNIONCUSTOMPOSTCOUNT,
            ])
        ) {
            $inputmodules[] = [FilterInputs::class, FilterInputs::MODULE_FILTERINPUT_UNIONCUSTOMPOSTTYPES];
        }
        if (
            $modules = HooksAPIFacade::getInstance()->applyFilters(
                'CustomPosts:FilterInnerModuleProcessor:inputmodules',
                $inputmodules,
                $module
            )
        ) {
            $ret = array_merge(
                $ret,
                $modules
            );
        }
        return $ret;
    }
}

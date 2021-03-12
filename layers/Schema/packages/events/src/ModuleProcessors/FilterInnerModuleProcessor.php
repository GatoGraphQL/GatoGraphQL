<?php

declare(strict_types=1);

namespace PoPSchema\Events\ModuleProcessors;

use PoP\ComponentModel\ModuleProcessors\AbstractModuleProcessor;
use PoPSchema\SchemaCommons\ModuleProcessors\FormInputs\CommonFilterInputs;
use PoPSchema\SchemaCommons\ModuleProcessors\FormInputs\CommonFilterMultipleInputs;

class FilterInnerModuleProcessor extends AbstractModuleProcessor
{
    public const MODULE_FILTERINNER_EVENTLIST = 'filterinner-eventlist';
    public const MODULE_FILTERINNER_EVENTCOUNT = 'filterinner-eventcount';

    public function getModulesToProcess(): array
    {
        return array(
            [self::class, self::MODULE_FILTERINNER_EVENTLIST],
            [self::class, self::MODULE_FILTERINNER_EVENTCOUNT],
        );
    }

    public function getSubmodules(array $module): array
    {
        $ret = parent::getSubmodules($module);

        $inputmodules = [
            self::MODULE_FILTERINNER_EVENTLIST => [
                [CommonFilterInputs::class, CommonFilterInputs::MODULE_FILTERINPUT_SEARCH],
                [CommonFilterInputs::class, CommonFilterInputs::MODULE_FILTERINPUT_ORDER],
                [CommonFilterInputs::class, CommonFilterInputs::MODULE_FILTERINPUT_LIMIT],
                [CommonFilterInputs::class, CommonFilterInputs::MODULE_FILTERINPUT_OFFSET],
                [CommonFilterMultipleInputs::class, CommonFilterMultipleInputs::MODULE_FILTERINPUT_DATES],
                [CommonFilterInputs::class, CommonFilterInputs::MODULE_FILTERINPUT_IDS],
                [CommonFilterInputs::class, CommonFilterInputs::MODULE_FILTERINPUT_ID],
            ],
            self::MODULE_FILTERINNER_EVENTCOUNT => [
                [CommonFilterInputs::class, CommonFilterInputs::MODULE_FILTERINPUT_SEARCH],
                [CommonFilterMultipleInputs::class, CommonFilterMultipleInputs::MODULE_FILTERINPUT_DATES],
                [CommonFilterInputs::class, CommonFilterInputs::MODULE_FILTERINPUT_IDS],
                [CommonFilterInputs::class, CommonFilterInputs::MODULE_FILTERINPUT_ID],
            ],
        ];
        $ret = array_merge(
            $ret,
            $inputmodules[$module[1]]
        );
        return $ret;
    }
}

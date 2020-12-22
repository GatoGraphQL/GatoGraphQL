<?php

declare(strict_types=1);

namespace PoPSchema\Events\ModuleProcessors;

use PoP\ComponentModel\ModuleProcessors\AbstractModuleProcessor;

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
                [\PoP_Module_Processor_FilterInputs::class, \PoP_Module_Processor_FilterInputs::MODULE_FILTERINPUT_SEARCH],
                [\PoP_Module_Processor_FilterInputs::class, \PoP_Module_Processor_FilterInputs::MODULE_FILTERINPUT_ORDER],
                [\PoP_Module_Processor_FilterInputs::class, \PoP_Module_Processor_FilterInputs::MODULE_FILTERINPUT_LIMIT],
                [\PoP_Module_Processor_FilterInputs::class, \PoP_Module_Processor_FilterInputs::MODULE_FILTERINPUT_OFFSET],
                [\PoP_Module_Processor_FilterInputs::class, \PoP_Module_Processor_FilterInputs::MODULE_FILTERINPUT_DATES],
                [\PoP_Module_Processor_FilterInputs::class, \PoP_Module_Processor_FilterInputs::MODULE_FILTERINPUT_IDS],
                [\PoP_Module_Processor_FilterInputs::class, \PoP_Module_Processor_FilterInputs::MODULE_FILTERINPUT_ID],
            ],
            self::MODULE_FILTERINNER_EVENTCOUNT => [
                [\PoP_Module_Processor_FilterInputs::class, \PoP_Module_Processor_FilterInputs::MODULE_FILTERINPUT_SEARCH],
                [\PoP_Module_Processor_FilterInputs::class, \PoP_Module_Processor_FilterInputs::MODULE_FILTERINPUT_DATES],
                [\PoP_Module_Processor_FilterInputs::class, \PoP_Module_Processor_FilterInputs::MODULE_FILTERINPUT_IDS],
                [\PoP_Module_Processor_FilterInputs::class, \PoP_Module_Processor_FilterInputs::MODULE_FILTERINPUT_ID],
            ],
        ];
        $ret = array_merge(
            $ret,
            $inputmodules[$module[1]]
        );
        return $ret;
    }
}

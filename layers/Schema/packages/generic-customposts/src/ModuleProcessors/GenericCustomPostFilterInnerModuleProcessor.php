<?php

declare(strict_types=1);

namespace PoPSchema\GenericCustomPosts\ModuleProcessors;

use PoP\Hooks\Facades\HooksAPIFacade;
use PoP\ComponentModel\ModuleProcessors\AbstractModuleProcessor;

class GenericCustomPostFilterInnerModuleProcessor extends AbstractModuleProcessor
{
    public const MODULE_FILTERINNER_GENERICCUSTOMPOSTLIST = 'filterinner-genericcustompostlist';
    public const MODULE_FILTERINNER_GENERICCUSTOMPOSTCOUNT = 'filterinner-genericcustompostcount';

    public function getModulesToProcess(): array
    {
        return array(
            [self::class, self::MODULE_FILTERINNER_GENERICCUSTOMPOSTLIST],
            [self::class, self::MODULE_FILTERINNER_GENERICCUSTOMPOSTCOUNT],
        );
    }

    public function getSubmodules(array $module): array
    {
        $ret = parent::getSubmodules($module);

        switch ($module[1]) {
            case self::MODULE_FILTERINNER_GENERICCUSTOMPOSTLIST:
                $inputmodules = [
                    [\PoP_Module_Processor_FilterInputs::class, \PoP_Module_Processor_FilterInputs::MODULE_FILTERINPUT_SEARCH],
                    [\PoP_Module_Processor_FilterInputs::class, \PoP_Module_Processor_FilterInputs::MODULE_FILTERINPUT_ORDER],
                    [\PoP_Module_Processor_FilterInputs::class, \PoP_Module_Processor_FilterInputs::MODULE_FILTERINPUT_LIMIT],
                    [\PoP_Module_Processor_FilterInputs::class, \PoP_Module_Processor_FilterInputs::MODULE_FILTERINPUT_OFFSET],
                    [\PoP_Module_Processor_FilterInputs::class, \PoP_Module_Processor_FilterInputs::MODULE_FILTERINPUT_DATES],
                    [\PoP_Module_Processor_FilterInputs::class, \PoP_Module_Processor_FilterInputs::MODULE_FILTERINPUT_IDS],
                    [\PoP_Module_Processor_FilterInputs::class, \PoP_Module_Processor_FilterInputs::MODULE_FILTERINPUT_ID],
                    [\PoP_CustomPosts_Module_Processor_FilterInputs::class, \PoP_CustomPosts_Module_Processor_FilterInputs::MODULE_FILTERINPUT_GENERICPOSTTYPES],
                ];
                break;
            case self::MODULE_FILTERINNER_GENERICCUSTOMPOSTCOUNT:
                $inputmodules = [
                    [\PoP_Module_Processor_FilterInputs::class, \PoP_Module_Processor_FilterInputs::MODULE_FILTERINPUT_SEARCH],
                    [\PoP_Module_Processor_FilterInputs::class, \PoP_Module_Processor_FilterInputs::MODULE_FILTERINPUT_DATES],
                    [\PoP_Module_Processor_FilterInputs::class, \PoP_Module_Processor_FilterInputs::MODULE_FILTERINPUT_IDS],
                    [\PoP_Module_Processor_FilterInputs::class, \PoP_Module_Processor_FilterInputs::MODULE_FILTERINPUT_ID],
                    [\PoP_CustomPosts_Module_Processor_FilterInputs::class, \PoP_CustomPosts_Module_Processor_FilterInputs::MODULE_FILTERINPUT_GENERICPOSTTYPES],
                ];
                break;
        }
        if ($modules = HooksAPIFacade::getInstance()->applyFilters(
            'GenericCustomPosts:FilterInners:inputmodules',
            $inputmodules,
            $module
        )) {
            $ret = array_merge(
                $ret,
                $modules
            );
        }
        return $ret;
    }
}

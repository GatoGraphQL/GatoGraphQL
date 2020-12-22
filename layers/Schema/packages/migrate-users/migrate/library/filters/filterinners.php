<?php
use PoP\Hooks\Facades\HooksAPIFacade;
use PoP\ComponentModel\ModuleProcessors\AbstractModuleProcessor;

class PoP_Users_Module_Processor_CustomFilterInners extends AbstractModuleProcessor
{
    public const MODULE_FILTERINNER_USERS = 'filterinner-users';
    public const MODULE_FILTERINNER_USERCOUNT = 'filterinner-usercount';

    public function getModulesToProcess(): array
    {
        return array(
            [self::class, self::MODULE_FILTERINNER_USERS],
            [self::class, self::MODULE_FILTERINNER_USERCOUNT],
        );
    }

    public function getSubmodules(array $module): array
    {
        $ret = parent::getSubmodules($module);

        $inputmodules = [
            self::MODULE_FILTERINNER_USERS => [
                [PoP_Users_Module_Processor_FilterInputs::class, PoP_Users_Module_Processor_FilterInputs::MODULE_FILTERINPUT_NAME],
                [PoP_Users_Module_Processor_FilterInputs::class, PoP_Users_Module_Processor_FilterInputs::MODULE_FILTERINPUT_EMAILS],
                [PoP_Module_Processor_FilterInputs::class, PoP_Module_Processor_FilterInputs::MODULE_FILTERINPUT_ORDER],
                [PoP_Module_Processor_FilterInputs::class, PoP_Module_Processor_FilterInputs::MODULE_FILTERINPUT_LIMIT],
                [PoP_Module_Processor_FilterInputs::class, PoP_Module_Processor_FilterInputs::MODULE_FILTERINPUT_OFFSET],
                [PoP_Module_Processor_FilterInputs::class, PoP_Module_Processor_FilterInputs::MODULE_FILTERINPUT_IDS],
                [PoP_Module_Processor_FilterInputs::class, PoP_Module_Processor_FilterInputs::MODULE_FILTERINPUT_ID],
            ],
            self::MODULE_FILTERINNER_USERCOUNT => [
                [PoP_Users_Module_Processor_FilterInputs::class, PoP_Users_Module_Processor_FilterInputs::MODULE_FILTERINPUT_NAME],
                [PoP_Users_Module_Processor_FilterInputs::class, PoP_Users_Module_Processor_FilterInputs::MODULE_FILTERINPUT_EMAILS],
                [PoP_Module_Processor_FilterInputs::class, PoP_Module_Processor_FilterInputs::MODULE_FILTERINPUT_IDS],
                [PoP_Module_Processor_FilterInputs::class, PoP_Module_Processor_FilterInputs::MODULE_FILTERINPUT_ID],
            ],
        ];
        if ($modules = HooksAPIFacade::getInstance()->applyFilters(
            'Users:FilterInners:inputmodules',
            $inputmodules[$module[1]],
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




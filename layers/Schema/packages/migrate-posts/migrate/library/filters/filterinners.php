<?php
use PoP\Hooks\Facades\HooksAPIFacade;
use PoP\ComponentModel\ModuleProcessors\AbstractModuleProcessor;

class PoP_Posts_Module_Processor_CustomFilterInners extends AbstractModuleProcessor
{
    public const MODULE_FILTERINNER_POSTS = 'filterinner-posts';
    public const MODULE_FILTERINNER_POSTCOUNT = 'filterinner-postcount';

    public function getModulesToProcess(): array
    {
        return array(
            [self::class, self::MODULE_FILTERINNER_POSTS],
            [self::class, self::MODULE_FILTERINNER_POSTCOUNT],
        );
    }

    public function getSubmodules(array $module): array
    {
        $ret = parent::getSubmodules($module);

        $inputmodules = [
            self::MODULE_FILTERINNER_POSTS => [
                [PoP_Module_Processor_FilterInputs::class, PoP_Module_Processor_FilterInputs::MODULE_FILTERINPUT_SEARCH],
                [PoP_Module_Processor_FilterInputs::class, PoP_Module_Processor_FilterInputs::MODULE_FILTERINPUT_ORDER],
                [PoP_Module_Processor_FilterInputs::class, PoP_Module_Processor_FilterInputs::MODULE_FILTERINPUT_LIMIT],
                [PoP_Module_Processor_FilterInputs::class, PoP_Module_Processor_FilterInputs::MODULE_FILTERINPUT_OFFSET],
                [PoP_Module_Processor_FilterInputs::class, PoP_Module_Processor_FilterInputs::MODULE_FILTERINPUT_DATES],
                [PoP_Module_Processor_FilterInputs::class, PoP_Module_Processor_FilterInputs::MODULE_FILTERINPUT_IDS],
                [PoP_Module_Processor_FilterInputs::class, PoP_Module_Processor_FilterInputs::MODULE_FILTERINPUT_ID],
            ],
            self::MODULE_FILTERINNER_POSTCOUNT => [
                [PoP_Module_Processor_FilterInputs::class, PoP_Module_Processor_FilterInputs::MODULE_FILTERINPUT_SEARCH],
                [PoP_Module_Processor_FilterInputs::class, PoP_Module_Processor_FilterInputs::MODULE_FILTERINPUT_DATES],
                [PoP_Module_Processor_FilterInputs::class, PoP_Module_Processor_FilterInputs::MODULE_FILTERINPUT_IDS],
                [PoP_Module_Processor_FilterInputs::class, PoP_Module_Processor_FilterInputs::MODULE_FILTERINPUT_ID],
            ],
        ];
        if ($modules = HooksAPIFacade::getInstance()->applyFilters(
            'Posts:FilterInners:inputmodules',
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




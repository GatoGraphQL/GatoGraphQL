<?php
use PoP\Hooks\Facades\HooksAPIFacade;
use PoP\ComponentModel\ModuleProcessors\AbstractModuleProcessor;
use PoPSchema\SchemaCommons\ModuleProcessors\FormInputs\CommonFilterInputs;

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
                [CommonFilterInputs::class, CommonFilterInputs::MODULE_FILTERINPUT_SEARCH],
                [CommonFilterInputs::class, CommonFilterInputs::MODULE_FILTERINPUT_ORDER],
                [CommonFilterInputs::class, CommonFilterInputs::MODULE_FILTERINPUT_LIMIT],
                [CommonFilterInputs::class, CommonFilterInputs::MODULE_FILTERINPUT_OFFSET],
                [CommonFilterInputs::class, CommonFilterInputs::MODULE_FILTERINPUT_DATES],
                [CommonFilterInputs::class, CommonFilterInputs::MODULE_FILTERINPUT_IDS],
                [CommonFilterInputs::class, CommonFilterInputs::MODULE_FILTERINPUT_ID],
            ],
            self::MODULE_FILTERINNER_POSTCOUNT => [
                [CommonFilterInputs::class, CommonFilterInputs::MODULE_FILTERINPUT_SEARCH],
                [CommonFilterInputs::class, CommonFilterInputs::MODULE_FILTERINPUT_DATES],
                [CommonFilterInputs::class, CommonFilterInputs::MODULE_FILTERINPUT_IDS],
                [CommonFilterInputs::class, CommonFilterInputs::MODULE_FILTERINPUT_ID],
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




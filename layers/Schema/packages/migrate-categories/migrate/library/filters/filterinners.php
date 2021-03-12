<?php
use PoP\Hooks\Facades\HooksAPIFacade;
use PoP\ComponentModel\ModuleProcessors\AbstractModuleProcessor;
use PoPSchema\SchemaCommons\ModuleProcessors\FormInputs\CommonFilterInputs;

class PoP_Categories_Module_Processor_CustomFilterInners extends AbstractModuleProcessor
{
    public const MODULE_FILTERINNER_CATEGORIES = 'filterinner-categories';
    public const MODULE_FILTERINNER_CATEGORYCOUNT = 'filterinner-categorycount';

    public function getModulesToProcess(): array
    {
        return array(
            [self::class, self::MODULE_FILTERINNER_CATEGORIES],
            [self::class, self::MODULE_FILTERINNER_CATEGORYCOUNT],
        );
    }

    public function getSubmodules(array $module): array
    {
        $ret = parent::getSubmodules($module);

        $inputmodules = [
            self::MODULE_FILTERINNER_CATEGORIES => [
                [CommonFilterInputs::class, CommonFilterInputs::MODULE_FILTERINPUT_SEARCH],
                [CommonFilterInputs::class, CommonFilterInputs::MODULE_FILTERINPUT_ORDER],
                [CommonFilterInputs::class, CommonFilterInputs::MODULE_FILTERINPUT_LIMIT],
                [CommonFilterInputs::class, CommonFilterInputs::MODULE_FILTERINPUT_OFFSET],
                [CommonFilterInputs::class, CommonFilterInputs::MODULE_FILTERINPUT_IDS],
                [CommonFilterInputs::class, CommonFilterInputs::MODULE_FILTERINPUT_ID],
            ],
            self::MODULE_FILTERINNER_CATEGORYCOUNT => [
                [CommonFilterInputs::class, CommonFilterInputs::MODULE_FILTERINPUT_SEARCH],
                [CommonFilterInputs::class, CommonFilterInputs::MODULE_FILTERINPUT_IDS],
                [CommonFilterInputs::class, CommonFilterInputs::MODULE_FILTERINPUT_ID],
            ],
        ];
        if ($modules = HooksAPIFacade::getInstance()->applyFilters(
            'Categories:FilterInners:inputmodules',
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




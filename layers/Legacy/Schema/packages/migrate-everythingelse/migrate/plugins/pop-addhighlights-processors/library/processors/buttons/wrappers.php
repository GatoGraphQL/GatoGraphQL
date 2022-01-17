<?php

use PoP\ComponentModel\Facades\Schema\FieldQueryInterpreterFacade;
use PoPCMSSchema\CustomPosts\Types\Status;

class Wassup_Module_Processor_ButtonWrappers extends PoP_Module_Processor_ConditionWrapperBase
{
    public const MODULE_BUTTONWRAPPER_HIGHLIGHTVIEW = 'buttonwrapper-highlightview';

    public function getModulesToProcess(): array
    {
        return array(
            [self::class, self::MODULE_BUTTONWRAPPER_HIGHLIGHTVIEW],
        );
    }

    public function getConditionSucceededSubmodules(array $module)
    {
        $ret = parent::getConditionSucceededSubmodules($module);

        switch ($module[1]) {
            case self::MODULE_BUTTONWRAPPER_HIGHLIGHTVIEW:
                $ret[] = [PoP_AddHighlights_Module_Processor_Buttons::class, PoP_AddHighlights_Module_Processor_Buttons::MODULE_BUTTON_HIGHLIGHTVIEW];
                break;
        }

        return $ret;
    }

    public function getConditionField(array $module): ?string
    {
        switch ($module[1]) {
            case self::MODULE_BUTTONWRAPPER_HIGHLIGHTVIEW:
                return FieldQueryInterpreterFacade::getInstance()->getField('isStatus', ['status' => Status::PUBLISHED], 'published');
        }

        return null;
    }
}




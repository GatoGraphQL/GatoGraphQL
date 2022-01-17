<?php
use PoP\ComponentModel\Facades\Schema\FieldQueryInterpreterFacade;
use PoPCMSSchema\CustomPosts\Types\Status;

class PoP_Module_Processor_ButtonWrappers extends PoP_Module_Processor_ConditionWrapperBase
{
    public const MODULE_BUTTONWRAPPER_POSTPERMALINK = 'buttonwrapper-postpermalink';

    public function getModulesToProcess(): array
    {
        return array(
            [self::class, self::MODULE_BUTTONWRAPPER_POSTPERMALINK],
        );
    }

    public function getConditionSucceededSubmodules(array $module)
    {
        $ret = parent::getConditionSucceededSubmodules($module);

        switch ($module[1]) {
            case self::MODULE_BUTTONWRAPPER_POSTPERMALINK:
                $ret[] = [PoP_Module_Processor_Buttons::class, PoP_Module_Processor_Buttons::MODULE_BUTTON_POSTPERMALINK];
                break;
        }

        return $ret;
    }

    public function getConditionField(array $module): ?string
    {
        switch ($module[1]) {
            case self::MODULE_BUTTONWRAPPER_POSTPERMALINK:
                return FieldQueryInterpreterFacade::getInstance()->getField('isStatus', ['status' => Status::PUBLISHED], 'published');
        }

        return null;
    }
}




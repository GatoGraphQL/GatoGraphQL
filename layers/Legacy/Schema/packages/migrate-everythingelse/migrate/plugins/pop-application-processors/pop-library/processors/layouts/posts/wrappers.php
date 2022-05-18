<?php

use PoP\ComponentModel\Facades\Schema\FieldQueryInterpreterFacade;
use PoPCMSSchema\CustomPosts\Types\Status;

class Wassup_Module_Processor_MultipleComponentLayoutWrappers extends PoP_Module_Processor_ConditionWrapperBase
{
    public final const MODULE_MULTICOMPONENTWRAPPER_USERHIGHLIGHTPOSTINTERACTION = 'multicomponentwrapper-userhighlightpostinteraction';
    public final const MODULE_MULTICOMPONENTWRAPPER_USERPOSTINTERACTION = 'multicomponentwrapper-userpostinteraction';

    public function getComponentVariationsToProcess(): array
    {
        return array(
            [self::class, self::MODULE_MULTICOMPONENTWRAPPER_USERHIGHLIGHTPOSTINTERACTION],
            [self::class, self::MODULE_MULTICOMPONENTWRAPPER_USERPOSTINTERACTION],
        );
    }

    public function getConditionSucceededSubmodules(array $module)
    {
        $ret = parent::getConditionSucceededSubmodules($module);

        switch ($module[1]) {
            case self::MODULE_MULTICOMPONENTWRAPPER_USERHIGHLIGHTPOSTINTERACTION:
                $ret[] = [Wassup_Module_Processor_MultipleComponentLayouts::class, Wassup_Module_Processor_MultipleComponentLayouts::MODULE_MULTICOMPONENT_USERHIGHLIGHTPOSTINTERACTION];
                break;

            case self::MODULE_MULTICOMPONENTWRAPPER_USERPOSTINTERACTION:
                $ret[] = [Wassup_Module_Processor_MultipleComponentLayouts::class, Wassup_Module_Processor_MultipleComponentLayouts::MODULE_MULTICOMPONENT_USERPOSTINTERACTION];
                break;
        }

        return $ret;
    }

    public function getConditionField(array $module): ?string
    {
        switch ($module[1]) {
            case self::MODULE_MULTICOMPONENTWRAPPER_USERHIGHLIGHTPOSTINTERACTION:
            case self::MODULE_MULTICOMPONENTWRAPPER_USERPOSTINTERACTION:
                return FieldQueryInterpreterFacade::getInstance()->getField('isStatus', ['status' => Status::PUBLISHED], 'published');
        }

        return null;
    }
}




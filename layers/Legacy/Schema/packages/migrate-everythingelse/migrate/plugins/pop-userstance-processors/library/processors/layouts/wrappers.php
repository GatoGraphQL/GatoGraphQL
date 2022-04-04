<?php
use PoP\ComponentModel\Facades\Schema\FieldQueryInterpreterFacade;
use PoPCMSSchema\CustomPosts\Types\Status;

class UserStance_Module_Processor_CustomWrapperLayouts extends PoP_Module_Processor_ConditionWrapperBase
{
    public final const MODULE_LAYOUTWRAPPER_USERSTANCEPOSTINTERACTION = 'layoutwrapper-userstancepostinteraction';
    public final const MODULE_USERSTANCE_LAYOUTWRAPPER_USERPOSTINTERACTION = 'userstance-layoutwrapper-userpostinteraction';
    public final const MODULE_USERSTANCE_LAYOUTWRAPPER_USERFULLVIEWINTERACTION = 'userstance-layoutwrapper-userfullviewinteraction';

    public function getModulesToProcess(): array
    {
        return array(
            [self::class, self::MODULE_LAYOUTWRAPPER_USERSTANCEPOSTINTERACTION],
            [self::class, self::MODULE_USERSTANCE_LAYOUTWRAPPER_USERPOSTINTERACTION],
            [self::class, self::MODULE_USERSTANCE_LAYOUTWRAPPER_USERFULLVIEWINTERACTION],
        );
    }

    public function getConditionSucceededSubmodules(array $module)
    {
        $ret = parent::getConditionSucceededSubmodules($module);

        switch ($module[1]) {
            case self::MODULE_LAYOUTWRAPPER_USERSTANCEPOSTINTERACTION:
                $ret[] = [UserStance_Module_Processor_UserPostInteractionLayouts::class, UserStance_Module_Processor_UserPostInteractionLayouts::MODULE_LAYOUT_USERSTANCEPOSTINTERACTION];
                break;

            case self::MODULE_USERSTANCE_LAYOUTWRAPPER_USERPOSTINTERACTION:
                $ret[] = [UserStance_Module_Processor_UserPostInteractionLayouts::class, UserStance_Module_Processor_UserPostInteractionLayouts::MODULE_USERSTANCE_LAYOUT_USERPOSTINTERACTION];
                break;

            case self::MODULE_USERSTANCE_LAYOUTWRAPPER_USERFULLVIEWINTERACTION:
                $ret[] = [UserStance_Module_Processor_UserPostInteractionLayouts::class, UserStance_Module_Processor_UserPostInteractionLayouts::MODULE_USERSTANCE_LAYOUT_USERFULLVIEWINTERACTION];
                break;
        }

        return $ret;
    }

    public function getConditionField(array $module): ?string
    {
        switch ($module[1]) {
            case self::MODULE_LAYOUTWRAPPER_USERSTANCEPOSTINTERACTION:
            case self::MODULE_USERSTANCE_LAYOUTWRAPPER_USERPOSTINTERACTION:
            case self::MODULE_USERSTANCE_LAYOUTWRAPPER_USERFULLVIEWINTERACTION:
                return FieldQueryInterpreterFacade::getInstance()->getField('isStatus', ['status' => Status::PUBLISHED], 'published');
        }

        return null;
    }

    public function initModelProps(array $module, array &$props): void
    {
        switch ($module[1]) {
            case self::MODULE_LAYOUTWRAPPER_USERSTANCEPOSTINTERACTION:
            case self::MODULE_USERSTANCE_LAYOUTWRAPPER_USERPOSTINTERACTION:
            case self::MODULE_USERSTANCE_LAYOUTWRAPPER_USERFULLVIEWINTERACTION:
                $this->appendProp($module, $props, 'class', 'userpostinteraction clearfix');
                break;
        }

        parent::initModelProps($module, $props);
    }
}




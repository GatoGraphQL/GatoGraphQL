<?php
use PoP\ComponentModel\Facades\Schema\FieldQueryInterpreterFacade;
use PoPCMSSchema\CustomPosts\Types\Status;

class UserStance_Module_Processor_CustomWrapperLayouts extends PoP_Module_Processor_ConditionWrapperBase
{
    public final const COMPONENT_LAYOUTWRAPPER_USERSTANCEPOSTINTERACTION = 'layoutwrapper-userstancepostinteraction';
    public final const COMPONENT_USERSTANCE_LAYOUTWRAPPER_USERPOSTINTERACTION = 'userstance-layoutwrapper-userpostinteraction';
    public final const COMPONENT_USERSTANCE_LAYOUTWRAPPER_USERFULLVIEWINTERACTION = 'userstance-layoutwrapper-userfullviewinteraction';

    public function getComponentsToProcess(): array
    {
        return array(
            [self::class, self::COMPONENT_LAYOUTWRAPPER_USERSTANCEPOSTINTERACTION],
            [self::class, self::COMPONENT_USERSTANCE_LAYOUTWRAPPER_USERPOSTINTERACTION],
            [self::class, self::COMPONENT_USERSTANCE_LAYOUTWRAPPER_USERFULLVIEWINTERACTION],
        );
    }

    public function getConditionSucceededSubmodules(array $component)
    {
        $ret = parent::getConditionSucceededSubmodules($component);

        switch ($component[1]) {
            case self::COMPONENT_LAYOUTWRAPPER_USERSTANCEPOSTINTERACTION:
                $ret[] = [UserStance_Module_Processor_UserPostInteractionLayouts::class, UserStance_Module_Processor_UserPostInteractionLayouts::COMPONENT_LAYOUT_USERSTANCEPOSTINTERACTION];
                break;

            case self::COMPONENT_USERSTANCE_LAYOUTWRAPPER_USERPOSTINTERACTION:
                $ret[] = [UserStance_Module_Processor_UserPostInteractionLayouts::class, UserStance_Module_Processor_UserPostInteractionLayouts::COMPONENT_USERSTANCE_LAYOUT_USERPOSTINTERACTION];
                break;

            case self::COMPONENT_USERSTANCE_LAYOUTWRAPPER_USERFULLVIEWINTERACTION:
                $ret[] = [UserStance_Module_Processor_UserPostInteractionLayouts::class, UserStance_Module_Processor_UserPostInteractionLayouts::COMPONENT_USERSTANCE_LAYOUT_USERFULLVIEWINTERACTION];
                break;
        }

        return $ret;
    }

    public function getConditionField(array $component): ?string
    {
        switch ($component[1]) {
            case self::COMPONENT_LAYOUTWRAPPER_USERSTANCEPOSTINTERACTION:
            case self::COMPONENT_USERSTANCE_LAYOUTWRAPPER_USERPOSTINTERACTION:
            case self::COMPONENT_USERSTANCE_LAYOUTWRAPPER_USERFULLVIEWINTERACTION:
                return FieldQueryInterpreterFacade::getInstance()->getField('isStatus', ['status' => Status::PUBLISHED], 'published');
        }

        return null;
    }

    public function initModelProps(array $component, array &$props): void
    {
        switch ($component[1]) {
            case self::COMPONENT_LAYOUTWRAPPER_USERSTANCEPOSTINTERACTION:
            case self::COMPONENT_USERSTANCE_LAYOUTWRAPPER_USERPOSTINTERACTION:
            case self::COMPONENT_USERSTANCE_LAYOUTWRAPPER_USERFULLVIEWINTERACTION:
                $this->appendProp($component, $props, 'class', 'userpostinteraction clearfix');
                break;
        }

        parent::initModelProps($component, $props);
    }
}




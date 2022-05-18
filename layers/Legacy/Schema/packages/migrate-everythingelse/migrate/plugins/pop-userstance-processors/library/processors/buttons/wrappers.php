<?php
use PoP\ComponentModel\Facades\Schema\FieldQueryInterpreterFacade;
use PoPCMSSchema\CustomPosts\Types\Status;

class UserStance_Module_Processor_ButtonWrappers extends PoP_Module_Processor_ConditionWrapperBase
{
    public final const COMPONENT_BUTTONWRAPPER_STANCEVIEW = 'buttonwrapper-stanceview';

    public function getComponentsToProcess(): array
    {
        return array(
            [self::class, self::COMPONENT_BUTTONWRAPPER_STANCEVIEW],
        );
    }

    public function getConditionSucceededSubmodules(array $component)
    {
        $ret = parent::getConditionSucceededSubmodules($component);

        switch ($component[1]) {
            case self::COMPONENT_BUTTONWRAPPER_STANCEVIEW:
                $ret[] = [UserStance_Module_Processor_Buttons::class, UserStance_Module_Processor_Buttons::COMPONENT_BUTTON_STANCEVIEW];
                break;
        }

        return $ret;
    }

    public function getConditionField(array $component): ?string
    {
        switch ($component[1]) {
            case self::COMPONENT_BUTTONWRAPPER_STANCEVIEW:
                return FieldQueryInterpreterFacade::getInstance()->getField('isStatus', ['status' => Status::PUBLISHED], 'published');
        }

        return null;
    }
}




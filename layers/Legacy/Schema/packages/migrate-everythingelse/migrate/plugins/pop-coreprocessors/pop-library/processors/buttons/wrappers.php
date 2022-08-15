<?php
use PoPCMSSchema\CustomPosts\Types\Status;

class PoP_Module_Processor_ButtonWrappers extends PoP_Module_Processor_ConditionWrapperBase
{
    public final const COMPONENT_BUTTONWRAPPER_POSTPERMALINK = 'buttonwrapper-postpermalink';

    public function getComponentNamesToProcess(): array
    {
        return array(
            self::COMPONENT_BUTTONWRAPPER_POSTPERMALINK,
        );
    }

    public function getConditionSucceededSubcomponents(\PoP\ComponentModel\Component\Component $component)
    {
        $ret = parent::getConditionSucceededSubcomponents($component);

        switch ($component->name) {
            case self::COMPONENT_BUTTONWRAPPER_POSTPERMALINK:
                $ret[] = [PoP_Module_Processor_Buttons::class, PoP_Module_Processor_Buttons::COMPONENT_BUTTON_POSTPERMALINK];
                break;
        }

        return $ret;
    }

    public function getConditionField(\PoP\ComponentModel\Component\Component $component): ?string
    {
        switch ($component->name) {
            case self::COMPONENT_BUTTONWRAPPER_POSTPERMALINK:
                return /* @todo Re-do this code! Left undone */ new Field('isStatus', ['status' => Status::PUBLISHED], 'published');
        }

        return null;
    }
}




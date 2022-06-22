<?php
use PoP\ComponentModel\Facades\Schema\FieldQueryInterpreterFacade;
use PoPCMSSchema\CustomPosts\Types\Status;

class GD_ContentCreation_Module_Processor_ButtonWrappers extends PoP_Module_Processor_ConditionWrapperBase
{
    public final const COMPONENT_BUTTONWRAPPER_POSTVIEW = 'buttonwrapper-postview';
    public final const COMPONENT_BUTTONWRAPPER_POSTPREVIEW = 'buttonwrapper-postpreview';

    public function getComponentNamesToProcess(): array
    {
        return array(
            self::COMPONENT_BUTTONWRAPPER_POSTVIEW,
            self::COMPONENT_BUTTONWRAPPER_POSTPREVIEW,
        );
    }

    public function getConditionSucceededSubcomponents(\PoP\ComponentModel\Component\Component $component)
    {
        $ret = parent::getConditionSucceededSubcomponents($component);

        switch ($component->name) {
            case self::COMPONENT_BUTTONWRAPPER_POSTVIEW:
                $ret[] = [GD_ContentCreation_Module_Processor_Buttons::class, GD_ContentCreation_Module_Processor_Buttons::COMPONENT_BUTTON_POSTVIEW];
                break;

            case self::COMPONENT_BUTTONWRAPPER_POSTPREVIEW:
                $ret[] = [GD_ContentCreation_Module_Processor_Buttons::class, GD_ContentCreation_Module_Processor_Buttons::COMPONENT_BUTTON_POSTPREVIEW];
                break;
        }

        return $ret;
    }

    public function getConditionField(\PoP\ComponentModel\Component\Component $component): ?string
    {
        switch ($component->name) {
            case self::COMPONENT_BUTTONWRAPPER_POSTVIEW:
                return FieldQueryInterpreterFacade::getInstance()->getField('isStatus', ['status' => Status::PUBLISHED], 'published');

            case self::COMPONENT_BUTTONWRAPPER_POSTPREVIEW:
                return FieldQueryInterpreterFacade::getInstance()->getField('not', ['field' => FieldQueryInterpreterFacade::getInstance()->getField('isStatus', ['status' => Status::PUBLISHED])], 'not-published');
        }

        return null;
    }
}




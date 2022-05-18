<?php
use PoP\ComponentModel\Facades\Schema\FieldQueryInterpreterFacade;
use PoPCMSSchema\CustomPosts\Types\Status;

class GD_ContentCreation_Module_Processor_ButtonWrappers extends PoP_Module_Processor_ConditionWrapperBase
{
    public final const MODULE_BUTTONWRAPPER_POSTVIEW = 'buttonwrapper-postview';
    public final const MODULE_BUTTONWRAPPER_POSTPREVIEW = 'buttonwrapper-postpreview';

    public function getComponentsToProcess(): array
    {
        return array(
            [self::class, self::COMPONENT_BUTTONWRAPPER_POSTVIEW],
            [self::class, self::COMPONENT_BUTTONWRAPPER_POSTPREVIEW],
        );
    }

    public function getConditionSucceededSubmodules(array $component)
    {
        $ret = parent::getConditionSucceededSubmodules($component);

        switch ($component[1]) {
            case self::COMPONENT_BUTTONWRAPPER_POSTVIEW:
                $ret[] = [GD_ContentCreation_Module_Processor_Buttons::class, GD_ContentCreation_Module_Processor_Buttons::COMPONENT_BUTTON_POSTVIEW];
                break;

            case self::COMPONENT_BUTTONWRAPPER_POSTPREVIEW:
                $ret[] = [GD_ContentCreation_Module_Processor_Buttons::class, GD_ContentCreation_Module_Processor_Buttons::COMPONENT_BUTTON_POSTPREVIEW];
                break;
        }

        return $ret;
    }

    public function getConditionField(array $component): ?string
    {
        switch ($component[1]) {
            case self::COMPONENT_BUTTONWRAPPER_POSTVIEW:
                return FieldQueryInterpreterFacade::getInstance()->getField('isStatus', ['status' => Status::PUBLISHED], 'published');

            case self::COMPONENT_BUTTONWRAPPER_POSTPREVIEW:
                return FieldQueryInterpreterFacade::getInstance()->getField('not', ['field' => FieldQueryInterpreterFacade::getInstance()->getField('isStatus', ['status' => Status::PUBLISHED])], 'not-published');
        }

        return null;
    }
}




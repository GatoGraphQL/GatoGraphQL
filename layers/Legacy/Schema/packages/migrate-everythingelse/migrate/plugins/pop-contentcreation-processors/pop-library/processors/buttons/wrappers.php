<?php
use PoP\ComponentModel\Facades\Schema\FieldQueryInterpreterFacade;
use PoPCMSSchema\CustomPosts\Types\Status;

class GD_ContentCreation_Module_Processor_ButtonWrappers extends PoP_Module_Processor_ConditionWrapperBase
{
    public final const MODULE_BUTTONWRAPPER_POSTVIEW = 'buttonwrapper-postview';
    public final const MODULE_BUTTONWRAPPER_POSTPREVIEW = 'buttonwrapper-postpreview';

    public function getComponentVariationsToProcess(): array
    {
        return array(
            [self::class, self::MODULE_BUTTONWRAPPER_POSTVIEW],
            [self::class, self::MODULE_BUTTONWRAPPER_POSTPREVIEW],
        );
    }

    public function getConditionSucceededSubmodules(array $componentVariation)
    {
        $ret = parent::getConditionSucceededSubmodules($componentVariation);

        switch ($componentVariation[1]) {
            case self::MODULE_BUTTONWRAPPER_POSTVIEW:
                $ret[] = [GD_ContentCreation_Module_Processor_Buttons::class, GD_ContentCreation_Module_Processor_Buttons::MODULE_BUTTON_POSTVIEW];
                break;

            case self::MODULE_BUTTONWRAPPER_POSTPREVIEW:
                $ret[] = [GD_ContentCreation_Module_Processor_Buttons::class, GD_ContentCreation_Module_Processor_Buttons::MODULE_BUTTON_POSTPREVIEW];
                break;
        }

        return $ret;
    }

    public function getConditionField(array $componentVariation): ?string
    {
        switch ($componentVariation[1]) {
            case self::MODULE_BUTTONWRAPPER_POSTVIEW:
                return FieldQueryInterpreterFacade::getInstance()->getField('isStatus', ['status' => Status::PUBLISHED], 'published');

            case self::MODULE_BUTTONWRAPPER_POSTPREVIEW:
                return FieldQueryInterpreterFacade::getInstance()->getField('not', ['field' => FieldQueryInterpreterFacade::getInstance()->getField('isStatus', ['status' => Status::PUBLISHED])], 'not-published');
        }

        return null;
    }
}




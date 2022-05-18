<?php

class PoP_Module_Processor_UserForms extends PoP_Module_Processor_FormsBase
{
    public final const MODULE_FORM_INVITENEWUSERS = 'form-inviteusers';
    public final const MODULE_FORM_MYPREFERENCES = 'form-mypreferences';

    public function getComponentVariationsToProcess(): array
    {
        return array(
            [self::class, self::MODULE_FORM_INVITENEWUSERS],
            [self::class, self::MODULE_FORM_MYPREFERENCES],
        );
    }

    public function getInnerSubmodule(array $componentVariation)
    {
        switch ($componentVariation[1]) {
            case self::MODULE_FORM_INVITENEWUSERS:
                return [PoP_Module_Processor_UserFormInners::class, PoP_Module_Processor_UserFormInners::MODULE_FORMINNER_INVITENEWUSERS];

            case self::MODULE_FORM_MYPREFERENCES:
                return [PoP_Module_Processor_UserFormInners::class, PoP_Module_Processor_UserFormInners::MODULE_FORMINNER_MYPREFERENCES];
        }

        return parent::getInnerSubmodule($componentVariation);
    }

    public function initModelProps(array $componentVariation, array &$props): void
    {
        switch ($componentVariation[1]) {
            case self::MODULE_FORM_MYPREFERENCES:
                // For security reasons: delete passwords (more since introducing Login block in offcanvas, so that it will stay there forever and other users could re-login using the exisiting filled-in info)
                $this->appendProp($componentVariation, $props, 'class', 'form-mypreferences');
                break;
        }

        parent::initModelProps($componentVariation, $props);
    }
}




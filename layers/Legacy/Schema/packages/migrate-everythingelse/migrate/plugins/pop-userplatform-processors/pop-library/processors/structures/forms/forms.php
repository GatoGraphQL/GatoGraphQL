<?php

class PoP_Module_Processor_UserForms extends PoP_Module_Processor_FormsBase
{
    public final const MODULE_FORM_INVITENEWUSERS = 'form-inviteusers';
    public final const MODULE_FORM_MYPREFERENCES = 'form-mypreferences';

    public function getComponentsToProcess(): array
    {
        return array(
            [self::class, self::COMPONENT_FORM_INVITENEWUSERS],
            [self::class, self::COMPONENT_FORM_MYPREFERENCES],
        );
    }

    public function getInnerSubmodule(array $component)
    {
        switch ($component[1]) {
            case self::COMPONENT_FORM_INVITENEWUSERS:
                return [PoP_Module_Processor_UserFormInners::class, PoP_Module_Processor_UserFormInners::COMPONENT_FORMINNER_INVITENEWUSERS];

            case self::COMPONENT_FORM_MYPREFERENCES:
                return [PoP_Module_Processor_UserFormInners::class, PoP_Module_Processor_UserFormInners::COMPONENT_FORMINNER_MYPREFERENCES];
        }

        return parent::getInnerSubmodule($component);
    }

    public function initModelProps(array $component, array &$props): void
    {
        switch ($component[1]) {
            case self::COMPONENT_FORM_MYPREFERENCES:
                // For security reasons: delete passwords (more since introducing Login block in offcanvas, so that it will stay there forever and other users could re-login using the exisiting filled-in info)
                $this->appendProp($component, $props, 'class', 'form-mypreferences');
                break;
        }

        parent::initModelProps($component, $props);
    }
}




<?php

class PoP_UserAvatarProcessors_Module_Processor_UserForms extends PoP_Module_Processor_FormsBase
{
    public final const MODULE_FORM_USERAVATAR_UPDATE = 'form-useravatar-update';

    public function getComponentsToProcess(): array
    {
        return array(
            [self::class, self::MODULE_FORM_USERAVATAR_UPDATE],
        );
    }

    public function getInnerSubmodule(array $component)
    {
        switch ($component[1]) {
            case self::MODULE_FORM_USERAVATAR_UPDATE:
                return [PoP_UserAvatarProcessors_Module_Processor_UserFormInners::class, PoP_UserAvatarProcessors_Module_Processor_UserFormInners::MODULE_FORMINNER_USERAVATAR_UPDATE];
        }

        return parent::getInnerSubmodule($component);
    }
}




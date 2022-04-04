<?php

class GD_URE_Module_Processor_ProfileForms extends PoP_Module_Processor_FormsBase
{
    public final const MODULE_FORM_EDITMEMBERSHIP = 'form-editmembership';
    public final const MODULE_FORM_MYCOMMUNITIES_UPDATE = 'form-mycommunities-update';

    public function getModulesToProcess(): array
    {
        return array(
            [self::class, self::MODULE_FORM_EDITMEMBERSHIP],
            [self::class, self::MODULE_FORM_MYCOMMUNITIES_UPDATE],
        );
    }

    public function getInnerSubmodule(array $module)
    {
        switch ($module[1]) {
            case self::MODULE_FORM_EDITMEMBERSHIP:
                return [GD_URE_Module_Processor_ProfileFormInners::class, GD_URE_Module_Processor_ProfileFormInners::MODULE_FORMINNER_EDITMEMBERSHIP];

            case self::MODULE_FORM_MYCOMMUNITIES_UPDATE:
                return [GD_URE_Module_Processor_ProfileFormInners::class, GD_URE_Module_Processor_ProfileFormInners::MODULE_FORMINNER_MYCOMMUNITIES_UPDATE];
        }

        return parent::getInnerSubmodule($module);
    }
}




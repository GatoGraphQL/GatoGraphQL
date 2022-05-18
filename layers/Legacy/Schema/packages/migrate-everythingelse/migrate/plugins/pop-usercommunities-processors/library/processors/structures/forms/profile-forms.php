<?php

class GD_URE_Module_Processor_ProfileForms extends PoP_Module_Processor_FormsBase
{
    public final const COMPONENT_FORM_EDITMEMBERSHIP = 'form-editmembership';
    public final const COMPONENT_FORM_MYCOMMUNITIES_UPDATE = 'form-mycommunities-update';

    public function getComponentsToProcess(): array
    {
        return array(
            [self::class, self::COMPONENT_FORM_EDITMEMBERSHIP],
            [self::class, self::COMPONENT_FORM_MYCOMMUNITIES_UPDATE],
        );
    }

    public function getInnerSubmodule(array $component)
    {
        switch ($component[1]) {
            case self::COMPONENT_FORM_EDITMEMBERSHIP:
                return [GD_URE_Module_Processor_ProfileFormInners::class, GD_URE_Module_Processor_ProfileFormInners::COMPONENT_FORMINNER_EDITMEMBERSHIP];

            case self::COMPONENT_FORM_MYCOMMUNITIES_UPDATE:
                return [GD_URE_Module_Processor_ProfileFormInners::class, GD_URE_Module_Processor_ProfileFormInners::COMPONENT_FORMINNER_MYCOMMUNITIES_UPDATE];
        }

        return parent::getInnerSubmodule($component);
    }
}




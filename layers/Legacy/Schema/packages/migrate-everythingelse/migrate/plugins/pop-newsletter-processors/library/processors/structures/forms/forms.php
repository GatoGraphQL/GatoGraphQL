<?php

class PoP_Newsletter_Module_Processor_GFForms extends PoP_Module_Processor_FormsBase
{
    public final const COMPONENT_FORM_NEWSLETTER = 'form-newsletter';
    public final const COMPONENT_FORM_NEWSLETTERUNSUBSCRIPTION = 'form-newsletterunsubscription';

    public function getComponentNamesToProcess(): array
    {
        return array(
            self::COMPONENT_FORM_NEWSLETTER,
            self::COMPONENT_FORM_NEWSLETTERUNSUBSCRIPTION,
        );
    }

    public function getInnerSubcomponent(\PoP\ComponentModel\Component\Component $component)
    {
        $inners = array(
            self::COMPONENT_FORM_NEWSLETTER => [PoP_Newsletter_Module_Processor_GFFormInners::class, PoP_Newsletter_Module_Processor_GFFormInners::COMPONENT_FORMINNER_NEWSLETTER],
            self::COMPONENT_FORM_NEWSLETTERUNSUBSCRIPTION => [PoP_Newsletter_Module_Processor_GFFormInners::class, PoP_Newsletter_Module_Processor_GFFormInners::COMPONENT_FORMINNER_NEWSLETTERUNSUBSCRIPTION],
        );

        if ($inner = $inners[$component->name] ?? null) {
            return $inner;
        }

        return parent::getInnerSubcomponent($component);
    }
}




<?php

class PoP_Newsletter_Module_Processor_GFForms extends PoP_Module_Processor_FormsBase
{
    public final const MODULE_FORM_NEWSLETTER = 'form-newsletter';
    public final const MODULE_FORM_NEWSLETTERUNSUBSCRIPTION = 'form-newsletterunsubscription';

    public function getComponentsToProcess(): array
    {
        return array(
            [self::class, self::MODULE_FORM_NEWSLETTER],
            [self::class, self::MODULE_FORM_NEWSLETTERUNSUBSCRIPTION],
        );
    }

    public function getInnerSubmodule(array $component)
    {
        $inners = array(
            self::MODULE_FORM_NEWSLETTER => [PoP_Newsletter_Module_Processor_GFFormInners::class, PoP_Newsletter_Module_Processor_GFFormInners::MODULE_FORMINNER_NEWSLETTER],
            self::MODULE_FORM_NEWSLETTERUNSUBSCRIPTION => [PoP_Newsletter_Module_Processor_GFFormInners::class, PoP_Newsletter_Module_Processor_GFFormInners::MODULE_FORMINNER_NEWSLETTERUNSUBSCRIPTION],
        );

        if ($inner = $inners[$component[1]] ?? null) {
            return $inner;
        }

        return parent::getInnerSubmodule($component);
    }
}




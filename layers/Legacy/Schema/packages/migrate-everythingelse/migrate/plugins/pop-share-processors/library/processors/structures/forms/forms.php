<?php

class PoP_Share_Module_Processor_GFForms extends PoP_Module_Processor_FormsBase
{
    public final const COMPONENT_FORM_SHAREBYEMAIL = 'form-sharebyemail';

    public function getComponentsToProcess(): array
    {
        return array(
            [self::class, self::COMPONENT_FORM_SHAREBYEMAIL],
        );
    }

    public function getInnerSubcomponent(array $component)
    {
        $inners = array(
            self::COMPONENT_FORM_SHAREBYEMAIL => [PoP_Share_Module_Processor_GFFormInners::class, PoP_Share_Module_Processor_GFFormInners::COMPONENT_FORMINNER_SHAREBYEMAIL],
        );

        if ($inner = $inners[$component[1]] ?? null) {
            return $inner;
        }

        return parent::getInnerSubcomponent($component);
    }
}




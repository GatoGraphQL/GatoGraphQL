<?php

class PoP_SocialNetwork_Module_Processor_GFForms extends PoP_Module_Processor_FormsBase
{
    public final const MODULE_FORM_CONTACTUSER = 'form-contactuser';

    public function getComponentsToProcess(): array
    {
        return array(
            [self::class, self::COMPONENT_FORM_CONTACTUSER],
        );
    }

    public function getInnerSubmodule(array $component)
    {
        $inners = array(
            self::COMPONENT_FORM_CONTACTUSER => [PoP_SocialNetwork_Module_Processor_GFFormInners::class, PoP_SocialNetwork_Module_Processor_GFFormInners::COMPONENT_FORMINNER_CONTACTUSER],
        );

        if ($inner = $inners[$component[1]] ?? null) {
            return $inner;
        }

        return parent::getInnerSubmodule($component);
    }
}




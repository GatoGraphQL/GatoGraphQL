<?php

class PoP_SocialNetwork_Module_Processor_GFForms extends PoP_Module_Processor_FormsBase
{
    public final const COMPONENT_FORM_CONTACTUSER = 'form-contactuser';

    /**
     * @return string[]
     */
    public function getComponentNamesToProcess(): array
    {
        return array(
            self::COMPONENT_FORM_CONTACTUSER,
        );
    }

    public function getInnerSubcomponent(\PoP\ComponentModel\Component\Component $component)
    {
        $inners = array(
            self::COMPONENT_FORM_CONTACTUSER => [PoP_SocialNetwork_Module_Processor_GFFormInners::class, PoP_SocialNetwork_Module_Processor_GFFormInners::COMPONENT_FORMINNER_CONTACTUSER],
        );

        if ($inner = $inners[$component->name] ?? null) {
            return $inner;
        }

        return parent::getInnerSubcomponent($component);
    }
}




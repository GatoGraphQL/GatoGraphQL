<?php
use PoP\Root\Facades\Translation\TranslationAPIFacade;

class Wassup_URE_AAL_Module_Processor_MemberTagsLayouts extends GD_URE_Module_Processor_MemberTagsLayoutsBase
{
    public final const COMPONENT_UREAAL_LAYOUTUSER_MEMBERTAGS = 'ure-aal-layoutuser-membertags-desc';

    /**
     * @return string[]
     */
    public function getComponentNamesToProcess(): array
    {
        return array(
            self::COMPONENT_UREAAL_LAYOUTUSER_MEMBERTAGS,
        );
    }

    public function getDescription(\PoP\ComponentModel\Component\Component $component, array &$props)
    {
        switch ($component->name) {
            case self::COMPONENT_UREAAL_LAYOUTUSER_MEMBERTAGS:
                return sprintf(
                    '<em>%s</em>',
                    TranslationAPIFacade::getInstance()->__('Tags:', 'poptheme-wassup')
                );
        }
    
        return parent::getDescription($component, $props);
    }
}



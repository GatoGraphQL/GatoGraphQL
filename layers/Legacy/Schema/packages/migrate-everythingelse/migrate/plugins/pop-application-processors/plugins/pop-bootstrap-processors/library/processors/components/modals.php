<?php
use PoP\Root\Facades\Translation\TranslationAPIFacade;

class PoP_Module_Processor_GFModalComponents extends PoP_Module_Processor_FormModalViewComponentsBase
{
    public final const COMPONENT_MODAL_SHAREBYEMAIL = 'modal-sharebyemail';

    public function getComponentsToProcess(): array
    {
        return array(
            [self::class, self::COMPONENT_MODAL_SHAREBYEMAIL],
        );
    }
    
    public function getInnerSubcomponents(\PoP\ComponentModel\Component\Component $component): array
    {
        $ret = parent::getInnerSubcomponents($component);

        switch ($component[1]) {
            case self::COMPONENT_MODAL_SHAREBYEMAIL:
                $ret[] = [PoP_Share_Module_Processor_Dataloads::class, PoP_Share_Module_Processor_Dataloads::COMPONENT_DATALOAD_SHAREBYEMAIL];
                break;
        }

        return $ret;
    }

    public function getHeaderTitle(\PoP\ComponentModel\Component\Component $component)
    {
        $header_placeholder = '<i class="fa %s fa-fw"></i><em>%s</em>';
        switch ($component[1]) {
            case self::COMPONENT_MODAL_SHAREBYEMAIL:
                return sprintf(
                    $header_placeholder,
                    'fa-share',
                    TranslationAPIFacade::getInstance()->__('Share by email:', 'pop-genericforms')
                );
        }

        return parent::getHeaderTitle($component);
    }
}



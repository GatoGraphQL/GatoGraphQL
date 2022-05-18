<?php
use PoP\Root\Facades\Translation\TranslationAPIFacade;

class PoP_Module_Processor_GFModalComponents extends PoP_Module_Processor_FormModalViewComponentsBase
{
    public final const MODULE_MODAL_SHAREBYEMAIL = 'modal-sharebyemail';

    public function getComponentVariationsToProcess(): array
    {
        return array(
            [self::class, self::MODULE_MODAL_SHAREBYEMAIL],
        );
    }
    
    public function getInnerSubmodules(array $componentVariation): array
    {
        $ret = parent::getInnerSubmodules($componentVariation);

        switch ($componentVariation[1]) {
            case self::MODULE_MODAL_SHAREBYEMAIL:
                $ret[] = [PoP_Share_Module_Processor_Dataloads::class, PoP_Share_Module_Processor_Dataloads::MODULE_DATALOAD_SHAREBYEMAIL];
                break;
        }

        return $ret;
    }

    public function getHeaderTitle(array $componentVariation)
    {
        $header_placeholder = '<i class="fa %s fa-fw"></i><em>%s</em>';
        switch ($componentVariation[1]) {
            case self::MODULE_MODAL_SHAREBYEMAIL:
                return sprintf(
                    $header_placeholder,
                    'fa-share',
                    TranslationAPIFacade::getInstance()->__('Share by email:', 'pop-genericforms')
                );
        }

        return parent::getHeaderTitle($componentVariation);
    }
}



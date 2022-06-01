<?php
use PoP\Root\Facades\Translation\TranslationAPIFacade;

class PoP_Module_Processor_FileUploadPictures extends PoP_Module_Processor_FileUploadPicturesBase
{
    public final const COMPONENT_FILEUPLOAD_PICTURE = 'fileupload-picture';

    public function getComponentsToProcess(): array
    {
        return array(
            [self::class, self::COMPONENT_FILEUPLOAD_PICTURE],
        );
    }

    public function getLabelText(\PoP\ComponentModel\Component\Component $component, array &$props)
    {
        switch ($component[1]) {
            case self::COMPONENT_FILEUPLOAD_PICTURE:
                return TranslationAPIFacade::getInstance()->__('Picture', 'pop-useravatar-processors');
        }
        
        return parent::getLabelText($component, $props);
    }
}




<?php
use PoP\Root\Facades\Translation\TranslationAPIFacade;

class PoP_Module_Processor_FileUploadPictures extends PoP_Module_Processor_FileUploadPicturesBase
{
    public final const MODULE_FILEUPLOAD_PICTURE = 'fileupload-picture';

    public function getComponentsToProcess(): array
    {
        return array(
            [self::class, self::MODULE_FILEUPLOAD_PICTURE],
        );
    }

    public function getLabelText(array $component, array &$props)
    {
        switch ($component[1]) {
            case self::MODULE_FILEUPLOAD_PICTURE:
                return TranslationAPIFacade::getInstance()->__('Picture', 'pop-useravatar-processors');
        }
        
        return parent::getLabelText($component, $props);
    }
}




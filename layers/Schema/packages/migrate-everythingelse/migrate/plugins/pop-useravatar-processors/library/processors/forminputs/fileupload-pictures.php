<?php
use PoP\Translation\Facades\TranslationAPIFacade;

class PoP_Module_Processor_FileUploadPictures extends PoP_Module_Processor_FileUploadPicturesBase
{
    public const MODULE_FILEUPLOAD_PICTURE = 'fileupload-picture';

    public function getModulesToProcess(): array
    {
        return array(
            [self::class, self::MODULE_FILEUPLOAD_PICTURE],
        );
    }

    public function getLabelText(array $module, array &$props)
    {
        switch ($module[1]) {
            case self::MODULE_FILEUPLOAD_PICTURE:
                return TranslationAPIFacade::getInstance()->__('Picture', 'pop-useravatar-processors');
        }
        
        return parent::getLabelText($module, $props);
    }
}




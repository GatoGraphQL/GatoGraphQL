<?php

class PoP_UserAvatarProcessors_Module_Processor_UserFormInners extends PoP_Module_Processor_FormInnersBase
{
    public final const MODULE_FORMINNER_USERAVATAR_UPDATE = 'forminner-useravatar-update';

    public function getComponentVariationsToProcess(): array
    {
        return array(
            [self::class, self::MODULE_FORMINNER_USERAVATAR_UPDATE],
        );
    }

    public function getLayoutSubmodules(array $module)
    {
        $ret = parent::getLayoutSubmodules($module);
    
        switch ($module[1]) {
            case self::MODULE_FORMINNER_USERAVATAR_UPDATE:
                $ret = array_merge(
                    array(
                        [PoP_Module_Processor_FileUploadPictures::class, PoP_Module_Processor_FileUploadPictures::MODULE_FILEUPLOAD_PICTURE],
                        [PoP_Module_Processor_SubmitButtons::class, PoP_Module_Processor_SubmitButtons::MODULE_SUBMITBUTTON_SAVE],
                    )
                );
                break;
        }

        return $ret;
    }
}




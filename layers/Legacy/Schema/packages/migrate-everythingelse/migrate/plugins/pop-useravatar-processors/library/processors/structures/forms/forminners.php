<?php

class PoP_UserAvatarProcessors_Module_Processor_UserFormInners extends PoP_Module_Processor_FormInnersBase
{
    public final const COMPONENT_FORMINNER_USERAVATAR_UPDATE = 'forminner-useravatar-update';

    public function getComponentsToProcess(): array
    {
        return array(
            [self::class, self::COMPONENT_FORMINNER_USERAVATAR_UPDATE],
        );
    }

    public function getLayoutSubcomponents(array $component)
    {
        $ret = parent::getLayoutSubcomponents($component);
    
        switch ($component[1]) {
            case self::COMPONENT_FORMINNER_USERAVATAR_UPDATE:
                $ret = array_merge(
                    array(
                        [PoP_Module_Processor_FileUploadPictures::class, PoP_Module_Processor_FileUploadPictures::COMPONENT_FILEUPLOAD_PICTURE],
                        [PoP_Module_Processor_SubmitButtons::class, PoP_Module_Processor_SubmitButtons::COMPONENT_SUBMITBUTTON_SAVE],
                    )
                );
                break;
        }

        return $ret;
    }
}




<?php

class PoP_UserAvatarProcessors_Module_Processor_UserFormInners extends PoP_Module_Processor_FormInnersBase
{
    public final const COMPONENT_FORMINNER_USERAVATAR_UPDATE = 'forminner-useravatar-update';

    public function getComponentNamesToProcess(): array
    {
        return array(
            self::COMPONENT_FORMINNER_USERAVATAR_UPDATE,
        );
    }

    /**
     * @return \PoP\ComponentModel\Component\Component[]
     */
    public function getLayoutSubcomponents(\PoP\ComponentModel\Component\Component $component): array
    {
        $ret = parent::getLayoutSubcomponents($component);
    
        switch ($component->name) {
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




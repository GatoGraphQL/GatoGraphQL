<?php

class PoP_Module_Processor_CommentsFormInners extends PoP_Module_Processor_FormInnersBase
{
    public final const MODULE_FORMINNER_ADDCOMMENT = 'forminner-addcomment';

    public function getComponentVariationsToProcess(): array
    {
        return array(
            [self::class, self::MODULE_FORMINNER_ADDCOMMENT],
        );
    }

    public function getLayoutSubmodules(array $componentVariation)
    {
        $ret = parent::getLayoutSubmodules($componentVariation);
    
        switch ($componentVariation[1]) {
            case self::MODULE_FORMINNER_ADDCOMMENT:
                $ret = array_merge(
                    $ret,
                    array(
                        [PoP_AddComment_Module_Processor_FormInputGroups::class, PoP_AddComment_Module_Processor_FormInputGroups::MODULE_FORMCOMPONENTGROUP_CARD_COMMENTPOST],
                        [PoP_AddComment_Module_Processor_FormInputGroups::class, PoP_AddComment_Module_Processor_FormInputGroups::MODULE_FORMCOMPONENTGROUP_CARD_PARENTCOMMENT],
                        [PoP_Module_Processor_CommentFormGroups::class, PoP_Module_Processor_CommentFormGroups::MODULE_FORMINPUTGROUP_COMMENTEDITOR],
                        [PoP_Module_Processor_SubmitButtons::class, PoP_Module_Processor_SubmitButtons::MODULE_SUBMITBUTTON_SUBMIT],
                    )
                );
                break;
        }

        return $ret;
    }
}




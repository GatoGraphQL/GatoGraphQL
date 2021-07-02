<?php

class PoP_Module_Processor_CommentsFormInners extends PoP_Module_Processor_FormInnersBase
{
    public const MODULE_FORMINNER_ADDCOMMENT = 'forminner-addcomment';

    public function getModulesToProcess(): array
    {
        return array(
            [self::class, self::MODULE_FORMINNER_ADDCOMMENT],
        );
    }

    public function getLayoutSubmodules(array $module)
    {
        $ret = parent::getLayoutSubmodules($module);
    
        switch ($module[1]) {
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




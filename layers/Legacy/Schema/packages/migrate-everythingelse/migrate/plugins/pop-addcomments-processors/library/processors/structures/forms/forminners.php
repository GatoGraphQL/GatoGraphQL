<?php

class PoP_Module_Processor_CommentsFormInners extends PoP_Module_Processor_FormInnersBase
{
    public final const COMPONENT_FORMINNER_ADDCOMMENT = 'forminner-addcomment';

    public function getComponentNamesToProcess(): array
    {
        return array(
            self::COMPONENT_FORMINNER_ADDCOMMENT,
        );
    }

    /**
     * @return \PoP\ComponentModel\Component\Component[]
     */
    public function getLayoutSubcomponents(\PoP\ComponentModel\Component\Component $component): array
    {
        $ret = parent::getLayoutSubcomponents($component);
    
        switch ($component->name) {
            case self::COMPONENT_FORMINNER_ADDCOMMENT:
                $ret = array_merge(
                    $ret,
                    array(
                        [PoP_AddComment_Module_Processor_FormInputGroups::class, PoP_AddComment_Module_Processor_FormInputGroups::COMPONENT_FORMCOMPONENTGROUP_CARD_COMMENTPOST],
                        [PoP_AddComment_Module_Processor_FormInputGroups::class, PoP_AddComment_Module_Processor_FormInputGroups::COMPONENT_FORMCOMPONENTGROUP_CARD_PARENTCOMMENT],
                        [PoP_Module_Processor_CommentFormGroups::class, PoP_Module_Processor_CommentFormGroups::COMPONENT_FORMINPUTGROUP_COMMENTEDITOR],
                        [PoP_Module_Processor_SubmitButtons::class, PoP_Module_Processor_SubmitButtons::COMPONENT_SUBMITBUTTON_SUBMIT],
                    )
                );
                break;
        }

        return $ret;
    }
}




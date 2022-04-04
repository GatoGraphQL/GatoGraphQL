<?php

class PoP_Module_Processor_CommentFormGroups extends PoP_Module_Processor_FormComponentGroupsBase
{
    public final const MODULE_FORMINPUTGROUP_COMMENTEDITOR = 'forminputgroupcommenteditor';

    public function getModulesToProcess(): array
    {
        return array(
            [self::class, self::MODULE_FORMINPUTGROUP_COMMENTEDITOR],
        );
    }

    public function getComponentSubmodule(array $module)
    {
        $components = array(
            self::MODULE_FORMINPUTGROUP_COMMENTEDITOR => [PoP_Module_Processor_CommentEditorFormInputs::class, PoP_Module_Processor_CommentEditorFormInputs::MODULE_FORMINPUT_COMMENTEDITOR],
        );

        if ($component = $components[$module[1]] ?? null) {
            return $component;
        }

        return parent::getComponentSubmodule($module);
    }
}




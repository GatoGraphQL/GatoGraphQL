<?php

class PoP_Module_Processor_CommentUserMentionsLayouts extends PoP_Module_Processor_CommentUserMentionsLayoutsBase
{
    public final const MODULE_LAYOUT_COMMENTUSERMENTIONS = 'layout-commentusermentions';

    public function getModulesToProcess(): array
    {
        return array(
            [self::class, self::MODULE_LAYOUT_COMMENTUSERMENTIONS],
        );
    }

    public function getLayoutSubmodules(array $module)
    {
        $ret = parent::getLayoutSubmodules($module);

        switch ($module[1]) {
            case self::MODULE_LAYOUT_COMMENTUSERMENTIONS:
                $ret[] = [PoP_Module_Processor_CustomPopoverLayouts::class, PoP_Module_Processor_CustomPopoverLayouts::MODULE_LAYOUT_POPOVER_USER_AVATAR40];
                break;
        }

        return $ret;
    }
}




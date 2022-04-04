<?php

class PoP_Module_Processor_NotificationSubcomponentLayouts extends PoP_Module_Processor_SubcomponentLayoutsBase
{
    public final const MODULE_SUBCOMPONENT_NOTIFICATIONCOMMENT = 'subcomponent-notificationcomment';

    public function getModulesToProcess(): array
    {
        return array(
            [self::class, self::MODULE_SUBCOMPONENT_NOTIFICATIONCOMMENT],
        );
    }

    public function getLayoutSubmodules(array $module)
    {
        $ret = parent::getLayoutSubmodules($module);

        switch ($module[1]) {
            case self::MODULE_SUBCOMPONENT_NOTIFICATIONCOMMENT:
                $ret[] = [PoP_Module_Processor_CommentScrolls::class, PoP_Module_Processor_CommentScrolls::MODULE_SCROLLLAYOUT_POSTCOMMENT];
                break;
        }

        return $ret;
    }

    public function getSubcomponentField(array $module)
    {
        switch ($module[1]) {
            case self::MODULE_SUBCOMPONENT_NOTIFICATIONCOMMENT:
                return 'commentObjectID';
        }

        return parent::getSubcomponentField($module);
    }

    public function isIndividual(array $module, array &$props)
    {
        switch ($module[1]) {
            case self::MODULE_SUBCOMPONENT_NOTIFICATIONCOMMENT:
                return false;
        }

        return parent::isIndividual($module, $props);
    }

    public function initWebPlatformModelProps(array $module, array &$props)
    {
        switch ($module[1]) {
            case self::MODULE_SUBCOMPONENT_NOTIFICATIONCOMMENT:
                // Make the comment shine whenever added, similar to PoP_Module_Processor_CommentsLayouts::MODULE_LAYOUT_COMMENT_ADD
                // but without adding the scrollTop effect
                $this->appendProp([PoP_Module_Processor_CommentsLayouts::class, PoP_Module_Processor_CommentsLayouts::MODULE_LAYOUT_COMMENT_LIST], $props, 'class', 'pop-highlight');

                // Make it beep
                $this->mergeJsmethodsProp([PoP_Module_Processor_CommentsLayouts::class, PoP_Module_Processor_CommentsLayouts::MODULE_LAYOUT_COMMENT_LIST], $props, array('beep'));
                break;
        }

        parent::initWebPlatformModelProps($module, $props);
    }
}




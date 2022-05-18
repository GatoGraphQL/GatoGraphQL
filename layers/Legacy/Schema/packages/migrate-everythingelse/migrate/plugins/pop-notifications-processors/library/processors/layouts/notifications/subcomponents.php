<?php

class PoP_Module_Processor_NotificationSubcomponentLayouts extends PoP_Module_Processor_SubcomponentLayoutsBase
{
    public final const MODULE_SUBCOMPONENT_NOTIFICATIONCOMMENT = 'subcomponent-notificationcomment';

    public function getComponentVariationsToProcess(): array
    {
        return array(
            [self::class, self::MODULE_SUBCOMPONENT_NOTIFICATIONCOMMENT],
        );
    }

    public function getLayoutSubmodules(array $componentVariation)
    {
        $ret = parent::getLayoutSubmodules($componentVariation);

        switch ($componentVariation[1]) {
            case self::MODULE_SUBCOMPONENT_NOTIFICATIONCOMMENT:
                $ret[] = [PoP_Module_Processor_CommentScrolls::class, PoP_Module_Processor_CommentScrolls::MODULE_SCROLLLAYOUT_POSTCOMMENT];
                break;
        }

        return $ret;
    }

    public function getSubcomponentField(array $componentVariation)
    {
        switch ($componentVariation[1]) {
            case self::MODULE_SUBCOMPONENT_NOTIFICATIONCOMMENT:
                return 'commentObjectID';
        }

        return parent::getSubcomponentField($componentVariation);
    }

    public function isIndividual(array $componentVariation, array &$props)
    {
        switch ($componentVariation[1]) {
            case self::MODULE_SUBCOMPONENT_NOTIFICATIONCOMMENT:
                return false;
        }

        return parent::isIndividual($componentVariation, $props);
    }

    public function initWebPlatformModelProps(array $componentVariation, array &$props)
    {
        switch ($componentVariation[1]) {
            case self::MODULE_SUBCOMPONENT_NOTIFICATIONCOMMENT:
                // Make the comment shine whenever added, similar to PoP_Module_Processor_CommentsLayouts::MODULE_LAYOUT_COMMENT_ADD
                // but without adding the scrollTop effect
                $this->appendProp([PoP_Module_Processor_CommentsLayouts::class, PoP_Module_Processor_CommentsLayouts::MODULE_LAYOUT_COMMENT_LIST], $props, 'class', 'pop-highlight');

                // Make it beep
                $this->mergeJsmethodsProp([PoP_Module_Processor_CommentsLayouts::class, PoP_Module_Processor_CommentsLayouts::MODULE_LAYOUT_COMMENT_LIST], $props, array('beep'));
                break;
        }

        parent::initWebPlatformModelProps($componentVariation, $props);
    }
}




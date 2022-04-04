<?php

class PoP_Module_Processor_CommentsContents extends PoP_Module_Processor_ContentsBase
{
    public final const MODULE_CONTENT_COMMENTSINGLE = 'content-commentsingle';

    public function getModulesToProcess(): array
    {
        return array(
            [self::class, self::MODULE_CONTENT_COMMENTSINGLE],
        );
    }
    public function getInnerSubmodule(array $module)
    {
        switch ($module[1]) {
            case self::MODULE_CONTENT_COMMENTSINGLE:
                return [PoP_Module_Processor_CommentContentInners::class, PoP_Module_Processor_CommentContentInners::MODULE_CONTENTINNER_COMMENTSINGLE];
        }

        return parent::getInnerSubmodule($module);
    }

    public function initModelProps(array $module, array &$props): void
    {
        switch ($module[1]) {
            case self::MODULE_CONTENT_COMMENTSINGLE:
                $this->appendProp($module, $props, 'class', 'well well-sm');
                break;
        }

        parent::initModelProps($module, $props);
    }
}



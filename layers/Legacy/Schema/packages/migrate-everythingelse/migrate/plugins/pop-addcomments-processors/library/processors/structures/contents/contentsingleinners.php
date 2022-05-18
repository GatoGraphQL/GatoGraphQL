<?php

class PoP_Module_Processor_CommentContentInners extends PoP_Module_Processor_ContentSingleInnersBase
{
    public final const MODULE_CONTENTINNER_COMMENTSINGLE = 'contentinner-commentsingle';

    public function getComponentsToProcess(): array
    {
        return array(
            [self::class, self::MODULE_CONTENTINNER_COMMENTSINGLE],
        );
    }

    public function getLayoutSubmodules(array $component)
    {
        $ret = parent::getLayoutSubmodules($component);

        switch ($component[1]) {
            case self::MODULE_CONTENTINNER_COMMENTSINGLE:
                $ret[] = [PoP_Module_Processor_CommentsLayouts::class, PoP_Module_Processor_CommentsLayouts::MODULE_LAYOUT_COMMENT_LIST];
                break;
        }

        return $ret;
    }
}



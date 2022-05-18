<?php

class PoP_Module_Processor_CommentsContents extends PoP_Module_Processor_ContentsBase
{
    public final const MODULE_CONTENT_COMMENTSINGLE = 'content-commentsingle';

    public function getComponentVariationsToProcess(): array
    {
        return array(
            [self::class, self::MODULE_CONTENT_COMMENTSINGLE],
        );
    }
    public function getInnerSubmodule(array $componentVariation)
    {
        switch ($componentVariation[1]) {
            case self::MODULE_CONTENT_COMMENTSINGLE:
                return [PoP_Module_Processor_CommentContentInners::class, PoP_Module_Processor_CommentContentInners::MODULE_CONTENTINNER_COMMENTSINGLE];
        }

        return parent::getInnerSubmodule($componentVariation);
    }

    public function initModelProps(array $componentVariation, array &$props): void
    {
        switch ($componentVariation[1]) {
            case self::MODULE_CONTENT_COMMENTSINGLE:
                $this->appendProp($componentVariation, $props, 'class', 'well well-sm');
                break;
        }

        parent::initModelProps($componentVariation, $props);
    }
}



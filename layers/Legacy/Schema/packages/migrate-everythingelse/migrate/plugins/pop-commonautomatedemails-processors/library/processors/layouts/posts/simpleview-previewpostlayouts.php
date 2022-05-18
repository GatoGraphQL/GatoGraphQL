<?php

class PoPTheme_Wassup_AE_Module_Processor_SimpleViewPreviewPostLayouts extends PoP_Module_Processor_BareSimpleViewPreviewPostLayoutsBase
{
    public final const MODULE_LAYOUT_AUTOMATEDEMAILS_PREVIEWPOST_POST_SIMPLEVIEW = 'layout-automatedemails-previewpost-post-simpleview';

    public function getComponentsToProcess(): array
    {
        return array(
            [self::class, self::COMPONENT_LAYOUT_AUTOMATEDEMAILS_PREVIEWPOST_POST_SIMPLEVIEW],
        );
    }


    public function getAuthorModule(array $component)
    {
        switch ($component[1]) {
            case self::COMPONENT_LAYOUT_AUTOMATEDEMAILS_PREVIEWPOST_POST_SIMPLEVIEW:
                return [PoP_Module_Processor_PostAuthorNameLayouts::class, PoP_Module_Processor_PostAuthorNameLayouts::COMPONENT_LAYOUTPOST_AUTHORNAME];
        }

        return parent::getAuthorModule($component);
    }

    public function getAbovecontentSubmodules(array $component)
    {
        $ret = parent::getAbovecontentSubmodules($component);

        switch ($component[1]) {
            case self::COMPONENT_LAYOUT_AUTOMATEDEMAILS_PREVIEWPOST_POST_SIMPLEVIEW:
                $ret[] = [PoP_Module_Processor_MultiplePostLayouts::class, PoP_Module_Processor_MultiplePostLayouts::COMPONENT_LAYOUT_MULTIPLECONTENT_SIMPLEVIEW_ABOVECONTENT];
                break;
        }

        return $ret;
    }

    public function getAftercontentSubmodules(array $component)
    {
        $ret = parent::getAftercontentSubmodules($component);

        switch ($component[1]) {
            case self::COMPONENT_LAYOUT_AUTOMATEDEMAILS_PREVIEWPOST_POST_SIMPLEVIEW:
                $ret[] = [PoP_Module_Processor_QuicklinkButtonGroups::class, PoP_Module_Processor_QuicklinkButtonGroups::COMPONENT_QUICKLINKBUTTONGROUP_COMMENTS_LABEL];
                break;
        }

        return $ret;
    }
}



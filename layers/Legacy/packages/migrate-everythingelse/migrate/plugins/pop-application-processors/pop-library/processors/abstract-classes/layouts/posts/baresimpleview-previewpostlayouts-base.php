<?php

abstract class PoP_Module_Processor_BareSimpleViewPreviewPostLayoutsBase extends PoP_Module_Processor_CustomPreviewPostLayoutsBase
{
    public function showDate(array $module)
    {
        return true;
    }

    public function getAbovecontentSubmodules(array $module)
    {
        $ret = parent::getAbovecontentSubmodules($module);

        $ret[] = [GD_Custom_Module_Processor_PostThumbLayoutWrappers::class, GD_Custom_Module_Processor_PostThumbLayoutWrappers::MODULE_LAYOUTWRAPPER_POSTTHUMB_LINKSELFCROPPEDFEED_VOLUNTEER];

        return $ret;
    }

    public function getAuthorAvatarModule(array $module)
    {
        if (defined('POP_AVATARPROCESSORS_INITIALIZED')) {
            return [PoP_Module_Processor_PostAuthorAvatarLayouts::class, PoP_Module_Processor_PostAuthorAvatarLayouts::MODULE_LAYOUTPOST_AUTHORAVATAR82];
        }

        return null;
    }

    public function getContentSubmodule(array $module)
    {
        return [PoP_Module_Processor_MaxHeightLayoutMultipleComponents::class, PoP_Module_Processor_MaxHeightLayoutMultipleComponents::MODULE_MULTICOMPONENT_SIMPLEVIEW_POSTCONTENT];
    }
    

    public function getPostThumbSubmodule(array $module)
    {
        return null;
    }

    public function getTitleHtmlmarkup(array $module, array &$props)
    {
        return 'h3';
    }

    public function authorPositions(array $module)
    {
        return array(
            GD_CONSTANT_AUTHORPOSITION_ABOVETITLE
        );
    }

    public function horizontalLayout(array $module)
    {
        return true;
    }
    

    public function getImmutableConfiguration(array $module, array &$props): array
    {
        $ret = parent::getImmutableConfiguration($module, $props);

        $ret[GD_JS_CLASSES]['wrapper'] = 'row';
        $ret[GD_JS_CLASSES]['thumb-wrapper'] = 'col-sm-2 avatar';
        $ret[GD_JS_CLASSES]['content-body'] = 'col-sm-10 simpleview';
        $ret[GD_JS_CLASSES]['aftercontent-inner'] = 'col-sm-12';

        return $ret;
    }
}

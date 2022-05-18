<?php

abstract class PoP_Module_Processor_BareSimpleViewPreviewPostLayoutsBase extends PoP_Module_Processor_CustomPreviewPostLayoutsBase
{
    public function showDate(array $component)
    {
        return true;
    }

    public function getAbovecontentSubmodules(array $component)
    {
        $ret = parent::getAbovecontentSubmodules($component);

        $ret[] = [GD_Custom_Module_Processor_PostThumbLayoutWrappers::class, GD_Custom_Module_Processor_PostThumbLayoutWrappers::MODULE_LAYOUTWRAPPER_POSTTHUMB_LINKSELFCROPPEDFEED_VOLUNTEER];

        return $ret;
    }

    public function getAuthorAvatarModule(array $component)
    {
        if (defined('POP_AVATARPROCESSORS_INITIALIZED')) {
            return [PoP_Module_Processor_PostAuthorAvatarLayouts::class, PoP_Module_Processor_PostAuthorAvatarLayouts::MODULE_LAYOUTPOST_AUTHORAVATAR82];
        }

        return null;
    }

    public function getContentSubmodule(array $component)
    {
        return [PoP_Module_Processor_MaxHeightLayoutMultipleComponents::class, PoP_Module_Processor_MaxHeightLayoutMultipleComponents::MODULE_MULTICOMPONENT_SIMPLEVIEW_POSTCONTENT];
    }
    

    public function getPostThumbSubmodule(array $component)
    {
        return null;
    }

    public function getTitleHtmlmarkup(array $component, array &$props)
    {
        return 'h3';
    }

    public function authorPositions(array $component)
    {
        return array(
            GD_CONSTANT_AUTHORPOSITION_ABOVETITLE
        );
    }

    public function horizontalLayout(array $component)
    {
        return true;
    }
    

    public function getImmutableConfiguration(array $component, array &$props): array
    {
        $ret = parent::getImmutableConfiguration($component, $props);

        $ret[GD_JS_CLASSES]['wrapper'] = 'row';
        $ret[GD_JS_CLASSES]['thumb-wrapper'] = 'col-sm-2 avatar';
        $ret[GD_JS_CLASSES]['content-body'] = 'col-sm-10 simpleview';
        $ret[GD_JS_CLASSES]['aftercontent-inner'] = 'col-sm-12';

        return $ret;
    }
}

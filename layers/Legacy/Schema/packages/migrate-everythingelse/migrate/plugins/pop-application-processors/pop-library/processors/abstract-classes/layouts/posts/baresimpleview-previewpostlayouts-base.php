<?php

abstract class PoP_Module_Processor_BareSimpleViewPreviewPostLayoutsBase extends PoP_Module_Processor_CustomPreviewPostLayoutsBase
{
    public function showDate(array $component)
    {
        return true;
    }

    public function getAbovecontentSubcomponents(array $component)
    {
        $ret = parent::getAbovecontentSubcomponents($component);

        $ret[] = [GD_Custom_Module_Processor_PostThumbLayoutWrappers::class, GD_Custom_Module_Processor_PostThumbLayoutWrappers::COMPONENT_LAYOUTWRAPPER_POSTTHUMB_LINKSELFCROPPEDFEED_VOLUNTEER];

        return $ret;
    }

    public function getAuthorAvatarComponent(array $component)
    {
        if (defined('POP_AVATARPROCESSORS_INITIALIZED')) {
            return [PoP_Module_Processor_PostAuthorAvatarLayouts::class, PoP_Module_Processor_PostAuthorAvatarLayouts::COMPONENT_LAYOUTPOST_AUTHORAVATAR82];
        }

        return null;
    }

    public function getContentSubcomponent(array $component)
    {
        return [PoP_Module_Processor_MaxHeightLayoutMultipleComponents::class, PoP_Module_Processor_MaxHeightLayoutMultipleComponents::COMPONENT_MULTICOMPONENT_SIMPLEVIEW_POSTCONTENT];
    }
    

    public function getPostThumbSubcomponent(array $component)
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

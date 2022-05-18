<?php

abstract class PoP_Module_Processor_CustomPreviewUserLayoutsBase extends PoP_Module_Processor_PreviewUserLayoutsBase
{
    public function horizontalLayout(array $componentVariation)
    {
        return false;
    }

    public function horizontalMediaLayout(array $componentVariation)
    {
        return false;
    }

    public function getImmutableConfiguration(array $componentVariation, array &$props): array
    {
        $ret = parent::getImmutableConfiguration($componentVariation, $props);

        if ($this->getQuicklinkgroupTopSubmodule($componentVariation)) {
            $ret[GD_JS_CLASSES]['quicklinkgroup-top'] = 'icon-only pull-right';
        }

        if ($this->horizontalLayout($componentVariation)) {
            $ret[GD_JS_CLASSES]['name'] = 'media-heading';
            $ret[GD_JS_CLASSES]['wrapper'] = 'row';
            $ret[GD_JS_CLASSES]['avatar-wrapper'] = 'col-xsm-3';
            $ret[GD_JS_CLASSES]['content-body'] = 'col-xsm-9';
        } elseif ($this->horizontalMediaLayout($componentVariation)) {
            $ret[GD_JS_CLASSES]['name'] = 'media-heading';
            $ret[GD_JS_CLASSES]['wrapper'] = 'media'; //' overflow-visible';
            $ret[GD_JS_CLASSES]['avatar-wrapper'] = 'media-left';
            $ret[GD_JS_CLASSES]['avatar'] = 'media-object';
            $ret[GD_JS_CLASSES]['content-body'] = 'media-body';
        }

        return $ret;
    }
}

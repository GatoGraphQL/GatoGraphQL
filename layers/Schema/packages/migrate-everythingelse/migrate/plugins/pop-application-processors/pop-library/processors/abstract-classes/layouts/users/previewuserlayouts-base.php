<?php

abstract class PoP_Module_Processor_CustomPreviewUserLayoutsBase extends PoP_Module_Processor_PreviewUserLayoutsBase
{
    public function horizontalLayout(array $module)
    {
        return false;
    }

    public function horizontalMediaLayout(array $module)
    {
        return false;
    }

    public function getImmutableConfiguration(array $module, array &$props): array
    {
        $ret = parent::getImmutableConfiguration($module, $props);

        if ($this->getQuicklinkgroupTopSubmodule($module)) {
            $ret[GD_JS_CLASSES]['quicklinkgroup-top'] = 'icon-only pull-right';
        }

        if ($this->horizontalLayout($module)) {
            $ret[GD_JS_CLASSES]['name'] = 'media-heading';
            $ret[GD_JS_CLASSES]['wrapper'] = 'row';
            $ret[GD_JS_CLASSES]['avatar-wrapper'] = 'col-xsm-3';
            $ret[GD_JS_CLASSES]['content-body'] = 'col-xsm-9';
        } elseif ($this->horizontalMediaLayout($module)) {
            $ret[GD_JS_CLASSES]['name'] = 'media-heading';
            $ret[GD_JS_CLASSES]['wrapper'] = 'media'; //' overflow-visible';
            $ret[GD_JS_CLASSES]['avatar-wrapper'] = 'media-left';
            $ret[GD_JS_CLASSES]['avatar'] = 'media-object';
            $ret[GD_JS_CLASSES]['content-body'] = 'media-body';
        }

        return $ret;
    }
}

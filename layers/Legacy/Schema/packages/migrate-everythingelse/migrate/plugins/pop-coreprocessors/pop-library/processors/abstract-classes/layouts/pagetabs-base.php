<?php

abstract class PoP_Module_Processor_PageTabsLayoutsBase extends PoPEngine_QueryDataComponentProcessorBase
{
    public function getTemplateResource(\PoP\ComponentModel\Component\Component $component, array &$props): ?array
    {
        return [PoP_CoreProcessors_TemplateResourceLoaderProcessor::class, PoP_CoreProcessors_TemplateResourceLoaderProcessor::RESOURCE_LAYOUT_PAGETAB];
    }

    protected function getFontawesome(\PoP\ComponentModel\Component\Component $component, array &$props)
    {
        return null;
    }
    protected function getThumb(\PoP\ComponentModel\Component\Component $component, array &$props)
    {
        return null;
    }
    protected function getPretitle(\PoP\ComponentModel\Component\Component $component, array &$props)
    {
        return null;
    }
    protected function getTitle(\PoP\ComponentModel\Component\Component $component, array &$props)
    {
        return null;
    }

    public function getMutableonrequestConfiguration(\PoP\ComponentModel\Component\Component $component, array &$props): array
    {
        $ret = parent::getMutableonrequestConfiguration($component, $props);

        if ($fontawesome = $this->getFontawesome($component, $props)) {
            $ret['fontawesome'] = $fontawesome;
        }

        // If using pretitle, disregard the thumb
        $pretitle = $this->getPretitle($component, $props);
        if (!$pretitle) {
            if ($thumb = $this->getThumb($component, $props)) {
                $ret['thumb'] = $thumb;
            }
        }

        if ($title = $this->getTitle($component, $props)) {
            if ($pretitle) {
                $title = $pretitle.': '.$title;
            }

            $ret['title'] = $title;
        }

        return $ret;
    }
}

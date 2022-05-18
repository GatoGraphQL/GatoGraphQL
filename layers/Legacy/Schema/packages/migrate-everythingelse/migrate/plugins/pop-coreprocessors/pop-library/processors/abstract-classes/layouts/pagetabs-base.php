<?php

abstract class PoP_Module_Processor_PageTabsLayoutsBase extends PoPEngine_QueryDataComponentProcessorBase
{
    public function getTemplateResource(array $module, array &$props): ?array
    {
        return [PoP_CoreProcessors_TemplateResourceLoaderProcessor::class, PoP_CoreProcessors_TemplateResourceLoaderProcessor::RESOURCE_LAYOUT_PAGETAB];
    }

    protected function getFontawesome(array $module, array &$props)
    {
        return null;
    }
    protected function getThumb(array $module, array &$props)
    {
        return null;
    }
    protected function getPretitle(array $module, array &$props)
    {
        return null;
    }
    protected function getTitle(array $module, array &$props)
    {
        return null;
    }

    public function getMutableonrequestConfiguration(array $module, array &$props): array
    {
        $ret = parent::getMutableonrequestConfiguration($module, $props);

        if ($fontawesome = $this->getFontawesome($module, $props)) {
            $ret['fontawesome'] = $fontawesome;
        }

        // If using pretitle, disregard the thumb
        $pretitle = $this->getPretitle($module, $props);
        if (!$pretitle) {
            if ($thumb = $this->getThumb($module, $props)) {
                $ret['thumb'] = $thumb;
            }
        }

        if ($title = $this->getTitle($module, $props)) {
            if ($pretitle) {
                $title = $pretitle.': '.$title;
            }

            $ret['title'] = $title;
        }

        return $ret;
    }
}

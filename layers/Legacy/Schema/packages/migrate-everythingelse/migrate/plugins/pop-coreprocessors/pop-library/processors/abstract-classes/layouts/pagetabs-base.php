<?php

abstract class PoP_Module_Processor_PageTabsLayoutsBase extends PoPEngine_QueryDataComponentProcessorBase
{
    public function getTemplateResource(array $component, array &$props): ?array
    {
        return [PoP_CoreProcessors_TemplateResourceLoaderProcessor::class, PoP_CoreProcessors_TemplateResourceLoaderProcessor::RESOURCE_LAYOUT_PAGETAB];
    }

    protected function getFontawesome(array $component, array &$props)
    {
        return null;
    }
    protected function getThumb(array $component, array &$props)
    {
        return null;
    }
    protected function getPretitle(array $component, array &$props)
    {
        return null;
    }
    protected function getTitle(array $component, array &$props)
    {
        return null;
    }

    public function getMutableonrequestConfiguration(array $component, array &$props): array
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

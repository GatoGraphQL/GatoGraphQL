<?php

abstract class PoP_Module_Processor_PageTabsLayoutsBase extends PoPEngine_QueryDataComponentProcessorBase
{
    public function getTemplateResource(array $componentVariation, array &$props): ?array
    {
        return [PoP_CoreProcessors_TemplateResourceLoaderProcessor::class, PoP_CoreProcessors_TemplateResourceLoaderProcessor::RESOURCE_LAYOUT_PAGETAB];
    }

    protected function getFontawesome(array $componentVariation, array &$props)
    {
        return null;
    }
    protected function getThumb(array $componentVariation, array &$props)
    {
        return null;
    }
    protected function getPretitle(array $componentVariation, array &$props)
    {
        return null;
    }
    protected function getTitle(array $componentVariation, array &$props)
    {
        return null;
    }

    public function getMutableonrequestConfiguration(array $componentVariation, array &$props): array
    {
        $ret = parent::getMutableonrequestConfiguration($componentVariation, $props);

        if ($fontawesome = $this->getFontawesome($componentVariation, $props)) {
            $ret['fontawesome'] = $fontawesome;
        }

        // If using pretitle, disregard the thumb
        $pretitle = $this->getPretitle($componentVariation, $props);
        if (!$pretitle) {
            if ($thumb = $this->getThumb($componentVariation, $props)) {
                $ret['thumb'] = $thumb;
            }
        }

        if ($title = $this->getTitle($componentVariation, $props)) {
            if ($pretitle) {
                $title = $pretitle.': '.$title;
            }

            $ret['title'] = $title;
        }

        return $ret;
    }
}

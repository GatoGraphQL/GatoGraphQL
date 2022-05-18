<?php

abstract class PoP_Module_Processor_CustomPreviewPostLayoutsBase extends PoP_Module_Processor_PreviewPostLayoutsBase
{
    protected function getDetailsfeedBottomSubmodules(array $component)
    {
        $layouts = array();

        // Add the highlights and the referencedby. Lazy or not lazy?
        if (PoP_ApplicationProcessors_Utils::feedDetailsLazyload()) {
            $layouts[] = [PoP_Module_Processor_CustomWrapperLayouts::class, PoP_Module_Processor_CustomWrapperLayouts::COMPONENT_CODEWRAPPER_LAZYLOADINGSPINNER];
            $layouts[] = [PoP_Module_Processor_HighlightReferencedbyLayouts::class, PoP_Module_Processor_HighlightReferencedbyLayouts::COMPONENT_LAZYSUBCOMPONENT_HIGHLIGHTS];
            $layouts[] = [PoP_Module_Processor_ReferencedbyLayouts::class, PoP_Module_Processor_ReferencedbyLayouts::COMPONENT_LAZYSUBCOMPONENT_REFERENCEDBY];
        } else {
            $layouts[] = [Wassup_Module_Processor_WidgetWrappers::class, Wassup_Module_Processor_WidgetWrappers::COMPONENT_WIDGETWRAPPER_HIGHLIGHTS_DETAILS];
            $layouts[] = [Wassup_Module_Processor_WidgetWrappers::class, Wassup_Module_Processor_WidgetWrappers::COMPONENT_WIDGETWRAPPER_REFERENCEDBY_DETAILS];
        }

        // Allow to override. Eg: TPP Debate website adds the Stance Counter
        $layouts = \PoP\Root\App::applyFilters('PoP_Module_Processor_CustomPreviewPostLayoutsBase:detailsfeed_bottom_components', $layouts, $component);

        return $layouts;
    }

    public function horizontalLayout(array $component)
    {
        return false;
    }

    public function horizontalMediaLayout(array $component)
    {
        return false;
    }

    public function getImmutableConfiguration(array $component, array &$props): array
    {
        $ret = parent::getImmutableConfiguration($component, $props);

        if ($this->getQuicklinkgroupTopSubmodule($component)) {
            $ret[GD_JS_CLASSES]['quicklinkgroup-top'] = 'icon-only pull-right';
        }

        $ret[GD_JS_CLASSES]['title'] = 'media-heading';
        if ($this->horizontalLayout($component)) {
            $ret[GD_JS_CLASSES]['wrapper'] = 'row';
            $ret[GD_JS_CLASSES]['thumb-wrapper'] = 'col-xsm-4';
            $ret[GD_JS_CLASSES]['content-body'] = 'col-xsm-8';
        } elseif ($this->horizontalMediaLayout($component)) {
            $ret[GD_JS_CLASSES]['wrapper'] = 'media'; //' overflow-visible';
            $ret[GD_JS_CLASSES]['thumb-wrapper'] = 'media-left';
            $ret[GD_JS_CLASSES]['content-body'] = 'media-body';
        }

        return $ret;
    }

    public function initModelProps(array $component, array &$props): void
    {

        // Make the thumb image responsive if it is not the media layout
        if (!$this->horizontalMediaLayout($component)) {
            if ($thumb = $this->getPostThumbSubmodule($component)) {
                $this->appendProp($thumb, $props, 'img-class', 'img-responsive');
            }
        }

        parent::initModelProps($component, $props);
    }
}

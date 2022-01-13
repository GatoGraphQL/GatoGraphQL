<?php
use PoP\Root\Facades\Hooks\HooksAPIFacade;

abstract class PoP_Module_Processor_CustomPreviewPostLayoutsBase extends PoP_Module_Processor_PreviewPostLayoutsBase
{
    protected function getDetailsfeedBottomSubmodules(array $module)
    {
        $layouts = array();

        // Add the highlights and the referencedby. Lazy or not lazy?
        if (PoP_ApplicationProcessors_Utils::feedDetailsLazyload()) {
            $layouts[] = [PoP_Module_Processor_CustomWrapperLayouts::class, PoP_Module_Processor_CustomWrapperLayouts::MODULE_CODEWRAPPER_LAZYLOADINGSPINNER];
            $layouts[] = [PoP_Module_Processor_HighlightReferencedbyLayouts::class, PoP_Module_Processor_HighlightReferencedbyLayouts::MODULE_LAZYSUBCOMPONENT_HIGHLIGHTS];
            $layouts[] = [PoP_Module_Processor_ReferencedbyLayouts::class, PoP_Module_Processor_ReferencedbyLayouts::MODULE_LAZYSUBCOMPONENT_REFERENCEDBY];
        } else {
            $layouts[] = [Wassup_Module_Processor_WidgetWrappers::class, Wassup_Module_Processor_WidgetWrappers::MODULE_WIDGETWRAPPER_HIGHLIGHTS_DETAILS];
            $layouts[] = [Wassup_Module_Processor_WidgetWrappers::class, Wassup_Module_Processor_WidgetWrappers::MODULE_WIDGETWRAPPER_REFERENCEDBY_DETAILS];
        }

        // Allow to override. Eg: TPP Debate website adds the Stance Counter
        $layouts = HooksAPIFacade::getInstance()->applyFilters('PoP_Module_Processor_CustomPreviewPostLayoutsBase:detailsfeed_bottom_modules', $layouts, $module);

        return $layouts;
    }

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

        $ret[GD_JS_CLASSES]['title'] = 'media-heading';
        if ($this->horizontalLayout($module)) {
            $ret[GD_JS_CLASSES]['wrapper'] = 'row';
            $ret[GD_JS_CLASSES]['thumb-wrapper'] = 'col-xsm-4';
            $ret[GD_JS_CLASSES]['content-body'] = 'col-xsm-8';
        } elseif ($this->horizontalMediaLayout($module)) {
            $ret[GD_JS_CLASSES]['wrapper'] = 'media'; //' overflow-visible';
            $ret[GD_JS_CLASSES]['thumb-wrapper'] = 'media-left';
            $ret[GD_JS_CLASSES]['content-body'] = 'media-body';
        }

        return $ret;
    }

    public function initModelProps(array $module, array &$props): void
    {

        // Make the thumb image responsive if it is not the media layout
        if (!$this->horizontalMediaLayout($module)) {
            if ($thumb = $this->getPostThumbSubmodule($module)) {
                $this->appendProp($thumb, $props, 'img-class', 'img-responsive');
            }
        }

        parent::initModelProps($module, $props);
    }
}

<?php
use PoP\Root\Facades\Translation\TranslationAPIFacade;

class PoP_ContentPostLinks_Module_Processor_CustomPreviewPostLayouts extends PoP_Module_Processor_CustomPreviewPostLayoutsBase
{
    public final const COMPONENT_LAYOUT_PREVIEWPOST_CONTENTPOSTLINK_NAVIGATOR = 'layout-previewpost-contentpostlink-navigator';
    public final const COMPONENT_LAYOUT_PREVIEWPOST_CONTENTPOSTLINK_ADDONS = 'layout-previewpost-contentpostlink-addons';
    public final const COMPONENT_LAYOUT_PREVIEWPOST_CONTENTPOSTLINK_DETAILS = 'layout-previewpost-contentpostlink-details';
    public final const COMPONENT_LAYOUT_PREVIEWPOST_CONTENTPOSTLINK_THUMBNAIL = 'layout-previewpost-contentpostlink-thumbnail';
    public final const COMPONENT_LAYOUT_PREVIEWPOST_CONTENTPOSTLINK_LIST = 'layout-previewpost-contentpostlink-list';
    public final const COMPONENT_LAYOUT_PREVIEWPOST_CONTENTPOSTLINK_RELATED = 'layout-previewpost-contentpostlink-related';
    public final const COMPONENT_LAYOUT_PREVIEWPOST_CONTENTPOSTLINK_EDIT = 'layout-previewpost-contentpostlink-edit';

    public function getComponentNamesToProcess(): array
    {
        return array(
            self::COMPONENT_LAYOUT_PREVIEWPOST_CONTENTPOSTLINK_NAVIGATOR,
            self::COMPONENT_LAYOUT_PREVIEWPOST_CONTENTPOSTLINK_ADDONS,
            self::COMPONENT_LAYOUT_PREVIEWPOST_CONTENTPOSTLINK_DETAILS,
            self::COMPONENT_LAYOUT_PREVIEWPOST_CONTENTPOSTLINK_THUMBNAIL,
            self::COMPONENT_LAYOUT_PREVIEWPOST_CONTENTPOSTLINK_LIST,
            self::COMPONENT_LAYOUT_PREVIEWPOST_CONTENTPOSTLINK_RELATED,
            self::COMPONENT_LAYOUT_PREVIEWPOST_CONTENTPOSTLINK_EDIT,
        );
    }

    public function getUrlField(\PoP\ComponentModel\Component\Component $component)
    {
        switch ($component->name) {
            case self::COMPONENT_LAYOUT_PREVIEWPOST_CONTENTPOSTLINK_EDIT:
                return 'editURL';
        }

        return parent::getUrlField($component);
    }

    public function getLinktarget(\PoP\ComponentModel\Component\Component $component, array &$props)
    {
        switch ($component->name) {
            case self::COMPONENT_LAYOUT_PREVIEWPOST_CONTENTPOSTLINK_EDIT:
                if (PoP_Application_Utils::getAddcontentTarget() == POP_TARGET_ADDONS) {
                    return POP_TARGET_ADDONS;
                }
                break;
        }

        return parent::getLinktarget($component, $props);
    }

    public function getQuicklinkgroupBottomSubcomponent(\PoP\ComponentModel\Component\Component $component)
    {
        switch ($component->name) {
            case self::COMPONENT_LAYOUT_PREVIEWPOST_CONTENTPOSTLINK_EDIT:
                return [PoP_Module_Processor_CustomQuicklinkGroups::class, PoP_Module_Processor_CustomQuicklinkGroups::COMPONENT_QUICKLINKGROUP_POSTEDIT];

            case self::COMPONENT_LAYOUT_PREVIEWPOST_CONTENTPOSTLINK_DETAILS:
                return [PoP_Module_Processor_CustomQuicklinkGroups::class, PoP_Module_Processor_CustomQuicklinkGroups::COMPONENT_QUICKLINKGROUP_POSTBOTTOMEXTENDED];
        }

        return parent::getQuicklinkgroupBottomSubcomponent($component);
    }

    public function getQuicklinkgroupTopSubcomponent(\PoP\ComponentModel\Component\Component $component)
    {
        switch ($component->name) {
            case self::COMPONENT_LAYOUT_PREVIEWPOST_CONTENTPOSTLINK_NAVIGATOR:
            case self::COMPONENT_LAYOUT_PREVIEWPOST_CONTENTPOSTLINK_DETAILS:
            case self::COMPONENT_LAYOUT_PREVIEWPOST_CONTENTPOSTLINK_THUMBNAIL:
            case self::COMPONENT_LAYOUT_PREVIEWPOST_CONTENTPOSTLINK_LIST:
            case self::COMPONENT_LAYOUT_PREVIEWPOST_CONTENTPOSTLINK_RELATED:
                return [PoP_Module_Processor_CustomQuicklinkGroups::class, PoP_Module_Processor_CustomQuicklinkGroups::COMPONENT_QUICKLINKGROUP_POST];
        }

        return parent::getQuicklinkgroupTopSubcomponent($component);
    }

    public function getBottomSubcomponents(\PoP\ComponentModel\Component\Component $component)
    {
        $ret = parent::getBottomSubcomponents($component);

        switch ($component->name) {
            case self::COMPONENT_LAYOUT_PREVIEWPOST_CONTENTPOSTLINK_DETAILS:
                $ret = array_merge(
                    $ret,
                    $this->getDetailsfeedBottomSubcomponents($component)
                );
                break;
        }

        return $ret;
    }

    public function getBelowthumbLayoutSubcomponents(\PoP\ComponentModel\Component\Component $component)
    {
        $ret = parent::getBelowthumbLayoutSubcomponents($component);

        switch ($component->name) {
            case self::COMPONENT_LAYOUT_PREVIEWPOST_CONTENTPOSTLINK_NAVIGATOR:
            case self::COMPONENT_LAYOUT_PREVIEWPOST_CONTENTPOSTLINK_DETAILS:
            case self::COMPONENT_LAYOUT_PREVIEWPOST_CONTENTPOSTLINK_THUMBNAIL:
                $ret[] = [PoP_Module_Processor_PublishedLayouts::class, PoP_Module_Processor_PublishedLayouts::COMPONENT_LAYOUT_PUBLISHED];
                if (PoP_ApplicationProcessors_Utils::addCategories()) {
                    $ret[] = [Wassup_Module_Processor_WidgetWrappers::class, Wassup_Module_Processor_WidgetWrappers::COMPONENT_LAYOUTWRAPPER_CATEGORIES];
                }
                if (PoP_ApplicationProcessors_Utils::addAppliesto()) {
                    $ret[] = [Wassup_Module_Processor_WidgetWrappers::class, Wassup_Module_Processor_WidgetWrappers::COMPONENT_LAYOUTWRAPPER_APPLIESTO];
                }
                break;
        }

        return $ret;
    }

    public function getPostThumbSubcomponent(\PoP\ComponentModel\Component\Component $component)
    {
        switch ($component->name) {
            case self::COMPONENT_LAYOUT_PREVIEWPOST_CONTENTPOSTLINK_EDIT:
                return [GD_Custom_Module_Processor_PostThumbLayouts::class, GD_Custom_Module_Processor_PostThumbLayouts::COMPONENT_LAYOUT_POSTTHUMB_CROPPEDSMALL_EDIT];

            case self::COMPONENT_LAYOUT_PREVIEWPOST_CONTENTPOSTLINK_RELATED:
            case self::COMPONENT_LAYOUT_PREVIEWPOST_CONTENTPOSTLINK_LIST:
            case self::COMPONENT_LAYOUT_PREVIEWPOST_CONTENTPOSTLINK_ADDONS:
                return [GD_Custom_Module_Processor_PostThumbLayouts::class, GD_Custom_Module_Processor_PostThumbLayouts::COMPONENT_LAYOUT_POSTTHUMB_CROPPEDSMALL];
            
            case self::COMPONENT_LAYOUT_PREVIEWPOST_CONTENTPOSTLINK_NAVIGATOR:
            case self::COMPONENT_LAYOUT_PREVIEWPOST_CONTENTPOSTLINK_DETAILS:
            case self::COMPONENT_LAYOUT_PREVIEWPOST_CONTENTPOSTLINK_THUMBNAIL:
                return [GD_Custom_Module_Processor_PostThumbLayouts::class, GD_Custom_Module_Processor_PostThumbLayouts::COMPONENT_LAYOUT_POSTTHUMB_CROPPEDMEDIUM];
        }

        return parent::getPostThumbSubcomponent($component);
    }

    public function showExcerpt(\PoP\ComponentModel\Component\Component $component)
    {
        switch ($component->name) {
            case self::COMPONENT_LAYOUT_PREVIEWPOST_CONTENTPOSTLINK_DETAILS:
                return true;
        }

        return parent::showExcerpt($component);
    }

    public function getTitleHtmlmarkup(\PoP\ComponentModel\Component\Component $component, array &$props)
    {
        switch ($component->name) {
            case self::COMPONENT_LAYOUT_PREVIEWPOST_CONTENTPOSTLINK_DETAILS:
                return 'h3';
        }

        return parent::getTitleHtmlmarkup($component, $props);
    }

    public function authorPositions(\PoP\ComponentModel\Component\Component $component)
    {
        switch ($component->name) {
            case self::COMPONENT_LAYOUT_PREVIEWPOST_CONTENTPOSTLINK_NAVIGATOR:
            case self::COMPONENT_LAYOUT_PREVIEWPOST_CONTENTPOSTLINK_ADDONS:
            case self::COMPONENT_LAYOUT_PREVIEWPOST_CONTENTPOSTLINK_RELATED:
            case self::COMPONENT_LAYOUT_PREVIEWPOST_CONTENTPOSTLINK_DETAILS:
            case self::COMPONENT_LAYOUT_PREVIEWPOST_CONTENTPOSTLINK_THUMBNAIL:
            case self::COMPONENT_LAYOUT_PREVIEWPOST_CONTENTPOSTLINK_LIST:
                return array(
                    GD_CONSTANT_AUTHORPOSITION_BELOWCONTENT
                );

            case self::COMPONENT_LAYOUT_PREVIEWPOST_CONTENTPOSTLINK_EDIT:
                return array();
        }

        return parent::authorPositions($component);
    }

    public function horizontalLayout(\PoP\ComponentModel\Component\Component $component)
    {
        switch ($component->name) {
            case self::COMPONENT_LAYOUT_PREVIEWPOST_CONTENTPOSTLINK_DETAILS:
                return true;
        }

        return parent::horizontalLayout($component);
    }

    public function horizontalMediaLayout(\PoP\ComponentModel\Component\Component $component)
    {
        switch ($component->name) {
            case self::COMPONENT_LAYOUT_PREVIEWPOST_CONTENTPOSTLINK_RELATED:
            case self::COMPONENT_LAYOUT_PREVIEWPOST_CONTENTPOSTLINK_EDIT:
            case self::COMPONENT_LAYOUT_PREVIEWPOST_CONTENTPOSTLINK_LIST:
            case self::COMPONENT_LAYOUT_PREVIEWPOST_CONTENTPOSTLINK_ADDONS:
                return true;
        }

        return parent::horizontalMediaLayout($component);
    }

    public function getImmutableConfiguration(\PoP\ComponentModel\Component\Component $component, array &$props): array
    {
        $ret = parent::getImmutableConfiguration($component, $props);

        switch ($component->name) {
            case self::COMPONENT_LAYOUT_PREVIEWPOST_CONTENTPOSTLINK_NAVIGATOR:
            case self::COMPONENT_LAYOUT_PREVIEWPOST_CONTENTPOSTLINK_ADDONS:
            case self::COMPONENT_LAYOUT_PREVIEWPOST_CONTENTPOSTLINK_RELATED:
            case self::COMPONENT_LAYOUT_PREVIEWPOST_CONTENTPOSTLINK_DETAILS:
            case self::COMPONENT_LAYOUT_PREVIEWPOST_CONTENTPOSTLINK_THUMBNAIL:
            case self::COMPONENT_LAYOUT_PREVIEWPOST_CONTENTPOSTLINK_LIST:
                $ret[GD_JS_CLASSES]['authors'] = 'pull-right authors-bottom';
                break;
        }

        switch ($component->name) {
            case self::COMPONENT_LAYOUT_PREVIEWPOST_CONTENTPOSTLINK_NAVIGATOR:
            case self::COMPONENT_LAYOUT_PREVIEWPOST_CONTENTPOSTLINK_DETAILS:
            case self::COMPONENT_LAYOUT_PREVIEWPOST_CONTENTPOSTLINK_THUMBNAIL:
                $ret[GD_JS_CLASSES]['belowthumb'] = 'bg-info text-info belowthumb';
                break;
        }

        switch ($component->name) {
            case self::COMPONENT_LAYOUT_PREVIEWPOST_CONTENTPOSTLINK_DETAILS:
                $ret[GD_JS_CLASSES]['thumb'] = 'pop-thumb-framed';
                break;
        }

        return $ret;
    }

    public function getTitleBeforeauthors(\PoP\ComponentModel\Component\Component $component, array &$props)
    {
        switch ($component->name) {
            case self::COMPONENT_LAYOUT_PREVIEWPOST_CONTENTPOSTLINK_NAVIGATOR:
            case self::COMPONENT_LAYOUT_PREVIEWPOST_CONTENTPOSTLINK_ADDONS:
            case self::COMPONENT_LAYOUT_PREVIEWPOST_CONTENTPOSTLINK_RELATED:
            case self::COMPONENT_LAYOUT_PREVIEWPOST_CONTENTPOSTLINK_DETAILS:
            case self::COMPONENT_LAYOUT_PREVIEWPOST_CONTENTPOSTLINK_THUMBNAIL:
            case self::COMPONENT_LAYOUT_PREVIEWPOST_CONTENTPOSTLINK_LIST:
                return array(
                    'belowcontent' => TranslationAPIFacade::getInstance()->__('posted by', 'poptheme-wassup')
                );
        }

        return parent::getTitleBeforeauthors($component, $props);
    }
}



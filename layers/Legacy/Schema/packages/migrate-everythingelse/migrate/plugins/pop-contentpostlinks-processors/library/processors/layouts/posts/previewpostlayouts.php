<?php
use PoP\Root\Facades\Translation\TranslationAPIFacade;

class PoP_ContentPostLinks_Module_Processor_CustomPreviewPostLayouts extends PoP_Module_Processor_CustomPreviewPostLayoutsBase
{
    public final const MODULE_LAYOUT_PREVIEWPOST_CONTENTPOSTLINK_NAVIGATOR = 'layout-previewpost-contentpostlink-navigator';
    public final const MODULE_LAYOUT_PREVIEWPOST_CONTENTPOSTLINK_ADDONS = 'layout-previewpost-contentpostlink-addons';
    public final const MODULE_LAYOUT_PREVIEWPOST_CONTENTPOSTLINK_DETAILS = 'layout-previewpost-contentpostlink-details';
    public final const MODULE_LAYOUT_PREVIEWPOST_CONTENTPOSTLINK_THUMBNAIL = 'layout-previewpost-contentpostlink-thumbnail';
    public final const MODULE_LAYOUT_PREVIEWPOST_CONTENTPOSTLINK_LIST = 'layout-previewpost-contentpostlink-list';
    public final const MODULE_LAYOUT_PREVIEWPOST_CONTENTPOSTLINK_RELATED = 'layout-previewpost-contentpostlink-related';
    public final const MODULE_LAYOUT_PREVIEWPOST_CONTENTPOSTLINK_EDIT = 'layout-previewpost-contentpostlink-edit';

    public function getComponentsToProcess(): array
    {
        return array(
            [self::class, self::MODULE_LAYOUT_PREVIEWPOST_CONTENTPOSTLINK_NAVIGATOR],
            [self::class, self::MODULE_LAYOUT_PREVIEWPOST_CONTENTPOSTLINK_ADDONS],
            [self::class, self::MODULE_LAYOUT_PREVIEWPOST_CONTENTPOSTLINK_DETAILS],
            [self::class, self::MODULE_LAYOUT_PREVIEWPOST_CONTENTPOSTLINK_THUMBNAIL],
            [self::class, self::MODULE_LAYOUT_PREVIEWPOST_CONTENTPOSTLINK_LIST],
            [self::class, self::MODULE_LAYOUT_PREVIEWPOST_CONTENTPOSTLINK_RELATED],
            [self::class, self::MODULE_LAYOUT_PREVIEWPOST_CONTENTPOSTLINK_EDIT],
        );
    }

    public function getUrlField(array $component)
    {
        switch ($component[1]) {
            case self::MODULE_LAYOUT_PREVIEWPOST_CONTENTPOSTLINK_EDIT:
                return 'editURL';
        }

        return parent::getUrlField($component);
    }

    public function getLinktarget(array $component, array &$props)
    {
        switch ($component[1]) {
            case self::MODULE_LAYOUT_PREVIEWPOST_CONTENTPOSTLINK_EDIT:
                if (PoP_Application_Utils::getAddcontentTarget() == POP_TARGET_ADDONS) {
                    return POP_TARGET_ADDONS;
                }
                break;
        }

        return parent::getLinktarget($component, $props);
    }

    public function getQuicklinkgroupBottomSubmodule(array $component)
    {
        switch ($component[1]) {
            case self::MODULE_LAYOUT_PREVIEWPOST_CONTENTPOSTLINK_EDIT:
                return [PoP_Module_Processor_CustomQuicklinkGroups::class, PoP_Module_Processor_CustomQuicklinkGroups::MODULE_QUICKLINKGROUP_POSTEDIT];

            case self::MODULE_LAYOUT_PREVIEWPOST_CONTENTPOSTLINK_DETAILS:
                return [PoP_Module_Processor_CustomQuicklinkGroups::class, PoP_Module_Processor_CustomQuicklinkGroups::MODULE_QUICKLINKGROUP_POSTBOTTOMEXTENDED];
        }

        return parent::getQuicklinkgroupBottomSubmodule($component);
    }

    public function getQuicklinkgroupTopSubmodule(array $component)
    {
        switch ($component[1]) {
            case self::MODULE_LAYOUT_PREVIEWPOST_CONTENTPOSTLINK_NAVIGATOR:
            case self::MODULE_LAYOUT_PREVIEWPOST_CONTENTPOSTLINK_DETAILS:
            case self::MODULE_LAYOUT_PREVIEWPOST_CONTENTPOSTLINK_THUMBNAIL:
            case self::MODULE_LAYOUT_PREVIEWPOST_CONTENTPOSTLINK_LIST:
            case self::MODULE_LAYOUT_PREVIEWPOST_CONTENTPOSTLINK_RELATED:
                return [PoP_Module_Processor_CustomQuicklinkGroups::class, PoP_Module_Processor_CustomQuicklinkGroups::MODULE_QUICKLINKGROUP_POST];
        }

        return parent::getQuicklinkgroupTopSubmodule($component);
    }

    public function getBottomSubmodules(array $component)
    {
        $ret = parent::getBottomSubmodules($component);

        switch ($component[1]) {
            case self::MODULE_LAYOUT_PREVIEWPOST_CONTENTPOSTLINK_DETAILS:
                $ret = array_merge(
                    $ret,
                    $this->getDetailsfeedBottomSubmodules($component)
                );
                break;
        }

        return $ret;
    }

    public function getBelowthumbLayoutSubmodules(array $component)
    {
        $ret = parent::getBelowthumbLayoutSubmodules($component);

        switch ($component[1]) {
            case self::MODULE_LAYOUT_PREVIEWPOST_CONTENTPOSTLINK_NAVIGATOR:
            case self::MODULE_LAYOUT_PREVIEWPOST_CONTENTPOSTLINK_DETAILS:
            case self::MODULE_LAYOUT_PREVIEWPOST_CONTENTPOSTLINK_THUMBNAIL:
                $ret[] = [PoP_Module_Processor_PublishedLayouts::class, PoP_Module_Processor_PublishedLayouts::MODULE_LAYOUT_PUBLISHED];
                if (PoP_ApplicationProcessors_Utils::addCategories()) {
                    $ret[] = [Wassup_Module_Processor_WidgetWrappers::class, Wassup_Module_Processor_WidgetWrappers::MODULE_LAYOUTWRAPPER_CATEGORIES];
                }
                if (PoP_ApplicationProcessors_Utils::addAppliesto()) {
                    $ret[] = [Wassup_Module_Processor_WidgetWrappers::class, Wassup_Module_Processor_WidgetWrappers::MODULE_LAYOUTWRAPPER_APPLIESTO];
                }
                break;
        }

        return $ret;
    }

    public function getPostThumbSubmodule(array $component)
    {
        switch ($component[1]) {
            case self::MODULE_LAYOUT_PREVIEWPOST_CONTENTPOSTLINK_EDIT:
                return [GD_Custom_Module_Processor_PostThumbLayouts::class, GD_Custom_Module_Processor_PostThumbLayouts::MODULE_LAYOUT_POSTTHUMB_CROPPEDSMALL_EDIT];

            case self::MODULE_LAYOUT_PREVIEWPOST_CONTENTPOSTLINK_RELATED:
            case self::MODULE_LAYOUT_PREVIEWPOST_CONTENTPOSTLINK_LIST:
            case self::MODULE_LAYOUT_PREVIEWPOST_CONTENTPOSTLINK_ADDONS:
                return [GD_Custom_Module_Processor_PostThumbLayouts::class, GD_Custom_Module_Processor_PostThumbLayouts::MODULE_LAYOUT_POSTTHUMB_CROPPEDSMALL];
            
            case self::MODULE_LAYOUT_PREVIEWPOST_CONTENTPOSTLINK_NAVIGATOR:
            case self::MODULE_LAYOUT_PREVIEWPOST_CONTENTPOSTLINK_DETAILS:
            case self::MODULE_LAYOUT_PREVIEWPOST_CONTENTPOSTLINK_THUMBNAIL:
                return [GD_Custom_Module_Processor_PostThumbLayouts::class, GD_Custom_Module_Processor_PostThumbLayouts::MODULE_LAYOUT_POSTTHUMB_CROPPEDMEDIUM];
        }

        return parent::getPostThumbSubmodule($component);
    }

    public function showExcerpt(array $component)
    {
        switch ($component[1]) {
            case self::MODULE_LAYOUT_PREVIEWPOST_CONTENTPOSTLINK_DETAILS:
                return true;
        }

        return parent::showExcerpt($component);
    }

    public function getTitleHtmlmarkup(array $component, array &$props)
    {
        switch ($component[1]) {
            case self::MODULE_LAYOUT_PREVIEWPOST_CONTENTPOSTLINK_DETAILS:
                return 'h3';
        }

        return parent::getTitleHtmlmarkup($component, $props);
    }

    public function authorPositions(array $component)
    {
        switch ($component[1]) {
            case self::MODULE_LAYOUT_PREVIEWPOST_CONTENTPOSTLINK_NAVIGATOR:
            case self::MODULE_LAYOUT_PREVIEWPOST_CONTENTPOSTLINK_ADDONS:
            case self::MODULE_LAYOUT_PREVIEWPOST_CONTENTPOSTLINK_RELATED:
            case self::MODULE_LAYOUT_PREVIEWPOST_CONTENTPOSTLINK_DETAILS:
            case self::MODULE_LAYOUT_PREVIEWPOST_CONTENTPOSTLINK_THUMBNAIL:
            case self::MODULE_LAYOUT_PREVIEWPOST_CONTENTPOSTLINK_LIST:
                return array(
                    GD_CONSTANT_AUTHORPOSITION_BELOWCONTENT
                );

            case self::MODULE_LAYOUT_PREVIEWPOST_CONTENTPOSTLINK_EDIT:
                return array();
        }

        return parent::authorPositions($component);
    }

    public function horizontalLayout(array $component)
    {
        switch ($component[1]) {
            case self::MODULE_LAYOUT_PREVIEWPOST_CONTENTPOSTLINK_DETAILS:
                return true;
        }

        return parent::horizontalLayout($component);
    }

    public function horizontalMediaLayout(array $component)
    {
        switch ($component[1]) {
            case self::MODULE_LAYOUT_PREVIEWPOST_CONTENTPOSTLINK_RELATED:
            case self::MODULE_LAYOUT_PREVIEWPOST_CONTENTPOSTLINK_EDIT:
            case self::MODULE_LAYOUT_PREVIEWPOST_CONTENTPOSTLINK_LIST:
            case self::MODULE_LAYOUT_PREVIEWPOST_CONTENTPOSTLINK_ADDONS:
                return true;
        }

        return parent::horizontalMediaLayout($component);
    }

    public function getImmutableConfiguration(array $component, array &$props): array
    {
        $ret = parent::getImmutableConfiguration($component, $props);

        switch ($component[1]) {
            case self::MODULE_LAYOUT_PREVIEWPOST_CONTENTPOSTLINK_NAVIGATOR:
            case self::MODULE_LAYOUT_PREVIEWPOST_CONTENTPOSTLINK_ADDONS:
            case self::MODULE_LAYOUT_PREVIEWPOST_CONTENTPOSTLINK_RELATED:
            case self::MODULE_LAYOUT_PREVIEWPOST_CONTENTPOSTLINK_DETAILS:
            case self::MODULE_LAYOUT_PREVIEWPOST_CONTENTPOSTLINK_THUMBNAIL:
            case self::MODULE_LAYOUT_PREVIEWPOST_CONTENTPOSTLINK_LIST:
                $ret[GD_JS_CLASSES]['authors'] = 'pull-right authors-bottom';
                break;
        }

        switch ($component[1]) {
            case self::MODULE_LAYOUT_PREVIEWPOST_CONTENTPOSTLINK_NAVIGATOR:
            case self::MODULE_LAYOUT_PREVIEWPOST_CONTENTPOSTLINK_DETAILS:
            case self::MODULE_LAYOUT_PREVIEWPOST_CONTENTPOSTLINK_THUMBNAIL:
                $ret[GD_JS_CLASSES]['belowthumb'] = 'bg-info text-info belowthumb';
                break;
        }

        switch ($component[1]) {
            case self::MODULE_LAYOUT_PREVIEWPOST_CONTENTPOSTLINK_DETAILS:
                $ret[GD_JS_CLASSES]['thumb'] = 'pop-thumb-framed';
                break;
        }

        return $ret;
    }

    public function getTitleBeforeauthors(array $component, array &$props)
    {
        switch ($component[1]) {
            case self::MODULE_LAYOUT_PREVIEWPOST_CONTENTPOSTLINK_NAVIGATOR:
            case self::MODULE_LAYOUT_PREVIEWPOST_CONTENTPOSTLINK_ADDONS:
            case self::MODULE_LAYOUT_PREVIEWPOST_CONTENTPOSTLINK_RELATED:
            case self::MODULE_LAYOUT_PREVIEWPOST_CONTENTPOSTLINK_DETAILS:
            case self::MODULE_LAYOUT_PREVIEWPOST_CONTENTPOSTLINK_THUMBNAIL:
            case self::MODULE_LAYOUT_PREVIEWPOST_CONTENTPOSTLINK_LIST:
                return array(
                    'belowcontent' => TranslationAPIFacade::getInstance()->__('posted by', 'poptheme-wassup')
                );
        }

        return parent::getTitleBeforeauthors($component, $props);
    }
}



<?php
use PoP\Root\Facades\Translation\TranslationAPIFacade;

class PoP_ContentPostLinks_Module_Processor_CustomPreviewPostLayouts extends PoP_Module_Processor_CustomPreviewPostLayoutsBase
{
    public const MODULE_LAYOUT_PREVIEWPOST_CONTENTPOSTLINK_NAVIGATOR = 'layout-previewpost-contentpostlink-navigator';
    public const MODULE_LAYOUT_PREVIEWPOST_CONTENTPOSTLINK_ADDONS = 'layout-previewpost-contentpostlink-addons';
    public const MODULE_LAYOUT_PREVIEWPOST_CONTENTPOSTLINK_DETAILS = 'layout-previewpost-contentpostlink-details';
    public const MODULE_LAYOUT_PREVIEWPOST_CONTENTPOSTLINK_THUMBNAIL = 'layout-previewpost-contentpostlink-thumbnail';
    public const MODULE_LAYOUT_PREVIEWPOST_CONTENTPOSTLINK_LIST = 'layout-previewpost-contentpostlink-list';
    public const MODULE_LAYOUT_PREVIEWPOST_CONTENTPOSTLINK_RELATED = 'layout-previewpost-contentpostlink-related';
    public const MODULE_LAYOUT_PREVIEWPOST_CONTENTPOSTLINK_EDIT = 'layout-previewpost-contentpostlink-edit';

    public function getModulesToProcess(): array
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

    public function getUrlField(array $module)
    {
        switch ($module[1]) {
            case self::MODULE_LAYOUT_PREVIEWPOST_CONTENTPOSTLINK_EDIT:
                return 'editURL';
        }

        return parent::getUrlField($module);
    }

    public function getLinktarget(array $module, array &$props)
    {
        switch ($module[1]) {
            case self::MODULE_LAYOUT_PREVIEWPOST_CONTENTPOSTLINK_EDIT:
                if (PoP_Application_Utils::getAddcontentTarget() == POP_TARGET_ADDONS) {
                    return POP_TARGET_ADDONS;
                }
                break;
        }

        return parent::getLinktarget($module, $props);
    }

    public function getQuicklinkgroupBottomSubmodule(array $module)
    {
        switch ($module[1]) {
            case self::MODULE_LAYOUT_PREVIEWPOST_CONTENTPOSTLINK_EDIT:
                return [PoP_Module_Processor_CustomQuicklinkGroups::class, PoP_Module_Processor_CustomQuicklinkGroups::MODULE_QUICKLINKGROUP_POSTEDIT];

            case self::MODULE_LAYOUT_PREVIEWPOST_CONTENTPOSTLINK_DETAILS:
                return [PoP_Module_Processor_CustomQuicklinkGroups::class, PoP_Module_Processor_CustomQuicklinkGroups::MODULE_QUICKLINKGROUP_POSTBOTTOMEXTENDED];
        }

        return parent::getQuicklinkgroupBottomSubmodule($module);
    }

    public function getQuicklinkgroupTopSubmodule(array $module)
    {
        switch ($module[1]) {
            case self::MODULE_LAYOUT_PREVIEWPOST_CONTENTPOSTLINK_NAVIGATOR:
            case self::MODULE_LAYOUT_PREVIEWPOST_CONTENTPOSTLINK_DETAILS:
            case self::MODULE_LAYOUT_PREVIEWPOST_CONTENTPOSTLINK_THUMBNAIL:
            case self::MODULE_LAYOUT_PREVIEWPOST_CONTENTPOSTLINK_LIST:
            case self::MODULE_LAYOUT_PREVIEWPOST_CONTENTPOSTLINK_RELATED:
                return [PoP_Module_Processor_CustomQuicklinkGroups::class, PoP_Module_Processor_CustomQuicklinkGroups::MODULE_QUICKLINKGROUP_POST];
        }

        return parent::getQuicklinkgroupTopSubmodule($module);
    }

    public function getBottomSubmodules(array $module)
    {
        $ret = parent::getBottomSubmodules($module);

        switch ($module[1]) {
            case self::MODULE_LAYOUT_PREVIEWPOST_CONTENTPOSTLINK_DETAILS:
                $ret = array_merge(
                    $ret,
                    $this->getDetailsfeedBottomSubmodules($module)
                );
                break;
        }

        return $ret;
    }

    public function getBelowthumbLayoutSubmodules(array $module)
    {
        $ret = parent::getBelowthumbLayoutSubmodules($module);

        switch ($module[1]) {
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

    public function getPostThumbSubmodule(array $module)
    {
        switch ($module[1]) {
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

        return parent::getPostThumbSubmodule($module);
    }

    public function showExcerpt(array $module)
    {
        switch ($module[1]) {
            case self::MODULE_LAYOUT_PREVIEWPOST_CONTENTPOSTLINK_DETAILS:
                return true;
        }

        return parent::showExcerpt($module);
    }

    public function getTitleHtmlmarkup(array $module, array &$props)
    {
        switch ($module[1]) {
            case self::MODULE_LAYOUT_PREVIEWPOST_CONTENTPOSTLINK_DETAILS:
                return 'h3';
        }

        return parent::getTitleHtmlmarkup($module, $props);
    }

    public function authorPositions(array $module)
    {
        switch ($module[1]) {
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

        return parent::authorPositions($module);
    }

    public function horizontalLayout(array $module)
    {
        switch ($module[1]) {
            case self::MODULE_LAYOUT_PREVIEWPOST_CONTENTPOSTLINK_DETAILS:
                return true;
        }

        return parent::horizontalLayout($module);
    }

    public function horizontalMediaLayout(array $module)
    {
        switch ($module[1]) {
            case self::MODULE_LAYOUT_PREVIEWPOST_CONTENTPOSTLINK_RELATED:
            case self::MODULE_LAYOUT_PREVIEWPOST_CONTENTPOSTLINK_EDIT:
            case self::MODULE_LAYOUT_PREVIEWPOST_CONTENTPOSTLINK_LIST:
            case self::MODULE_LAYOUT_PREVIEWPOST_CONTENTPOSTLINK_ADDONS:
                return true;
        }

        return parent::horizontalMediaLayout($module);
    }

    public function getImmutableConfiguration(array $module, array &$props): array
    {
        $ret = parent::getImmutableConfiguration($module, $props);

        switch ($module[1]) {
            case self::MODULE_LAYOUT_PREVIEWPOST_CONTENTPOSTLINK_NAVIGATOR:
            case self::MODULE_LAYOUT_PREVIEWPOST_CONTENTPOSTLINK_ADDONS:
            case self::MODULE_LAYOUT_PREVIEWPOST_CONTENTPOSTLINK_RELATED:
            case self::MODULE_LAYOUT_PREVIEWPOST_CONTENTPOSTLINK_DETAILS:
            case self::MODULE_LAYOUT_PREVIEWPOST_CONTENTPOSTLINK_THUMBNAIL:
            case self::MODULE_LAYOUT_PREVIEWPOST_CONTENTPOSTLINK_LIST:
                $ret[GD_JS_CLASSES]['authors'] = 'pull-right authors-bottom';
                break;
        }

        switch ($module[1]) {
            case self::MODULE_LAYOUT_PREVIEWPOST_CONTENTPOSTLINK_NAVIGATOR:
            case self::MODULE_LAYOUT_PREVIEWPOST_CONTENTPOSTLINK_DETAILS:
            case self::MODULE_LAYOUT_PREVIEWPOST_CONTENTPOSTLINK_THUMBNAIL:
                $ret[GD_JS_CLASSES]['belowthumb'] = 'bg-info text-info belowthumb';
                break;
        }

        switch ($module[1]) {
            case self::MODULE_LAYOUT_PREVIEWPOST_CONTENTPOSTLINK_DETAILS:
                $ret[GD_JS_CLASSES]['thumb'] = 'pop-thumb-framed';
                break;
        }

        return $ret;
    }

    public function getTitleBeforeauthors(array $module, array &$props)
    {
        switch ($module[1]) {
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

        return parent::getTitleBeforeauthors($module, $props);
    }
}



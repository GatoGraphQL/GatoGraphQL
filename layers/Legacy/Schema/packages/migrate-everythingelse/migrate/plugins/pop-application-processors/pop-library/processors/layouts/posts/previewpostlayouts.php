<?php
use PoP\Root\Facades\Translation\TranslationAPIFacade;

class PoP_Module_Processor_CustomPreviewPostLayouts extends PoP_Module_Processor_CustomPreviewPostLayoutsBase
{
    public final const MODULE_LAYOUT_PREVIEWPOST_HEADER = 'layout-previewpost-header';
    public final const MODULE_LAYOUT_PREVIEWPOST_NAVIGATOR = 'layout-previewpost-navigator';
    public final const MODULE_LAYOUT_PREVIEWPOST_ADDONS = 'layout-previewpost-addons';
    public final const MODULE_LAYOUT_PREVIEWPOST_DETAILS = 'layout-previewpost-details';
    public final const MODULE_LAYOUT_PREVIEWPOST_THUMBNAIL = 'layout-previewpost-thumbnail';
    public final const MODULE_LAYOUT_PREVIEWPOST_LIST = 'layout-previewpost-list';
    public final const MODULE_LAYOUT_PREVIEWPOST_LINE = 'layout-previewpost-line';
    public final const MODULE_LAYOUT_PREVIEWPOST_RELATED = 'layout-previewpost-related';
    public final const MODULE_LAYOUT_PREVIEWPOST_EDIT = 'layout-previewpost-edit';
    public final const MODULE_LAYOUT_PREVIEWPOST_HIGHLIGHT_CONTENT = 'layout-previewpost-highlight-content';
    public final const MODULE_LAYOUT_PREVIEWPOST_HIGHLIGHT_EDIT = 'layout-previewpost-highlight-edit';
    public final const MODULE_LAYOUT_PREVIEWPOST_POST_NAVIGATOR = 'layout-previewpost-post-navigator';
    public final const MODULE_LAYOUT_PREVIEWPOST_POST_THUMBNAIL = 'layout-previewpost-post-thumbnail';
    public final const MODULE_LAYOUT_PREVIEWPOST_POST_ADDONS = 'layout-previewpost-post-addons';
    public final const MODULE_LAYOUT_PREVIEWPOST_POST_DETAILS = 'layout-previewpost-post-details';
    public final const MODULE_LAYOUT_PREVIEWPOST_POST_LIST = 'layout-previewpost-post-list';
    public final const MODULE_LAYOUT_PREVIEWPOST_POST_RELATED = 'layout-previewpost-post-related';
    public final const MODULE_LAYOUT_PREVIEWPOST_POST_EDIT = 'layout-previewpost-post-edit';

    public function getComponentVariationsToProcess(): array
    {
        return array(
            [self::class, self::MODULE_LAYOUT_PREVIEWPOST_HEADER],

            [self::class, self::MODULE_LAYOUT_PREVIEWPOST_NAVIGATOR],
            [self::class, self::MODULE_LAYOUT_PREVIEWPOST_ADDONS],
            [self::class, self::MODULE_LAYOUT_PREVIEWPOST_DETAILS],
            [self::class, self::MODULE_LAYOUT_PREVIEWPOST_THUMBNAIL],
            [self::class, self::MODULE_LAYOUT_PREVIEWPOST_LIST],
            [self::class, self::MODULE_LAYOUT_PREVIEWPOST_LINE],
            [self::class, self::MODULE_LAYOUT_PREVIEWPOST_RELATED],
            [self::class, self::MODULE_LAYOUT_PREVIEWPOST_EDIT],

            [self::class, self::MODULE_LAYOUT_PREVIEWPOST_HIGHLIGHT_CONTENT],
            [self::class, self::MODULE_LAYOUT_PREVIEWPOST_HIGHLIGHT_EDIT],

            [self::class, self::MODULE_LAYOUT_PREVIEWPOST_POST_NAVIGATOR],
            [self::class, self::MODULE_LAYOUT_PREVIEWPOST_POST_THUMBNAIL],
            [self::class, self::MODULE_LAYOUT_PREVIEWPOST_POST_ADDONS],
            [self::class, self::MODULE_LAYOUT_PREVIEWPOST_POST_DETAILS],
            [self::class, self::MODULE_LAYOUT_PREVIEWPOST_POST_LIST],
            [self::class, self::MODULE_LAYOUT_PREVIEWPOST_POST_RELATED],
            [self::class, self::MODULE_LAYOUT_PREVIEWPOST_POST_EDIT],
        );
    }

    public function getUrlField(array $module)
    {
        switch ($module[1]) {
            case self::MODULE_LAYOUT_PREVIEWPOST_EDIT:
            case self::MODULE_LAYOUT_PREVIEWPOST_HIGHLIGHT_EDIT:
            case self::MODULE_LAYOUT_PREVIEWPOST_POST_EDIT:
                return 'editURL';
        }

        return parent::getUrlField($module);
    }

    public function getLinktarget(array $module, array &$props)
    {
        switch ($module[1]) {
            case self::MODULE_LAYOUT_PREVIEWPOST_EDIT:
            case self::MODULE_LAYOUT_PREVIEWPOST_HIGHLIGHT_EDIT:
            case self::MODULE_LAYOUT_PREVIEWPOST_POST_EDIT:
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
            case self::MODULE_LAYOUT_PREVIEWPOST_EDIT:
            case self::MODULE_LAYOUT_PREVIEWPOST_POST_EDIT:
                return [PoP_Module_Processor_CustomQuicklinkGroups::class, PoP_Module_Processor_CustomQuicklinkGroups::MODULE_QUICKLINKGROUP_POSTEDIT];

            case self::MODULE_LAYOUT_PREVIEWPOST_HIGHLIGHT_EDIT:
                return [PoP_AddHighlights_Module_Processor_CustomQuicklinkGroups::class, PoP_AddHighlights_Module_Processor_CustomQuicklinkGroups::MODULE_QUICKLINKGROUP_HIGHLIGHTEDIT];

            case self::MODULE_LAYOUT_PREVIEWPOST_HIGHLIGHT_CONTENT:
                return [PoP_AddHighlights_Module_Processor_CustomQuicklinkGroups::class, PoP_AddHighlights_Module_Processor_CustomQuicklinkGroups::MODULE_QUICKLINKGROUP_HIGHLIGHTCONTENT];

            case self::MODULE_LAYOUT_PREVIEWPOST_DETAILS:
            case self::MODULE_LAYOUT_PREVIEWPOST_POST_DETAILS:
                return [PoP_Module_Processor_CustomQuicklinkGroups::class, PoP_Module_Processor_CustomQuicklinkGroups::MODULE_QUICKLINKGROUP_POSTBOTTOMEXTENDED];
        }

        return parent::getQuicklinkgroupBottomSubmodule($module);
    }

    public function showPosttitle(array $module)
    {
        switch ($module[1]) {
            case self::MODULE_LAYOUT_PREVIEWPOST_HIGHLIGHT_CONTENT:
            case self::MODULE_LAYOUT_PREVIEWPOST_HIGHLIGHT_EDIT:
                return false;
        }

        return parent::showPosttitle($module);
    }

    public function getContentSubmodule(array $module)
    {
        switch ($module[1]) {
            case self::MODULE_LAYOUT_PREVIEWPOST_HIGHLIGHT_CONTENT:
            case self::MODULE_LAYOUT_PREVIEWPOST_HIGHLIGHT_EDIT:
                return [PoP_Module_Processor_ContentLayouts::class, PoP_Module_Processor_ContentLayouts::MODULE_LAYOUT_CONTENT_POSTCOMPACT];
        }

        return parent::getContentSubmodule($module);
    }

    public function getQuicklinkgroupTopSubmodule(array $module)
    {
        switch ($module[1]) {
            case self::MODULE_LAYOUT_PREVIEWPOST_NAVIGATOR:
            case self::MODULE_LAYOUT_PREVIEWPOST_DETAILS:
            case self::MODULE_LAYOUT_PREVIEWPOST_THUMBNAIL:
            case self::MODULE_LAYOUT_PREVIEWPOST_LIST:
            case self::MODULE_LAYOUT_PREVIEWPOST_RELATED:
            case self::MODULE_LAYOUT_PREVIEWPOST_POST_NAVIGATOR:
            case self::MODULE_LAYOUT_PREVIEWPOST_POST_DETAILS:
            case self::MODULE_LAYOUT_PREVIEWPOST_POST_THUMBNAIL:
            case self::MODULE_LAYOUT_PREVIEWPOST_POST_ADDONS:
            case self::MODULE_LAYOUT_PREVIEWPOST_POST_LIST:
            case self::MODULE_LAYOUT_PREVIEWPOST_POST_RELATED:
                return [PoP_Module_Processor_CustomQuicklinkGroups::class, PoP_Module_Processor_CustomQuicklinkGroups::MODULE_QUICKLINKGROUP_POST];
        }

        return parent::getQuicklinkgroupTopSubmodule($module);
    }

    public function getBottomSubmodules(array $module)
    {
        $ret = parent::getBottomSubmodules($module);

        switch ($module[1]) {
            case self::MODULE_LAYOUT_PREVIEWPOST_ADDONS:
            case self::MODULE_LAYOUT_PREVIEWPOST_LIST:
            case self::MODULE_LAYOUT_PREVIEWPOST_RELATED:
            case self::MODULE_LAYOUT_PREVIEWPOST_POST_LIST:
            case self::MODULE_LAYOUT_PREVIEWPOST_POST_ADDONS:
            case self::MODULE_LAYOUT_PREVIEWPOST_POST_RELATED:
                $ret[] = [PoP_Module_Processor_PublishedLayouts::class, PoP_Module_Processor_PublishedLayouts::MODULE_LAYOUT_PUBLISHED];
                break;

            case self::MODULE_LAYOUT_PREVIEWPOST_POST_THUMBNAIL:
                $ret[] = [PoP_Module_Processor_PublishedLayouts::class, PoP_Module_Processor_PublishedLayouts::MODULE_LAYOUT_PUBLISHED];
                if (PoP_ApplicationProcessors_Utils::addCategories()) {
                    $ret[] = [Wassup_Module_Processor_WidgetWrappers::class, Wassup_Module_Processor_WidgetWrappers::MODULE_LAYOUTWRAPPER_CATEGORIES];
                }
                if (PoP_ApplicationProcessors_Utils::addAppliesto()) {
                    $ret[] = [Wassup_Module_Processor_WidgetWrappers::class, Wassup_Module_Processor_WidgetWrappers::MODULE_LAYOUTWRAPPER_APPLIESTO];
                }
                break;

            case self::MODULE_LAYOUT_PREVIEWPOST_HIGHLIGHT_EDIT:
                $ret[] = [PoP_Module_Processor_Widgets::class, PoP_Module_Processor_Widgets::MODULE_WIDGET_HIGHLIGHTEDPOST_LINE];
                break;

            case self::MODULE_LAYOUT_PREVIEWPOST_DETAILS:
            case self::MODULE_LAYOUT_PREVIEWPOST_POST_DETAILS:
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
            case self::MODULE_LAYOUT_PREVIEWPOST_NAVIGATOR:
            case self::MODULE_LAYOUT_PREVIEWPOST_DETAILS:
            case self::MODULE_LAYOUT_PREVIEWPOST_THUMBNAIL:
            case self::MODULE_LAYOUT_PREVIEWPOST_POST_NAVIGATOR:
            case self::MODULE_LAYOUT_PREVIEWPOST_POST_DETAILS:
            case self::MODULE_LAYOUT_PREVIEWPOST_POST_THUMBNAIL:
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
            case self::MODULE_LAYOUT_PREVIEWPOST_EDIT:
            case self::MODULE_LAYOUT_PREVIEWPOST_POST_EDIT:
                return [GD_Custom_Module_Processor_PostThumbLayouts::class, GD_Custom_Module_Processor_PostThumbLayouts::MODULE_LAYOUT_POSTTHUMB_CROPPEDSMALL_EDIT];

            case self::MODULE_LAYOUT_PREVIEWPOST_HEADER:
            case self::MODULE_LAYOUT_PREVIEWPOST_RELATED:
            case self::MODULE_LAYOUT_PREVIEWPOST_LIST:
            case self::MODULE_LAYOUT_PREVIEWPOST_ADDONS:
            case self::MODULE_LAYOUT_PREVIEWPOST_POST_RELATED:
            case self::MODULE_LAYOUT_PREVIEWPOST_POST_LIST:
            case self::MODULE_LAYOUT_PREVIEWPOST_POST_ADDONS:
                return [GD_Custom_Module_Processor_PostThumbLayouts::class, GD_Custom_Module_Processor_PostThumbLayouts::MODULE_LAYOUT_POSTTHUMB_CROPPEDSMALL];

            case self::MODULE_LAYOUT_PREVIEWPOST_LINE:
                return [GD_Custom_Module_Processor_PostThumbLayouts::class, GD_Custom_Module_Processor_PostThumbLayouts::MODULE_LAYOUT_POSTTHUMB_XSMALL];

            case self::MODULE_LAYOUT_PREVIEWPOST_NAVIGATOR:
            case self::MODULE_LAYOUT_PREVIEWPOST_DETAILS:
            case self::MODULE_LAYOUT_PREVIEWPOST_THUMBNAIL:
            case self::MODULE_LAYOUT_PREVIEWPOST_POST_NAVIGATOR:
            case self::MODULE_LAYOUT_PREVIEWPOST_POST_DETAILS:
            case self::MODULE_LAYOUT_PREVIEWPOST_POST_THUMBNAIL:
                return [GD_Custom_Module_Processor_PostThumbLayouts::class, GD_Custom_Module_Processor_PostThumbLayouts::MODULE_LAYOUT_POSTTHUMB_CROPPEDMEDIUM];

            case self::MODULE_LAYOUT_PREVIEWPOST_HIGHLIGHT_CONTENT:
            case self::MODULE_LAYOUT_PREVIEWPOST_HIGHLIGHT_EDIT:
                return null;
        }

        return parent::getPostThumbSubmodule($module);
    }

    public function showExcerpt(array $module)
    {
        switch ($module[1]) {
            case self::MODULE_LAYOUT_PREVIEWPOST_DETAILS:
            case self::MODULE_LAYOUT_PREVIEWPOST_POST_DETAILS:
                return true;
        }

        return parent::showExcerpt($module);
    }

    public function getTitleHtmlmarkup(array $module, array &$props)
    {
        switch ($module[1]) {
            case self::MODULE_LAYOUT_PREVIEWPOST_DETAILS:
            case self::MODULE_LAYOUT_PREVIEWPOST_POST_DETAILS:
                return 'h3';

            case self::MODULE_LAYOUT_PREVIEWPOST_LINE:
                return 'span';
        }

        return parent::getTitleHtmlmarkup($module, $props);
    }

    public function getAuthorAvatarModule(array $module)
    {
        if (defined('POP_AVATARPROCESSORS_INITIALIZED')) {
            switch ($module[1]) {
                case self::MODULE_LAYOUT_PREVIEWPOST_POST_ADDONS:
                case self::MODULE_LAYOUT_PREVIEWPOST_POST_LIST:
                case self::MODULE_LAYOUT_PREVIEWPOST_POST_NAVIGATOR:
                case self::MODULE_LAYOUT_PREVIEWPOST_POST_THUMBNAIL:
                case self::MODULE_LAYOUT_PREVIEWPOST_POST_RELATED:
                    return [PoP_Module_Processor_PostAuthorAvatarLayouts::class, PoP_Module_Processor_PostAuthorAvatarLayouts::MODULE_LAYOUTPOST_AUTHORAVATAR];
            }
        }

        return parent::getAuthorAvatarModule($module);
    }

    public function authorPositions(array $module)
    {
        switch ($module[1]) {
            case self::MODULE_LAYOUT_PREVIEWPOST_NAVIGATOR:
            case self::MODULE_LAYOUT_PREVIEWPOST_ADDONS:
            case self::MODULE_LAYOUT_PREVIEWPOST_RELATED:
            case self::MODULE_LAYOUT_PREVIEWPOST_DETAILS:
            case self::MODULE_LAYOUT_PREVIEWPOST_THUMBNAIL:
            case self::MODULE_LAYOUT_PREVIEWPOST_LIST:
            case self::MODULE_LAYOUT_PREVIEWPOST_POST_NAVIGATOR:
            case self::MODULE_LAYOUT_PREVIEWPOST_POST_THUMBNAIL:
            case self::MODULE_LAYOUT_PREVIEWPOST_POST_DETAILS:
            case self::MODULE_LAYOUT_PREVIEWPOST_POST_RELATED:
            case self::MODULE_LAYOUT_PREVIEWPOST_POST_ADDONS:
            case self::MODULE_LAYOUT_PREVIEWPOST_POST_LIST:
                return array(
                    GD_CONSTANT_AUTHORPOSITION_ABOVETITLE
                );

            case self::MODULE_LAYOUT_PREVIEWPOST_HIGHLIGHT_CONTENT:
                return array(
                    GD_CONSTANT_AUTHORPOSITION_BELOWCONTENT
                );

            case self::MODULE_LAYOUT_PREVIEWPOST_HEADER:
            case self::MODULE_LAYOUT_PREVIEWPOST_EDIT:
            case self::MODULE_LAYOUT_PREVIEWPOST_HIGHLIGHT_EDIT:
            case self::MODULE_LAYOUT_PREVIEWPOST_HIGHLIGHT_CONTENT:
            case self::MODULE_LAYOUT_PREVIEWPOST_POST_EDIT:
            case self::MODULE_LAYOUT_PREVIEWPOST_LINE:
                return array();
        }

        return parent::authorPositions($module);
    }

    public function horizontalLayout(array $module)
    {
        switch ($module[1]) {
            case self::MODULE_LAYOUT_PREVIEWPOST_DETAILS:
            case self::MODULE_LAYOUT_PREVIEWPOST_POST_DETAILS:
                return true;
        }

        return parent::horizontalLayout($module);
    }

    public function horizontalMediaLayout(array $module)
    {
        switch ($module[1]) {
            case self::MODULE_LAYOUT_PREVIEWPOST_HEADER:
            case self::MODULE_LAYOUT_PREVIEWPOST_RELATED:
            case self::MODULE_LAYOUT_PREVIEWPOST_EDIT:
            case self::MODULE_LAYOUT_PREVIEWPOST_LIST:
            case self::MODULE_LAYOUT_PREVIEWPOST_ADDONS:
            case self::MODULE_LAYOUT_PREVIEWPOST_LINE:
            case self::MODULE_LAYOUT_PREVIEWPOST_POST_RELATED:
            case self::MODULE_LAYOUT_PREVIEWPOST_POST_ADDONS:
            case self::MODULE_LAYOUT_PREVIEWPOST_POST_LIST:
            case self::MODULE_LAYOUT_PREVIEWPOST_POST_EDIT:
                return true;
        }

        return parent::horizontalMediaLayout($module);
    }

    public function getImmutableConfiguration(array $module, array &$props): array
    {
        $ret = parent::getImmutableConfiguration($module, $props);

        switch ($module[1]) {
            case self::MODULE_LAYOUT_PREVIEWPOST_HIGHLIGHT_CONTENT:
                $ret[GD_JS_CLASSES]['authors'] = 'pull-right authors-bottom';
                break;
        }

        switch ($module[1]) {
            case self::MODULE_LAYOUT_PREVIEWPOST_NAVIGATOR:
            case self::MODULE_LAYOUT_PREVIEWPOST_DETAILS:
            case self::MODULE_LAYOUT_PREVIEWPOST_THUMBNAIL:
            case self::MODULE_LAYOUT_PREVIEWPOST_POST_NAVIGATOR:
            case self::MODULE_LAYOUT_PREVIEWPOST_POST_DETAILS:
            case self::MODULE_LAYOUT_PREVIEWPOST_POST_THUMBNAIL:
                $ret[GD_JS_CLASSES]['belowthumb'] = 'bg-info text-info belowthumb';
                break;

            case self::MODULE_LAYOUT_PREVIEWPOST_HIGHLIGHT_EDIT:
                $ret[GD_JS_CLASSES]['content'] = 'well';
                break;
        }

        switch ($module[1]) {
            case self::MODULE_LAYOUT_PREVIEWPOST_DETAILS:
            case self::MODULE_LAYOUT_PREVIEWPOST_POST_DETAILS:
                $ret[GD_JS_CLASSES]['thumb'] = 'pop-thumb-framed';
                break;
        }

        return $ret;
    }

    public function getTitleBeforeauthors(array $module, array &$props)
    {
        switch ($module[1]) {
            case self::MODULE_LAYOUT_PREVIEWPOST_HIGHLIGHT_CONTENT:
                return array(
                    'belowcontent' => TranslationAPIFacade::getInstance()->__('added by', 'poptheme-wassup')
                );
        }

        return parent::getTitleBeforeauthors($module, $props);
    }

    public function initModelProps(array $module, array &$props): void
    {
        switch ($module[1]) {
            case self::MODULE_LAYOUT_PREVIEWPOST_HEADER:
                $this->appendProp($module, $props, 'class', 'alert alert-info alert-sm');
                break;

            case self::MODULE_LAYOUT_PREVIEWPOST_HIGHLIGHT_CONTENT:
                $this->appendProp($module, $props, 'class', 'well well-highlight');
                break;
        }

        parent::initModelProps($module, $props);
    }
}



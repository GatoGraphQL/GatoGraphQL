<?php

class PoPTheme_Wassup_AE_Module_Processor_PreviewPostLayouts extends PoP_Module_Processor_CustomPreviewPostLayoutsBase
{
    public final const MODULE_LAYOUT_AUTOMATEDEMAILS_PREVIEWPOST_POST_DETAILS = 'layout-automatedemails-previewpost-post-details';
    public final const MODULE_LAYOUT_AUTOMATEDEMAILS_PREVIEWPOST_POST_THUMBNAIL = 'layout-automatedemails-previewpost-post-thumbnail';
    public final const MODULE_LAYOUT_AUTOMATEDEMAILS_PREVIEWPOST_POST_LIST = 'layout-automatedemails-previewpost-post-list';

    public function getComponentVariationsToProcess(): array
    {
        return array(
            [self::class, self::MODULE_LAYOUT_AUTOMATEDEMAILS_PREVIEWPOST_POST_DETAILS],
            [self::class, self::MODULE_LAYOUT_AUTOMATEDEMAILS_PREVIEWPOST_POST_THUMBNAIL],
            [self::class, self::MODULE_LAYOUT_AUTOMATEDEMAILS_PREVIEWPOST_POST_LIST],
        );
    }


    public function getAuthorModule(array $module)
    {
        switch ($module[1]) {
            case self::MODULE_LAYOUT_AUTOMATEDEMAILS_PREVIEWPOST_POST_DETAILS:
            case self::MODULE_LAYOUT_AUTOMATEDEMAILS_PREVIEWPOST_POST_THUMBNAIL:
            case self::MODULE_LAYOUT_AUTOMATEDEMAILS_PREVIEWPOST_POST_LIST:
                return [PoP_Module_Processor_PostAuthorNameLayouts::class, PoP_Module_Processor_PostAuthorNameLayouts::MODULE_LAYOUTPOST_AUTHORNAME];
        }

        return parent::getAuthorModule($module);
    }

    public function getQuicklinkgroupBottomSubmodule(array $module)
    {
        switch ($module[1]) {
            case self::MODULE_LAYOUT_AUTOMATEDEMAILS_PREVIEWPOST_POST_DETAILS:
            case self::MODULE_LAYOUT_AUTOMATEDEMAILS_PREVIEWPOST_POST_THUMBNAIL:
                return [PoP_Module_Processor_QuicklinkButtonGroups::class, PoP_Module_Processor_QuicklinkButtonGroups::MODULE_QUICKLINKBUTTONGROUP_COMMENTS_LABEL];
        }

        return parent::getQuicklinkgroupBottomSubmodule($module);
    }

    public function getBelowthumbLayoutSubmodules(array $module)
    {
        $ret = parent::getBelowthumbLayoutSubmodules($module);

        switch ($module[1]) {
            case self::MODULE_LAYOUT_AUTOMATEDEMAILS_PREVIEWPOST_POST_DETAILS:
                $ret[] = [PoP_Module_Processor_PublishedLayouts::class, PoP_Module_Processor_PublishedLayouts::MODULE_LAYOUT_PUBLISHED];
                break;
        }

        return $ret;
    }

    public function getBottomSubmodules(array $module)
    {
        $ret = parent::getBottomSubmodules($module);

        switch ($module[1]) {
            case self::MODULE_LAYOUT_AUTOMATEDEMAILS_PREVIEWPOST_POST_LIST:
                $ret[] = [PoP_Module_Processor_QuicklinkButtonGroups::class, PoP_Module_Processor_QuicklinkButtonGroups::MODULE_QUICKLINKBUTTONGROUP_COMMENTS_LABEL];
                break;
        }

        return $ret;
    }

    public function getAbovecontentSubmodules(array $module)
    {
        $ret = parent::getAbovecontentSubmodules($module);

        switch ($module[1]) {
            case self::MODULE_LAYOUT_AUTOMATEDEMAILS_PREVIEWPOST_POST_LIST:
                $ret[] = [PoP_Module_Processor_PublishedLayouts::class, PoP_Module_Processor_PublishedLayouts::MODULE_LAYOUT_PUBLISHED];
                break;
        }

        return $ret;
    }

    public function getPostThumbSubmodule(array $module)
    {
        switch ($module[1]) {
            case self::MODULE_LAYOUT_AUTOMATEDEMAILS_PREVIEWPOST_POST_LIST:
                return [GD_Custom_Module_Processor_PostThumbLayouts::class, GD_Custom_Module_Processor_PostThumbLayouts::MODULE_LAYOUT_POSTTHUMB_CROPPEDSMALL];

            case self::MODULE_LAYOUT_AUTOMATEDEMAILS_PREVIEWPOST_POST_DETAILS:
            case self::MODULE_LAYOUT_AUTOMATEDEMAILS_PREVIEWPOST_POST_THUMBNAIL:
                return [GD_Custom_Module_Processor_PostThumbLayouts::class, GD_Custom_Module_Processor_PostThumbLayouts::MODULE_LAYOUT_POSTTHUMB_CROPPEDMEDIUM];
        }

        return parent::getPostThumbSubmodule($module);
    }

    public function showExcerpt(array $module)
    {
        switch ($module[1]) {
            case self::MODULE_LAYOUT_AUTOMATEDEMAILS_PREVIEWPOST_POST_LIST:
            case self::MODULE_LAYOUT_AUTOMATEDEMAILS_PREVIEWPOST_POST_DETAILS:
                return true;
        }

        return parent::showExcerpt($module);
    }

    public function getTitleHtmlmarkup(array $module, array &$props)
    {
        switch ($module[1]) {
            case self::MODULE_LAYOUT_AUTOMATEDEMAILS_PREVIEWPOST_POST_LIST:
            case self::MODULE_LAYOUT_AUTOMATEDEMAILS_PREVIEWPOST_POST_THUMBNAIL:
            case self::MODULE_LAYOUT_AUTOMATEDEMAILS_PREVIEWPOST_POST_DETAILS:
                return 'h3';
        }

        return parent::getTitleHtmlmarkup($module, $props);
    }

    public function authorPositions(array $module)
    {
        switch ($module[1]) {
            case self::MODULE_LAYOUT_AUTOMATEDEMAILS_PREVIEWPOST_POST_DETAILS:
            case self::MODULE_LAYOUT_AUTOMATEDEMAILS_PREVIEWPOST_POST_THUMBNAIL:
            case self::MODULE_LAYOUT_AUTOMATEDEMAILS_PREVIEWPOST_POST_LIST:
                return array(
                    GD_CONSTANT_AUTHORPOSITION_ABOVETITLE,
                );
        }

        return parent::authorPositions($module);
    }

    public function horizontalLayout(array $module)
    {
        switch ($module[1]) {
            case self::MODULE_LAYOUT_AUTOMATEDEMAILS_PREVIEWPOST_POST_DETAILS:
                return true;
        }

        return parent::horizontalLayout($module);
    }

    public function horizontalMediaLayout(array $module)
    {
        switch ($module[1]) {
            case self::MODULE_LAYOUT_AUTOMATEDEMAILS_PREVIEWPOST_POST_LIST:
                return true;
        }

        return parent::horizontalMediaLayout($module);
    }
    

    public function getImmutableConfiguration(array $module, array &$props): array
    {
        $ret = parent::getImmutableConfiguration($module, $props);

        switch ($module[1]) {
            case self::MODULE_LAYOUT_AUTOMATEDEMAILS_PREVIEWPOST_POST_LIST:
                $ret[GD_JS_CLASSES]['excerpt'] = 'email-excerpt';
                $ret[GD_JS_CLASSES]['authors-abovetitle'] = 'email-authors-abovetitle';
                break;

            case self::MODULE_LAYOUT_AUTOMATEDEMAILS_PREVIEWPOST_POST_DETAILS:
            case self::MODULE_LAYOUT_AUTOMATEDEMAILS_PREVIEWPOST_POST_THUMBNAIL:
                $ret[GD_JS_CLASSES]['belowthumb'] = 'bg-info text-info belowthumb';
                break;
        }

        switch ($module[1]) {
            case self::MODULE_LAYOUT_AUTOMATEDEMAILS_PREVIEWPOST_POST_DETAILS:
                $ret[GD_JS_CLASSES]['thumb'] = 'pop-thumb-framed';
                break;
        }

        return $ret;
    }
}



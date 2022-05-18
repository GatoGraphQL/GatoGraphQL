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


    public function getAuthorModule(array $componentVariation)
    {
        switch ($componentVariation[1]) {
            case self::MODULE_LAYOUT_AUTOMATEDEMAILS_PREVIEWPOST_POST_DETAILS:
            case self::MODULE_LAYOUT_AUTOMATEDEMAILS_PREVIEWPOST_POST_THUMBNAIL:
            case self::MODULE_LAYOUT_AUTOMATEDEMAILS_PREVIEWPOST_POST_LIST:
                return [PoP_Module_Processor_PostAuthorNameLayouts::class, PoP_Module_Processor_PostAuthorNameLayouts::MODULE_LAYOUTPOST_AUTHORNAME];
        }

        return parent::getAuthorModule($componentVariation);
    }

    public function getQuicklinkgroupBottomSubmodule(array $componentVariation)
    {
        switch ($componentVariation[1]) {
            case self::MODULE_LAYOUT_AUTOMATEDEMAILS_PREVIEWPOST_POST_DETAILS:
            case self::MODULE_LAYOUT_AUTOMATEDEMAILS_PREVIEWPOST_POST_THUMBNAIL:
                return [PoP_Module_Processor_QuicklinkButtonGroups::class, PoP_Module_Processor_QuicklinkButtonGroups::MODULE_QUICKLINKBUTTONGROUP_COMMENTS_LABEL];
        }

        return parent::getQuicklinkgroupBottomSubmodule($componentVariation);
    }

    public function getBelowthumbLayoutSubmodules(array $componentVariation)
    {
        $ret = parent::getBelowthumbLayoutSubmodules($componentVariation);

        switch ($componentVariation[1]) {
            case self::MODULE_LAYOUT_AUTOMATEDEMAILS_PREVIEWPOST_POST_DETAILS:
                $ret[] = [PoP_Module_Processor_PublishedLayouts::class, PoP_Module_Processor_PublishedLayouts::MODULE_LAYOUT_PUBLISHED];
                break;
        }

        return $ret;
    }

    public function getBottomSubmodules(array $componentVariation)
    {
        $ret = parent::getBottomSubmodules($componentVariation);

        switch ($componentVariation[1]) {
            case self::MODULE_LAYOUT_AUTOMATEDEMAILS_PREVIEWPOST_POST_LIST:
                $ret[] = [PoP_Module_Processor_QuicklinkButtonGroups::class, PoP_Module_Processor_QuicklinkButtonGroups::MODULE_QUICKLINKBUTTONGROUP_COMMENTS_LABEL];
                break;
        }

        return $ret;
    }

    public function getAbovecontentSubmodules(array $componentVariation)
    {
        $ret = parent::getAbovecontentSubmodules($componentVariation);

        switch ($componentVariation[1]) {
            case self::MODULE_LAYOUT_AUTOMATEDEMAILS_PREVIEWPOST_POST_LIST:
                $ret[] = [PoP_Module_Processor_PublishedLayouts::class, PoP_Module_Processor_PublishedLayouts::MODULE_LAYOUT_PUBLISHED];
                break;
        }

        return $ret;
    }

    public function getPostThumbSubmodule(array $componentVariation)
    {
        switch ($componentVariation[1]) {
            case self::MODULE_LAYOUT_AUTOMATEDEMAILS_PREVIEWPOST_POST_LIST:
                return [GD_Custom_Module_Processor_PostThumbLayouts::class, GD_Custom_Module_Processor_PostThumbLayouts::MODULE_LAYOUT_POSTTHUMB_CROPPEDSMALL];

            case self::MODULE_LAYOUT_AUTOMATEDEMAILS_PREVIEWPOST_POST_DETAILS:
            case self::MODULE_LAYOUT_AUTOMATEDEMAILS_PREVIEWPOST_POST_THUMBNAIL:
                return [GD_Custom_Module_Processor_PostThumbLayouts::class, GD_Custom_Module_Processor_PostThumbLayouts::MODULE_LAYOUT_POSTTHUMB_CROPPEDMEDIUM];
        }

        return parent::getPostThumbSubmodule($componentVariation);
    }

    public function showExcerpt(array $componentVariation)
    {
        switch ($componentVariation[1]) {
            case self::MODULE_LAYOUT_AUTOMATEDEMAILS_PREVIEWPOST_POST_LIST:
            case self::MODULE_LAYOUT_AUTOMATEDEMAILS_PREVIEWPOST_POST_DETAILS:
                return true;
        }

        return parent::showExcerpt($componentVariation);
    }

    public function getTitleHtmlmarkup(array $componentVariation, array &$props)
    {
        switch ($componentVariation[1]) {
            case self::MODULE_LAYOUT_AUTOMATEDEMAILS_PREVIEWPOST_POST_LIST:
            case self::MODULE_LAYOUT_AUTOMATEDEMAILS_PREVIEWPOST_POST_THUMBNAIL:
            case self::MODULE_LAYOUT_AUTOMATEDEMAILS_PREVIEWPOST_POST_DETAILS:
                return 'h3';
        }

        return parent::getTitleHtmlmarkup($componentVariation, $props);
    }

    public function authorPositions(array $componentVariation)
    {
        switch ($componentVariation[1]) {
            case self::MODULE_LAYOUT_AUTOMATEDEMAILS_PREVIEWPOST_POST_DETAILS:
            case self::MODULE_LAYOUT_AUTOMATEDEMAILS_PREVIEWPOST_POST_THUMBNAIL:
            case self::MODULE_LAYOUT_AUTOMATEDEMAILS_PREVIEWPOST_POST_LIST:
                return array(
                    GD_CONSTANT_AUTHORPOSITION_ABOVETITLE,
                );
        }

        return parent::authorPositions($componentVariation);
    }

    public function horizontalLayout(array $componentVariation)
    {
        switch ($componentVariation[1]) {
            case self::MODULE_LAYOUT_AUTOMATEDEMAILS_PREVIEWPOST_POST_DETAILS:
                return true;
        }

        return parent::horizontalLayout($componentVariation);
    }

    public function horizontalMediaLayout(array $componentVariation)
    {
        switch ($componentVariation[1]) {
            case self::MODULE_LAYOUT_AUTOMATEDEMAILS_PREVIEWPOST_POST_LIST:
                return true;
        }

        return parent::horizontalMediaLayout($componentVariation);
    }
    

    public function getImmutableConfiguration(array $componentVariation, array &$props): array
    {
        $ret = parent::getImmutableConfiguration($componentVariation, $props);

        switch ($componentVariation[1]) {
            case self::MODULE_LAYOUT_AUTOMATEDEMAILS_PREVIEWPOST_POST_LIST:
                $ret[GD_JS_CLASSES]['excerpt'] = 'email-excerpt';
                $ret[GD_JS_CLASSES]['authors-abovetitle'] = 'email-authors-abovetitle';
                break;

            case self::MODULE_LAYOUT_AUTOMATEDEMAILS_PREVIEWPOST_POST_DETAILS:
            case self::MODULE_LAYOUT_AUTOMATEDEMAILS_PREVIEWPOST_POST_THUMBNAIL:
                $ret[GD_JS_CLASSES]['belowthumb'] = 'bg-info text-info belowthumb';
                break;
        }

        switch ($componentVariation[1]) {
            case self::MODULE_LAYOUT_AUTOMATEDEMAILS_PREVIEWPOST_POST_DETAILS:
                $ret[GD_JS_CLASSES]['thumb'] = 'pop-thumb-framed';
                break;
        }

        return $ret;
    }
}



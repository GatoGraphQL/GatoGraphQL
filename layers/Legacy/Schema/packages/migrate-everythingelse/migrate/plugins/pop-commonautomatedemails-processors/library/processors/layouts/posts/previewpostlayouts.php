<?php

class PoPTheme_Wassup_AE_Module_Processor_PreviewPostLayouts extends PoP_Module_Processor_CustomPreviewPostLayoutsBase
{
    public final const COMPONENT_LAYOUT_AUTOMATEDEMAILS_PREVIEWPOST_POST_DETAILS = 'layout-automatedemails-previewpost-post-details';
    public final const COMPONENT_LAYOUT_AUTOMATEDEMAILS_PREVIEWPOST_POST_THUMBNAIL = 'layout-automatedemails-previewpost-post-thumbnail';
    public final const COMPONENT_LAYOUT_AUTOMATEDEMAILS_PREVIEWPOST_POST_LIST = 'layout-automatedemails-previewpost-post-list';

    public function getComponentsToProcess(): array
    {
        return array(
            [self::class, self::COMPONENT_LAYOUT_AUTOMATEDEMAILS_PREVIEWPOST_POST_DETAILS],
            [self::class, self::COMPONENT_LAYOUT_AUTOMATEDEMAILS_PREVIEWPOST_POST_THUMBNAIL],
            [self::class, self::COMPONENT_LAYOUT_AUTOMATEDEMAILS_PREVIEWPOST_POST_LIST],
        );
    }


    public function getAuthorModule(array $component)
    {
        switch ($component[1]) {
            case self::COMPONENT_LAYOUT_AUTOMATEDEMAILS_PREVIEWPOST_POST_DETAILS:
            case self::COMPONENT_LAYOUT_AUTOMATEDEMAILS_PREVIEWPOST_POST_THUMBNAIL:
            case self::COMPONENT_LAYOUT_AUTOMATEDEMAILS_PREVIEWPOST_POST_LIST:
                return [PoP_Module_Processor_PostAuthorNameLayouts::class, PoP_Module_Processor_PostAuthorNameLayouts::COMPONENT_LAYOUTPOST_AUTHORNAME];
        }

        return parent::getAuthorModule($component);
    }

    public function getQuicklinkgroupBottomSubmodule(array $component)
    {
        switch ($component[1]) {
            case self::COMPONENT_LAYOUT_AUTOMATEDEMAILS_PREVIEWPOST_POST_DETAILS:
            case self::COMPONENT_LAYOUT_AUTOMATEDEMAILS_PREVIEWPOST_POST_THUMBNAIL:
                return [PoP_Module_Processor_QuicklinkButtonGroups::class, PoP_Module_Processor_QuicklinkButtonGroups::COMPONENT_QUICKLINKBUTTONGROUP_COMMENTS_LABEL];
        }

        return parent::getQuicklinkgroupBottomSubmodule($component);
    }

    public function getBelowthumbLayoutSubmodules(array $component)
    {
        $ret = parent::getBelowthumbLayoutSubmodules($component);

        switch ($component[1]) {
            case self::COMPONENT_LAYOUT_AUTOMATEDEMAILS_PREVIEWPOST_POST_DETAILS:
                $ret[] = [PoP_Module_Processor_PublishedLayouts::class, PoP_Module_Processor_PublishedLayouts::COMPONENT_LAYOUT_PUBLISHED];
                break;
        }

        return $ret;
    }

    public function getBottomSubmodules(array $component)
    {
        $ret = parent::getBottomSubmodules($component);

        switch ($component[1]) {
            case self::COMPONENT_LAYOUT_AUTOMATEDEMAILS_PREVIEWPOST_POST_LIST:
                $ret[] = [PoP_Module_Processor_QuicklinkButtonGroups::class, PoP_Module_Processor_QuicklinkButtonGroups::COMPONENT_QUICKLINKBUTTONGROUP_COMMENTS_LABEL];
                break;
        }

        return $ret;
    }

    public function getAbovecontentSubmodules(array $component)
    {
        $ret = parent::getAbovecontentSubmodules($component);

        switch ($component[1]) {
            case self::COMPONENT_LAYOUT_AUTOMATEDEMAILS_PREVIEWPOST_POST_LIST:
                $ret[] = [PoP_Module_Processor_PublishedLayouts::class, PoP_Module_Processor_PublishedLayouts::COMPONENT_LAYOUT_PUBLISHED];
                break;
        }

        return $ret;
    }

    public function getPostThumbSubmodule(array $component)
    {
        switch ($component[1]) {
            case self::COMPONENT_LAYOUT_AUTOMATEDEMAILS_PREVIEWPOST_POST_LIST:
                return [GD_Custom_Module_Processor_PostThumbLayouts::class, GD_Custom_Module_Processor_PostThumbLayouts::COMPONENT_LAYOUT_POSTTHUMB_CROPPEDSMALL];

            case self::COMPONENT_LAYOUT_AUTOMATEDEMAILS_PREVIEWPOST_POST_DETAILS:
            case self::COMPONENT_LAYOUT_AUTOMATEDEMAILS_PREVIEWPOST_POST_THUMBNAIL:
                return [GD_Custom_Module_Processor_PostThumbLayouts::class, GD_Custom_Module_Processor_PostThumbLayouts::COMPONENT_LAYOUT_POSTTHUMB_CROPPEDMEDIUM];
        }

        return parent::getPostThumbSubmodule($component);
    }

    public function showExcerpt(array $component)
    {
        switch ($component[1]) {
            case self::COMPONENT_LAYOUT_AUTOMATEDEMAILS_PREVIEWPOST_POST_LIST:
            case self::COMPONENT_LAYOUT_AUTOMATEDEMAILS_PREVIEWPOST_POST_DETAILS:
                return true;
        }

        return parent::showExcerpt($component);
    }

    public function getTitleHtmlmarkup(array $component, array &$props)
    {
        switch ($component[1]) {
            case self::COMPONENT_LAYOUT_AUTOMATEDEMAILS_PREVIEWPOST_POST_LIST:
            case self::COMPONENT_LAYOUT_AUTOMATEDEMAILS_PREVIEWPOST_POST_THUMBNAIL:
            case self::COMPONENT_LAYOUT_AUTOMATEDEMAILS_PREVIEWPOST_POST_DETAILS:
                return 'h3';
        }

        return parent::getTitleHtmlmarkup($component, $props);
    }

    public function authorPositions(array $component)
    {
        switch ($component[1]) {
            case self::COMPONENT_LAYOUT_AUTOMATEDEMAILS_PREVIEWPOST_POST_DETAILS:
            case self::COMPONENT_LAYOUT_AUTOMATEDEMAILS_PREVIEWPOST_POST_THUMBNAIL:
            case self::COMPONENT_LAYOUT_AUTOMATEDEMAILS_PREVIEWPOST_POST_LIST:
                return array(
                    GD_CONSTANT_AUTHORPOSITION_ABOVETITLE,
                );
        }

        return parent::authorPositions($component);
    }

    public function horizontalLayout(array $component)
    {
        switch ($component[1]) {
            case self::COMPONENT_LAYOUT_AUTOMATEDEMAILS_PREVIEWPOST_POST_DETAILS:
                return true;
        }

        return parent::horizontalLayout($component);
    }

    public function horizontalMediaLayout(array $component)
    {
        switch ($component[1]) {
            case self::COMPONENT_LAYOUT_AUTOMATEDEMAILS_PREVIEWPOST_POST_LIST:
                return true;
        }

        return parent::horizontalMediaLayout($component);
    }
    

    public function getImmutableConfiguration(array $component, array &$props): array
    {
        $ret = parent::getImmutableConfiguration($component, $props);

        switch ($component[1]) {
            case self::COMPONENT_LAYOUT_AUTOMATEDEMAILS_PREVIEWPOST_POST_LIST:
                $ret[GD_JS_CLASSES]['excerpt'] = 'email-excerpt';
                $ret[GD_JS_CLASSES]['authors-abovetitle'] = 'email-authors-abovetitle';
                break;

            case self::COMPONENT_LAYOUT_AUTOMATEDEMAILS_PREVIEWPOST_POST_DETAILS:
            case self::COMPONENT_LAYOUT_AUTOMATEDEMAILS_PREVIEWPOST_POST_THUMBNAIL:
                $ret[GD_JS_CLASSES]['belowthumb'] = 'bg-info text-info belowthumb';
                break;
        }

        switch ($component[1]) {
            case self::COMPONENT_LAYOUT_AUTOMATEDEMAILS_PREVIEWPOST_POST_DETAILS:
                $ret[GD_JS_CLASSES]['thumb'] = 'pop-thumb-framed';
                break;
        }

        return $ret;
    }
}



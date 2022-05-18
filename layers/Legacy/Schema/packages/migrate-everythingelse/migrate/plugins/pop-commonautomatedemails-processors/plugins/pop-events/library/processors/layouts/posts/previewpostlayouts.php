<?php

class PoPTheme_Wassup_EM_AE_Module_Processor_PreviewPostLayouts extends PoP_Module_Processor_CustomPreviewPostLayoutsBase
{
    public final const MODULE_LAYOUT_AUTOMATEDEMAILS_PREVIEWPOST_EVENT_DETAILS = 'layout-automatedemails-previewpost-event-details';
    public final const MODULE_LAYOUT_AUTOMATEDEMAILS_PREVIEWPOST_EVENT_THUMBNAIL = 'layout-automatedemails-previewpost-event-thumbnail';
    public final const MODULE_LAYOUT_AUTOMATEDEMAILS_PREVIEWPOST_EVENT_LIST = 'layout-automatedemails-previewpost-event-list';
    
    public function getComponentsToProcess(): array
    {
        return array(
            [self::class, self::MODULE_LAYOUT_AUTOMATEDEMAILS_PREVIEWPOST_EVENT_DETAILS],
            [self::class, self::MODULE_LAYOUT_AUTOMATEDEMAILS_PREVIEWPOST_EVENT_THUMBNAIL],
            [self::class, self::MODULE_LAYOUT_AUTOMATEDEMAILS_PREVIEWPOST_EVENT_LIST],
        );
    }


    public function getAuthorModule(array $component)
    {
        switch ($component[1]) {
            case self::MODULE_LAYOUT_AUTOMATEDEMAILS_PREVIEWPOST_EVENT_DETAILS:
            case self::MODULE_LAYOUT_AUTOMATEDEMAILS_PREVIEWPOST_EVENT_THUMBNAIL:
            case self::MODULE_LAYOUT_AUTOMATEDEMAILS_PREVIEWPOST_EVENT_LIST:

                return [PoP_Module_Processor_PostAuthorNameLayouts::class, PoP_Module_Processor_PostAuthorNameLayouts::MODULE_LAYOUTPOST_AUTHORNAME];
        }

        return parent::getAuthorModule($component);
    }

    public function getQuicklinkgroupBottomSubmodule(array $component)
    {
        switch ($component[1]) {
            case self::MODULE_LAYOUT_AUTOMATEDEMAILS_PREVIEWPOST_EVENT_DETAILS:
            case self::MODULE_LAYOUT_AUTOMATEDEMAILS_PREVIEWPOST_EVENT_THUMBNAIL:
                return [PoPTheme_Wassup_EM_AE_Module_Processor_QuicklinkGroups::class, PoPTheme_Wassup_EM_AE_Module_Processor_QuicklinkGroups::MODULE_QUICKLINKGROUP_EVENTBOTTOM];
        }

        return parent::getQuicklinkgroupBottomSubmodule($component);
    }

    public function getBelowthumbLayoutSubmodules(array $component)
    {
        $ret = parent::getBelowthumbLayoutSubmodules($component);

        switch ($component[1]) {
            case self::MODULE_LAYOUT_AUTOMATEDEMAILS_PREVIEWPOST_EVENT_THUMBNAIL:
                $ret[] = [GD_EM_Module_Processor_DateTimeLayouts::class, GD_EM_Module_Processor_DateTimeLayouts::MODULE_EM_LAYOUT_DATETIME];
                $ret[] = [PoP_Module_Processor_LocationViewComponentButtonWrapperss::class, PoP_Module_Processor_LocationViewComponentButtonWrapperss::MODULE_VIEWCOMPONENT_BUTTONWRAPPER_POSTLOCATIONS];
                break;

            case self::MODULE_LAYOUT_AUTOMATEDEMAILS_PREVIEWPOST_EVENT_DETAILS:
                $ret[] = [GD_EM_Module_Processor_DateTimeLayouts::class, GD_EM_Module_Processor_DateTimeLayouts::MODULE_EM_LAYOUT_DATETIME];
                $ret[] = [PoP_Module_Processor_LocationViewComponentButtonWrapperss::class, PoP_Module_Processor_LocationViewComponentButtonWrapperss::MODULE_VIEWCOMPONENT_BUTTONWRAPPER_POSTLOCATIONS];
                break;
        }

        return $ret;
    }

    public function getBottomSubmodules(array $component)
    {
        $ret = parent::getBottomSubmodules($component);

        switch ($component[1]) {
            case self::MODULE_LAYOUT_AUTOMATEDEMAILS_PREVIEWPOST_EVENT_LIST:
                $ret[] = [PoPTheme_Wassup_EM_AE_Module_Processor_QuicklinkGroups::class, PoPTheme_Wassup_EM_AE_Module_Processor_QuicklinkGroups::MODULE_QUICKLINKGROUP_EVENTBOTTOM];
                break;
        }

        return $ret;
    }

    public function getAbovecontentSubmodules(array $component)
    {
        $ret = parent::getAbovecontentSubmodules($component);

        switch ($component[1]) {
            case self::MODULE_LAYOUT_AUTOMATEDEMAILS_PREVIEWPOST_EVENT_LIST:
                $ret[] = [GD_EM_Module_Processor_DateTimeLayouts::class, GD_EM_Module_Processor_DateTimeLayouts::MODULE_EM_LAYOUT_DATETIMEHORIZONTAL];
                $ret[] = [PoP_Module_Processor_LocationViewComponentButtonWrapperss::class, PoP_Module_Processor_LocationViewComponentButtonWrapperss::MODULE_VIEWCOMPONENT_BUTTONWRAPPER_POSTLOCATIONS];
                break;
        }

        return $ret;
    }

    public function getPostThumbSubmodule(array $component)
    {
        switch ($component[1]) {
            case self::MODULE_LAYOUT_AUTOMATEDEMAILS_PREVIEWPOST_EVENT_LIST:
                return [GD_Custom_Module_Processor_PostThumbLayouts::class, GD_Custom_Module_Processor_PostThumbLayouts::MODULE_LAYOUT_POSTTHUMB_CROPPEDSMALL_VOLUNTEER];

            case self::MODULE_LAYOUT_AUTOMATEDEMAILS_PREVIEWPOST_EVENT_DETAILS:
            case self::MODULE_LAYOUT_AUTOMATEDEMAILS_PREVIEWPOST_EVENT_THUMBNAIL:
                return [GD_Custom_Module_Processor_PostThumbLayouts::class, GD_Custom_Module_Processor_PostThumbLayouts::MODULE_LAYOUT_POSTTHUMB_CROPPEDMEDIUM_VOLUNTEER];
        }

        return parent::getPostThumbSubmodule($component);
    }

    public function showExcerpt(array $component)
    {
        switch ($component[1]) {
            case self::MODULE_LAYOUT_AUTOMATEDEMAILS_PREVIEWPOST_EVENT_LIST:
            case self::MODULE_LAYOUT_AUTOMATEDEMAILS_PREVIEWPOST_EVENT_DETAILS:

                return true;
        }

        return parent::showExcerpt($component);
    }

    public function getTitleHtmlmarkup(array $component, array &$props)
    {
        switch ($component[1]) {
            case self::MODULE_LAYOUT_AUTOMATEDEMAILS_PREVIEWPOST_EVENT_LIST:
            case self::MODULE_LAYOUT_AUTOMATEDEMAILS_PREVIEWPOST_EVENT_THUMBNAIL:
            case self::MODULE_LAYOUT_AUTOMATEDEMAILS_PREVIEWPOST_EVENT_DETAILS:
            
                return 'h3';
        }

        return parent::getTitleHtmlmarkup($component, $props);
    }

    public function authorPositions(array $component)
    {
        switch ($component[1]) {
            case self::MODULE_LAYOUT_AUTOMATEDEMAILS_PREVIEWPOST_EVENT_DETAILS:
            case self::MODULE_LAYOUT_AUTOMATEDEMAILS_PREVIEWPOST_EVENT_THUMBNAIL:
            case self::MODULE_LAYOUT_AUTOMATEDEMAILS_PREVIEWPOST_EVENT_LIST:

                return array(
                    GD_CONSTANT_AUTHORPOSITION_ABOVETITLE,
                );
        }

        return parent::authorPositions($component);
    }

    public function horizontalLayout(array $component)
    {
        switch ($component[1]) {
            case self::MODULE_LAYOUT_AUTOMATEDEMAILS_PREVIEWPOST_EVENT_DETAILS:
                
                return true;
        }

        return parent::horizontalLayout($component);
    }

    public function horizontalMediaLayout(array $component)
    {
        switch ($component[1]) {
            case self::MODULE_LAYOUT_AUTOMATEDEMAILS_PREVIEWPOST_EVENT_LIST:

                return true;
        }

        return parent::horizontalMediaLayout($component);
    }
    

    public function getImmutableConfiguration(array $component, array &$props): array
    {
        $ret = parent::getImmutableConfiguration($component, $props);

        switch ($component[1]) {
            case self::MODULE_LAYOUT_AUTOMATEDEMAILS_PREVIEWPOST_EVENT_LIST:
                $ret[GD_JS_CLASSES]['excerpt'] = 'email-excerpt';
                $ret[GD_JS_CLASSES]['authors-abovetitle'] = 'email-authors-abovetitle';
                break;

            case self::MODULE_LAYOUT_AUTOMATEDEMAILS_PREVIEWPOST_EVENT_DETAILS:
            case self::MODULE_LAYOUT_AUTOMATEDEMAILS_PREVIEWPOST_EVENT_THUMBNAIL:
            
                $ret[GD_JS_CLASSES]['belowthumb'] = 'bg-info text-info belowthumb';
                break;
        }

        switch ($component[1]) {
            case self::MODULE_LAYOUT_AUTOMATEDEMAILS_PREVIEWPOST_EVENT_DETAILS:
            
                $ret[GD_JS_CLASSES]['thumb'] = 'pop-thumb-framed';
                break;
        }

        return $ret;
    }
}



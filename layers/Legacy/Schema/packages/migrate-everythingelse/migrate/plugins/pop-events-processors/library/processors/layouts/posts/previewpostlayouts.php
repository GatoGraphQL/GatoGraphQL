<?php
use PoP\Root\Facades\Translation\TranslationAPIFacade;

class GD_EM_Module_Processor_CustomPreviewPostLayouts extends PoP_Module_Processor_CustomPreviewPostLayoutsBase
{
    public final const COMPONENT_LAYOUT_PREVIEWPOST_EVENT_NAVIGATOR = 'layout-previewpost-event-navigator';
    public final const COMPONENT_LAYOUT_PREVIEWPOST_EVENT_ADDONS = 'layout-previewpost-event-addons';
    public final const COMPONENT_LAYOUT_PREVIEWPOST_EVENT_DETAILS = 'layout-previewpost-event-details';
    public final const COMPONENT_LAYOUT_PREVIEWPOST_EVENT_THUMBNAIL = 'layout-previewpost-event-thumbnail';
    public final const COMPONENT_LAYOUT_PREVIEWPOST_EVENT_LIST = 'layout-previewpost-event-list';
    public final const COMPONENT_LAYOUT_PREVIEWPOST_EVENT_RELATED = 'layout-previewpost-event-related';
    public final const COMPONENT_LAYOUT_PREVIEWPOST_EVENT_EDIT = 'layout-previewpost-event-edit';
    public final const COMPONENT_LAYOUT_PREVIEWPOST_EVENT_POPOVER = 'layout-previewpost-event-popover';
    public final const COMPONENT_LAYOUT_PREVIEWPOST_EVENT_CAROUSEL = 'layout-previewpost-event-carousel';
    public final const COMPONENT_LAYOUT_PREVIEWPOST_PASTEVENT_NAVIGATOR = 'layout-previewost-pastevent-navigator';
    public final const COMPONENT_LAYOUT_PREVIEWPOST_PASTEVENT_ADDONS = 'layout-previewost-pastevent-addons';
    public final const COMPONENT_LAYOUT_PREVIEWPOST_PASTEVENT_DETAILS = 'layout-previewost-pastevent-details';
    public final const COMPONENT_LAYOUT_PREVIEWPOST_PASTEVENT_THUMBNAIL = 'layout-previewost-pastevent-thumbnail';
    public final const COMPONENT_LAYOUT_PREVIEWPOST_PASTEVENT_LIST = 'layout-previewost-pastevent-list';
    public final const COMPONENT_LAYOUT_PREVIEWPOST_PASTEVENT_RELATED = 'layout-previewost-pastevent-related';
    public final const COMPONENT_LAYOUT_PREVIEWPOST_PASTEVENT_EDIT = 'layout-previewost-pastevent-edit';

    public function getComponentsToProcess(): array
    {
        return array(
            [self::class, self::COMPONENT_LAYOUT_PREVIEWPOST_EVENT_NAVIGATOR],
            [self::class, self::COMPONENT_LAYOUT_PREVIEWPOST_EVENT_ADDONS],
            [self::class, self::COMPONENT_LAYOUT_PREVIEWPOST_EVENT_DETAILS],
            [self::class, self::COMPONENT_LAYOUT_PREVIEWPOST_EVENT_THUMBNAIL],
            [self::class, self::COMPONENT_LAYOUT_PREVIEWPOST_EVENT_LIST],
            [self::class, self::COMPONENT_LAYOUT_PREVIEWPOST_EVENT_RELATED],
            [self::class, self::COMPONENT_LAYOUT_PREVIEWPOST_EVENT_EDIT],
            [self::class, self::COMPONENT_LAYOUT_PREVIEWPOST_EVENT_POPOVER],
            [self::class, self::COMPONENT_LAYOUT_PREVIEWPOST_EVENT_CAROUSEL],
            [self::class, self::COMPONENT_LAYOUT_PREVIEWPOST_PASTEVENT_NAVIGATOR],
            [self::class, self::COMPONENT_LAYOUT_PREVIEWPOST_PASTEVENT_ADDONS],
            [self::class, self::COMPONENT_LAYOUT_PREVIEWPOST_PASTEVENT_DETAILS],
            [self::class, self::COMPONENT_LAYOUT_PREVIEWPOST_PASTEVENT_THUMBNAIL],
            [self::class, self::COMPONENT_LAYOUT_PREVIEWPOST_PASTEVENT_LIST],
            [self::class, self::COMPONENT_LAYOUT_PREVIEWPOST_PASTEVENT_RELATED],
            [self::class, self::COMPONENT_LAYOUT_PREVIEWPOST_PASTEVENT_EDIT],
        );
    }

    public function getUrlField(\PoP\ComponentModel\Component\Component $component)
    {
        switch ($component->name) {
            case self::COMPONENT_LAYOUT_PREVIEWPOST_EVENT_EDIT:
            case self::COMPONENT_LAYOUT_PREVIEWPOST_PASTEVENT_EDIT:
                return 'editURL';
        }

        return parent::getUrlField($component);
    }

    public function getLinktarget(\PoP\ComponentModel\Component\Component $component, array &$props)
    {
        switch ($component->name) {
            case self::COMPONENT_LAYOUT_PREVIEWPOST_EVENT_EDIT:
            case self::COMPONENT_LAYOUT_PREVIEWPOST_PASTEVENT_EDIT:
                if (PoP_Application_Utils::getAddcontentTarget() == POP_TARGET_ADDONS) {
                    return POP_TARGET_ADDONS;
                }
                break;
        }

        return parent::getLinktarget($component, $props);
    }

    public function getAuthorComponent(\PoP\ComponentModel\Component\Component $component)
    {
        switch ($component->name) {
            case self::COMPONENT_LAYOUT_PREVIEWPOST_EVENT_POPOVER:
                return [PoP_Module_Processor_PostAuthorNameLayouts::class, PoP_Module_Processor_PostAuthorNameLayouts::COMPONENT_LAYOUTPOST_AUTHORNAME];
        }

        return parent::getAuthorComponent($component);
    }

    public function getQuicklinkgroupBottomSubcomponent(\PoP\ComponentModel\Component\Component $component)
    {
        switch ($component->name) {
            case self::COMPONENT_LAYOUT_PREVIEWPOST_EVENT_EDIT:
            case self::COMPONENT_LAYOUT_PREVIEWPOST_PASTEVENT_EDIT:
                return [PoP_Module_Processor_CustomQuicklinkGroups::class, PoP_Module_Processor_CustomQuicklinkGroups::COMPONENT_QUICKLINKGROUP_POSTEDIT];

            case self::COMPONENT_LAYOUT_PREVIEWPOST_EVENT_DETAILS:
                return [PoP_Module_Processor_CustomQuicklinkGroups::class, PoP_Module_Processor_CustomQuicklinkGroups::COMPONENT_QUICKLINKGROUP_POSTBOTTOMEXTENDEDVOLUNTEER];

            case self::COMPONENT_LAYOUT_PREVIEWPOST_PASTEVENT_DETAILS:
                return [PoP_Module_Processor_CustomQuicklinkGroups::class, PoP_Module_Processor_CustomQuicklinkGroups::COMPONENT_QUICKLINKGROUP_POSTBOTTOMEXTENDED];
        }

        return parent::getQuicklinkgroupBottomSubcomponent($component);
    }

    public function getQuicklinkgroupTopSubcomponent(\PoP\ComponentModel\Component\Component $component)
    {
        switch ($component->name) {
            case self::COMPONENT_LAYOUT_PREVIEWPOST_EVENT_NAVIGATOR:
            case self::COMPONENT_LAYOUT_PREVIEWPOST_EVENT_DETAILS:
            case self::COMPONENT_LAYOUT_PREVIEWPOST_EVENT_THUMBNAIL:
            case self::COMPONENT_LAYOUT_PREVIEWPOST_EVENT_LIST:
            case self::COMPONENT_LAYOUT_PREVIEWPOST_EVENT_RELATED:
            case self::COMPONENT_LAYOUT_PREVIEWPOST_EVENT_POPOVER:
            case self::COMPONENT_LAYOUT_PREVIEWPOST_EVENT_CAROUSEL:
            case self::COMPONENT_LAYOUT_PREVIEWPOST_PASTEVENT_NAVIGATOR:
            case self::COMPONENT_LAYOUT_PREVIEWPOST_PASTEVENT_DETAILS:
            case self::COMPONENT_LAYOUT_PREVIEWPOST_PASTEVENT_THUMBNAIL:
            case self::COMPONENT_LAYOUT_PREVIEWPOST_PASTEVENT_LIST:
            case self::COMPONENT_LAYOUT_PREVIEWPOST_PASTEVENT_RELATED:
                return [PoP_Module_Processor_CustomQuicklinkGroups::class, PoP_Module_Processor_CustomQuicklinkGroups::COMPONENT_QUICKLINKGROUP_POST];
        }

        return parent::getQuicklinkgroupTopSubcomponent($component);
    }

    public function getBelowthumbLayoutSubcomponents(\PoP\ComponentModel\Component\Component $component)
    {
        $ret = parent::getBelowthumbLayoutSubcomponents($component);

        switch ($component->name) {
            case self::COMPONENT_LAYOUT_PREVIEWPOST_EVENT_NAVIGATOR:
            case self::COMPONENT_LAYOUT_PREVIEWPOST_EVENT_DETAILS:
            case self::COMPONENT_LAYOUT_PREVIEWPOST_EVENT_THUMBNAIL:
            case self::COMPONENT_LAYOUT_PREVIEWPOST_EVENT_POPOVER:
                $ret[] = [GD_EM_Module_Processor_DateTimeLayouts::class, GD_EM_Module_Processor_DateTimeLayouts::COMPONENT_EM_LAYOUT_DATETIMEDOWNLOADLINKS];
                $ret[] = [PoP_Module_Processor_LocationViewComponentButtonWrapperss::class, PoP_Module_Processor_LocationViewComponentButtonWrapperss::COMPONENT_VIEWCOMPONENT_BUTTONWRAPPER_POSTLOCATIONS];
                break;

            case self::COMPONENT_LAYOUT_PREVIEWPOST_PASTEVENT_NAVIGATOR:
            case self::COMPONENT_LAYOUT_PREVIEWPOST_PASTEVENT_DETAILS:
            case self::COMPONENT_LAYOUT_PREVIEWPOST_PASTEVENT_THUMBNAIL:
                $ret[] = [GD_EM_Module_Processor_DateTimeLayouts::class, GD_EM_Module_Processor_DateTimeLayouts::COMPONENT_EM_LAYOUT_DATETIME];
                $ret[] = [PoP_Module_Processor_LocationViewComponentButtonWrapperss::class, PoP_Module_Processor_LocationViewComponentButtonWrapperss::COMPONENT_VIEWCOMPONENT_BUTTONWRAPPER_POSTLOCATIONS];
                break;
        }

        return $ret;
    }

    public function getBottomSubcomponents(\PoP\ComponentModel\Component\Component $component)
    {
        $ret = parent::getBottomSubcomponents($component);

        switch ($component->name) {
            case self::COMPONENT_LAYOUT_PREVIEWPOST_EVENT_LIST:
            case self::COMPONENT_LAYOUT_PREVIEWPOST_EVENT_CAROUSEL:
            case self::COMPONENT_LAYOUT_PREVIEWPOST_EVENT_RELATED:
                $ret[] = [GD_EM_Module_Processor_DateTimeLayouts::class, GD_EM_Module_Processor_DateTimeLayouts::COMPONENT_EM_LAYOUT_DATETIMEDOWNLOADLINKS];
                $ret[] = [PoP_Module_Processor_LocationViewComponentButtonWrapperss::class, PoP_Module_Processor_LocationViewComponentButtonWrapperss::COMPONENT_VIEWCOMPONENT_BUTTONWRAPPER_POSTLOCATIONS];
                break;

            case self::COMPONENT_LAYOUT_PREVIEWPOST_PASTEVENT_LIST:
                $ret[] = [GD_EM_Module_Processor_DateTimeLayouts::class, GD_EM_Module_Processor_DateTimeLayouts::COMPONENT_EM_LAYOUT_DATETIME];
                $ret[] = [PoP_Module_Processor_LocationViewComponentButtonWrapperss::class, PoP_Module_Processor_LocationViewComponentButtonWrapperss::COMPONENT_VIEWCOMPONENT_BUTTONWRAPPER_POSTLOCATIONS];
                break;

            case self::COMPONENT_LAYOUT_PREVIEWPOST_EVENT_ADDONS:
            case self::COMPONENT_LAYOUT_PREVIEWPOST_PASTEVENT_RELATED:
            case self::COMPONENT_LAYOUT_PREVIEWPOST_PASTEVENT_ADDONS:
                $ret[] = [GD_EM_Module_Processor_DateTimeLayouts::class, GD_EM_Module_Processor_DateTimeLayouts::COMPONENT_EM_LAYOUT_DATETIME];
                break;

            case self::COMPONENT_LAYOUT_PREVIEWPOST_EVENT_DETAILS:
            case self::COMPONENT_LAYOUT_PREVIEWPOST_PASTEVENT_DETAILS:
                $ret = array_merge(
                    $ret,
                    $this->getDetailsfeedBottomSubcomponents($component)
                );
                break;
        }

        return $ret;
    }

    public function getPostThumbSubcomponent(\PoP\ComponentModel\Component\Component $component)
    {
        switch ($component->name) {
            case self::COMPONENT_LAYOUT_PREVIEWPOST_EVENT_EDIT:
            case self::COMPONENT_LAYOUT_PREVIEWPOST_PASTEVENT_EDIT:
                return [GD_Custom_Module_Processor_PostThumbLayouts::class, GD_Custom_Module_Processor_PostThumbLayouts::COMPONENT_LAYOUT_POSTTHUMB_CROPPEDSMALL_EDIT];

            case self::COMPONENT_LAYOUT_PREVIEWPOST_PASTEVENT_RELATED:
            case self::COMPONENT_LAYOUT_PREVIEWPOST_PASTEVENT_LIST:
                return [GD_Custom_Module_Processor_PostThumbLayouts::class, GD_Custom_Module_Processor_PostThumbLayouts::COMPONENT_LAYOUT_POSTTHUMB_CROPPEDSMALL];

            case self::COMPONENT_LAYOUT_PREVIEWPOST_EVENT_RELATED:
            case self::COMPONENT_LAYOUT_PREVIEWPOST_EVENT_LIST:
                return [GD_Custom_Module_Processor_PostThumbLayouts::class, GD_Custom_Module_Processor_PostThumbLayouts::COMPONENT_LAYOUT_POSTTHUMB_CROPPEDSMALL_VOLUNTEER];

            case self::COMPONENT_LAYOUT_PREVIEWPOST_PASTEVENT_NAVIGATOR:
            case self::COMPONENT_LAYOUT_PREVIEWPOST_PASTEVENT_DETAILS:
            case self::COMPONENT_LAYOUT_PREVIEWPOST_PASTEVENT_THUMBNAIL:
                return [GD_Custom_Module_Processor_PostThumbLayouts::class, GD_Custom_Module_Processor_PostThumbLayouts::COMPONENT_LAYOUT_POSTTHUMB_CROPPEDMEDIUM];

            case self::COMPONENT_LAYOUT_PREVIEWPOST_EVENT_NAVIGATOR:
            case self::COMPONENT_LAYOUT_PREVIEWPOST_EVENT_DETAILS:
            case self::COMPONENT_LAYOUT_PREVIEWPOST_EVENT_THUMBNAIL:
            case self::COMPONENT_LAYOUT_PREVIEWPOST_EVENT_POPOVER:
            case self::COMPONENT_LAYOUT_PREVIEWPOST_EVENT_CAROUSEL:
                return [GD_Custom_Module_Processor_PostThumbLayouts::class, GD_Custom_Module_Processor_PostThumbLayouts::COMPONENT_LAYOUT_POSTTHUMB_CROPPEDMEDIUM_VOLUNTEER];

            case self::COMPONENT_LAYOUT_PREVIEWPOST_PASTEVENT_ADDONS:
            case self::COMPONENT_LAYOUT_PREVIEWPOST_EVENT_ADDONS:
                return [GD_Custom_Module_Processor_PostThumbLayouts::class, GD_Custom_Module_Processor_PostThumbLayouts::COMPONENT_LAYOUT_POSTTHUMB_XSMALL];
        }

        return parent::getPostThumbSubcomponent($component);
    }

    public function showExcerpt(\PoP\ComponentModel\Component\Component $component)
    {
        switch ($component->name) {
            case self::COMPONENT_LAYOUT_PREVIEWPOST_EVENT_DETAILS:
            case self::COMPONENT_LAYOUT_PREVIEWPOST_PASTEVENT_DETAILS:
                return true;
        }

        return parent::showExcerpt($component);
    }

    public function getTitleHtmlmarkup(\PoP\ComponentModel\Component\Component $component, array &$props)
    {
        switch ($component->name) {
            case self::COMPONENT_LAYOUT_PREVIEWPOST_EVENT_DETAILS:
            case self::COMPONENT_LAYOUT_PREVIEWPOST_PASTEVENT_DETAILS:
                return 'h3';
        }

        return parent::getTitleHtmlmarkup($component, $props);
    }

    public function authorPositions(\PoP\ComponentModel\Component\Component $component)
    {
        switch ($component->name) {
            case self::COMPONENT_LAYOUT_PREVIEWPOST_EVENT_NAVIGATOR:
            case self::COMPONENT_LAYOUT_PREVIEWPOST_PASTEVENT_NAVIGATOR:
            case self::COMPONENT_LAYOUT_PREVIEWPOST_EVENT_ADDONS:
            case self::COMPONENT_LAYOUT_PREVIEWPOST_PASTEVENT_ADDONS:
            case self::COMPONENT_LAYOUT_PREVIEWPOST_EVENT_RELATED:
            case self::COMPONENT_LAYOUT_PREVIEWPOST_PASTEVENT_RELATED:
            case self::COMPONENT_LAYOUT_PREVIEWPOST_EVENT_DETAILS:
            case self::COMPONENT_LAYOUT_PREVIEWPOST_PASTEVENT_DETAILS:
            case self::COMPONENT_LAYOUT_PREVIEWPOST_EVENT_THUMBNAIL:
            case self::COMPONENT_LAYOUT_PREVIEWPOST_PASTEVENT_THUMBNAIL:
            case self::COMPONENT_LAYOUT_PREVIEWPOST_EVENT_LIST:
            case self::COMPONENT_LAYOUT_PREVIEWPOST_PASTEVENT_LIST:
            case self::COMPONENT_LAYOUT_PREVIEWPOST_EVENT_POPOVER:
                return array(
                    GD_CONSTANT_AUTHORPOSITION_ABOVETITLE,
                    GD_CONSTANT_AUTHORPOSITION_BELOWCONTENT,
                );

            case self::COMPONENT_LAYOUT_PREVIEWPOST_EVENT_CAROUSEL:
                return array(
                    GD_CONSTANT_AUTHORPOSITION_ABOVETITLE
                );

            case self::COMPONENT_LAYOUT_PREVIEWPOST_EVENT_EDIT:
            case self::COMPONENT_LAYOUT_PREVIEWPOST_PASTEVENT_EDIT:
                return array();
        }

        return parent::authorPositions($component);
    }

    public function getTitleBeforeauthors(\PoP\ComponentModel\Component\Component $component, array &$props)
    {
        switch ($component->name) {
            case self::COMPONENT_LAYOUT_PREVIEWPOST_EVENT_NAVIGATOR:
            case self::COMPONENT_LAYOUT_PREVIEWPOST_PASTEVENT_NAVIGATOR:
            case self::COMPONENT_LAYOUT_PREVIEWPOST_EVENT_ADDONS:
            case self::COMPONENT_LAYOUT_PREVIEWPOST_PASTEVENT_ADDONS:
            case self::COMPONENT_LAYOUT_PREVIEWPOST_EVENT_RELATED:
            case self::COMPONENT_LAYOUT_PREVIEWPOST_PASTEVENT_RELATED:
            case self::COMPONENT_LAYOUT_PREVIEWPOST_EVENT_DETAILS:
            case self::COMPONENT_LAYOUT_PREVIEWPOST_PASTEVENT_DETAILS:
            case self::COMPONENT_LAYOUT_PREVIEWPOST_EVENT_THUMBNAIL:
            case self::COMPONENT_LAYOUT_PREVIEWPOST_PASTEVENT_THUMBNAIL:
            case self::COMPONENT_LAYOUT_PREVIEWPOST_EVENT_LIST:
            case self::COMPONENT_LAYOUT_PREVIEWPOST_PASTEVENT_LIST:
            case self::COMPONENT_LAYOUT_PREVIEWPOST_EVENT_POPOVER:
                return array(
                    'belowcontent' => TranslationAPIFacade::getInstance()->__('posted by', 'poptheme-wassup')
                );
        }

        return parent::getTitleBeforeauthors($component, $props);
    }

    public function horizontalLayout(\PoP\ComponentModel\Component\Component $component)
    {
        switch ($component->name) {
            case self::COMPONENT_LAYOUT_PREVIEWPOST_EVENT_DETAILS:
            case self::COMPONENT_LAYOUT_PREVIEWPOST_PASTEVENT_DETAILS:
            case self::COMPONENT_LAYOUT_PREVIEWPOST_EVENT_CAROUSEL:
                return true;
        }

        return parent::horizontalLayout($component);
    }

    public function horizontalMediaLayout(\PoP\ComponentModel\Component\Component $component)
    {
        switch ($component->name) {
            case self::COMPONENT_LAYOUT_PREVIEWPOST_EVENT_RELATED:
            case self::COMPONENT_LAYOUT_PREVIEWPOST_PASTEVENT_RELATED:
            case self::COMPONENT_LAYOUT_PREVIEWPOST_EVENT_EDIT:
            case self::COMPONENT_LAYOUT_PREVIEWPOST_PASTEVENT_EDIT:
            case self::COMPONENT_LAYOUT_PREVIEWPOST_EVENT_LIST:
            case self::COMPONENT_LAYOUT_PREVIEWPOST_PASTEVENT_LIST:
            case self::COMPONENT_LAYOUT_PREVIEWPOST_EVENT_ADDONS:
            case self::COMPONENT_LAYOUT_PREVIEWPOST_PASTEVENT_ADDONS:
                return true;
        }

        return parent::horizontalMediaLayout($component);
    }


    public function getImmutableConfiguration(\PoP\ComponentModel\Component\Component $component, array &$props): array
    {
        $ret = parent::getImmutableConfiguration($component, $props);

        switch ($component->name) {
            case self::COMPONENT_LAYOUT_PREVIEWPOST_EVENT_NAVIGATOR:
            case self::COMPONENT_LAYOUT_PREVIEWPOST_EVENT_DETAILS:
            case self::COMPONENT_LAYOUT_PREVIEWPOST_EVENT_THUMBNAIL:
            case self::COMPONENT_LAYOUT_PREVIEWPOST_EVENT_POPOVER:
            case self::COMPONENT_LAYOUT_PREVIEWPOST_PASTEVENT_NAVIGATOR:
            case self::COMPONENT_LAYOUT_PREVIEWPOST_PASTEVENT_DETAILS:
            case self::COMPONENT_LAYOUT_PREVIEWPOST_PASTEVENT_THUMBNAIL:
                $ret[GD_JS_CLASSES]['belowthumb'] = 'bg-info text-info belowthumb';
                break;
        }
        switch ($component->name) {
            case self::COMPONENT_LAYOUT_PREVIEWPOST_EVENT_POPOVER:
                // Hide for small screens since otherwise it doesn't fit in the viewport and the whole popover is then not visible
                $ret[GD_JS_CLASSES]['thumb'] = 'hidden-xs';
                break;
        }

        switch ($component->name) {
            case self::COMPONENT_LAYOUT_PREVIEWPOST_EVENT_DETAILS:
            case self::COMPONENT_LAYOUT_PREVIEWPOST_PASTEVENT_DETAILS:
                $ret[GD_JS_CLASSES]['thumb'] = 'pop-thumb-framed';
                break;
        }

        switch ($component->name) {
            case self::COMPONENT_LAYOUT_PREVIEWPOST_EVENT_NAVIGATOR:
            case self::COMPONENT_LAYOUT_PREVIEWPOST_PASTEVENT_NAVIGATOR:
            case self::COMPONENT_LAYOUT_PREVIEWPOST_EVENT_ADDONS:
            case self::COMPONENT_LAYOUT_PREVIEWPOST_PASTEVENT_ADDONS:
            case self::COMPONENT_LAYOUT_PREVIEWPOST_EVENT_RELATED:
            case self::COMPONENT_LAYOUT_PREVIEWPOST_PASTEVENT_RELATED:
            case self::COMPONENT_LAYOUT_PREVIEWPOST_EVENT_DETAILS:
            case self::COMPONENT_LAYOUT_PREVIEWPOST_PASTEVENT_DETAILS:
            case self::COMPONENT_LAYOUT_PREVIEWPOST_EVENT_THUMBNAIL:
            case self::COMPONENT_LAYOUT_PREVIEWPOST_PASTEVENT_THUMBNAIL:
            case self::COMPONENT_LAYOUT_PREVIEWPOST_EVENT_LIST:
            case self::COMPONENT_LAYOUT_PREVIEWPOST_PASTEVENT_LIST:
                $ret[GD_JS_CLASSES]['authors-belowcontent'] = 'pull-right';
                break;
        }

        return $ret;
    }

    public function initModelProps(\PoP\ComponentModel\Component\Component $component, array &$props): void
    {
        switch ($component->name) {
            case self::COMPONENT_LAYOUT_PREVIEWPOST_EVENT_CAROUSEL:
                $this->appendProp($component, $props, 'class', 'events-carousel');
                break;
        }

        parent::initModelProps($component, $props);
    }
}



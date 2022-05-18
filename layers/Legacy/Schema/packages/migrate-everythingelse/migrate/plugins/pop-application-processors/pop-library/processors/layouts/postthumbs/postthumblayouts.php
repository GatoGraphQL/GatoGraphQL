<?php

class GD_Custom_Module_Processor_PostThumbLayouts extends PoP_Module_Processor_PostThumbLayoutsBase
{
    public final const COMPONENT_LAYOUT_POSTTHUMB_FAVICON = 'layout-postthumb-favicon';
    public final const COMPONENT_LAYOUT_POSTTHUMB_ORIGINALFEATUREDIMAGE = 'layout-postthumb-originalfeaturedimage';
    public final const COMPONENT_LAYOUT_POSTTHUMB_FEATUREDIMAGE = 'layout-postthumb-featuredimage';
    public final const COMPONENT_LAYOUT_POSTTHUMB_FEATUREDIMAGE_VOLUNTEER = 'layout-postthumb-featuredimage-volunteer';
    public final const COMPONENT_LAYOUT_POSTTHUMB_XSMALL = 'layout-postthumb-xsmall';
    public final const COMPONENT_LAYOUT_POSTTHUMB_CROPPEDSMALL = 'layout-postthumb-croppedsmall';
    public final const COMPONENT_LAYOUT_POSTTHUMB_CROPPEDMEDIUM = 'layout-postthumb-croppedmedium';
    public final const COMPONENT_LAYOUT_POSTTHUMB_CROPPEDFEED = 'layout-postthumb-croppedfeed';
    public final const COMPONENT_LAYOUT_POSTTHUMB_CROPPEDSMALL_EDIT = 'layout-postthumb-croppedsmall-edit';
    public final const COMPONENT_LAYOUT_POSTTHUMB_CROPPEDSMALL_VOLUNTEER = 'layout-postthumb-croppedsmall-volunteer';
    public final const COMPONENT_LAYOUT_POSTTHUMB_CROPPEDMEDIUM_VOLUNTEER = 'layout-postthumb-croppedmedium-volunteer';
    public final const COMPONENT_LAYOUT_POSTTHUMB_CROPPEDFEED_VOLUNTEER = 'layout-postthumb-croppedfeed-volunteer';
    public final const COMPONENT_LAYOUT_POSTTHUMB_LINKSELFORIGINALFEATUREDIMAGE = 'layout-postthumb-linkselforiginalfeaturedimage';
    public final const COMPONENT_LAYOUT_POSTTHUMB_LINKSELFCROPPEDFEED = 'layout-postthumb-linkselfcroppedfeed';
    public final const COMPONENT_LAYOUT_POSTTHUMB_LINKSELFCROPPEDFEED_VOLUNTEER = 'layout-postthumb-linkselfcroppedfeed-volunteer';

    public function getComponentsToProcess(): array
    {
        return array(
            [self::class, self::COMPONENT_LAYOUT_POSTTHUMB_FAVICON],
            [self::class, self::COMPONENT_LAYOUT_POSTTHUMB_ORIGINALFEATUREDIMAGE],
            [self::class, self::COMPONENT_LAYOUT_POSTTHUMB_FEATUREDIMAGE],
            [self::class, self::COMPONENT_LAYOUT_POSTTHUMB_FEATUREDIMAGE_VOLUNTEER],
            [self::class, self::COMPONENT_LAYOUT_POSTTHUMB_XSMALL],
            [self::class, self::COMPONENT_LAYOUT_POSTTHUMB_CROPPEDSMALL],
            [self::class, self::COMPONENT_LAYOUT_POSTTHUMB_CROPPEDMEDIUM],
            [self::class, self::COMPONENT_LAYOUT_POSTTHUMB_CROPPEDFEED],
            [self::class, self::COMPONENT_LAYOUT_POSTTHUMB_CROPPEDSMALL_EDIT],
            [self::class, self::COMPONENT_LAYOUT_POSTTHUMB_CROPPEDSMALL_VOLUNTEER],
            [self::class, self::COMPONENT_LAYOUT_POSTTHUMB_CROPPEDMEDIUM_VOLUNTEER],
            [self::class, self::COMPONENT_LAYOUT_POSTTHUMB_CROPPEDFEED_VOLUNTEER],
            [self::class, self::COMPONENT_LAYOUT_POSTTHUMB_LINKSELFORIGINALFEATUREDIMAGE],
            [self::class, self::COMPONENT_LAYOUT_POSTTHUMB_LINKSELFCROPPEDFEED],
            [self::class, self::COMPONENT_LAYOUT_POSTTHUMB_LINKSELFCROPPEDFEED_VOLUNTEER],
        );
    }

    protected function getThumbFieldArgs(array $component, array &$props)
    {
        switch ($component[1]) {
            case self::COMPONENT_LAYOUT_POSTTHUMB_FAVICON:
                return ['size' => 'favicon'];

            case self::COMPONENT_LAYOUT_POSTTHUMB_LINKSELFORIGINALFEATUREDIMAGE:
                return ['size' => 'large', 'addDescription' => true];

            case self::COMPONENT_LAYOUT_POSTTHUMB_ORIGINALFEATUREDIMAGE:
            case self::COMPONENT_LAYOUT_POSTTHUMB_FEATUREDIMAGE:
            case self::COMPONENT_LAYOUT_POSTTHUMB_FEATUREDIMAGE_VOLUNTEER:
                return ['size' => 'thumb-pagewide'];

            case self::COMPONENT_LAYOUT_POSTTHUMB_XSMALL:
                return ['size' => 'thumb-xs'];

            case self::COMPONENT_LAYOUT_POSTTHUMB_CROPPEDSMALL:
            case self::COMPONENT_LAYOUT_POSTTHUMB_CROPPEDSMALL_EDIT:
            case self::COMPONENT_LAYOUT_POSTTHUMB_CROPPEDSMALL_VOLUNTEER:
                return ['size' => 'thumb-sm'];

            case self::COMPONENT_LAYOUT_POSTTHUMB_CROPPEDMEDIUM:
            case self::COMPONENT_LAYOUT_POSTTHUMB_CROPPEDMEDIUM_VOLUNTEER:
                return ['size' => 'thumb-md'];

            case self::COMPONENT_LAYOUT_POSTTHUMB_CROPPEDFEED:
            case self::COMPONENT_LAYOUT_POSTTHUMB_CROPPEDFEED_VOLUNTEER:
            case self::COMPONENT_LAYOUT_POSTTHUMB_LINKSELFCROPPEDFEED:
            case self::COMPONENT_LAYOUT_POSTTHUMB_LINKSELFCROPPEDFEED_VOLUNTEER:
                return ['size' => 'thumb-feed'];
        }

        return parent::getThumbFieldArgs($component, $props);
    }

    public function getUrlField(array $component)
    {
        switch ($component[1]) {
            case self::COMPONENT_LAYOUT_POSTTHUMB_ORIGINALFEATUREDIMAGE:
            case self::COMPONENT_LAYOUT_POSTTHUMB_FEATUREDIMAGE:
            case self::COMPONENT_LAYOUT_POSTTHUMB_FEATUREDIMAGE_VOLUNTEER:
            case self::COMPONENT_LAYOUT_POSTTHUMB_LINKSELFORIGINALFEATUREDIMAGE:
            case self::COMPONENT_LAYOUT_POSTTHUMB_LINKSELFCROPPEDFEED:
            case self::COMPONENT_LAYOUT_POSTTHUMB_LINKSELFCROPPEDFEED_VOLUNTEER:
                return 'thumbFullSrc';

            case self::COMPONENT_LAYOUT_POSTTHUMB_CROPPEDSMALL_EDIT:
                return 'editURL';
        }

        return parent::getUrlField($component);
    }

    public function getLinktarget(array $component, array &$props)
    {
        switch ($component[1]) {
            case self::COMPONENT_LAYOUT_POSTTHUMB_CROPPEDSMALL_EDIT:
                if (PoP_Application_Utils::getAddcontentTarget() == POP_TARGET_ADDONS) {
                    return POP_TARGET_ADDONS;
                }
                break;
        }

        return parent::getLinktarget($component, $props);
    }

    public function getExtraThumbLayoutSubmodules(array $component)
    {
        $ret = parent::getExtraThumbLayoutSubmodules($component);

        switch ($component[1]) {
            case self::COMPONENT_LAYOUT_POSTTHUMB_FEATUREDIMAGE_VOLUNTEER:
            case self::COMPONENT_LAYOUT_POSTTHUMB_CROPPEDSMALL_VOLUNTEER:
            case self::COMPONENT_LAYOUT_POSTTHUMB_CROPPEDMEDIUM_VOLUNTEER:
            case self::COMPONENT_LAYOUT_POSTTHUMB_CROPPEDFEED_VOLUNTEER:
                // case self::COMPONENT_LAYOUT_POSTTHUMB_CROPPEDLARGE_VOLUNTEER:
            case self::COMPONENT_LAYOUT_POSTTHUMB_LINKSELFCROPPEDFEED_VOLUNTEER:
                // Only if the Volunteering is enabled
                if (defined('POP_VOLUNTEERING_ROUTE_VOLUNTEER') && POP_VOLUNTEERING_ROUTE_VOLUNTEER) {
                    $ret[] = [PoP_Module_Processor_VolunteerTagLayouts::class, PoP_Module_Processor_VolunteerTagLayouts::COMPONENT_LAYOUT_POSTADDITIONAL_VOLUNTEER];
                }
                break;

            case self::COMPONENT_LAYOUT_POSTTHUMB_XSMALL:
                // Override the parent since we don't want to show the multipost-labels here, this thumb is too small
                return array();
        }

        return $ret;
    }

    public function getThumbLinkClass(array $component)
    {
        switch ($component[1]) {
            case self::COMPONENT_LAYOUT_POSTTHUMB_ORIGINALFEATUREDIMAGE:
            case self::COMPONENT_LAYOUT_POSTTHUMB_FEATUREDIMAGE:
            case self::COMPONENT_LAYOUT_POSTTHUMB_FEATUREDIMAGE_VOLUNTEER:
                return 'thumbnail';
        }

        return parent::getThumbLinkClass($component);
    }

    public function getThumbImgClass(array $component)
    {
        switch ($component[1]) {
            case self::COMPONENT_LAYOUT_POSTTHUMB_LINKSELFORIGINALFEATUREDIMAGE:
                return 'img-responsive';
        }

        return parent::getThumbImgClass($component);
    }

    public function getDbobjectParams(array $component): array
    {
        switch ($component[1]) {
            case self::COMPONENT_LAYOUT_POSTTHUMB_ORIGINALFEATUREDIMAGE:
            case self::COMPONENT_LAYOUT_POSTTHUMB_FEATUREDIMAGE:
            case self::COMPONENT_LAYOUT_POSTTHUMB_FEATUREDIMAGE_VOLUNTEER:
            case self::COMPONENT_LAYOUT_POSTTHUMB_LINKSELFORIGINALFEATUREDIMAGE:
            case self::COMPONENT_LAYOUT_POSTTHUMB_LINKSELFCROPPEDFEED:
            case self::COMPONENT_LAYOUT_POSTTHUMB_LINKSELFCROPPEDFEED_VOLUNTEER:
                return array(
                    'data-size' => 'thumbFullDimensions',
                );
        }

        return parent::getDbobjectParams($component);
    }

    public function initModelProps(array $component, array &$props): void
    {
        switch ($component[1]) {
            case self::COMPONENT_LAYOUT_POSTTHUMB_CROPPEDFEED:
            case self::COMPONENT_LAYOUT_POSTTHUMB_CROPPEDFEED_VOLUNTEER:
            case self::COMPONENT_LAYOUT_POSTTHUMB_LINKSELFORIGINALFEATUREDIMAGE:
            case self::COMPONENT_LAYOUT_POSTTHUMB_LINKSELFCROPPEDFEED:
            case self::COMPONENT_LAYOUT_POSTTHUMB_LINKSELFCROPPEDFEED_VOLUNTEER:
                // Artificial property added to identify the template when adding component-resources
                $this->setProp($component, $props, 'resourceloader', 'thumb-feed');

                // Style to add a background-image while loading the feed image
                $this->appendProp($component, $props, 'class', 'thumb-feed');
                break;
        }

        parent::initModelProps($component, $props);
    }
}




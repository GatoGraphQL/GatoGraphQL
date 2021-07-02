<?php

class GD_Custom_Module_Processor_PostThumbLayouts extends PoP_Module_Processor_PostThumbLayoutsBase
{
    public const MODULE_LAYOUT_POSTTHUMB_FAVICON = 'layout-postthumb-favicon';
    public const MODULE_LAYOUT_POSTTHUMB_ORIGINALFEATUREDIMAGE = 'layout-postthumb-originalfeaturedimage';
    public const MODULE_LAYOUT_POSTTHUMB_FEATUREDIMAGE = 'layout-postthumb-featuredimage';
    public const MODULE_LAYOUT_POSTTHUMB_FEATUREDIMAGE_VOLUNTEER = 'layout-postthumb-featuredimage-volunteer';
    public const MODULE_LAYOUT_POSTTHUMB_XSMALL = 'layout-postthumb-xsmall';
    public const MODULE_LAYOUT_POSTTHUMB_CROPPEDSMALL = 'layout-postthumb-croppedsmall';
    public const MODULE_LAYOUT_POSTTHUMB_CROPPEDMEDIUM = 'layout-postthumb-croppedmedium';
    public const MODULE_LAYOUT_POSTTHUMB_CROPPEDFEED = 'layout-postthumb-croppedfeed';
    public const MODULE_LAYOUT_POSTTHUMB_CROPPEDSMALL_EDIT = 'layout-postthumb-croppedsmall-edit';
    public const MODULE_LAYOUT_POSTTHUMB_CROPPEDSMALL_VOLUNTEER = 'layout-postthumb-croppedsmall-volunteer';
    public const MODULE_LAYOUT_POSTTHUMB_CROPPEDMEDIUM_VOLUNTEER = 'layout-postthumb-croppedmedium-volunteer';
    public const MODULE_LAYOUT_POSTTHUMB_CROPPEDFEED_VOLUNTEER = 'layout-postthumb-croppedfeed-volunteer';
    public const MODULE_LAYOUT_POSTTHUMB_LINKSELFORIGINALFEATUREDIMAGE = 'layout-postthumb-linkselforiginalfeaturedimage';
    public const MODULE_LAYOUT_POSTTHUMB_LINKSELFCROPPEDFEED = 'layout-postthumb-linkselfcroppedfeed';
    public const MODULE_LAYOUT_POSTTHUMB_LINKSELFCROPPEDFEED_VOLUNTEER = 'layout-postthumb-linkselfcroppedfeed-volunteer';

    public function getModulesToProcess(): array
    {
        return array(
            [self::class, self::MODULE_LAYOUT_POSTTHUMB_FAVICON],
            [self::class, self::MODULE_LAYOUT_POSTTHUMB_ORIGINALFEATUREDIMAGE],
            [self::class, self::MODULE_LAYOUT_POSTTHUMB_FEATUREDIMAGE],
            [self::class, self::MODULE_LAYOUT_POSTTHUMB_FEATUREDIMAGE_VOLUNTEER],
            [self::class, self::MODULE_LAYOUT_POSTTHUMB_XSMALL],
            [self::class, self::MODULE_LAYOUT_POSTTHUMB_CROPPEDSMALL],
            [self::class, self::MODULE_LAYOUT_POSTTHUMB_CROPPEDMEDIUM],
            [self::class, self::MODULE_LAYOUT_POSTTHUMB_CROPPEDFEED],
            [self::class, self::MODULE_LAYOUT_POSTTHUMB_CROPPEDSMALL_EDIT],
            [self::class, self::MODULE_LAYOUT_POSTTHUMB_CROPPEDSMALL_VOLUNTEER],
            [self::class, self::MODULE_LAYOUT_POSTTHUMB_CROPPEDMEDIUM_VOLUNTEER],
            [self::class, self::MODULE_LAYOUT_POSTTHUMB_CROPPEDFEED_VOLUNTEER],
            [self::class, self::MODULE_LAYOUT_POSTTHUMB_LINKSELFORIGINALFEATUREDIMAGE],
            [self::class, self::MODULE_LAYOUT_POSTTHUMB_LINKSELFCROPPEDFEED],
            [self::class, self::MODULE_LAYOUT_POSTTHUMB_LINKSELFCROPPEDFEED_VOLUNTEER],
        );
    }

    protected function getThumbFieldArgs(array $module, array &$props)
    {
        switch ($module[1]) {
            case self::MODULE_LAYOUT_POSTTHUMB_FAVICON:
                return ['size' => 'favicon'];

            case self::MODULE_LAYOUT_POSTTHUMB_LINKSELFORIGINALFEATUREDIMAGE:
                return ['size' => 'large', 'addDescription' => true];

            case self::MODULE_LAYOUT_POSTTHUMB_ORIGINALFEATUREDIMAGE:
            case self::MODULE_LAYOUT_POSTTHUMB_FEATUREDIMAGE:
            case self::MODULE_LAYOUT_POSTTHUMB_FEATUREDIMAGE_VOLUNTEER:
                return ['size' => 'thumb-pagewide'];

            case self::MODULE_LAYOUT_POSTTHUMB_XSMALL:
                return ['size' => 'thumb-xs'];

            case self::MODULE_LAYOUT_POSTTHUMB_CROPPEDSMALL:
            case self::MODULE_LAYOUT_POSTTHUMB_CROPPEDSMALL_EDIT:
            case self::MODULE_LAYOUT_POSTTHUMB_CROPPEDSMALL_VOLUNTEER:
                return ['size' => 'thumb-sm'];

            case self::MODULE_LAYOUT_POSTTHUMB_CROPPEDMEDIUM:
            case self::MODULE_LAYOUT_POSTTHUMB_CROPPEDMEDIUM_VOLUNTEER:
                return ['size' => 'thumb-md'];

            case self::MODULE_LAYOUT_POSTTHUMB_CROPPEDFEED:
            case self::MODULE_LAYOUT_POSTTHUMB_CROPPEDFEED_VOLUNTEER:
            case self::MODULE_LAYOUT_POSTTHUMB_LINKSELFCROPPEDFEED:
            case self::MODULE_LAYOUT_POSTTHUMB_LINKSELFCROPPEDFEED_VOLUNTEER:
                return ['size' => 'thumb-feed'];
        }

        return parent::getThumbFieldArgs($module, $props);
    }

    public function getUrlField(array $module)
    {
        switch ($module[1]) {
            case self::MODULE_LAYOUT_POSTTHUMB_ORIGINALFEATUREDIMAGE:
            case self::MODULE_LAYOUT_POSTTHUMB_FEATUREDIMAGE:
            case self::MODULE_LAYOUT_POSTTHUMB_FEATUREDIMAGE_VOLUNTEER:
            case self::MODULE_LAYOUT_POSTTHUMB_LINKSELFORIGINALFEATUREDIMAGE:
            case self::MODULE_LAYOUT_POSTTHUMB_LINKSELFCROPPEDFEED:
            case self::MODULE_LAYOUT_POSTTHUMB_LINKSELFCROPPEDFEED_VOLUNTEER:
                return 'thumbFullSrc';

            case self::MODULE_LAYOUT_POSTTHUMB_CROPPEDSMALL_EDIT:
                return 'editURL';
        }

        return parent::getUrlField($module);
    }

    public function getLinktarget(array $module, array &$props)
    {
        switch ($module[1]) {
            case self::MODULE_LAYOUT_POSTTHUMB_CROPPEDSMALL_EDIT:
                if (PoP_Application_Utils::getAddcontentTarget() == POP_TARGET_ADDONS) {
                    return POP_TARGET_ADDONS;
                }
                break;
        }

        return parent::getLinktarget($module, $props);
    }

    public function getExtraThumbLayoutSubmodules(array $module)
    {
        $ret = parent::getExtraThumbLayoutSubmodules($module);

        switch ($module[1]) {
            case self::MODULE_LAYOUT_POSTTHUMB_FEATUREDIMAGE_VOLUNTEER:
            case self::MODULE_LAYOUT_POSTTHUMB_CROPPEDSMALL_VOLUNTEER:
            case self::MODULE_LAYOUT_POSTTHUMB_CROPPEDMEDIUM_VOLUNTEER:
            case self::MODULE_LAYOUT_POSTTHUMB_CROPPEDFEED_VOLUNTEER:
                // case self::MODULE_LAYOUT_POSTTHUMB_CROPPEDLARGE_VOLUNTEER:
            case self::MODULE_LAYOUT_POSTTHUMB_LINKSELFCROPPEDFEED_VOLUNTEER:
                // Only if the Volunteering is enabled
                if (defined('POP_VOLUNTEERING_ROUTE_VOLUNTEER') && POP_VOLUNTEERING_ROUTE_VOLUNTEER) {
                    $ret[] = [PoP_Module_Processor_VolunteerTagLayouts::class, PoP_Module_Processor_VolunteerTagLayouts::MODULE_LAYOUT_POSTADDITIONAL_VOLUNTEER];
                }
                break;

            case self::MODULE_LAYOUT_POSTTHUMB_XSMALL:
                // Override the parent since we don't want to show the multipost-labels here, this thumb is too small
                return array();
        }

        return $ret;
    }

    public function getThumbLinkClass(array $module)
    {
        switch ($module[1]) {
            case self::MODULE_LAYOUT_POSTTHUMB_ORIGINALFEATUREDIMAGE:
            case self::MODULE_LAYOUT_POSTTHUMB_FEATUREDIMAGE:
            case self::MODULE_LAYOUT_POSTTHUMB_FEATUREDIMAGE_VOLUNTEER:
                return 'thumbnail';
        }

        return parent::getThumbLinkClass($module);
    }

    public function getThumbImgClass(array $module)
    {
        switch ($module[1]) {
            case self::MODULE_LAYOUT_POSTTHUMB_LINKSELFORIGINALFEATUREDIMAGE:
                return 'img-responsive';
        }

        return parent::getThumbImgClass($module);
    }

    public function getDbobjectParams(array $module): array
    {
        switch ($module[1]) {
            case self::MODULE_LAYOUT_POSTTHUMB_ORIGINALFEATUREDIMAGE:
            case self::MODULE_LAYOUT_POSTTHUMB_FEATUREDIMAGE:
            case self::MODULE_LAYOUT_POSTTHUMB_FEATUREDIMAGE_VOLUNTEER:
            case self::MODULE_LAYOUT_POSTTHUMB_LINKSELFORIGINALFEATUREDIMAGE:
            case self::MODULE_LAYOUT_POSTTHUMB_LINKSELFCROPPEDFEED:
            case self::MODULE_LAYOUT_POSTTHUMB_LINKSELFCROPPEDFEED_VOLUNTEER:
                return array(
                    'data-size' => 'thumbFullDimensions',
                );
        }

        return parent::getDbobjectParams($module);
    }

    public function initModelProps(array $module, array &$props): void
    {
        switch ($module[1]) {
            case self::MODULE_LAYOUT_POSTTHUMB_CROPPEDFEED:
            case self::MODULE_LAYOUT_POSTTHUMB_CROPPEDFEED_VOLUNTEER:
            case self::MODULE_LAYOUT_POSTTHUMB_LINKSELFORIGINALFEATUREDIMAGE:
            case self::MODULE_LAYOUT_POSTTHUMB_LINKSELFCROPPEDFEED:
            case self::MODULE_LAYOUT_POSTTHUMB_LINKSELFCROPPEDFEED_VOLUNTEER:
                // Artificial property added to identify the template when adding module-resources
                $this->setProp($module, $props, 'resourceloader', 'thumb-feed');

                // Style to add a background-image while loading the feed image
                $this->appendProp($module, $props, 'class', 'thumb-feed');
                break;
        }

        parent::initModelProps($module, $props);
    }
}




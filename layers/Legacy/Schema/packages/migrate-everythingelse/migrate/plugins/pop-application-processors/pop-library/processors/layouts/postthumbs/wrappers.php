<?php

class GD_Custom_Module_Processor_PostThumbLayoutWrappers extends PoP_Module_Processor_ConditionWrapperBase
{
    public final const MODULE_LAYOUTWRAPPER_POSTTHUMB_FAVICON = 'layoutwrapper-postthumb-favicon';
    public final const MODULE_LAYOUTWRAPPER_POSTTHUMB_ORIGINALFEATUREDIMAGE = 'layoutwrapper-postthumb-originalfeaturedimage';
    public final const MODULE_LAYOUTWRAPPER_POSTTHUMB_FEATUREDIMAGE = 'layoutwrapper-postthumb-featuredimage';
    public final const MODULE_LAYOUTWRAPPER_POSTTHUMB_FEATUREDIMAGE_VOLUNTEER = 'layoutwrapper-postthumb-featuredimage-volunteer';
    public final const MODULE_LAYOUTWRAPPER_POSTTHUMB_XSMALL = 'layoutwrapper-postthumb-xsmall';
    public final const MODULE_LAYOUTWRAPPER_POSTTHUMB_CROPPEDSMALL = 'layoutwrapper-postthumb-croppedsmall';
    public final const MODULE_LAYOUTWRAPPER_POSTTHUMB_CROPPEDMEDIUM = 'layoutwrapper-postthumb-croppedmedium';
    public final const MODULE_LAYOUTWRAPPER_POSTTHUMB_CROPPEDFEED = 'layoutwrapper-postthumb-croppedfeed';
    public final const MODULE_LAYOUTWRAPPER_POSTTHUMB_CROPPEDSMALL_EDIT = 'layoutwrapper-postthumb-croppedsmall-edit';
    public final const MODULE_LAYOUTWRAPPER_POSTTHUMB_CROPPEDSMALL_VOLUNTEER = 'layoutwrapper-postthumb-croppedsmall-volunteer';
    public final const MODULE_LAYOUTWRAPPER_POSTTHUMB_CROPPEDMEDIUM_VOLUNTEER = 'layoutwrapper-postthumb-croppedmedium-volunteer';
    public final const MODULE_LAYOUTWRAPPER_POSTTHUMB_CROPPEDFEED_VOLUNTEER = 'layoutwrapper-postthumb-croppedfeed-volunteer';
    public final const MODULE_LAYOUTWRAPPER_POSTTHUMB_LINKSELFORIGINALFEATUREDIMAGE = 'layoutwrapper-postthumb-linkselforiginalfeaturedimage';
    public final const MODULE_LAYOUTWRAPPER_POSTTHUMB_LINKSELFCROPPEDFEED = 'layoutwrapper-postthumb-linkselfcroppedfeed';
    public final const MODULE_LAYOUTWRAPPER_POSTTHUMB_LINKSELFCROPPEDFEED_VOLUNTEER = 'layoutwrapper-postthumb-linkselfcroppedfeed-volunteer';

    public function getComponentVariationsToProcess(): array
    {
        return array(
            [self::class, self::MODULE_LAYOUTWRAPPER_POSTTHUMB_FAVICON],
            [self::class, self::MODULE_LAYOUTWRAPPER_POSTTHUMB_ORIGINALFEATUREDIMAGE],
            [self::class, self::MODULE_LAYOUTWRAPPER_POSTTHUMB_FEATUREDIMAGE],
            [self::class, self::MODULE_LAYOUTWRAPPER_POSTTHUMB_FEATUREDIMAGE_VOLUNTEER],
            [self::class, self::MODULE_LAYOUTWRAPPER_POSTTHUMB_XSMALL],
            [self::class, self::MODULE_LAYOUTWRAPPER_POSTTHUMB_CROPPEDSMALL],
            [self::class, self::MODULE_LAYOUTWRAPPER_POSTTHUMB_CROPPEDMEDIUM],
            [self::class, self::MODULE_LAYOUTWRAPPER_POSTTHUMB_CROPPEDFEED],
            [self::class, self::MODULE_LAYOUTWRAPPER_POSTTHUMB_CROPPEDSMALL_EDIT],
            [self::class, self::MODULE_LAYOUTWRAPPER_POSTTHUMB_CROPPEDSMALL_VOLUNTEER],
            [self::class, self::MODULE_LAYOUTWRAPPER_POSTTHUMB_CROPPEDMEDIUM_VOLUNTEER],
            [self::class, self::MODULE_LAYOUTWRAPPER_POSTTHUMB_CROPPEDFEED_VOLUNTEER],
            [self::class, self::MODULE_LAYOUTWRAPPER_POSTTHUMB_LINKSELFORIGINALFEATUREDIMAGE],
            [self::class, self::MODULE_LAYOUTWRAPPER_POSTTHUMB_LINKSELFCROPPEDFEED],
            [self::class, self::MODULE_LAYOUTWRAPPER_POSTTHUMB_LINKSELFCROPPEDFEED_VOLUNTEER],

        );
    }

    public function getConditionSucceededSubmodules(array $module)
    {
        $ret = parent::getConditionSucceededSubmodules($module);

        $layouts = array(
            self::MODULE_LAYOUTWRAPPER_POSTTHUMB_FAVICON => [GD_Custom_Module_Processor_PostThumbLayouts::class, GD_Custom_Module_Processor_PostThumbLayouts::MODULE_LAYOUT_POSTTHUMB_FAVICON],
            self::MODULE_LAYOUTWRAPPER_POSTTHUMB_ORIGINALFEATUREDIMAGE => [GD_Custom_Module_Processor_PostThumbLayouts::class, GD_Custom_Module_Processor_PostThumbLayouts::MODULE_LAYOUT_POSTTHUMB_ORIGINALFEATUREDIMAGE],
            self::MODULE_LAYOUTWRAPPER_POSTTHUMB_FEATUREDIMAGE => [GD_Custom_Module_Processor_PostThumbLayouts::class, GD_Custom_Module_Processor_PostThumbLayouts::MODULE_LAYOUT_POSTTHUMB_FEATUREDIMAGE],
            self::MODULE_LAYOUTWRAPPER_POSTTHUMB_FEATUREDIMAGE_VOLUNTEER => [GD_Custom_Module_Processor_PostThumbLayouts::class, GD_Custom_Module_Processor_PostThumbLayouts::MODULE_LAYOUT_POSTTHUMB_FEATUREDIMAGE_VOLUNTEER],
            self::MODULE_LAYOUTWRAPPER_POSTTHUMB_XSMALL => [GD_Custom_Module_Processor_PostThumbLayouts::class, GD_Custom_Module_Processor_PostThumbLayouts::MODULE_LAYOUT_POSTTHUMB_XSMALL],
            self::MODULE_LAYOUTWRAPPER_POSTTHUMB_CROPPEDSMALL => [GD_Custom_Module_Processor_PostThumbLayouts::class, GD_Custom_Module_Processor_PostThumbLayouts::MODULE_LAYOUT_POSTTHUMB_CROPPEDSMALL],
            self::MODULE_LAYOUTWRAPPER_POSTTHUMB_CROPPEDMEDIUM => [GD_Custom_Module_Processor_PostThumbLayouts::class, GD_Custom_Module_Processor_PostThumbLayouts::MODULE_LAYOUT_POSTTHUMB_CROPPEDMEDIUM],
            self::MODULE_LAYOUTWRAPPER_POSTTHUMB_CROPPEDFEED => [GD_Custom_Module_Processor_PostThumbLayouts::class, GD_Custom_Module_Processor_PostThumbLayouts::MODULE_LAYOUT_POSTTHUMB_CROPPEDFEED],
            self::MODULE_LAYOUTWRAPPER_POSTTHUMB_CROPPEDSMALL_EDIT => [GD_Custom_Module_Processor_PostThumbLayouts::class, GD_Custom_Module_Processor_PostThumbLayouts::MODULE_LAYOUT_POSTTHUMB_CROPPEDSMALL_EDIT],
            self::MODULE_LAYOUTWRAPPER_POSTTHUMB_CROPPEDSMALL_VOLUNTEER => [GD_Custom_Module_Processor_PostThumbLayouts::class, GD_Custom_Module_Processor_PostThumbLayouts::MODULE_LAYOUT_POSTTHUMB_CROPPEDSMALL_VOLUNTEER],
            self::MODULE_LAYOUTWRAPPER_POSTTHUMB_CROPPEDMEDIUM_VOLUNTEER => [GD_Custom_Module_Processor_PostThumbLayouts::class, GD_Custom_Module_Processor_PostThumbLayouts::MODULE_LAYOUT_POSTTHUMB_CROPPEDMEDIUM_VOLUNTEER],
            self::MODULE_LAYOUTWRAPPER_POSTTHUMB_CROPPEDFEED_VOLUNTEER => [GD_Custom_Module_Processor_PostThumbLayouts::class, GD_Custom_Module_Processor_PostThumbLayouts::MODULE_LAYOUT_POSTTHUMB_CROPPEDFEED_VOLUNTEER],
            self::MODULE_LAYOUTWRAPPER_POSTTHUMB_LINKSELFORIGINALFEATUREDIMAGE => [GD_Custom_Module_Processor_PostThumbLayouts::class, GD_Custom_Module_Processor_PostThumbLayouts::MODULE_LAYOUT_POSTTHUMB_LINKSELFORIGINALFEATUREDIMAGE],
            self::MODULE_LAYOUTWRAPPER_POSTTHUMB_LINKSELFCROPPEDFEED => [GD_Custom_Module_Processor_PostThumbLayouts::class, GD_Custom_Module_Processor_PostThumbLayouts::MODULE_LAYOUT_POSTTHUMB_LINKSELFCROPPEDFEED],
            self::MODULE_LAYOUTWRAPPER_POSTTHUMB_LINKSELFCROPPEDFEED_VOLUNTEER => [GD_Custom_Module_Processor_PostThumbLayouts::class, GD_Custom_Module_Processor_PostThumbLayouts::MODULE_LAYOUT_POSTTHUMB_LINKSELFCROPPEDFEED_VOLUNTEER],
        );

        if ($layout = $layouts[$module[1]] ?? null) {
            $ret[] = $layout;
        }

        return $ret;
    }

    public function getConditionField(array $module): ?string
    {
        switch ($module[1]) {
            case self::MODULE_LAYOUTWRAPPER_POSTTHUMB_FAVICON:
            case self::MODULE_LAYOUTWRAPPER_POSTTHUMB_ORIGINALFEATUREDIMAGE:
            case self::MODULE_LAYOUTWRAPPER_POSTTHUMB_FEATUREDIMAGE:
            case self::MODULE_LAYOUTWRAPPER_POSTTHUMB_FEATUREDIMAGE_VOLUNTEER:
            case self::MODULE_LAYOUTWRAPPER_POSTTHUMB_XSMALL:
            case self::MODULE_LAYOUTWRAPPER_POSTTHUMB_CROPPEDSMALL:
            case self::MODULE_LAYOUTWRAPPER_POSTTHUMB_CROPPEDMEDIUM:
            case self::MODULE_LAYOUTWRAPPER_POSTTHUMB_CROPPEDFEED:
            case self::MODULE_LAYOUTWRAPPER_POSTTHUMB_CROPPEDSMALL_EDIT:
            case self::MODULE_LAYOUTWRAPPER_POSTTHUMB_CROPPEDSMALL_VOLUNTEER:
            case self::MODULE_LAYOUTWRAPPER_POSTTHUMB_CROPPEDMEDIUM_VOLUNTEER:
            case self::MODULE_LAYOUTWRAPPER_POSTTHUMB_CROPPEDFEED_VOLUNTEER:
            case self::MODULE_LAYOUTWRAPPER_POSTTHUMB_LINKSELFORIGINALFEATUREDIMAGE:
            case self::MODULE_LAYOUTWRAPPER_POSTTHUMB_LINKSELFCROPPEDFEED:
            case self::MODULE_LAYOUTWRAPPER_POSTTHUMB_LINKSELFCROPPEDFEED_VOLUNTEER:
                return 'hasFeaturedImage';
        }

        return null;
    }

    public function initModelProps(array $module, array &$props): void
    {
        switch ($module[1]) {
            case self::MODULE_LAYOUTWRAPPER_POSTTHUMB_XSMALL:
            case self::MODULE_LAYOUTWRAPPER_POSTTHUMB_CROPPEDSMALL:
            case self::MODULE_LAYOUTWRAPPER_POSTTHUMB_CROPPEDMEDIUM:
            case self::MODULE_LAYOUTWRAPPER_POSTTHUMB_CROPPEDFEED:
            case self::MODULE_LAYOUTWRAPPER_POSTTHUMB_CROPPEDSMALL_EDIT:
            case self::MODULE_LAYOUTWRAPPER_POSTTHUMB_CROPPEDSMALL_VOLUNTEER:
            case self::MODULE_LAYOUTWRAPPER_POSTTHUMB_CROPPEDMEDIUM_VOLUNTEER:
            case self::MODULE_LAYOUTWRAPPER_POSTTHUMB_CROPPEDFEED_VOLUNTEER:
            case self::MODULE_LAYOUTWRAPPER_POSTTHUMB_LINKSELFORIGINALFEATUREDIMAGE:
            case self::MODULE_LAYOUTWRAPPER_POSTTHUMB_LINKSELFCROPPEDFEED:
            case self::MODULE_LAYOUTWRAPPER_POSTTHUMB_LINKSELFCROPPEDFEED_VOLUNTEER:
                $this->appendProp($module, $props, 'class', 'wrapper-featuredimage');
                break;
        }

        parent::initModelProps($module, $props);
    }
}




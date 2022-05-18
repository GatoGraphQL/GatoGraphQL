<?php

class GD_Custom_Module_Processor_PostThumbLayoutWrappers extends PoP_Module_Processor_ConditionWrapperBase
{
    public final const COMPONENT_LAYOUTWRAPPER_POSTTHUMB_FAVICON = 'layoutwrapper-postthumb-favicon';
    public final const COMPONENT_LAYOUTWRAPPER_POSTTHUMB_ORIGINALFEATUREDIMAGE = 'layoutwrapper-postthumb-originalfeaturedimage';
    public final const COMPONENT_LAYOUTWRAPPER_POSTTHUMB_FEATUREDIMAGE = 'layoutwrapper-postthumb-featuredimage';
    public final const COMPONENT_LAYOUTWRAPPER_POSTTHUMB_FEATUREDIMAGE_VOLUNTEER = 'layoutwrapper-postthumb-featuredimage-volunteer';
    public final const COMPONENT_LAYOUTWRAPPER_POSTTHUMB_XSMALL = 'layoutwrapper-postthumb-xsmall';
    public final const COMPONENT_LAYOUTWRAPPER_POSTTHUMB_CROPPEDSMALL = 'layoutwrapper-postthumb-croppedsmall';
    public final const COMPONENT_LAYOUTWRAPPER_POSTTHUMB_CROPPEDMEDIUM = 'layoutwrapper-postthumb-croppedmedium';
    public final const COMPONENT_LAYOUTWRAPPER_POSTTHUMB_CROPPEDFEED = 'layoutwrapper-postthumb-croppedfeed';
    public final const COMPONENT_LAYOUTWRAPPER_POSTTHUMB_CROPPEDSMALL_EDIT = 'layoutwrapper-postthumb-croppedsmall-edit';
    public final const COMPONENT_LAYOUTWRAPPER_POSTTHUMB_CROPPEDSMALL_VOLUNTEER = 'layoutwrapper-postthumb-croppedsmall-volunteer';
    public final const COMPONENT_LAYOUTWRAPPER_POSTTHUMB_CROPPEDMEDIUM_VOLUNTEER = 'layoutwrapper-postthumb-croppedmedium-volunteer';
    public final const COMPONENT_LAYOUTWRAPPER_POSTTHUMB_CROPPEDFEED_VOLUNTEER = 'layoutwrapper-postthumb-croppedfeed-volunteer';
    public final const COMPONENT_LAYOUTWRAPPER_POSTTHUMB_LINKSELFORIGINALFEATUREDIMAGE = 'layoutwrapper-postthumb-linkselforiginalfeaturedimage';
    public final const COMPONENT_LAYOUTWRAPPER_POSTTHUMB_LINKSELFCROPPEDFEED = 'layoutwrapper-postthumb-linkselfcroppedfeed';
    public final const COMPONENT_LAYOUTWRAPPER_POSTTHUMB_LINKSELFCROPPEDFEED_VOLUNTEER = 'layoutwrapper-postthumb-linkselfcroppedfeed-volunteer';

    public function getComponentsToProcess(): array
    {
        return array(
            [self::class, self::COMPONENT_LAYOUTWRAPPER_POSTTHUMB_FAVICON],
            [self::class, self::COMPONENT_LAYOUTWRAPPER_POSTTHUMB_ORIGINALFEATUREDIMAGE],
            [self::class, self::COMPONENT_LAYOUTWRAPPER_POSTTHUMB_FEATUREDIMAGE],
            [self::class, self::COMPONENT_LAYOUTWRAPPER_POSTTHUMB_FEATUREDIMAGE_VOLUNTEER],
            [self::class, self::COMPONENT_LAYOUTWRAPPER_POSTTHUMB_XSMALL],
            [self::class, self::COMPONENT_LAYOUTWRAPPER_POSTTHUMB_CROPPEDSMALL],
            [self::class, self::COMPONENT_LAYOUTWRAPPER_POSTTHUMB_CROPPEDMEDIUM],
            [self::class, self::COMPONENT_LAYOUTWRAPPER_POSTTHUMB_CROPPEDFEED],
            [self::class, self::COMPONENT_LAYOUTWRAPPER_POSTTHUMB_CROPPEDSMALL_EDIT],
            [self::class, self::COMPONENT_LAYOUTWRAPPER_POSTTHUMB_CROPPEDSMALL_VOLUNTEER],
            [self::class, self::COMPONENT_LAYOUTWRAPPER_POSTTHUMB_CROPPEDMEDIUM_VOLUNTEER],
            [self::class, self::COMPONENT_LAYOUTWRAPPER_POSTTHUMB_CROPPEDFEED_VOLUNTEER],
            [self::class, self::COMPONENT_LAYOUTWRAPPER_POSTTHUMB_LINKSELFORIGINALFEATUREDIMAGE],
            [self::class, self::COMPONENT_LAYOUTWRAPPER_POSTTHUMB_LINKSELFCROPPEDFEED],
            [self::class, self::COMPONENT_LAYOUTWRAPPER_POSTTHUMB_LINKSELFCROPPEDFEED_VOLUNTEER],

        );
    }

    public function getConditionSucceededSubmodules(array $component)
    {
        $ret = parent::getConditionSucceededSubmodules($component);

        $layouts = array(
            self::COMPONENT_LAYOUTWRAPPER_POSTTHUMB_FAVICON => [GD_Custom_Module_Processor_PostThumbLayouts::class, GD_Custom_Module_Processor_PostThumbLayouts::COMPONENT_LAYOUT_POSTTHUMB_FAVICON],
            self::COMPONENT_LAYOUTWRAPPER_POSTTHUMB_ORIGINALFEATUREDIMAGE => [GD_Custom_Module_Processor_PostThumbLayouts::class, GD_Custom_Module_Processor_PostThumbLayouts::COMPONENT_LAYOUT_POSTTHUMB_ORIGINALFEATUREDIMAGE],
            self::COMPONENT_LAYOUTWRAPPER_POSTTHUMB_FEATUREDIMAGE => [GD_Custom_Module_Processor_PostThumbLayouts::class, GD_Custom_Module_Processor_PostThumbLayouts::COMPONENT_LAYOUT_POSTTHUMB_FEATUREDIMAGE],
            self::COMPONENT_LAYOUTWRAPPER_POSTTHUMB_FEATUREDIMAGE_VOLUNTEER => [GD_Custom_Module_Processor_PostThumbLayouts::class, GD_Custom_Module_Processor_PostThumbLayouts::COMPONENT_LAYOUT_POSTTHUMB_FEATUREDIMAGE_VOLUNTEER],
            self::COMPONENT_LAYOUTWRAPPER_POSTTHUMB_XSMALL => [GD_Custom_Module_Processor_PostThumbLayouts::class, GD_Custom_Module_Processor_PostThumbLayouts::COMPONENT_LAYOUT_POSTTHUMB_XSMALL],
            self::COMPONENT_LAYOUTWRAPPER_POSTTHUMB_CROPPEDSMALL => [GD_Custom_Module_Processor_PostThumbLayouts::class, GD_Custom_Module_Processor_PostThumbLayouts::COMPONENT_LAYOUT_POSTTHUMB_CROPPEDSMALL],
            self::COMPONENT_LAYOUTWRAPPER_POSTTHUMB_CROPPEDMEDIUM => [GD_Custom_Module_Processor_PostThumbLayouts::class, GD_Custom_Module_Processor_PostThumbLayouts::COMPONENT_LAYOUT_POSTTHUMB_CROPPEDMEDIUM],
            self::COMPONENT_LAYOUTWRAPPER_POSTTHUMB_CROPPEDFEED => [GD_Custom_Module_Processor_PostThumbLayouts::class, GD_Custom_Module_Processor_PostThumbLayouts::COMPONENT_LAYOUT_POSTTHUMB_CROPPEDFEED],
            self::COMPONENT_LAYOUTWRAPPER_POSTTHUMB_CROPPEDSMALL_EDIT => [GD_Custom_Module_Processor_PostThumbLayouts::class, GD_Custom_Module_Processor_PostThumbLayouts::COMPONENT_LAYOUT_POSTTHUMB_CROPPEDSMALL_EDIT],
            self::COMPONENT_LAYOUTWRAPPER_POSTTHUMB_CROPPEDSMALL_VOLUNTEER => [GD_Custom_Module_Processor_PostThumbLayouts::class, GD_Custom_Module_Processor_PostThumbLayouts::COMPONENT_LAYOUT_POSTTHUMB_CROPPEDSMALL_VOLUNTEER],
            self::COMPONENT_LAYOUTWRAPPER_POSTTHUMB_CROPPEDMEDIUM_VOLUNTEER => [GD_Custom_Module_Processor_PostThumbLayouts::class, GD_Custom_Module_Processor_PostThumbLayouts::COMPONENT_LAYOUT_POSTTHUMB_CROPPEDMEDIUM_VOLUNTEER],
            self::COMPONENT_LAYOUTWRAPPER_POSTTHUMB_CROPPEDFEED_VOLUNTEER => [GD_Custom_Module_Processor_PostThumbLayouts::class, GD_Custom_Module_Processor_PostThumbLayouts::COMPONENT_LAYOUT_POSTTHUMB_CROPPEDFEED_VOLUNTEER],
            self::COMPONENT_LAYOUTWRAPPER_POSTTHUMB_LINKSELFORIGINALFEATUREDIMAGE => [GD_Custom_Module_Processor_PostThumbLayouts::class, GD_Custom_Module_Processor_PostThumbLayouts::COMPONENT_LAYOUT_POSTTHUMB_LINKSELFORIGINALFEATUREDIMAGE],
            self::COMPONENT_LAYOUTWRAPPER_POSTTHUMB_LINKSELFCROPPEDFEED => [GD_Custom_Module_Processor_PostThumbLayouts::class, GD_Custom_Module_Processor_PostThumbLayouts::COMPONENT_LAYOUT_POSTTHUMB_LINKSELFCROPPEDFEED],
            self::COMPONENT_LAYOUTWRAPPER_POSTTHUMB_LINKSELFCROPPEDFEED_VOLUNTEER => [GD_Custom_Module_Processor_PostThumbLayouts::class, GD_Custom_Module_Processor_PostThumbLayouts::COMPONENT_LAYOUT_POSTTHUMB_LINKSELFCROPPEDFEED_VOLUNTEER],
        );

        if ($layout = $layouts[$component[1]] ?? null) {
            $ret[] = $layout;
        }

        return $ret;
    }

    public function getConditionField(array $component): ?string
    {
        switch ($component[1]) {
            case self::COMPONENT_LAYOUTWRAPPER_POSTTHUMB_FAVICON:
            case self::COMPONENT_LAYOUTWRAPPER_POSTTHUMB_ORIGINALFEATUREDIMAGE:
            case self::COMPONENT_LAYOUTWRAPPER_POSTTHUMB_FEATUREDIMAGE:
            case self::COMPONENT_LAYOUTWRAPPER_POSTTHUMB_FEATUREDIMAGE_VOLUNTEER:
            case self::COMPONENT_LAYOUTWRAPPER_POSTTHUMB_XSMALL:
            case self::COMPONENT_LAYOUTWRAPPER_POSTTHUMB_CROPPEDSMALL:
            case self::COMPONENT_LAYOUTWRAPPER_POSTTHUMB_CROPPEDMEDIUM:
            case self::COMPONENT_LAYOUTWRAPPER_POSTTHUMB_CROPPEDFEED:
            case self::COMPONENT_LAYOUTWRAPPER_POSTTHUMB_CROPPEDSMALL_EDIT:
            case self::COMPONENT_LAYOUTWRAPPER_POSTTHUMB_CROPPEDSMALL_VOLUNTEER:
            case self::COMPONENT_LAYOUTWRAPPER_POSTTHUMB_CROPPEDMEDIUM_VOLUNTEER:
            case self::COMPONENT_LAYOUTWRAPPER_POSTTHUMB_CROPPEDFEED_VOLUNTEER:
            case self::COMPONENT_LAYOUTWRAPPER_POSTTHUMB_LINKSELFORIGINALFEATUREDIMAGE:
            case self::COMPONENT_LAYOUTWRAPPER_POSTTHUMB_LINKSELFCROPPEDFEED:
            case self::COMPONENT_LAYOUTWRAPPER_POSTTHUMB_LINKSELFCROPPEDFEED_VOLUNTEER:
                return 'hasFeaturedImage';
        }

        return null;
    }

    public function initModelProps(array $component, array &$props): void
    {
        switch ($component[1]) {
            case self::COMPONENT_LAYOUTWRAPPER_POSTTHUMB_XSMALL:
            case self::COMPONENT_LAYOUTWRAPPER_POSTTHUMB_CROPPEDSMALL:
            case self::COMPONENT_LAYOUTWRAPPER_POSTTHUMB_CROPPEDMEDIUM:
            case self::COMPONENT_LAYOUTWRAPPER_POSTTHUMB_CROPPEDFEED:
            case self::COMPONENT_LAYOUTWRAPPER_POSTTHUMB_CROPPEDSMALL_EDIT:
            case self::COMPONENT_LAYOUTWRAPPER_POSTTHUMB_CROPPEDSMALL_VOLUNTEER:
            case self::COMPONENT_LAYOUTWRAPPER_POSTTHUMB_CROPPEDMEDIUM_VOLUNTEER:
            case self::COMPONENT_LAYOUTWRAPPER_POSTTHUMB_CROPPEDFEED_VOLUNTEER:
            case self::COMPONENT_LAYOUTWRAPPER_POSTTHUMB_LINKSELFORIGINALFEATUREDIMAGE:
            case self::COMPONENT_LAYOUTWRAPPER_POSTTHUMB_LINKSELFCROPPEDFEED:
            case self::COMPONENT_LAYOUTWRAPPER_POSTTHUMB_LINKSELFCROPPEDFEED_VOLUNTEER:
                $this->appendProp($component, $props, 'class', 'wrapper-featuredimage');
                break;
        }

        parent::initModelProps($component, $props);
    }
}




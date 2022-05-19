<?php

class GD_Custom_Module_Processor_CustomScrolls extends PoP_Module_Processor_ScrollsBase
{
    public final const COMPONENT_SCROLL_WHOWEARE_DETAILS = 'scroll-whoweare-details';
    public final const COMPONENT_SCROLL_WHOWEARE_THUMBNAIL = 'scroll-whoweare-thumbnail';
    public final const COMPONENT_SCROLL_WHOWEARE_LIST = 'scroll-whoweare-list';
    public final const COMPONENT_SCROLL_WHOWEARE_FULLVIEW = 'scroll-whoweare-fullview';

    public function getComponentsToProcess(): array
    {
        return array(
            [self::class, self::COMPONENT_SCROLL_WHOWEARE_DETAILS],
            [self::class, self::COMPONENT_SCROLL_WHOWEARE_THUMBNAIL],
            [self::class, self::COMPONENT_SCROLL_WHOWEARE_LIST],
            [self::class, self::COMPONENT_SCROLL_WHOWEARE_FULLVIEW],
        );
    }

    public function getInnerSubcomponent(array $component)
    {
        $inners = array(
            self::COMPONENT_SCROLL_WHOWEARE_DETAILS => [GD_Custom_Module_Processor_CustomScrollInners::class, GD_Custom_Module_Processor_CustomScrollInners::COMPONENT_SCROLLINNER_WHOWEARE_DETAILS],
            self::COMPONENT_SCROLL_WHOWEARE_THUMBNAIL => [GD_Custom_Module_Processor_CustomScrollInners::class, GD_Custom_Module_Processor_CustomScrollInners::COMPONENT_SCROLLINNER_WHOWEARE_THUMBNAIL],
            self::COMPONENT_SCROLL_WHOWEARE_LIST => [GD_Custom_Module_Processor_CustomScrollInners::class, GD_Custom_Module_Processor_CustomScrollInners::COMPONENT_SCROLLINNER_WHOWEARE_LIST],
            self::COMPONENT_SCROLL_WHOWEARE_FULLVIEW => [GD_Custom_Module_Processor_CustomScrollInners::class, GD_Custom_Module_Processor_CustomScrollInners::COMPONENT_SCROLLINNER_WHOWEARE_FULLVIEW],
        );

        if ($inner = $inners[$component[1]] ?? null) {
            return $inner;
        }

        return parent::getInnerSubcomponent($component);
    }

    public function getFetchmoreButtonSubcomponent(array $component)
    {
        switch ($component[1]) {
            case self::COMPONENT_SCROLL_WHOWEARE_DETAILS:
            case self::COMPONENT_SCROLL_WHOWEARE_THUMBNAIL:
            case self::COMPONENT_SCROLL_WHOWEARE_LIST:
            case self::COMPONENT_SCROLL_WHOWEARE_FULLVIEW:
                return null;
        }

        return parent::getFetchmoreButtonSubcomponent($component);
    }

    public function initModelProps(array $component, array &$props): void
    {

        // Extra classes
        $thumbnails = array(
            [self::class, self::COMPONENT_SCROLL_WHOWEARE_THUMBNAIL],
        );
        $details = array(
            [self::class, self::COMPONENT_SCROLL_WHOWEARE_DETAILS],
        );
        $lists = array(
            [self::class, self::COMPONENT_SCROLL_WHOWEARE_LIST],
        );
        $fullviews = array(
            [self::class, self::COMPONENT_SCROLL_WHOWEARE_FULLVIEW],
        );

        $extra_class = '';
        if (in_array($component, $details)) {
            $extra_class = 'details';
        } elseif (in_array($component, $thumbnails)) {
            $extra_class = 'thumb';
        } elseif (in_array($component, $fullviews)) {
            $extra_class = 'fullview';
        } elseif (in_array($component, $lists)) {
            $extra_class = 'list';
        }
        $this->appendProp($component, $props, 'class', $extra_class);

        parent::initModelProps($component, $props);
    }
}



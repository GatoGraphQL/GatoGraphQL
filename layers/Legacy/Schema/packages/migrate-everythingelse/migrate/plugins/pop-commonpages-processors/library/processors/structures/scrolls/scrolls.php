<?php

class GD_Custom_Module_Processor_CustomScrolls extends PoP_Module_Processor_ScrollsBase
{
    public final const MODULE_SCROLL_WHOWEARE_DETAILS = 'scroll-whoweare-details';
    public final const MODULE_SCROLL_WHOWEARE_THUMBNAIL = 'scroll-whoweare-thumbnail';
    public final const MODULE_SCROLL_WHOWEARE_LIST = 'scroll-whoweare-list';
    public final const MODULE_SCROLL_WHOWEARE_FULLVIEW = 'scroll-whoweare-fullview';

    public function getComponentVariationsToProcess(): array
    {
        return array(
            [self::class, self::MODULE_SCROLL_WHOWEARE_DETAILS],
            [self::class, self::MODULE_SCROLL_WHOWEARE_THUMBNAIL],
            [self::class, self::MODULE_SCROLL_WHOWEARE_LIST],
            [self::class, self::MODULE_SCROLL_WHOWEARE_FULLVIEW],
        );
    }

    public function getInnerSubmodule(array $componentVariation)
    {
        $inners = array(
            self::MODULE_SCROLL_WHOWEARE_DETAILS => [GD_Custom_Module_Processor_CustomScrollInners::class, GD_Custom_Module_Processor_CustomScrollInners::MODULE_SCROLLINNER_WHOWEARE_DETAILS],
            self::MODULE_SCROLL_WHOWEARE_THUMBNAIL => [GD_Custom_Module_Processor_CustomScrollInners::class, GD_Custom_Module_Processor_CustomScrollInners::MODULE_SCROLLINNER_WHOWEARE_THUMBNAIL],
            self::MODULE_SCROLL_WHOWEARE_LIST => [GD_Custom_Module_Processor_CustomScrollInners::class, GD_Custom_Module_Processor_CustomScrollInners::MODULE_SCROLLINNER_WHOWEARE_LIST],
            self::MODULE_SCROLL_WHOWEARE_FULLVIEW => [GD_Custom_Module_Processor_CustomScrollInners::class, GD_Custom_Module_Processor_CustomScrollInners::MODULE_SCROLLINNER_WHOWEARE_FULLVIEW],
        );

        if ($inner = $inners[$componentVariation[1]] ?? null) {
            return $inner;
        }

        return parent::getInnerSubmodule($componentVariation);
    }

    public function getFetchmoreButtonSubmodule(array $componentVariation)
    {
        switch ($componentVariation[1]) {
            case self::MODULE_SCROLL_WHOWEARE_DETAILS:
            case self::MODULE_SCROLL_WHOWEARE_THUMBNAIL:
            case self::MODULE_SCROLL_WHOWEARE_LIST:
            case self::MODULE_SCROLL_WHOWEARE_FULLVIEW:
                return null;
        }

        return parent::getFetchmoreButtonSubmodule($componentVariation);
    }

    public function initModelProps(array $componentVariation, array &$props): void
    {

        // Extra classes
        $thumbnails = array(
            [self::class, self::MODULE_SCROLL_WHOWEARE_THUMBNAIL],
        );
        $details = array(
            [self::class, self::MODULE_SCROLL_WHOWEARE_DETAILS],
        );
        $lists = array(
            [self::class, self::MODULE_SCROLL_WHOWEARE_LIST],
        );
        $fullviews = array(
            [self::class, self::MODULE_SCROLL_WHOWEARE_FULLVIEW],
        );

        $extra_class = '';
        if (in_array($componentVariation, $details)) {
            $extra_class = 'details';
        } elseif (in_array($componentVariation, $thumbnails)) {
            $extra_class = 'thumb';
        } elseif (in_array($componentVariation, $fullviews)) {
            $extra_class = 'fullview';
        } elseif (in_array($componentVariation, $lists)) {
            $extra_class = 'list';
        }
        $this->appendProp($componentVariation, $props, 'class', $extra_class);

        parent::initModelProps($componentVariation, $props);
    }
}



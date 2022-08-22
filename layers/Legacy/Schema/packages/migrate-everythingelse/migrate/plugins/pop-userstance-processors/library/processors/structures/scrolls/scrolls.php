<?php

class UserStance_Module_Processor_CustomScrolls extends PoP_Module_Processor_ScrollsBase
{
    public final const COMPONENT_SCROLL_MYSTANCES_FULLVIEWPREVIEW = 'scroll-mystances-fullviewpreview';
    public final const COMPONENT_SCROLL_STANCES_NAVIGATOR = 'scroll-stances-navigator';
    public final const COMPONENT_SCROLL_STANCES_ADDONS = 'scroll-stances-addons';
    public final const COMPONENT_SCROLL_STANCES_FULLVIEW = 'scroll-stances-fullview';
    public final const COMPONENT_SCROLL_STANCES_THUMBNAIL = 'scroll-stances-thumbnail';
    public final const COMPONENT_SCROLL_STANCES_LIST = 'scroll-stances-list';
    public final const COMPONENT_SCROLL_AUTHORSTANCES_FULLVIEW = 'scroll-authorstances-fullview';
    public final const COMPONENT_SCROLL_AUTHORSTANCES_THUMBNAIL = 'scroll-authorstances-thumbnail';
    public final const COMPONENT_SCROLL_AUTHORSTANCES_LIST = 'scroll-authorstances-list';
    public final const COMPONENT_SCROLL_SINGLERELATEDSTANCECONTENT_FULLVIEW = 'scroll-singlerelatedstancecontent-fullview';

    /**
     * @return string[]
     */
    public function getComponentNamesToProcess(): array
    {
        return array(
            self::COMPONENT_SCROLL_MYSTANCES_FULLVIEWPREVIEW,
            self::COMPONENT_SCROLL_STANCES_NAVIGATOR,
            self::COMPONENT_SCROLL_STANCES_ADDONS,
            self::COMPONENT_SCROLL_STANCES_FULLVIEW,
            self::COMPONENT_SCROLL_STANCES_THUMBNAIL,
            self::COMPONENT_SCROLL_STANCES_LIST,
            self::COMPONENT_SCROLL_AUTHORSTANCES_FULLVIEW,
            self::COMPONENT_SCROLL_AUTHORSTANCES_THUMBNAIL,
            self::COMPONENT_SCROLL_AUTHORSTANCES_LIST,
            self::COMPONENT_SCROLL_SINGLERELATEDSTANCECONTENT_FULLVIEW,
        );
    }


    public function getInnerSubcomponent(\PoP\ComponentModel\Component\Component $component)
    {
        $inners = array(
            self::COMPONENT_SCROLL_MYSTANCES_FULLVIEWPREVIEW => [UserStance_Module_Processor_CustomScrollInners::class, UserStance_Module_Processor_CustomScrollInners::COMPONENT_SCROLLINNER_MYSTANCES_FULLVIEWPREVIEW],
            self::COMPONENT_SCROLL_STANCES_NAVIGATOR => [UserStance_Module_Processor_CustomScrollInners::class, UserStance_Module_Processor_CustomScrollInners::COMPONENT_SCROLLINNER_STANCES_NAVIGATOR],
            self::COMPONENT_SCROLL_STANCES_ADDONS => [UserStance_Module_Processor_CustomScrollInners::class, UserStance_Module_Processor_CustomScrollInners::COMPONENT_SCROLLINNER_STANCES_ADDONS],
            self::COMPONENT_SCROLL_STANCES_FULLVIEW => [UserStance_Module_Processor_CustomScrollInners::class, UserStance_Module_Processor_CustomScrollInners::COMPONENT_SCROLLINNER_STANCES_FULLVIEW],
            self::COMPONENT_SCROLL_STANCES_THUMBNAIL => [UserStance_Module_Processor_CustomScrollInners::class, UserStance_Module_Processor_CustomScrollInners::COMPONENT_SCROLLINNER_STANCES_THUMBNAIL],
            self::COMPONENT_SCROLL_STANCES_LIST => [UserStance_Module_Processor_CustomScrollInners::class, UserStance_Module_Processor_CustomScrollInners::COMPONENT_SCROLLINNER_STANCES_LIST],
            self::COMPONENT_SCROLL_AUTHORSTANCES_FULLVIEW => [UserStance_Module_Processor_CustomScrollInners::class, UserStance_Module_Processor_CustomScrollInners::COMPONENT_SCROLLINNER_AUTHORSTANCES_FULLVIEW],
            self::COMPONENT_SCROLL_AUTHORSTANCES_THUMBNAIL => [UserStance_Module_Processor_CustomScrollInners::class, UserStance_Module_Processor_CustomScrollInners::COMPONENT_SCROLLINNER_AUTHORSTANCES_THUMBNAIL],
            self::COMPONENT_SCROLL_AUTHORSTANCES_LIST => [UserStance_Module_Processor_CustomScrollInners::class, UserStance_Module_Processor_CustomScrollInners::COMPONENT_SCROLLINNER_AUTHORSTANCES_LIST],
            self::COMPONENT_SCROLL_SINGLERELATEDSTANCECONTENT_FULLVIEW => [UserStance_Module_Processor_CustomScrollInners::class, UserStance_Module_Processor_CustomScrollInners::COMPONENT_SCROLLINNER_SINGLERELATEDSTANCECONTENT_FULLVIEW],
        );

        if ($inner = $inners[$component->name] ?? null) {
            return $inner;
        }

        return parent::getInnerSubcomponent($component);
    }

    public function initModelProps(\PoP\ComponentModel\Component\Component $component, array &$props): void
    {
        // Extra classes
        $independentitem_thumbnails = array(
            self::COMPONENT_SCROLL_STANCES_THUMBNAIL,
            self::COMPONENT_SCROLL_AUTHORSTANCES_THUMBNAIL,
        );
        $independentitem_lists = array(
            self::COMPONENT_SCROLL_STANCES_LIST,
            self::COMPONENT_SCROLL_AUTHORSTANCES_LIST,
        );
        $navigators = array(
            self::COMPONENT_SCROLL_STANCES_NAVIGATOR,
        );
        $addons = array(
            self::COMPONENT_SCROLL_STANCES_ADDONS,
        );
        $fullviews = array(
            self::COMPONENT_SCROLL_MYSTANCES_FULLVIEWPREVIEW,
            self::COMPONENT_SCROLL_STANCES_FULLVIEW,
            self::COMPONENT_SCROLL_AUTHORSTANCES_FULLVIEW,
            self::COMPONENT_SCROLL_SINGLERELATEDSTANCECONTENT_FULLVIEW,
        );

        $extra_class = '';
        if (in_array($component, $navigators)) {
            $extra_class = 'navigator';
        } elseif (in_array($component, $addons)) {
            $extra_class = 'addons';
        } elseif (in_array($component, $fullviews)) {
            $extra_class = 'fullview';
        }
        elseif (in_array($component, $independentitem_thumbnails)) {
            $extra_class = 'thumb independent';
        } elseif (in_array($component, $independentitem_lists)) {
            $extra_class = 'list independent';
        }
        $this->appendProp($component, $props, 'class', $extra_class);


        $inner = $this->getInnerSubcomponent($component);
        if (in_array($component, $navigators)) {
            // Make it activeItem: highlight on viewing the corresponding fullview
            $this->appendProp($inner, $props, 'class', 'pop-activeitem');
        }

        parent::initModelProps($component, $props);
    }
}



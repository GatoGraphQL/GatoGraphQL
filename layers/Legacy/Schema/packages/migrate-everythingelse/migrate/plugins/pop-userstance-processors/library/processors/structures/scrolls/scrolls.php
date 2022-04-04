<?php

class UserStance_Module_Processor_CustomScrolls extends PoP_Module_Processor_ScrollsBase
{
    public final const MODULE_SCROLL_MYSTANCES_FULLVIEWPREVIEW = 'scroll-mystances-fullviewpreview';
    public final const MODULE_SCROLL_STANCES_NAVIGATOR = 'scroll-stances-navigator';
    public final const MODULE_SCROLL_STANCES_ADDONS = 'scroll-stances-addons';
    public final const MODULE_SCROLL_STANCES_FULLVIEW = 'scroll-stances-fullview';
    public final const MODULE_SCROLL_STANCES_THUMBNAIL = 'scroll-stances-thumbnail';
    public final const MODULE_SCROLL_STANCES_LIST = 'scroll-stances-list';
    public final const MODULE_SCROLL_AUTHORSTANCES_FULLVIEW = 'scroll-authorstances-fullview';
    public final const MODULE_SCROLL_AUTHORSTANCES_THUMBNAIL = 'scroll-authorstances-thumbnail';
    public final const MODULE_SCROLL_AUTHORSTANCES_LIST = 'scroll-authorstances-list';
    public final const MODULE_SCROLL_SINGLERELATEDSTANCECONTENT_FULLVIEW = 'scroll-singlerelatedstancecontent-fullview';

    public function getModulesToProcess(): array
    {
        return array(
            [self::class, self::MODULE_SCROLL_MYSTANCES_FULLVIEWPREVIEW],
            [self::class, self::MODULE_SCROLL_STANCES_NAVIGATOR],
            [self::class, self::MODULE_SCROLL_STANCES_ADDONS],
            [self::class, self::MODULE_SCROLL_STANCES_FULLVIEW],
            [self::class, self::MODULE_SCROLL_STANCES_THUMBNAIL],
            [self::class, self::MODULE_SCROLL_STANCES_LIST],
            [self::class, self::MODULE_SCROLL_AUTHORSTANCES_FULLVIEW],
            [self::class, self::MODULE_SCROLL_AUTHORSTANCES_THUMBNAIL],
            [self::class, self::MODULE_SCROLL_AUTHORSTANCES_LIST],
            [self::class, self::MODULE_SCROLL_SINGLERELATEDSTANCECONTENT_FULLVIEW],
        );
    }


    public function getInnerSubmodule(array $module)
    {
        $inners = array(
            self::MODULE_SCROLL_MYSTANCES_FULLVIEWPREVIEW => [UserStance_Module_Processor_CustomScrollInners::class, UserStance_Module_Processor_CustomScrollInners::MODULE_SCROLLINNER_MYSTANCES_FULLVIEWPREVIEW],
            self::MODULE_SCROLL_STANCES_NAVIGATOR => [UserStance_Module_Processor_CustomScrollInners::class, UserStance_Module_Processor_CustomScrollInners::MODULE_SCROLLINNER_STANCES_NAVIGATOR],
            self::MODULE_SCROLL_STANCES_ADDONS => [UserStance_Module_Processor_CustomScrollInners::class, UserStance_Module_Processor_CustomScrollInners::MODULE_SCROLLINNER_STANCES_ADDONS],
            self::MODULE_SCROLL_STANCES_FULLVIEW => [UserStance_Module_Processor_CustomScrollInners::class, UserStance_Module_Processor_CustomScrollInners::MODULE_SCROLLINNER_STANCES_FULLVIEW],
            self::MODULE_SCROLL_STANCES_THUMBNAIL => [UserStance_Module_Processor_CustomScrollInners::class, UserStance_Module_Processor_CustomScrollInners::MODULE_SCROLLINNER_STANCES_THUMBNAIL],
            self::MODULE_SCROLL_STANCES_LIST => [UserStance_Module_Processor_CustomScrollInners::class, UserStance_Module_Processor_CustomScrollInners::MODULE_SCROLLINNER_STANCES_LIST],
            self::MODULE_SCROLL_AUTHORSTANCES_FULLVIEW => [UserStance_Module_Processor_CustomScrollInners::class, UserStance_Module_Processor_CustomScrollInners::MODULE_SCROLLINNER_AUTHORSTANCES_FULLVIEW],
            self::MODULE_SCROLL_AUTHORSTANCES_THUMBNAIL => [UserStance_Module_Processor_CustomScrollInners::class, UserStance_Module_Processor_CustomScrollInners::MODULE_SCROLLINNER_AUTHORSTANCES_THUMBNAIL],
            self::MODULE_SCROLL_AUTHORSTANCES_LIST => [UserStance_Module_Processor_CustomScrollInners::class, UserStance_Module_Processor_CustomScrollInners::MODULE_SCROLLINNER_AUTHORSTANCES_LIST],
            self::MODULE_SCROLL_SINGLERELATEDSTANCECONTENT_FULLVIEW => [UserStance_Module_Processor_CustomScrollInners::class, UserStance_Module_Processor_CustomScrollInners::MODULE_SCROLLINNER_SINGLERELATEDSTANCECONTENT_FULLVIEW],
        );

        if ($inner = $inners[$module[1]] ?? null) {
            return $inner;
        }

        return parent::getInnerSubmodule($module);
    }

    public function initModelProps(array $module, array &$props): void
    {
        // Extra classes
        $independentitem_thumbnails = array(
            [self::class, self::MODULE_SCROLL_STANCES_THUMBNAIL],
            [self::class, self::MODULE_SCROLL_AUTHORSTANCES_THUMBNAIL],
        );
        $independentitem_lists = array(
            [self::class, self::MODULE_SCROLL_STANCES_LIST],
            [self::class, self::MODULE_SCROLL_AUTHORSTANCES_LIST],
        );
        $navigators = array(
            [self::class, self::MODULE_SCROLL_STANCES_NAVIGATOR],
        );
        $addons = array(
            [self::class, self::MODULE_SCROLL_STANCES_ADDONS],
        );
        $fullviews = array(
            [self::class, self::MODULE_SCROLL_MYSTANCES_FULLVIEWPREVIEW],
            [self::class, self::MODULE_SCROLL_STANCES_FULLVIEW],
            [self::class, self::MODULE_SCROLL_AUTHORSTANCES_FULLVIEW],
            [self::class, self::MODULE_SCROLL_SINGLERELATEDSTANCECONTENT_FULLVIEW],
        );

        $extra_class = '';
        if (in_array($module, $navigators)) {
            $extra_class = 'navigator';
        } elseif (in_array($module, $addons)) {
            $extra_class = 'addons';
        } elseif (in_array($module, $fullviews)) {
            $extra_class = 'fullview';
        }
        elseif (in_array($module, $independentitem_thumbnails)) {
            $extra_class = 'thumb independent';
        } elseif (in_array($module, $independentitem_lists)) {
            $extra_class = 'list independent';
        }
        $this->appendProp($module, $props, 'class', $extra_class);


        $inner = $this->getInnerSubmodule($module);
        if (in_array($module, $navigators)) {
            // Make it activeItem: highlight on viewing the corresponding fullview
            $this->appendProp($inner, $props, 'class', 'pop-activeitem');
        }

        parent::initModelProps($module, $props);
    }
}



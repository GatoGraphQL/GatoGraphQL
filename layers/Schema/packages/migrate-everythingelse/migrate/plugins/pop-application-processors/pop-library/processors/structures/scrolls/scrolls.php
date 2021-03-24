<?php

class PoP_Module_Processor_CustomScrolls extends PoP_Module_Processor_ScrollsBase
{
    public const MODULE_SCROLL_CONTENT_NAVIGATOR = 'scroll-content-navigator';
    public const MODULE_SCROLL_HIGHLIGHTS_NAVIGATOR = 'scroll-highlights-navigator';
    public const MODULE_SCROLL_POSTS_NAVIGATOR = 'scroll-posts-navigator';
    public const MODULE_SCROLL_USERS_NAVIGATOR = 'scroll-users-navigator';
    public const MODULE_SCROLL_USER_NAVIGATOR = 'scroll-user-navigator';
    public const MODULE_SCROLL_CONTENT_ADDONS = 'scroll-content-addons';
    public const MODULE_SCROLL_HIGHLIGHTS_ADDONS = 'scroll-highlights-addons';
    public const MODULE_SCROLL_POSTS_ADDONS = 'scroll-posts-addons';
    public const MODULE_SCROLL_USERS_ADDONS = 'scroll-users-addons';
    public const MODULE_SCROLL_USER_ADDONS = 'scroll-user-addons';
    public const MODULE_SCROLL_CONTENT_DETAILS = 'scroll-content-details';
    public const MODULE_SCROLL_POSTS_DETAILS = 'scroll-posts-details';
    public const MODULE_SCROLL_TAGS_DETAILS = 'scroll-tags-details';
    public const MODULE_SCROLL_USERS_DETAILS = 'scroll-users-details';
    public const MODULE_SCROLL_USER_DETAILS = 'scroll-user-details';
    public const MODULE_SCROLL_CONTENT_SIMPLEVIEW = 'scroll-content-simpleview';
    public const MODULE_SCROLL_POSTS_SIMPLEVIEW = 'scroll-posts-simpleview';
    public const MODULE_SCROLL_CONTENT_FULLVIEW = 'scroll-content-fullview';
    public const MODULE_SCROLL_HIGHLIGHTS_FULLVIEW = 'scroll-highlights-fullview';
    public const MODULE_SCROLL_POSTS_FULLVIEW = 'scroll-posts-fullview';
    public const MODULE_SCROLL_USERS_FULLVIEW = 'scroll-users-fullview';
    public const MODULE_SCROLL_USER_FULLVIEW = 'scroll-user-fullview';
    public const MODULE_SCROLL_AUTHORCONTENT_FULLVIEW = 'scroll-authorcontent-fullview';
    public const MODULE_SCROLL_AUTHORHIGHLIGHTS_FULLVIEW = 'scroll-authorhighlights-fullview';
    public const MODULE_SCROLL_AUTHORPOSTS_FULLVIEW = 'scroll-authorposts-fullview';
    public const MODULE_SCROLL_SINGLERELATEDCONTENT_FULLVIEW = 'scroll-singlerelatedcontent-fullview';
    public const MODULE_SCROLL_CONTENT_THUMBNAIL = 'scroll-content-thumbnail';
    public const MODULE_SCROLL_HIGHLIGHTS_THUMBNAIL = 'scroll-highlights-thumbnail';
    public const MODULE_SCROLL_POSTS_THUMBNAIL = 'scroll-posts-thumbnail';
    public const MODULE_SCROLL_USERS_THUMBNAIL = 'scroll-users-thumbnail';
    public const MODULE_SCROLL_USER_THUMBNAIL = 'scroll-user-thumbnail';
    public const MODULE_SCROLL_CONTENT_LIST = 'scroll-content-list';
    public const MODULE_SCROLL_HIGHLIGHTS_LIST = 'scroll-highlights-list';
    public const MODULE_SCROLL_POSTS_LIST = 'scroll-posts-list';
    public const MODULE_SCROLL_TAGS_LIST = 'scroll-tags-list';
    public const MODULE_SCROLL_POSTS_LINE = 'scroll-posts-line';
    public const MODULE_SCROLL_USERS_LIST = 'scroll-users-list';
    public const MODULE_SCROLL_USER_LIST = 'scroll-user-list';

    public function getModulesToProcess(): array
    {
        return array(
            [self::class, self::MODULE_SCROLL_CONTENT_NAVIGATOR],
            [self::class, self::MODULE_SCROLL_HIGHLIGHTS_NAVIGATOR],
            [self::class, self::MODULE_SCROLL_POSTS_NAVIGATOR],
            [self::class, self::MODULE_SCROLL_USERS_NAVIGATOR],
            [self::class, self::MODULE_SCROLL_USER_NAVIGATOR],
            [self::class, self::MODULE_SCROLL_CONTENT_ADDONS],
            [self::class, self::MODULE_SCROLL_HIGHLIGHTS_ADDONS],
            [self::class, self::MODULE_SCROLL_POSTS_ADDONS],
            [self::class, self::MODULE_SCROLL_USERS_ADDONS],
            [self::class, self::MODULE_SCROLL_USER_ADDONS],
            [self::class, self::MODULE_SCROLL_CONTENT_DETAILS],
            [self::class, self::MODULE_SCROLL_POSTS_DETAILS],
            [self::class, self::MODULE_SCROLL_TAGS_DETAILS],
            [self::class, self::MODULE_SCROLL_USERS_DETAILS],
            [self::class, self::MODULE_SCROLL_USER_DETAILS],
            [self::class, self::MODULE_SCROLL_CONTENT_SIMPLEVIEW],
            [self::class, self::MODULE_SCROLL_POSTS_SIMPLEVIEW],
            [self::class, self::MODULE_SCROLL_CONTENT_FULLVIEW],
            [self::class, self::MODULE_SCROLL_HIGHLIGHTS_FULLVIEW],
            [self::class, self::MODULE_SCROLL_POSTS_FULLVIEW],
            [self::class, self::MODULE_SCROLL_USERS_FULLVIEW],
            [self::class, self::MODULE_SCROLL_USER_FULLVIEW],
            [self::class, self::MODULE_SCROLL_CONTENT_THUMBNAIL],
            [self::class, self::MODULE_SCROLL_HIGHLIGHTS_THUMBNAIL],
            [self::class, self::MODULE_SCROLL_POSTS_THUMBNAIL],
            [self::class, self::MODULE_SCROLL_USERS_THUMBNAIL],
            [self::class, self::MODULE_SCROLL_USER_THUMBNAIL],
            [self::class, self::MODULE_SCROLL_CONTENT_LIST],
            [self::class, self::MODULE_SCROLL_HIGHLIGHTS_LIST],
            [self::class, self::MODULE_SCROLL_POSTS_LIST],
            [self::class, self::MODULE_SCROLL_USERS_LIST],
            [self::class, self::MODULE_SCROLL_USER_LIST],
            [self::class, self::MODULE_SCROLL_TAGS_LIST],
            [self::class, self::MODULE_SCROLL_POSTS_LINE],
            [self::class, self::MODULE_SCROLL_AUTHORCONTENT_FULLVIEW],
            [self::class, self::MODULE_SCROLL_AUTHORHIGHLIGHTS_FULLVIEW],
            [self::class, self::MODULE_SCROLL_AUTHORPOSTS_FULLVIEW],
            [self::class, self::MODULE_SCROLL_SINGLERELATEDCONTENT_FULLVIEW],
        );
    }


    public function getInnerSubmodule(array $module)
    {
        $inners = array(
            self::MODULE_SCROLL_CONTENT_NAVIGATOR => [PoP_Module_Processor_CustomScrollInners::class, PoP_Module_Processor_CustomScrollInners::MODULE_SCROLLINNER_CONTENT_NAVIGATOR],
            self::MODULE_SCROLL_HIGHLIGHTS_NAVIGATOR => [PoP_Module_Processor_CustomScrollInners::class, PoP_Module_Processor_CustomScrollInners::MODULE_SCROLLINNER_HIGHLIGHTS_NAVIGATOR],
            self::MODULE_SCROLL_POSTS_NAVIGATOR => [PoP_Module_Processor_CustomScrollInners::class, PoP_Module_Processor_CustomScrollInners::MODULE_SCROLLINNER_POSTS_NAVIGATOR],
            self::MODULE_SCROLL_USERS_NAVIGATOR => [PoP_Module_Processor_CustomScrollInners::class, PoP_Module_Processor_CustomScrollInners::MODULE_SCROLLINNER_USERS_NAVIGATOR],
            self::MODULE_SCROLL_USER_NAVIGATOR => [PoP_Module_Processor_CustomScrollInners::class, PoP_Module_Processor_CustomScrollInners::MODULE_SCROLLINNER_USER_NAVIGATOR],
            self::MODULE_SCROLL_CONTENT_ADDONS => [PoP_Module_Processor_CustomScrollInners::class, PoP_Module_Processor_CustomScrollInners::MODULE_SCROLLINNER_CONTENT_ADDONS],
            self::MODULE_SCROLL_HIGHLIGHTS_ADDONS => [PoP_Module_Processor_CustomScrollInners::class, PoP_Module_Processor_CustomScrollInners::MODULE_SCROLLINNER_HIGHLIGHTS_ADDONS],
            self::MODULE_SCROLL_POSTS_ADDONS => [PoP_Module_Processor_CustomScrollInners::class, PoP_Module_Processor_CustomScrollInners::MODULE_SCROLLINNER_POSTS_ADDONS],
            self::MODULE_SCROLL_USERS_ADDONS => [PoP_Module_Processor_CustomScrollInners::class, PoP_Module_Processor_CustomScrollInners::MODULE_SCROLLINNER_USERS_ADDONS],
            self::MODULE_SCROLL_USER_ADDONS => [PoP_Module_Processor_CustomScrollInners::class, PoP_Module_Processor_CustomScrollInners::MODULE_SCROLLINNER_USER_ADDONS],
            self::MODULE_SCROLL_CONTENT_DETAILS => [PoP_Module_Processor_CustomScrollInners::class, PoP_Module_Processor_CustomScrollInners::MODULE_SCROLLINNER_CONTENT_DETAILS],
            self::MODULE_SCROLL_POSTS_DETAILS => [PoP_Module_Processor_CustomScrollInners::class, PoP_Module_Processor_CustomScrollInners::MODULE_SCROLLINNER_POSTS_DETAILS],
            self::MODULE_SCROLL_TAGS_DETAILS => [PoP_Module_Processor_CustomScrollInners::class, PoP_Module_Processor_CustomScrollInners::MODULE_SCROLLINNER_TAGS_DETAILS],
            self::MODULE_SCROLL_USERS_DETAILS => [PoP_Module_Processor_CustomScrollInners::class, PoP_Module_Processor_CustomScrollInners::MODULE_SCROLLINNER_USERS_DETAILS],
            self::MODULE_SCROLL_USER_DETAILS => [PoP_Module_Processor_CustomScrollInners::class, PoP_Module_Processor_CustomScrollInners::MODULE_SCROLLINNER_USER_DETAILS],
            self::MODULE_SCROLL_CONTENT_SIMPLEVIEW => [PoP_Module_Processor_CustomScrollInners::class, PoP_Module_Processor_CustomScrollInners::MODULE_SCROLLINNER_CONTENT_SIMPLEVIEW],
            self::MODULE_SCROLL_POSTS_SIMPLEVIEW => [PoP_Module_Processor_CustomScrollInners::class, PoP_Module_Processor_CustomScrollInners::MODULE_SCROLLINNER_POSTS_SIMPLEVIEW],
            self::MODULE_SCROLL_CONTENT_FULLVIEW => [PoP_Module_Processor_CustomScrollInners::class, PoP_Module_Processor_CustomScrollInners::MODULE_SCROLLINNER_CONTENT_FULLVIEW],
            self::MODULE_SCROLL_HIGHLIGHTS_FULLVIEW => [PoP_Module_Processor_CustomScrollInners::class, PoP_Module_Processor_CustomScrollInners::MODULE_SCROLLINNER_HIGHLIGHTS_FULLVIEW],
            self::MODULE_SCROLL_POSTS_FULLVIEW => [PoP_Module_Processor_CustomScrollInners::class, PoP_Module_Processor_CustomScrollInners::MODULE_SCROLLINNER_POSTS_FULLVIEW],
            self::MODULE_SCROLL_USERS_FULLVIEW => [PoP_Module_Processor_CustomScrollInners::class, PoP_Module_Processor_CustomScrollInners::MODULE_SCROLLINNER_USERS_FULLVIEW],
            self::MODULE_SCROLL_USER_FULLVIEW => [PoP_Module_Processor_CustomScrollInners::class, PoP_Module_Processor_CustomScrollInners::MODULE_SCROLLINNER_USER_FULLVIEW],
            self::MODULE_SCROLL_CONTENT_THUMBNAIL => [PoP_Module_Processor_CustomScrollInners::class, PoP_Module_Processor_CustomScrollInners::MODULE_SCROLLINNER_CONTENT_THUMBNAIL],
            self::MODULE_SCROLL_HIGHLIGHTS_THUMBNAIL => [PoP_Module_Processor_CustomScrollInners::class, PoP_Module_Processor_CustomScrollInners::MODULE_SCROLLINNER_HIGHLIGHTS_THUMBNAIL],
            self::MODULE_SCROLL_POSTS_THUMBNAIL => [PoP_Module_Processor_CustomScrollInners::class, PoP_Module_Processor_CustomScrollInners::MODULE_SCROLLINNER_POSTS_THUMBNAIL],
            self::MODULE_SCROLL_USERS_THUMBNAIL => [PoP_Module_Processor_CustomScrollInners::class, PoP_Module_Processor_CustomScrollInners::MODULE_SCROLLINNER_USERS_THUMBNAIL],
            self::MODULE_SCROLL_USER_THUMBNAIL => [PoP_Module_Processor_CustomScrollInners::class, PoP_Module_Processor_CustomScrollInners::MODULE_SCROLLINNER_USER_THUMBNAIL],
            self::MODULE_SCROLL_CONTENT_LIST => [PoP_Module_Processor_CustomScrollInners::class, PoP_Module_Processor_CustomScrollInners::MODULE_SCROLLINNER_CONTENT_LIST],
            self::MODULE_SCROLL_HIGHLIGHTS_LIST => [PoP_Module_Processor_CustomScrollInners::class, PoP_Module_Processor_CustomScrollInners::MODULE_SCROLLINNER_HIGHLIGHTS_LIST],
            self::MODULE_SCROLL_POSTS_LIST => [PoP_Module_Processor_CustomScrollInners::class, PoP_Module_Processor_CustomScrollInners::MODULE_SCROLLINNER_POSTS_LIST],
            self::MODULE_SCROLL_USERS_LIST => [PoP_Module_Processor_CustomScrollInners::class, PoP_Module_Processor_CustomScrollInners::MODULE_SCROLLINNER_USERS_LIST],
            self::MODULE_SCROLL_USER_LIST => [PoP_Module_Processor_CustomScrollInners::class, PoP_Module_Processor_CustomScrollInners::MODULE_SCROLLINNER_USER_LIST],
            self::MODULE_SCROLL_TAGS_LIST => [PoP_Module_Processor_CustomScrollInners::class, PoP_Module_Processor_CustomScrollInners::MODULE_SCROLLINNER_TAGS_LIST],
            self::MODULE_SCROLL_POSTS_LINE => [PoP_Module_Processor_CustomScrollInners::class, PoP_Module_Processor_CustomScrollInners::MODULE_SCROLLINNER_POSTS_LINE],
            self::MODULE_SCROLL_AUTHORCONTENT_FULLVIEW => [PoP_Module_Processor_CustomScrollInners::class, PoP_Module_Processor_CustomScrollInners::MODULE_SCROLLINNER_AUTHORCONTENT_FULLVIEW],
            self::MODULE_SCROLL_AUTHORHIGHLIGHTS_FULLVIEW => [PoP_Module_Processor_CustomScrollInners::class, PoP_Module_Processor_CustomScrollInners::MODULE_SCROLLINNER_AUTHORHIGHLIGHTS_FULLVIEW],
            self::MODULE_SCROLL_AUTHORPOSTS_FULLVIEW => [PoP_Module_Processor_CustomScrollInners::class, PoP_Module_Processor_CustomScrollInners::MODULE_SCROLLINNER_AUTHORPOSTS_FULLVIEW],
            self::MODULE_SCROLL_SINGLERELATEDCONTENT_FULLVIEW => [PoP_Module_Processor_CustomScrollInners::class, PoP_Module_Processor_CustomScrollInners::MODULE_SCROLLINNER_SINGLERELATEDCONTENT_FULLVIEW],
        );

        if ($inner = $inners[$module[1]] ?? null) {
            return $inner;
        }

        return parent::getInnerSubmodule($module);
    }

    public function initModelProps(array $module, array &$props): void
    {

        // Extra classes
        $thumbnails = array(
            [self::class, self::MODULE_SCROLL_CONTENT_THUMBNAIL],
            [self::class, self::MODULE_SCROLL_POSTS_THUMBNAIL],

            [self::class, self::MODULE_SCROLL_USERS_THUMBNAIL],
            [self::class, self::MODULE_SCROLL_USER_THUMBNAIL],
        );
        $independentitem_thumbnails = array(
            [self::class, self::MODULE_SCROLL_HIGHLIGHTS_THUMBNAIL],
        );
        $lists = array(
            [self::class, self::MODULE_SCROLL_CONTENT_LIST],
            [self::class, self::MODULE_SCROLL_TAGS_LIST],
            [self::class, self::MODULE_SCROLL_POSTS_LIST],

            [self::class, self::MODULE_SCROLL_USERS_LIST],
            [self::class, self::MODULE_SCROLL_USER_LIST],
        );
        $independentitem_lists = array(
            [self::class, self::MODULE_SCROLL_HIGHLIGHTS_LIST],
        );
        $lines = array(
            [self::class, self::MODULE_SCROLL_POSTS_LINE],
        );
        $details = array(
            [self::class, self::MODULE_SCROLL_CONTENT_DETAILS],
            [self::class, self::MODULE_SCROLL_POSTS_DETAILS],
            [self::class, self::MODULE_SCROLL_TAGS_DETAILS],

            [self::class, self::MODULE_SCROLL_USERS_DETAILS],
            [self::class, self::MODULE_SCROLL_USER_DETAILS],
        );
        $navigators = array(
            [self::class, self::MODULE_SCROLL_CONTENT_NAVIGATOR],
            [self::class, self::MODULE_SCROLL_HIGHLIGHTS_NAVIGATOR],
            [self::class, self::MODULE_SCROLL_POSTS_NAVIGATOR],
            [self::class, self::MODULE_SCROLL_USERS_NAVIGATOR],
            [self::class, self::MODULE_SCROLL_USER_NAVIGATOR],
        );
        $addons = array(
            [self::class, self::MODULE_SCROLL_CONTENT_ADDONS],
            [self::class, self::MODULE_SCROLL_HIGHLIGHTS_ADDONS],
            [self::class, self::MODULE_SCROLL_POSTS_ADDONS],
            [self::class, self::MODULE_SCROLL_USERS_ADDONS],
            [self::class, self::MODULE_SCROLL_USER_ADDONS],
        );
        $simpleviews = array(
            [self::class, self::MODULE_SCROLL_CONTENT_SIMPLEVIEW],
            [self::class, self::MODULE_SCROLL_POSTS_SIMPLEVIEW],
        );
        $fullviews = array(
            [self::class, self::MODULE_SCROLL_CONTENT_FULLVIEW],
            [self::class, self::MODULE_SCROLL_HIGHLIGHTS_FULLVIEW],
            [self::class, self::MODULE_SCROLL_POSTS_FULLVIEW],

            [self::class, self::MODULE_SCROLL_USERS_FULLVIEW],
            [self::class, self::MODULE_SCROLL_USER_FULLVIEW],

            [self::class, self::MODULE_SCROLL_AUTHORCONTENT_FULLVIEW],
            [self::class, self::MODULE_SCROLL_AUTHORHIGHLIGHTS_FULLVIEW],
            [self::class, self::MODULE_SCROLL_AUTHORPOSTS_FULLVIEW],

            [self::class, self::MODULE_SCROLL_SINGLERELATEDCONTENT_FULLVIEW],
        );

        $extra_class = '';
        if (in_array($module, $navigators)) {
            $extra_class = 'navigator text-inverse';
        } elseif (in_array($module, $addons)) {
            $extra_class = 'addons';
        } elseif (in_array($module, $simpleviews)) {
            $extra_class = 'simpleview';
        } elseif (in_array($module, $fullviews)) {
            $extra_class = 'fullview';
        } elseif (in_array($module, $details)) {
            $extra_class = 'details';
        } elseif (in_array($module, $thumbnails)) {
            $extra_class = 'thumb';
        } elseif (in_array($module, $independentitem_thumbnails)) {
            $extra_class = 'thumb independent';
        } elseif (in_array($module, $lists)) {
            $extra_class = 'list';
        } elseif (in_array($module, $independentitem_lists)) {
            $extra_class = 'list independent';
        } elseif (in_array($module, $lines)) {
            $extra_class = 'line';
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



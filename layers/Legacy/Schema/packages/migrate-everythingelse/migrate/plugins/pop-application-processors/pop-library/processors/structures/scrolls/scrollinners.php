<?php

class PoP_Module_Processor_CustomScrollInners extends PoP_Module_Processor_ScrollInnersBase
{
    public const MODULE_SCROLLINNER_CONTENT_NAVIGATOR = 'scrollinner-content-navigator';
    public const MODULE_SCROLLINNER_HIGHLIGHTS_NAVIGATOR = 'scrollinner-highlights-navigator';
    public const MODULE_SCROLLINNER_POSTS_NAVIGATOR = 'scrollinner-posts-navigator';
    public const MODULE_SCROLLINNER_USERS_NAVIGATOR = 'scrollinner-users-navigator';
    public const MODULE_SCROLLINNER_USER_NAVIGATOR = 'scrollinner-user-navigator';
    public const MODULE_SCROLLINNER_CONTENT_ADDONS = 'scrollinner-content-addons';
    public const MODULE_SCROLLINNER_HIGHLIGHTS_ADDONS = 'scrollinner-highlights-addons';
    public const MODULE_SCROLLINNER_POSTS_ADDONS = 'scrollinner-posts-addons';
    public const MODULE_SCROLLINNER_USERS_ADDONS = 'scrollinner-users-addons';
    public const MODULE_SCROLLINNER_USER_ADDONS = 'scrollinner-user-addons';
    public const MODULE_SCROLLINNER_CONTENT_DETAILS = 'scrollinner-content-details';
    public const MODULE_SCROLLINNER_POSTS_DETAILS = 'scrollinner-posts-details';
    public const MODULE_SCROLLINNER_TAGS_DETAILS = 'scrollinner-tags-details';
    public const MODULE_SCROLLINNER_USERS_DETAILS = 'scrollinner-users-details';
    public const MODULE_SCROLLINNER_USER_DETAILS = 'scrollinner-user-details';
    public const MODULE_SCROLLINNER_CONTENT_SIMPLEVIEW = 'scrollinner-content-simpleview';
    public const MODULE_SCROLLINNER_POSTS_SIMPLEVIEW = 'scrollinner-posts-simpleview';
    public const MODULE_SCROLLINNER_CONTENT_FULLVIEW = 'scrollinner-content-fullview';
    public const MODULE_SCROLLINNER_HIGHLIGHTS_FULLVIEW = 'scrollinner-highlights-fullview';
    public const MODULE_SCROLLINNER_POSTS_FULLVIEW = 'scrollinner-posts-fullview';
    public const MODULE_SCROLLINNER_USERS_FULLVIEW = 'scrollinner-users-fullview';
    public const MODULE_SCROLLINNER_USER_FULLVIEW = 'scrollinner-user-fullview';
    public const MODULE_SCROLLINNER_AUTHORCONTENT_FULLVIEW = 'scrollinner-authorcontent-fullview';
    public const MODULE_SCROLLINNER_AUTHORHIGHLIGHTS_FULLVIEW = 'scrollinner-authorhighlights-fullview';
    public const MODULE_SCROLLINNER_AUTHORPOSTS_FULLVIEW = 'scrollinner-authorposts-fullview';
    public const MODULE_SCROLLINNER_SINGLERELATEDCONTENT_FULLVIEW = 'scrollinner-singlerelatedcontent-fullview';
    public const MODULE_SCROLLINNER_CONTENT_THUMBNAIL = 'scrollinner-content-thumbnail';
    public const MODULE_SCROLLINNER_HIGHLIGHTS_THUMBNAIL = 'scrollinner-highlights-thumbnail';
    public const MODULE_SCROLLINNER_POSTS_THUMBNAIL = 'scrollinner-posts-thumbnail';
    public const MODULE_SCROLLINNER_USERS_THUMBNAIL = 'scrollinner-users-thumbnail';
    public const MODULE_SCROLLINNER_USER_THUMBNAIL = 'scrollinner-user-thumbnail';
    public const MODULE_SCROLLINNER_CONTENT_LIST = 'scrollinner-content-list';
    public const MODULE_SCROLLINNER_HIGHLIGHTS_LIST = 'scrollinner-highlights-list';
    public const MODULE_SCROLLINNER_POSTS_LIST = 'scrollinner-posts-list';
    public const MODULE_SCROLLINNER_TAGS_LIST = 'scrollinner-tags-list';
    public const MODULE_SCROLLINNER_POSTS_LINE = 'scrollinner-posts-line';
    public const MODULE_SCROLLINNER_USERS_LIST = 'scrollinner-users-list';
    public const MODULE_SCROLLINNER_USER_LIST = 'scrollinner-user-list';

    public function getModulesToProcess(): array
    {
        return array(
            [self::class, self::MODULE_SCROLLINNER_CONTENT_NAVIGATOR],
            [self::class, self::MODULE_SCROLLINNER_HIGHLIGHTS_NAVIGATOR],
            [self::class, self::MODULE_SCROLLINNER_POSTS_NAVIGATOR],
            [self::class, self::MODULE_SCROLLINNER_USERS_NAVIGATOR],
            [self::class, self::MODULE_SCROLLINNER_USER_NAVIGATOR],
            [self::class, self::MODULE_SCROLLINNER_CONTENT_ADDONS],
            [self::class, self::MODULE_SCROLLINNER_HIGHLIGHTS_ADDONS],
            [self::class, self::MODULE_SCROLLINNER_POSTS_ADDONS],
            [self::class, self::MODULE_SCROLLINNER_USERS_ADDONS],
            [self::class, self::MODULE_SCROLLINNER_USER_ADDONS],
            [self::class, self::MODULE_SCROLLINNER_CONTENT_DETAILS],
            [self::class, self::MODULE_SCROLLINNER_POSTS_DETAILS],
            [self::class, self::MODULE_SCROLLINNER_TAGS_DETAILS],
            [self::class, self::MODULE_SCROLLINNER_USERS_DETAILS],
            [self::class, self::MODULE_SCROLLINNER_USER_DETAILS],
            [self::class, self::MODULE_SCROLLINNER_CONTENT_SIMPLEVIEW],
            [self::class, self::MODULE_SCROLLINNER_POSTS_SIMPLEVIEW],
            [self::class, self::MODULE_SCROLLINNER_CONTENT_FULLVIEW],
            [self::class, self::MODULE_SCROLLINNER_HIGHLIGHTS_FULLVIEW],
            [self::class, self::MODULE_SCROLLINNER_POSTS_FULLVIEW],
            [self::class, self::MODULE_SCROLLINNER_USERS_FULLVIEW],
            [self::class, self::MODULE_SCROLLINNER_USER_FULLVIEW],
            [self::class, self::MODULE_SCROLLINNER_CONTENT_THUMBNAIL],
            [self::class, self::MODULE_SCROLLINNER_HIGHLIGHTS_THUMBNAIL],
            [self::class, self::MODULE_SCROLLINNER_POSTS_THUMBNAIL],
            [self::class, self::MODULE_SCROLLINNER_USERS_THUMBNAIL],
            [self::class, self::MODULE_SCROLLINNER_USER_THUMBNAIL],
            [self::class, self::MODULE_SCROLLINNER_CONTENT_LIST],
            [self::class, self::MODULE_SCROLLINNER_HIGHLIGHTS_LIST],
            [self::class, self::MODULE_SCROLLINNER_POSTS_LIST],
            [self::class, self::MODULE_SCROLLINNER_USERS_LIST],
            [self::class, self::MODULE_SCROLLINNER_USER_LIST],
            [self::class, self::MODULE_SCROLLINNER_TAGS_LIST],
            [self::class, self::MODULE_SCROLLINNER_POSTS_LINE],
            [self::class, self::MODULE_SCROLLINNER_AUTHORCONTENT_FULLVIEW],
            [self::class, self::MODULE_SCROLLINNER_AUTHORHIGHLIGHTS_FULLVIEW],
            [self::class, self::MODULE_SCROLLINNER_AUTHORPOSTS_FULLVIEW],
            [self::class, self::MODULE_SCROLLINNER_SINGLERELATEDCONTENT_FULLVIEW],
        );
    }

    public function getLayoutGrid(array $module, array &$props)
    {
        switch ($module[1]) {
            case self::MODULE_SCROLLINNER_CONTENT_THUMBNAIL:
            case self::MODULE_SCROLLINNER_HIGHLIGHTS_THUMBNAIL:
            case self::MODULE_SCROLLINNER_POSTS_THUMBNAIL:
                // Allow ThemeStyle Expansive to override the grid
                return \PoP\Root\App::getHookManager()->applyFilters(
                    POP_HOOK_SCROLLINNER_THUMBNAIL_GRID,
                    array(
                        'row-items' => 2,
                        'class' => 'col-xsm-6'
                    )
                );

            case self::MODULE_SCROLLINNER_USERS_THUMBNAIL:
            case self::MODULE_SCROLLINNER_USER_THUMBNAIL:
                return array(
                    'row-items' => 3,
                    'class' => 'col-xsm-4'
                );

            case self::MODULE_SCROLLINNER_CONTENT_NAVIGATOR:
            case self::MODULE_SCROLLINNER_HIGHLIGHTS_NAVIGATOR:
            case self::MODULE_SCROLLINNER_POSTS_NAVIGATOR:
            case self::MODULE_SCROLLINNER_USERS_NAVIGATOR:
            case self::MODULE_SCROLLINNER_USER_NAVIGATOR:
            case self::MODULE_SCROLLINNER_CONTENT_ADDONS:
            case self::MODULE_SCROLLINNER_HIGHLIGHTS_ADDONS:
            case self::MODULE_SCROLLINNER_POSTS_ADDONS:
            case self::MODULE_SCROLLINNER_USERS_ADDONS:
            case self::MODULE_SCROLLINNER_USER_ADDONS:
            case self::MODULE_SCROLLINNER_CONTENT_DETAILS:
            case self::MODULE_SCROLLINNER_POSTS_DETAILS:
            case self::MODULE_SCROLLINNER_TAGS_DETAILS:
            case self::MODULE_SCROLLINNER_USERS_DETAILS:
            case self::MODULE_SCROLLINNER_USER_DETAILS:
            case self::MODULE_SCROLLINNER_CONTENT_SIMPLEVIEW:
            case self::MODULE_SCROLLINNER_POSTS_SIMPLEVIEW:
            case self::MODULE_SCROLLINNER_CONTENT_FULLVIEW:
            case self::MODULE_SCROLLINNER_HIGHLIGHTS_FULLVIEW:
            case self::MODULE_SCROLLINNER_POSTS_FULLVIEW:
            case self::MODULE_SCROLLINNER_USERS_FULLVIEW:
            case self::MODULE_SCROLLINNER_USER_FULLVIEW:
            case self::MODULE_SCROLLINNER_CONTENT_LIST:
            case self::MODULE_SCROLLINNER_HIGHLIGHTS_LIST:
            case self::MODULE_SCROLLINNER_POSTS_LIST:
            case self::MODULE_SCROLLINNER_TAGS_LIST:
            case self::MODULE_SCROLLINNER_USERS_LIST:
            case self::MODULE_SCROLLINNER_USER_LIST:
            case self::MODULE_SCROLLINNER_POSTS_LINE:
            case self::MODULE_SCROLLINNER_AUTHORCONTENT_FULLVIEW:
            case self::MODULE_SCROLLINNER_AUTHORHIGHLIGHTS_FULLVIEW:
            case self::MODULE_SCROLLINNER_AUTHORPOSTS_FULLVIEW:
            case self::MODULE_SCROLLINNER_SINGLERELATEDCONTENT_FULLVIEW:
                return array(
                    'row-items' => 1,
                    'class' => 'col-sm-12'
                );
        }

        return parent::getLayoutGrid($module, $props);
    }

    public function getLayoutSubmodules(array $module)
    {
        $ret = parent::getLayoutSubmodules($module);

        $layouts = array(
            self::MODULE_SCROLLINNER_CONTENT_NAVIGATOR => [PoP_Module_Processor_MultiplePostLayouts::class, PoP_Module_Processor_MultiplePostLayouts::MODULE_LAYOUT_MULTIPLECONTENT_NAVIGATOR],
            self::MODULE_SCROLLINNER_CONTENT_ADDONS => [PoP_Module_Processor_MultiplePostLayouts::class, PoP_Module_Processor_MultiplePostLayouts::MODULE_LAYOUT_MULTIPLECONTENT_ADDONS],
            self::MODULE_SCROLLINNER_USERS_NAVIGATOR => [PoP_Module_Processor_MultipleUserLayouts::class, PoP_Module_Processor_MultipleUserLayouts::MODULE_LAYOUT_MULTIPLEUSER_NAVIGATOR],
            self::MODULE_SCROLLINNER_USER_NAVIGATOR => [PoP_Module_Processor_CustomPreviewUserLayouts::class, PoP_Module_Processor_CustomPreviewUserLayouts::MODULE_LAYOUT_PREVIEWUSER_NAVIGATOR],
            self::MODULE_SCROLLINNER_USERS_ADDONS => [PoP_Module_Processor_MultipleUserLayouts::class, PoP_Module_Processor_MultipleUserLayouts::MODULE_LAYOUT_MULTIPLEUSER_ADDONS],
            self::MODULE_SCROLLINNER_USER_ADDONS => [PoP_Module_Processor_CustomPreviewUserLayouts::class, PoP_Module_Processor_CustomPreviewUserLayouts::MODULE_LAYOUT_PREVIEWUSER_ADDONS],
            self::MODULE_SCROLLINNER_CONTENT_DETAILS => [PoP_Module_Processor_MultiplePostLayouts::class, PoP_Module_Processor_MultiplePostLayouts::MODULE_LAYOUT_MULTIPLECONTENT_DETAILS],
            self::MODULE_SCROLLINNER_USERS_DETAILS => [PoP_Module_Processor_MultipleUserLayouts::class, PoP_Module_Processor_MultipleUserLayouts::MODULE_LAYOUT_MULTIPLEUSER_DETAILS],
            self::MODULE_SCROLLINNER_USER_DETAILS => [PoP_Module_Processor_CustomPreviewUserLayouts::class, PoP_Module_Processor_CustomPreviewUserLayouts::MODULE_LAYOUT_PREVIEWUSER_DETAILS],
            self::MODULE_SCROLLINNER_CONTENT_THUMBNAIL => [PoP_Module_Processor_MultiplePostLayouts::class, PoP_Module_Processor_MultiplePostLayouts::MODULE_LAYOUT_MULTIPLECONTENT_THUMBNAIL],
            self::MODULE_SCROLLINNER_USERS_THUMBNAIL => [PoP_Module_Processor_MultipleUserLayouts::class, PoP_Module_Processor_MultipleUserLayouts::MODULE_LAYOUT_MULTIPLEUSER_THUMBNAIL],
            self::MODULE_SCROLLINNER_USER_THUMBNAIL => [PoP_Module_Processor_CustomPreviewUserLayouts::class, PoP_Module_Processor_CustomPreviewUserLayouts::MODULE_LAYOUT_PREVIEWUSER_THUMBNAIL],
            self::MODULE_SCROLLINNER_CONTENT_LIST => [PoP_Module_Processor_MultiplePostLayouts::class, PoP_Module_Processor_MultiplePostLayouts::MODULE_LAYOUT_MULTIPLECONTENT_LIST],
            self::MODULE_SCROLLINNER_USERS_LIST => [PoP_Module_Processor_MultipleUserLayouts::class, PoP_Module_Processor_MultipleUserLayouts::MODULE_LAYOUT_MULTIPLEUSER_LIST],
            self::MODULE_SCROLLINNER_USER_LIST => [PoP_Module_Processor_CustomPreviewUserLayouts::class, PoP_Module_Processor_CustomPreviewUserLayouts::MODULE_LAYOUT_PREVIEWUSER_LIST],
            self::MODULE_SCROLLINNER_CONTENT_SIMPLEVIEW => [PoP_Module_Processor_MultiplePostLayouts::class, PoP_Module_Processor_MultiplePostLayouts::MODULE_LAYOUT_MULTIPLECONTENT_SIMPLEVIEW],
            self::MODULE_SCROLLINNER_CONTENT_FULLVIEW => [PoP_Module_Processor_MultiplePostLayouts::class, PoP_Module_Processor_MultiplePostLayouts::MODULE_LAYOUT_MULTIPLECONTENT_FULLVIEW],
            self::MODULE_SCROLLINNER_USERS_FULLVIEW => [PoP_Module_Processor_MultipleUserLayouts::class, PoP_Module_Processor_MultipleUserLayouts::MODULE_LAYOUT_MULTIPLEUSER_FULLUSER],
            self::MODULE_SCROLLINNER_USER_FULLVIEW => [PoP_Module_Processor_MultipleUserLayouts::class, PoP_Module_Processor_MultipleUserLayouts::MODULE_LAYOUT_MULTIPLEUSER_FULLUSER],
            self::MODULE_SCROLLINNER_AUTHORCONTENT_FULLVIEW => [PoP_Module_Processor_MultiplePostLayouts::class, PoP_Module_Processor_MultiplePostLayouts::MODULE_LAYOUT_AUTHORMULTIPLECONTENT_FULLVIEW],
            self::MODULE_SCROLLINNER_SINGLERELATEDCONTENT_FULLVIEW => [PoP_Module_Processor_MultiplePostLayouts::class, PoP_Module_Processor_MultiplePostLayouts::MODULE_LAYOUT_SINGLEMULTIPLECONTENT_FULLVIEW],
            self::MODULE_SCROLLINNER_HIGHLIGHTS_NAVIGATOR => [PoP_Module_Processor_CustomPreviewPostLayouts::class, PoP_Module_Processor_CustomPreviewPostLayouts::MODULE_LAYOUT_PREVIEWPOST_HIGHLIGHT_CONTENT],
            self::MODULE_SCROLLINNER_POSTS_NAVIGATOR => [PoP_Module_Processor_CustomPreviewPostLayouts::class, PoP_Module_Processor_CustomPreviewPostLayouts::MODULE_LAYOUT_PREVIEWPOST_POST_NAVIGATOR],
            self::MODULE_SCROLLINNER_HIGHLIGHTS_ADDONS => [PoP_Module_Processor_CustomPreviewPostLayouts::class, PoP_Module_Processor_CustomPreviewPostLayouts::MODULE_LAYOUT_PREVIEWPOST_HIGHLIGHT_CONTENT],
            self::MODULE_SCROLLINNER_POSTS_ADDONS => [PoP_Module_Processor_CustomPreviewPostLayouts::class, PoP_Module_Processor_CustomPreviewPostLayouts::MODULE_LAYOUT_PREVIEWPOST_POST_ADDONS],
            self::MODULE_SCROLLINNER_POSTS_DETAILS => [PoP_Module_Processor_CustomPreviewPostLayouts::class, PoP_Module_Processor_CustomPreviewPostLayouts::MODULE_LAYOUT_PREVIEWPOST_POST_DETAILS],
            self::MODULE_SCROLLINNER_TAGS_DETAILS => [PoP_Module_ProcessorTagMultipleComponents::class, PoP_Module_ProcessorTagMultipleComponents::MODULE_LAYOUT_TAG_DETAILS],
            self::MODULE_SCROLLINNER_HIGHLIGHTS_THUMBNAIL => [PoP_Module_Processor_CustomPreviewPostLayouts::class, PoP_Module_Processor_CustomPreviewPostLayouts::MODULE_LAYOUT_PREVIEWPOST_HIGHLIGHT_CONTENT],
            self::MODULE_SCROLLINNER_POSTS_THUMBNAIL => [PoP_Module_Processor_CustomPreviewPostLayouts::class, PoP_Module_Processor_CustomPreviewPostLayouts::MODULE_LAYOUT_PREVIEWPOST_POST_THUMBNAIL],
            self::MODULE_SCROLLINNER_HIGHLIGHTS_LIST => [PoP_Module_Processor_CustomPreviewPostLayouts::class, PoP_Module_Processor_CustomPreviewPostLayouts::MODULE_LAYOUT_PREVIEWPOST_HIGHLIGHT_CONTENT],
            self::MODULE_SCROLLINNER_POSTS_LIST => [PoP_Module_Processor_CustomPreviewPostLayouts::class, PoP_Module_Processor_CustomPreviewPostLayouts::MODULE_LAYOUT_PREVIEWPOST_POST_LIST],
            self::MODULE_SCROLLINNER_TAGS_LIST => [PoP_Module_Processor_TagLayouts::class, PoP_Module_Processor_TagLayouts::MODULE_LAYOUT_TAG],
            self::MODULE_SCROLLINNER_POSTS_LINE => [PoP_Module_Processor_CustomPreviewPostLayouts::class, PoP_Module_Processor_CustomPreviewPostLayouts::MODULE_LAYOUT_PREVIEWPOST_LINE],
            self::MODULE_SCROLLINNER_POSTS_SIMPLEVIEW => [PoP_Module_Processor_CustomSimpleViewPreviewPostLayouts::class, PoP_Module_Processor_CustomSimpleViewPreviewPostLayouts::MODULE_LAYOUT_PREVIEWPOST_SIMPLEVIEW],
            self::MODULE_SCROLLINNER_POSTS_FULLVIEW => [PoP_Module_Processor_CustomFullViewLayouts::class, PoP_Module_Processor_CustomFullViewLayouts::MODULE_LAYOUT_FULLVIEW_POST],
            self::MODULE_SCROLLINNER_HIGHLIGHTS_FULLVIEW => [PoP_Module_Processor_CustomFullViewLayouts::class, PoP_Module_Processor_CustomFullViewLayouts::MODULE_LAYOUT_FULLVIEW_HIGHLIGHT],
            self::MODULE_SCROLLINNER_AUTHORHIGHLIGHTS_FULLVIEW => [PoP_Module_Processor_CustomFullViewLayouts::class, PoP_Module_Processor_CustomFullViewLayouts::MODULE_AUTHORLAYOUT_FULLVIEW_HIGHLIGHT],
            self::MODULE_SCROLLINNER_AUTHORPOSTS_FULLVIEW => [PoP_Module_Processor_CustomFullViewLayouts::class, PoP_Module_Processor_CustomFullViewLayouts::MODULE_AUTHORLAYOUT_FULLVIEW_POST],
        );

        if ($layout = $layouts[$module[1]] ?? null) {
            $ret[] = $layout;
        }

        return $ret;
    }
}



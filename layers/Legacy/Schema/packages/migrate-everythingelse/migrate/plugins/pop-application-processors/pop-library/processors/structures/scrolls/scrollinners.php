<?php

class PoP_Module_Processor_CustomScrollInners extends PoP_Module_Processor_ScrollInnersBase
{
    public final const COMPONENT_SCROLLINNER_CONTENT_NAVIGATOR = 'scrollinner-content-navigator';
    public final const COMPONENT_SCROLLINNER_HIGHLIGHTS_NAVIGATOR = 'scrollinner-highlights-navigator';
    public final const COMPONENT_SCROLLINNER_POSTS_NAVIGATOR = 'scrollinner-posts-navigator';
    public final const COMPONENT_SCROLLINNER_USERS_NAVIGATOR = 'scrollinner-users-navigator';
    public final const COMPONENT_SCROLLINNER_USER_NAVIGATOR = 'scrollinner-user-navigator';
    public final const COMPONENT_SCROLLINNER_CONTENT_ADDONS = 'scrollinner-content-addons';
    public final const COMPONENT_SCROLLINNER_HIGHLIGHTS_ADDONS = 'scrollinner-highlights-addons';
    public final const COMPONENT_SCROLLINNER_POSTS_ADDONS = 'scrollinner-posts-addons';
    public final const COMPONENT_SCROLLINNER_USERS_ADDONS = 'scrollinner-users-addons';
    public final const COMPONENT_SCROLLINNER_USER_ADDONS = 'scrollinner-user-addons';
    public final const COMPONENT_SCROLLINNER_CONTENT_DETAILS = 'scrollinner-content-details';
    public final const COMPONENT_SCROLLINNER_POSTS_DETAILS = 'scrollinner-posts-details';
    public final const COMPONENT_SCROLLINNER_TAGS_DETAILS = 'scrollinner-tags-details';
    public final const COMPONENT_SCROLLINNER_USERS_DETAILS = 'scrollinner-users-details';
    public final const COMPONENT_SCROLLINNER_USER_DETAILS = 'scrollinner-user-details';
    public final const COMPONENT_SCROLLINNER_CONTENT_SIMPLEVIEW = 'scrollinner-content-simpleview';
    public final const COMPONENT_SCROLLINNER_POSTS_SIMPLEVIEW = 'scrollinner-posts-simpleview';
    public final const COMPONENT_SCROLLINNER_CONTENT_FULLVIEW = 'scrollinner-content-fullview';
    public final const COMPONENT_SCROLLINNER_HIGHLIGHTS_FULLVIEW = 'scrollinner-highlights-fullview';
    public final const COMPONENT_SCROLLINNER_POSTS_FULLVIEW = 'scrollinner-posts-fullview';
    public final const COMPONENT_SCROLLINNER_USERS_FULLVIEW = 'scrollinner-users-fullview';
    public final const COMPONENT_SCROLLINNER_USER_FULLVIEW = 'scrollinner-user-fullview';
    public final const COMPONENT_SCROLLINNER_AUTHORCONTENT_FULLVIEW = 'scrollinner-authorcontent-fullview';
    public final const COMPONENT_SCROLLINNER_AUTHORHIGHLIGHTS_FULLVIEW = 'scrollinner-authorhighlights-fullview';
    public final const COMPONENT_SCROLLINNER_AUTHORPOSTS_FULLVIEW = 'scrollinner-authorposts-fullview';
    public final const COMPONENT_SCROLLINNER_SINGLERELATEDCONTENT_FULLVIEW = 'scrollinner-singlerelatedcontent-fullview';
    public final const COMPONENT_SCROLLINNER_CONTENT_THUMBNAIL = 'scrollinner-content-thumbnail';
    public final const COMPONENT_SCROLLINNER_HIGHLIGHTS_THUMBNAIL = 'scrollinner-highlights-thumbnail';
    public final const COMPONENT_SCROLLINNER_POSTS_THUMBNAIL = 'scrollinner-posts-thumbnail';
    public final const COMPONENT_SCROLLINNER_USERS_THUMBNAIL = 'scrollinner-users-thumbnail';
    public final const COMPONENT_SCROLLINNER_USER_THUMBNAIL = 'scrollinner-user-thumbnail';
    public final const COMPONENT_SCROLLINNER_CONTENT_LIST = 'scrollinner-content-list';
    public final const COMPONENT_SCROLLINNER_HIGHLIGHTS_LIST = 'scrollinner-highlights-list';
    public final const COMPONENT_SCROLLINNER_POSTS_LIST = 'scrollinner-posts-list';
    public final const COMPONENT_SCROLLINNER_TAGS_LIST = 'scrollinner-tags-list';
    public final const COMPONENT_SCROLLINNER_POSTS_LINE = 'scrollinner-posts-line';
    public final const COMPONENT_SCROLLINNER_USERS_LIST = 'scrollinner-users-list';
    public final const COMPONENT_SCROLLINNER_USER_LIST = 'scrollinner-user-list';

    public function getComponentsToProcess(): array
    {
        return array(
            [self::class, self::COMPONENT_SCROLLINNER_CONTENT_NAVIGATOR],
            [self::class, self::COMPONENT_SCROLLINNER_HIGHLIGHTS_NAVIGATOR],
            [self::class, self::COMPONENT_SCROLLINNER_POSTS_NAVIGATOR],
            [self::class, self::COMPONENT_SCROLLINNER_USERS_NAVIGATOR],
            [self::class, self::COMPONENT_SCROLLINNER_USER_NAVIGATOR],
            [self::class, self::COMPONENT_SCROLLINNER_CONTENT_ADDONS],
            [self::class, self::COMPONENT_SCROLLINNER_HIGHLIGHTS_ADDONS],
            [self::class, self::COMPONENT_SCROLLINNER_POSTS_ADDONS],
            [self::class, self::COMPONENT_SCROLLINNER_USERS_ADDONS],
            [self::class, self::COMPONENT_SCROLLINNER_USER_ADDONS],
            [self::class, self::COMPONENT_SCROLLINNER_CONTENT_DETAILS],
            [self::class, self::COMPONENT_SCROLLINNER_POSTS_DETAILS],
            [self::class, self::COMPONENT_SCROLLINNER_TAGS_DETAILS],
            [self::class, self::COMPONENT_SCROLLINNER_USERS_DETAILS],
            [self::class, self::COMPONENT_SCROLLINNER_USER_DETAILS],
            [self::class, self::COMPONENT_SCROLLINNER_CONTENT_SIMPLEVIEW],
            [self::class, self::COMPONENT_SCROLLINNER_POSTS_SIMPLEVIEW],
            [self::class, self::COMPONENT_SCROLLINNER_CONTENT_FULLVIEW],
            [self::class, self::COMPONENT_SCROLLINNER_HIGHLIGHTS_FULLVIEW],
            [self::class, self::COMPONENT_SCROLLINNER_POSTS_FULLVIEW],
            [self::class, self::COMPONENT_SCROLLINNER_USERS_FULLVIEW],
            [self::class, self::COMPONENT_SCROLLINNER_USER_FULLVIEW],
            [self::class, self::COMPONENT_SCROLLINNER_CONTENT_THUMBNAIL],
            [self::class, self::COMPONENT_SCROLLINNER_HIGHLIGHTS_THUMBNAIL],
            [self::class, self::COMPONENT_SCROLLINNER_POSTS_THUMBNAIL],
            [self::class, self::COMPONENT_SCROLLINNER_USERS_THUMBNAIL],
            [self::class, self::COMPONENT_SCROLLINNER_USER_THUMBNAIL],
            [self::class, self::COMPONENT_SCROLLINNER_CONTENT_LIST],
            [self::class, self::COMPONENT_SCROLLINNER_HIGHLIGHTS_LIST],
            [self::class, self::COMPONENT_SCROLLINNER_POSTS_LIST],
            [self::class, self::COMPONENT_SCROLLINNER_USERS_LIST],
            [self::class, self::COMPONENT_SCROLLINNER_USER_LIST],
            [self::class, self::COMPONENT_SCROLLINNER_TAGS_LIST],
            [self::class, self::COMPONENT_SCROLLINNER_POSTS_LINE],
            [self::class, self::COMPONENT_SCROLLINNER_AUTHORCONTENT_FULLVIEW],
            [self::class, self::COMPONENT_SCROLLINNER_AUTHORHIGHLIGHTS_FULLVIEW],
            [self::class, self::COMPONENT_SCROLLINNER_AUTHORPOSTS_FULLVIEW],
            [self::class, self::COMPONENT_SCROLLINNER_SINGLERELATEDCONTENT_FULLVIEW],
        );
    }

    public function getLayoutGrid(array $component, array &$props)
    {
        switch ($component[1]) {
            case self::COMPONENT_SCROLLINNER_CONTENT_THUMBNAIL:
            case self::COMPONENT_SCROLLINNER_HIGHLIGHTS_THUMBNAIL:
            case self::COMPONENT_SCROLLINNER_POSTS_THUMBNAIL:
                // Allow ThemeStyle Expansive to override the grid
                return \PoP\Root\App::applyFilters(
                    POP_HOOK_SCROLLINNER_THUMBNAIL_GRID,
                    array(
                        'row-items' => 2,
                        'class' => 'col-xsm-6'
                    )
                );

            case self::COMPONENT_SCROLLINNER_USERS_THUMBNAIL:
            case self::COMPONENT_SCROLLINNER_USER_THUMBNAIL:
                return array(
                    'row-items' => 3,
                    'class' => 'col-xsm-4'
                );

            case self::COMPONENT_SCROLLINNER_CONTENT_NAVIGATOR:
            case self::COMPONENT_SCROLLINNER_HIGHLIGHTS_NAVIGATOR:
            case self::COMPONENT_SCROLLINNER_POSTS_NAVIGATOR:
            case self::COMPONENT_SCROLLINNER_USERS_NAVIGATOR:
            case self::COMPONENT_SCROLLINNER_USER_NAVIGATOR:
            case self::COMPONENT_SCROLLINNER_CONTENT_ADDONS:
            case self::COMPONENT_SCROLLINNER_HIGHLIGHTS_ADDONS:
            case self::COMPONENT_SCROLLINNER_POSTS_ADDONS:
            case self::COMPONENT_SCROLLINNER_USERS_ADDONS:
            case self::COMPONENT_SCROLLINNER_USER_ADDONS:
            case self::COMPONENT_SCROLLINNER_CONTENT_DETAILS:
            case self::COMPONENT_SCROLLINNER_POSTS_DETAILS:
            case self::COMPONENT_SCROLLINNER_TAGS_DETAILS:
            case self::COMPONENT_SCROLLINNER_USERS_DETAILS:
            case self::COMPONENT_SCROLLINNER_USER_DETAILS:
            case self::COMPONENT_SCROLLINNER_CONTENT_SIMPLEVIEW:
            case self::COMPONENT_SCROLLINNER_POSTS_SIMPLEVIEW:
            case self::COMPONENT_SCROLLINNER_CONTENT_FULLVIEW:
            case self::COMPONENT_SCROLLINNER_HIGHLIGHTS_FULLVIEW:
            case self::COMPONENT_SCROLLINNER_POSTS_FULLVIEW:
            case self::COMPONENT_SCROLLINNER_USERS_FULLVIEW:
            case self::COMPONENT_SCROLLINNER_USER_FULLVIEW:
            case self::COMPONENT_SCROLLINNER_CONTENT_LIST:
            case self::COMPONENT_SCROLLINNER_HIGHLIGHTS_LIST:
            case self::COMPONENT_SCROLLINNER_POSTS_LIST:
            case self::COMPONENT_SCROLLINNER_TAGS_LIST:
            case self::COMPONENT_SCROLLINNER_USERS_LIST:
            case self::COMPONENT_SCROLLINNER_USER_LIST:
            case self::COMPONENT_SCROLLINNER_POSTS_LINE:
            case self::COMPONENT_SCROLLINNER_AUTHORCONTENT_FULLVIEW:
            case self::COMPONENT_SCROLLINNER_AUTHORHIGHLIGHTS_FULLVIEW:
            case self::COMPONENT_SCROLLINNER_AUTHORPOSTS_FULLVIEW:
            case self::COMPONENT_SCROLLINNER_SINGLERELATEDCONTENT_FULLVIEW:
                return array(
                    'row-items' => 1,
                    'class' => 'col-sm-12'
                );
        }

        return parent::getLayoutGrid($component, $props);
    }

    public function getLayoutSubcomponents(array $component)
    {
        $ret = parent::getLayoutSubcomponents($component);

        $layouts = array(
            self::COMPONENT_SCROLLINNER_CONTENT_NAVIGATOR => [PoP_Module_Processor_MultiplePostLayouts::class, PoP_Module_Processor_MultiplePostLayouts::COMPONENT_LAYOUT_MULTIPLECONTENT_NAVIGATOR],
            self::COMPONENT_SCROLLINNER_CONTENT_ADDONS => [PoP_Module_Processor_MultiplePostLayouts::class, PoP_Module_Processor_MultiplePostLayouts::COMPONENT_LAYOUT_MULTIPLECONTENT_ADDONS],
            self::COMPONENT_SCROLLINNER_USERS_NAVIGATOR => [PoP_Module_Processor_MultipleUserLayouts::class, PoP_Module_Processor_MultipleUserLayouts::COMPONENT_LAYOUT_MULTIPLEUSER_NAVIGATOR],
            self::COMPONENT_SCROLLINNER_USER_NAVIGATOR => [PoP_Module_Processor_CustomPreviewUserLayouts::class, PoP_Module_Processor_CustomPreviewUserLayouts::COMPONENT_LAYOUT_PREVIEWUSER_NAVIGATOR],
            self::COMPONENT_SCROLLINNER_USERS_ADDONS => [PoP_Module_Processor_MultipleUserLayouts::class, PoP_Module_Processor_MultipleUserLayouts::COMPONENT_LAYOUT_MULTIPLEUSER_ADDONS],
            self::COMPONENT_SCROLLINNER_USER_ADDONS => [PoP_Module_Processor_CustomPreviewUserLayouts::class, PoP_Module_Processor_CustomPreviewUserLayouts::COMPONENT_LAYOUT_PREVIEWUSER_ADDONS],
            self::COMPONENT_SCROLLINNER_CONTENT_DETAILS => [PoP_Module_Processor_MultiplePostLayouts::class, PoP_Module_Processor_MultiplePostLayouts::COMPONENT_LAYOUT_MULTIPLECONTENT_DETAILS],
            self::COMPONENT_SCROLLINNER_USERS_DETAILS => [PoP_Module_Processor_MultipleUserLayouts::class, PoP_Module_Processor_MultipleUserLayouts::COMPONENT_LAYOUT_MULTIPLEUSER_DETAILS],
            self::COMPONENT_SCROLLINNER_USER_DETAILS => [PoP_Module_Processor_CustomPreviewUserLayouts::class, PoP_Module_Processor_CustomPreviewUserLayouts::COMPONENT_LAYOUT_PREVIEWUSER_DETAILS],
            self::COMPONENT_SCROLLINNER_CONTENT_THUMBNAIL => [PoP_Module_Processor_MultiplePostLayouts::class, PoP_Module_Processor_MultiplePostLayouts::COMPONENT_LAYOUT_MULTIPLECONTENT_THUMBNAIL],
            self::COMPONENT_SCROLLINNER_USERS_THUMBNAIL => [PoP_Module_Processor_MultipleUserLayouts::class, PoP_Module_Processor_MultipleUserLayouts::COMPONENT_LAYOUT_MULTIPLEUSER_THUMBNAIL],
            self::COMPONENT_SCROLLINNER_USER_THUMBNAIL => [PoP_Module_Processor_CustomPreviewUserLayouts::class, PoP_Module_Processor_CustomPreviewUserLayouts::COMPONENT_LAYOUT_PREVIEWUSER_THUMBNAIL],
            self::COMPONENT_SCROLLINNER_CONTENT_LIST => [PoP_Module_Processor_MultiplePostLayouts::class, PoP_Module_Processor_MultiplePostLayouts::COMPONENT_LAYOUT_MULTIPLECONTENT_LIST],
            self::COMPONENT_SCROLLINNER_USERS_LIST => [PoP_Module_Processor_MultipleUserLayouts::class, PoP_Module_Processor_MultipleUserLayouts::COMPONENT_LAYOUT_MULTIPLEUSER_LIST],
            self::COMPONENT_SCROLLINNER_USER_LIST => [PoP_Module_Processor_CustomPreviewUserLayouts::class, PoP_Module_Processor_CustomPreviewUserLayouts::COMPONENT_LAYOUT_PREVIEWUSER_LIST],
            self::COMPONENT_SCROLLINNER_CONTENT_SIMPLEVIEW => [PoP_Module_Processor_MultiplePostLayouts::class, PoP_Module_Processor_MultiplePostLayouts::COMPONENT_LAYOUT_MULTIPLECONTENT_SIMPLEVIEW],
            self::COMPONENT_SCROLLINNER_CONTENT_FULLVIEW => [PoP_Module_Processor_MultiplePostLayouts::class, PoP_Module_Processor_MultiplePostLayouts::COMPONENT_LAYOUT_MULTIPLECONTENT_FULLVIEW],
            self::COMPONENT_SCROLLINNER_USERS_FULLVIEW => [PoP_Module_Processor_MultipleUserLayouts::class, PoP_Module_Processor_MultipleUserLayouts::COMPONENT_LAYOUT_MULTIPLEUSER_FULLUSER],
            self::COMPONENT_SCROLLINNER_USER_FULLVIEW => [PoP_Module_Processor_MultipleUserLayouts::class, PoP_Module_Processor_MultipleUserLayouts::COMPONENT_LAYOUT_MULTIPLEUSER_FULLUSER],
            self::COMPONENT_SCROLLINNER_AUTHORCONTENT_FULLVIEW => [PoP_Module_Processor_MultiplePostLayouts::class, PoP_Module_Processor_MultiplePostLayouts::COMPONENT_LAYOUT_AUTHORMULTIPLECONTENT_FULLVIEW],
            self::COMPONENT_SCROLLINNER_SINGLERELATEDCONTENT_FULLVIEW => [PoP_Module_Processor_MultiplePostLayouts::class, PoP_Module_Processor_MultiplePostLayouts::COMPONENT_LAYOUT_SINGLEMULTIPLECONTENT_FULLVIEW],
            self::COMPONENT_SCROLLINNER_HIGHLIGHTS_NAVIGATOR => [PoP_Module_Processor_CustomPreviewPostLayouts::class, PoP_Module_Processor_CustomPreviewPostLayouts::COMPONENT_LAYOUT_PREVIEWPOST_HIGHLIGHT_CONTENT],
            self::COMPONENT_SCROLLINNER_POSTS_NAVIGATOR => [PoP_Module_Processor_CustomPreviewPostLayouts::class, PoP_Module_Processor_CustomPreviewPostLayouts::COMPONENT_LAYOUT_PREVIEWPOST_POST_NAVIGATOR],
            self::COMPONENT_SCROLLINNER_HIGHLIGHTS_ADDONS => [PoP_Module_Processor_CustomPreviewPostLayouts::class, PoP_Module_Processor_CustomPreviewPostLayouts::COMPONENT_LAYOUT_PREVIEWPOST_HIGHLIGHT_CONTENT],
            self::COMPONENT_SCROLLINNER_POSTS_ADDONS => [PoP_Module_Processor_CustomPreviewPostLayouts::class, PoP_Module_Processor_CustomPreviewPostLayouts::COMPONENT_LAYOUT_PREVIEWPOST_POST_ADDONS],
            self::COMPONENT_SCROLLINNER_POSTS_DETAILS => [PoP_Module_Processor_CustomPreviewPostLayouts::class, PoP_Module_Processor_CustomPreviewPostLayouts::COMPONENT_LAYOUT_PREVIEWPOST_POST_DETAILS],
            self::COMPONENT_SCROLLINNER_TAGS_DETAILS => [PoP_Module_ProcessorTagMultipleComponents::class, PoP_Module_ProcessorTagMultipleComponents::COMPONENT_LAYOUT_TAG_DETAILS],
            self::COMPONENT_SCROLLINNER_HIGHLIGHTS_THUMBNAIL => [PoP_Module_Processor_CustomPreviewPostLayouts::class, PoP_Module_Processor_CustomPreviewPostLayouts::COMPONENT_LAYOUT_PREVIEWPOST_HIGHLIGHT_CONTENT],
            self::COMPONENT_SCROLLINNER_POSTS_THUMBNAIL => [PoP_Module_Processor_CustomPreviewPostLayouts::class, PoP_Module_Processor_CustomPreviewPostLayouts::COMPONENT_LAYOUT_PREVIEWPOST_POST_THUMBNAIL],
            self::COMPONENT_SCROLLINNER_HIGHLIGHTS_LIST => [PoP_Module_Processor_CustomPreviewPostLayouts::class, PoP_Module_Processor_CustomPreviewPostLayouts::COMPONENT_LAYOUT_PREVIEWPOST_HIGHLIGHT_CONTENT],
            self::COMPONENT_SCROLLINNER_POSTS_LIST => [PoP_Module_Processor_CustomPreviewPostLayouts::class, PoP_Module_Processor_CustomPreviewPostLayouts::COMPONENT_LAYOUT_PREVIEWPOST_POST_LIST],
            self::COMPONENT_SCROLLINNER_TAGS_LIST => [PoP_Module_Processor_TagLayouts::class, PoP_Module_Processor_TagLayouts::COMPONENT_LAYOUT_TAG],
            self::COMPONENT_SCROLLINNER_POSTS_LINE => [PoP_Module_Processor_CustomPreviewPostLayouts::class, PoP_Module_Processor_CustomPreviewPostLayouts::COMPONENT_LAYOUT_PREVIEWPOST_LINE],
            self::COMPONENT_SCROLLINNER_POSTS_SIMPLEVIEW => [PoP_Module_Processor_CustomSimpleViewPreviewPostLayouts::class, PoP_Module_Processor_CustomSimpleViewPreviewPostLayouts::COMPONENT_LAYOUT_PREVIEWPOST_SIMPLEVIEW],
            self::COMPONENT_SCROLLINNER_POSTS_FULLVIEW => [PoP_Module_Processor_CustomFullViewLayouts::class, PoP_Module_Processor_CustomFullViewLayouts::COMPONENT_LAYOUT_FULLVIEW_POST],
            self::COMPONENT_SCROLLINNER_HIGHLIGHTS_FULLVIEW => [PoP_Module_Processor_CustomFullViewLayouts::class, PoP_Module_Processor_CustomFullViewLayouts::COMPONENT_LAYOUT_FULLVIEW_HIGHLIGHT],
            self::COMPONENT_SCROLLINNER_AUTHORHIGHLIGHTS_FULLVIEW => [PoP_Module_Processor_CustomFullViewLayouts::class, PoP_Module_Processor_CustomFullViewLayouts::COMPONENT_AUTHORLAYOUT_FULLVIEW_HIGHLIGHT],
            self::COMPONENT_SCROLLINNER_AUTHORPOSTS_FULLVIEW => [PoP_Module_Processor_CustomFullViewLayouts::class, PoP_Module_Processor_CustomFullViewLayouts::COMPONENT_AUTHORLAYOUT_FULLVIEW_POST],
        );

        if ($layout = $layouts[$component[1]] ?? null) {
            $ret[] = $layout;
        }

        return $ret;
    }
}



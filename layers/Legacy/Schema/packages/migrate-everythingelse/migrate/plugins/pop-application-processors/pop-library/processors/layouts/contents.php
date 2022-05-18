<?php

class PoP_Module_Processor_ContentLayouts extends PoP_Module_Processor_ContentLayoutsBase
{
    public final const MODULE_LAYOUT_CONTENT_POST = 'layout-content-post';
    public final const MODULE_LAYOUT_CONTENT_POSTFEED = 'layout-content-postfeed';
    public final const MODULE_LAYOUT_CONTENT_POSTCOMPACT = 'layout-content-postcompact';
    public final const MODULE_LAYOUT_CONTENT_COMMENT = 'layout-content-comment';
    public final const MODULE_LAYOUT_CONTENT_PAGE = 'layout-content-page';

    public function getComponentVariationsToProcess(): array
    {
        return array(
            [self::class, self::MODULE_LAYOUT_CONTENT_POST],
            [self::class, self::MODULE_LAYOUT_CONTENT_POSTFEED],
            [self::class, self::MODULE_LAYOUT_CONTENT_POSTCOMPACT],
            [self::class, self::MODULE_LAYOUT_CONTENT_COMMENT],
            [self::class, self::MODULE_LAYOUT_CONTENT_PAGE],
        );
    }

    protected function getUsermentionsLayout(array $module)
    {

        // Add the layout below to preload the popover content for user @mentions, coupled with js function 'contentPopover'
        switch ($module[1]) {
            case self::MODULE_LAYOUT_CONTENT_POST:
            case self::MODULE_LAYOUT_CONTENT_POSTFEED:
            case self::MODULE_LAYOUT_CONTENT_POSTCOMPACT:
                return [PoP_Module_Processor_PostUserMentionsLayouts::class, PoP_Module_Processor_PostUserMentionsLayouts::MODULE_LAYOUT_POSTUSERMENTIONS];

            case self::MODULE_LAYOUT_CONTENT_COMMENT:
                return [PoP_Module_Processor_CommentUserMentionsLayouts::class, PoP_Module_Processor_CommentUserMentionsLayouts::MODULE_LAYOUT_COMMENTUSERMENTIONS];
        }

        return null;
    }

    public function getAbovecontentSubmodules(array $module)
    {
        $ret = parent::getAbovecontentSubmodules($module);

        switch ($module[1]) {
            case self::MODULE_LAYOUT_CONTENT_POST:
                if (defined('POP_ADDPOSTLINKSPROCESSORS_INITIALIZED')) {
                    $ret[] = [PoP_AddPostLinks_Module_Processor_LayoutWrappers::class, PoP_AddPostLinks_Module_Processor_LayoutWrappers::MODULE_ADDPOSTLINKS_LAYOUTWRAPPER_LINKFRAMEVISIBLE];
                }
                break;

            case self::MODULE_LAYOUT_CONTENT_POSTFEED:
                if (defined('POP_ADDPOSTLINKSPROCESSORS_INITIALIZED')) {
                    $ret[] = [PoP_AddPostLinks_Module_Processor_LayoutWrappers::class, PoP_AddPostLinks_Module_Processor_LayoutWrappers::MODULE_ADDPOSTLINKS_LAYOUTWRAPPER_LINKFRAMECOLLAPSED];
                }
                break;
        }

        // Add the layout below to preload the popover content for user @mentions, coupled with js function 'contentPopover'
        switch ($module[1]) {
            case self::MODULE_LAYOUT_CONTENT_POST:
            case self::MODULE_LAYOUT_CONTENT_POSTFEED:
            case self::MODULE_LAYOUT_CONTENT_POSTCOMPACT:
            case self::MODULE_LAYOUT_CONTENT_COMMENT:
                $ret[] = $this->getUsermentionsLayout($module);
                break;
        }

        return $ret;
    }

    public function getContentMaxlength(array $module, array &$props)
    {
        switch ($module[1]) {
            case self::MODULE_LAYOUT_CONTENT_POSTCOMPACT:
                // Length: 400 characters max
                return 400;
        }

        return parent::getContentMaxlength($module, $props);
    }

    public function getJsmethods(array $module, array &$props)
    {
        $ret = parent::getJsmethods($module, $props);

        switch ($module[1]) {
            case self::MODULE_LAYOUT_CONTENT_POST:
            case self::MODULE_LAYOUT_CONTENT_POSTFEED:
            case self::MODULE_LAYOUT_CONTENT_POSTCOMPACT:
            case self::MODULE_LAYOUT_CONTENT_COMMENT:
                // Make the images inside img-responsive
                $this->addJsmethod($ret, 'imageResponsive');

                // Add the popover for the @mentions
                $this->addJsmethod($ret, 'contentPopover');
                break;
        }

        return $ret;
    }

    public function initModelProps(array $module, array &$props): void
    {

        // Hide the @mentions popover code
        switch ($module[1]) {
            case self::MODULE_LAYOUT_CONTENT_POST:
            case self::MODULE_LAYOUT_CONTENT_POSTFEED:
            case self::MODULE_LAYOUT_CONTENT_POSTCOMPACT:
            case self::MODULE_LAYOUT_CONTENT_COMMENT:
                $usermentions = $this->getUsermentionsLayout($module);
                $this->appendProp($usermentions, $props, 'class', 'hidden');
                break;
        }

        switch ($module[1]) {
            case self::MODULE_LAYOUT_CONTENT_POST:
            case self::MODULE_LAYOUT_CONTENT_POSTFEED:
                $this->appendProp($module, $props, 'class', 'readable');
                break;
        }

        parent::initModelProps($module, $props);
    }
}




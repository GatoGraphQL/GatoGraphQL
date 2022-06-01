<?php

class PoP_Module_Processor_ContentLayouts extends PoP_Module_Processor_ContentLayoutsBase
{
    public final const COMPONENT_LAYOUT_CONTENT_POST = 'layout-content-post';
    public final const COMPONENT_LAYOUT_CONTENT_POSTFEED = 'layout-content-postfeed';
    public final const COMPONENT_LAYOUT_CONTENT_POSTCOMPACT = 'layout-content-postcompact';
    public final const COMPONENT_LAYOUT_CONTENT_COMMENT = 'layout-content-comment';
    public final const COMPONENT_LAYOUT_CONTENT_PAGE = 'layout-content-page';

    public function getComponentNamesToProcess(): array
    {
        return array(
            self::COMPONENT_LAYOUT_CONTENT_POST,
            self::COMPONENT_LAYOUT_CONTENT_POSTFEED,
            self::COMPONENT_LAYOUT_CONTENT_POSTCOMPACT,
            self::COMPONENT_LAYOUT_CONTENT_COMMENT,
            self::COMPONENT_LAYOUT_CONTENT_PAGE,
        );
    }

    protected function getUsermentionsLayout(\PoP\ComponentModel\Component\Component $component)
    {

        // Add the layout below to preload the popover content for user @mentions, coupled with js function 'contentPopover'
        switch ($component->name) {
            case self::COMPONENT_LAYOUT_CONTENT_POST:
            case self::COMPONENT_LAYOUT_CONTENT_POSTFEED:
            case self::COMPONENT_LAYOUT_CONTENT_POSTCOMPACT:
                return [PoP_Module_Processor_PostUserMentionsLayouts::class, PoP_Module_Processor_PostUserMentionsLayouts::COMPONENT_LAYOUT_POSTUSERMENTIONS];

            case self::COMPONENT_LAYOUT_CONTENT_COMMENT:
                return [PoP_Module_Processor_CommentUserMentionsLayouts::class, PoP_Module_Processor_CommentUserMentionsLayouts::COMPONENT_LAYOUT_COMMENTUSERMENTIONS];
        }

        return null;
    }

    public function getAbovecontentSubcomponents(\PoP\ComponentModel\Component\Component $component)
    {
        $ret = parent::getAbovecontentSubcomponents($component);

        switch ($component->name) {
            case self::COMPONENT_LAYOUT_CONTENT_POST:
                if (defined('POP_ADDPOSTLINKSPROCESSORS_INITIALIZED')) {
                    $ret[] = [PoP_AddPostLinks_Module_Processor_LayoutWrappers::class, PoP_AddPostLinks_Module_Processor_LayoutWrappers::COMPONENT_ADDPOSTLINKS_LAYOUTWRAPPER_LINKFRAMEVISIBLE];
                }
                break;

            case self::COMPONENT_LAYOUT_CONTENT_POSTFEED:
                if (defined('POP_ADDPOSTLINKSPROCESSORS_INITIALIZED')) {
                    $ret[] = [PoP_AddPostLinks_Module_Processor_LayoutWrappers::class, PoP_AddPostLinks_Module_Processor_LayoutWrappers::COMPONENT_ADDPOSTLINKS_LAYOUTWRAPPER_LINKFRAMECOLLAPSED];
                }
                break;
        }

        // Add the layout below to preload the popover content for user @mentions, coupled with js function 'contentPopover'
        switch ($component->name) {
            case self::COMPONENT_LAYOUT_CONTENT_POST:
            case self::COMPONENT_LAYOUT_CONTENT_POSTFEED:
            case self::COMPONENT_LAYOUT_CONTENT_POSTCOMPACT:
            case self::COMPONENT_LAYOUT_CONTENT_COMMENT:
                $ret[] = $this->getUsermentionsLayout($component);
                break;
        }

        return $ret;
    }

    public function getContentMaxlength(\PoP\ComponentModel\Component\Component $component, array &$props)
    {
        switch ($component->name) {
            case self::COMPONENT_LAYOUT_CONTENT_POSTCOMPACT:
                // Length: 400 characters max
                return 400;
        }

        return parent::getContentMaxlength($component, $props);
    }

    public function getJsmethods(\PoP\ComponentModel\Component\Component $component, array &$props)
    {
        $ret = parent::getJsmethods($component, $props);

        switch ($component->name) {
            case self::COMPONENT_LAYOUT_CONTENT_POST:
            case self::COMPONENT_LAYOUT_CONTENT_POSTFEED:
            case self::COMPONENT_LAYOUT_CONTENT_POSTCOMPACT:
            case self::COMPONENT_LAYOUT_CONTENT_COMMENT:
                // Make the images inside img-responsive
                $this->addJsmethod($ret, 'imageResponsive');

                // Add the popover for the @mentions
                $this->addJsmethod($ret, 'contentPopover');
                break;
        }

        return $ret;
    }

    public function initModelProps(\PoP\ComponentModel\Component\Component $component, array &$props): void
    {

        // Hide the @mentions popover code
        switch ($component->name) {
            case self::COMPONENT_LAYOUT_CONTENT_POST:
            case self::COMPONENT_LAYOUT_CONTENT_POSTFEED:
            case self::COMPONENT_LAYOUT_CONTENT_POSTCOMPACT:
            case self::COMPONENT_LAYOUT_CONTENT_COMMENT:
                $usermentions = $this->getUsermentionsLayout($component);
                $this->appendProp($usermentions, $props, 'class', 'hidden');
                break;
        }

        switch ($component->name) {
            case self::COMPONENT_LAYOUT_CONTENT_POST:
            case self::COMPONENT_LAYOUT_CONTENT_POSTFEED:
                $this->appendProp($component, $props, 'class', 'readable');
                break;
        }

        parent::initModelProps($component, $props);
    }
}




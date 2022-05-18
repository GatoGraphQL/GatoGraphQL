<?php
use PoP\Root\Facades\Translation\TranslationAPIFacade;

class UserStance_Module_Processor_CustomPreviewPostLayouts extends PoP_Module_Processor_CustomPreviewPostLayoutsBase
{
    public final const COMPONENT_LAYOUT_PREVIEWPOST_STANCE_CONTENTAUTHOR = 'layout-previewpost-stance-contentauthor';
    public final const COMPONENT_LAYOUT_PREVIEWPOST_STANCE_CONTENTREFERENCED = 'layout-previewpost-stance-contentreferenced';
    public final const COMPONENT_LAYOUT_PREVIEWPOST_STANCE_CONTENTAUTHORREFERENCED = 'layout-previewpost-stance-contentauthorreferenced';
    public final const COMPONENT_LAYOUT_PREVIEWPOST_STANCE_NAVIGATOR = 'layout-previewpost-stance-navigator';
    public final const COMPONENT_LAYOUT_PREVIEWPOST_STANCE_THUMBNAIL = 'layout-previewpost-stance-thumbnail';
    public final const COMPONENT_LAYOUT_PREVIEWPOST_STANCE_EDIT = 'layout-previewpost-stance-edit';

    public function getComponentsToProcess(): array
    {
        return array(
            [self::class, self::COMPONENT_LAYOUT_PREVIEWPOST_STANCE_CONTENTAUTHOR],
            [self::class, self::COMPONENT_LAYOUT_PREVIEWPOST_STANCE_CONTENTREFERENCED],
            [self::class, self::COMPONENT_LAYOUT_PREVIEWPOST_STANCE_CONTENTAUTHORREFERENCED],
            [self::class, self::COMPONENT_LAYOUT_PREVIEWPOST_STANCE_NAVIGATOR],
            [self::class, self::COMPONENT_LAYOUT_PREVIEWPOST_STANCE_THUMBNAIL],
            [self::class, self::COMPONENT_LAYOUT_PREVIEWPOST_STANCE_EDIT],
        );
    }

    public function getUrlField(array $component)
    {
        switch ($component[1]) {
            case self::COMPONENT_LAYOUT_PREVIEWPOST_STANCE_EDIT:
                return 'editURL';
        }

        return parent::getUrlField($component);
    }

    public function getLinktarget(array $component, array &$props)
    {
        switch ($component[1]) {
            case self::COMPONENT_LAYOUT_PREVIEWPOST_STANCE_EDIT:
                if (PoP_Application_Utils::getAddcontentTarget() == POP_TARGET_ADDONS) {
                    return POP_TARGET_ADDONS;
                }
                break;
        }

        return parent::getLinktarget($component, $props);
    }

    public function getQuicklinkgroupBottomSubmodule(array $component)
    {
        switch ($component[1]) {
            case self::COMPONENT_LAYOUT_PREVIEWPOST_STANCE_EDIT:
                return [UserStance_Module_Processor_CustomQuicklinkGroups::class, UserStance_Module_Processor_CustomQuicklinkGroups::COMPONENT_QUICKLINKGROUP_STANCEEDIT];

            case self::COMPONENT_LAYOUT_PREVIEWPOST_STANCE_CONTENTAUTHOR:
            case self::COMPONENT_LAYOUT_PREVIEWPOST_STANCE_CONTENTREFERENCED:
            case self::COMPONENT_LAYOUT_PREVIEWPOST_STANCE_CONTENTAUTHORREFERENCED:
            case self::COMPONENT_LAYOUT_PREVIEWPOST_STANCE_NAVIGATOR:
            case self::COMPONENT_LAYOUT_PREVIEWPOST_STANCE_THUMBNAIL:
                return [UserStance_Module_Processor_CustomQuicklinkGroups::class, UserStance_Module_Processor_CustomQuicklinkGroups::COMPONENT_QUICKLINKGROUP_STANCECONTENT];
        }

        return parent::getQuicklinkgroupBottomSubmodule($component);
    }

    public function showPosttitle(array $component)
    {
        switch ($component[1]) {
            case self::COMPONENT_LAYOUT_PREVIEWPOST_STANCE_CONTENTAUTHOR:
            case self::COMPONENT_LAYOUT_PREVIEWPOST_STANCE_CONTENTREFERENCED:
            case self::COMPONENT_LAYOUT_PREVIEWPOST_STANCE_CONTENTAUTHORREFERENCED:
            case self::COMPONENT_LAYOUT_PREVIEWPOST_STANCE_NAVIGATOR:
            case self::COMPONENT_LAYOUT_PREVIEWPOST_STANCE_THUMBNAIL:
            case self::COMPONENT_LAYOUT_PREVIEWPOST_STANCE_EDIT:
                return false;
        }

        return parent::showPosttitle($component);
    }

    public function getContentSubmodule(array $component)
    {
        switch ($component[1]) {
            case self::COMPONENT_LAYOUT_PREVIEWPOST_STANCE_CONTENTAUTHOR:
            case self::COMPONENT_LAYOUT_PREVIEWPOST_STANCE_CONTENTREFERENCED:
            case self::COMPONENT_LAYOUT_PREVIEWPOST_STANCE_CONTENTAUTHORREFERENCED:
            case self::COMPONENT_LAYOUT_PREVIEWPOST_STANCE_NAVIGATOR:
            case self::COMPONENT_LAYOUT_PREVIEWPOST_STANCE_THUMBNAIL:
            case self::COMPONENT_LAYOUT_PREVIEWPOST_STANCE_EDIT:
                return [PoP_Module_Processor_ContentLayouts::class, PoP_Module_Processor_ContentLayouts::COMPONENT_LAYOUT_CONTENT_POSTCOMPACT];
        }

        return parent::getContentSubmodule($component);
    }

    public function getAbovecontentSubmodules(array $component)
    {
        $ret = parent::getAbovecontentSubmodules($component);

        switch ($component[1]) {
            case self::COMPONENT_LAYOUT_PREVIEWPOST_STANCE_CONTENTAUTHOR:
            case self::COMPONENT_LAYOUT_PREVIEWPOST_STANCE_CONTENTREFERENCED:
            case self::COMPONENT_LAYOUT_PREVIEWPOST_STANCE_CONTENTAUTHORREFERENCED:
            case self::COMPONENT_LAYOUT_PREVIEWPOST_STANCE_NAVIGATOR:
            case self::COMPONENT_LAYOUT_PREVIEWPOST_STANCE_THUMBNAIL:
                $ret[] = [UserStance_Module_Processor_Layouts::class, UserStance_Module_Processor_Layouts::COMPONENT_LAYOUTSTANCE];
                break;
        }

        return $ret;
    }

    public function getAuthorAvatarModule(array $component)
    {
        if (defined('POP_AVATARPROCESSORS_INITIALIZED')) {
            switch ($component[1]) {
                case self::COMPONENT_LAYOUT_PREVIEWPOST_STANCE_CONTENTAUTHOR:
                case self::COMPONENT_LAYOUT_PREVIEWPOST_STANCE_CONTENTREFERENCED:
                case self::COMPONENT_LAYOUT_PREVIEWPOST_STANCE_CONTENTAUTHORREFERENCED:
                    return [PoP_Module_Processor_PostAuthorAvatarLayouts::class, PoP_Module_Processor_PostAuthorAvatarLayouts::COMPONENT_LAYOUTPOST_AUTHORAVATAR60];
            }
        }

        return parent::getAuthorAvatarModule($component);
    }

    public function getBottomSubmodules(array $component)
    {
        $ret = parent::getBottomSubmodules($component);

        switch ($component[1]) {
            case self::COMPONENT_LAYOUT_PREVIEWPOST_STANCE_EDIT:
                $ret[] = [PoP_Module_Processor_StanceTargetSubcomponentLayouts::class, PoP_Module_Processor_StanceTargetSubcomponentLayouts::COMPONENT_LAYOUT_STANCETARGET_LINE];
                break;
        }

        return $ret;
    }

    public function getBelowcontentSubmodules(array $component)
    {
        $ret = parent::getBelowcontentSubmodules($component);

        switch ($component[1]) {
            case self::COMPONENT_LAYOUT_PREVIEWPOST_STANCE_CONTENTREFERENCED:
                $ret[] = [PoP_Module_Processor_StanceTargetSubcomponentLayouts::class, PoP_Module_Processor_StanceTargetSubcomponentLayouts::COMPONENT_LAYOUT_STANCETARGET_POSTTITLE];
                break;

            case self::COMPONENT_LAYOUT_PREVIEWPOST_STANCE_CONTENTAUTHORREFERENCED:
            case self::COMPONENT_LAYOUT_PREVIEWPOST_STANCE_NAVIGATOR:
            case self::COMPONENT_LAYOUT_PREVIEWPOST_STANCE_THUMBNAIL:
                $ret[] = [PoP_Module_Processor_StanceTargetSubcomponentLayouts::class, PoP_Module_Processor_StanceTargetSubcomponentLayouts::COMPONENT_LAYOUT_STANCETARGET_AUTHORPOSTTITLE];
                break;
        }

        return $ret;
    }

    public function getPostThumbSubmodule(array $component)
    {
        switch ($component[1]) {
            case self::COMPONENT_LAYOUT_PREVIEWPOST_STANCE_CONTENTAUTHOR:
            case self::COMPONENT_LAYOUT_PREVIEWPOST_STANCE_CONTENTREFERENCED:
            case self::COMPONENT_LAYOUT_PREVIEWPOST_STANCE_CONTENTAUTHORREFERENCED:
            case self::COMPONENT_LAYOUT_PREVIEWPOST_STANCE_NAVIGATOR:
            case self::COMPONENT_LAYOUT_PREVIEWPOST_STANCE_THUMBNAIL:
            case self::COMPONENT_LAYOUT_PREVIEWPOST_STANCE_EDIT:
                return null;
        }

        return parent::getPostThumbSubmodule($component);
    }

    public function authorPositions(array $component)
    {
        switch ($component[1]) {
            case self::COMPONENT_LAYOUT_PREVIEWPOST_STANCE_CONTENTAUTHOR:
            case self::COMPONENT_LAYOUT_PREVIEWPOST_STANCE_CONTENTAUTHORREFERENCED:
            case self::COMPONENT_LAYOUT_PREVIEWPOST_STANCE_NAVIGATOR:
            case self::COMPONENT_LAYOUT_PREVIEWPOST_STANCE_THUMBNAIL:
                return array(
                    GD_CONSTANT_AUTHORPOSITION_BELOWCONTENT
                );

            case self::COMPONENT_LAYOUT_PREVIEWPOST_STANCE_EDIT:
            case self::COMPONENT_LAYOUT_PREVIEWPOST_STANCE_CONTENTREFERENCED:
                return array();
        }

        return parent::authorPositions($component);
    }

    public function getImmutableConfiguration(array $component, array &$props): array
    {
        $ret = parent::getImmutableConfiguration($component, $props);

        switch ($component[1]) {
            case self::COMPONENT_LAYOUT_PREVIEWPOST_STANCE_CONTENTAUTHORREFERENCED:
            case self::COMPONENT_LAYOUT_PREVIEWPOST_STANCE_NAVIGATOR:
            case self::COMPONENT_LAYOUT_PREVIEWPOST_STANCE_THUMBNAIL:
                $ret[GD_JS_CLASSES]['authors'] = 'inline';
                $ret[GD_JS_CLASSES]['belowcontent'] = 'inline';
                $ret[GD_JS_CLASSES]['belowcontent-inner'] = 'inline';
                break;
        }

        switch ($component[1]) {
            case self::COMPONENT_LAYOUT_PREVIEWPOST_STANCE_CONTENTAUTHOR:
            case self::COMPONENT_LAYOUT_PREVIEWPOST_STANCE_CONTENTREFERENCED:
            case self::COMPONENT_LAYOUT_PREVIEWPOST_STANCE_CONTENTAUTHORREFERENCED:
            case self::COMPONENT_LAYOUT_PREVIEWPOST_STANCE_NAVIGATOR:
            case self::COMPONENT_LAYOUT_PREVIEWPOST_STANCE_THUMBNAIL:
                $ret[GD_JS_CLASSES]['wrapper'] .= ' media';
                $ret[GD_JS_CLASSES]['content-body'] .= ' media-body';
                $ret[GD_JS_CLASSES]['thumb-wrapper'] .= ' media-left';
                $ret[GD_JS_CLASSES]['content'] .= ' readable';
                break;
        }

        return $ret;
    }

    public function getTitleBeforeauthors(array $component, array &$props)
    {
        switch ($component[1]) {
            case self::COMPONENT_LAYOUT_PREVIEWPOST_STANCE_CONTENTAUTHOR:
            case self::COMPONENT_LAYOUT_PREVIEWPOST_STANCE_CONTENTAUTHORREFERENCED:
            case self::COMPONENT_LAYOUT_PREVIEWPOST_STANCE_NAVIGATOR:
            case self::COMPONENT_LAYOUT_PREVIEWPOST_STANCE_THUMBNAIL:
                return array(
                    'belowcontent' => sprintf(
                        '<span class="pop-pulltextright">%s</span>',
                        TranslationAPIFacade::getInstance()->__('—', 'pop-userstance-processors')
                    )
                );
        }

        return parent::getTitleBeforeauthors($component, $props);
    }

    public function initModelProps(array $component, array &$props): void
    {
        switch ($component[1]) {
            case self::COMPONENT_LAYOUT_PREVIEWPOST_STANCE_CONTENTAUTHOR:
            case self::COMPONENT_LAYOUT_PREVIEWPOST_STANCE_CONTENTREFERENCED:
            case self::COMPONENT_LAYOUT_PREVIEWPOST_STANCE_CONTENTAUTHORREFERENCED:
            case self::COMPONENT_LAYOUT_PREVIEWPOST_STANCE_NAVIGATOR:
            case self::COMPONENT_LAYOUT_PREVIEWPOST_STANCE_THUMBNAIL:
                $this->appendProp($component, $props, 'class', 'alert alert-stance stances');
                break;
        }

        parent::initModelProps($component, $props);
    }
}



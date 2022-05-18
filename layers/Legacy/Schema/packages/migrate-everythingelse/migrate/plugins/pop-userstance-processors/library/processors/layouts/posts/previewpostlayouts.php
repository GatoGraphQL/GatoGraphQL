<?php
use PoP\Root\Facades\Translation\TranslationAPIFacade;

class UserStance_Module_Processor_CustomPreviewPostLayouts extends PoP_Module_Processor_CustomPreviewPostLayoutsBase
{
    public final const MODULE_LAYOUT_PREVIEWPOST_STANCE_CONTENTAUTHOR = 'layout-previewpost-stance-contentauthor';
    public final const MODULE_LAYOUT_PREVIEWPOST_STANCE_CONTENTREFERENCED = 'layout-previewpost-stance-contentreferenced';
    public final const MODULE_LAYOUT_PREVIEWPOST_STANCE_CONTENTAUTHORREFERENCED = 'layout-previewpost-stance-contentauthorreferenced';
    public final const MODULE_LAYOUT_PREVIEWPOST_STANCE_NAVIGATOR = 'layout-previewpost-stance-navigator';
    public final const MODULE_LAYOUT_PREVIEWPOST_STANCE_THUMBNAIL = 'layout-previewpost-stance-thumbnail';
    public final const MODULE_LAYOUT_PREVIEWPOST_STANCE_EDIT = 'layout-previewpost-stance-edit';

    public function getComponentVariationsToProcess(): array
    {
        return array(
            [self::class, self::MODULE_LAYOUT_PREVIEWPOST_STANCE_CONTENTAUTHOR],
            [self::class, self::MODULE_LAYOUT_PREVIEWPOST_STANCE_CONTENTREFERENCED],
            [self::class, self::MODULE_LAYOUT_PREVIEWPOST_STANCE_CONTENTAUTHORREFERENCED],
            [self::class, self::MODULE_LAYOUT_PREVIEWPOST_STANCE_NAVIGATOR],
            [self::class, self::MODULE_LAYOUT_PREVIEWPOST_STANCE_THUMBNAIL],
            [self::class, self::MODULE_LAYOUT_PREVIEWPOST_STANCE_EDIT],
        );
    }

    public function getUrlField(array $componentVariation)
    {
        switch ($componentVariation[1]) {
            case self::MODULE_LAYOUT_PREVIEWPOST_STANCE_EDIT:
                return 'editURL';
        }

        return parent::getUrlField($componentVariation);
    }

    public function getLinktarget(array $componentVariation, array &$props)
    {
        switch ($componentVariation[1]) {
            case self::MODULE_LAYOUT_PREVIEWPOST_STANCE_EDIT:
                if (PoP_Application_Utils::getAddcontentTarget() == POP_TARGET_ADDONS) {
                    return POP_TARGET_ADDONS;
                }
                break;
        }

        return parent::getLinktarget($componentVariation, $props);
    }

    public function getQuicklinkgroupBottomSubmodule(array $componentVariation)
    {
        switch ($componentVariation[1]) {
            case self::MODULE_LAYOUT_PREVIEWPOST_STANCE_EDIT:
                return [UserStance_Module_Processor_CustomQuicklinkGroups::class, UserStance_Module_Processor_CustomQuicklinkGroups::MODULE_QUICKLINKGROUP_STANCEEDIT];

            case self::MODULE_LAYOUT_PREVIEWPOST_STANCE_CONTENTAUTHOR:
            case self::MODULE_LAYOUT_PREVIEWPOST_STANCE_CONTENTREFERENCED:
            case self::MODULE_LAYOUT_PREVIEWPOST_STANCE_CONTENTAUTHORREFERENCED:
            case self::MODULE_LAYOUT_PREVIEWPOST_STANCE_NAVIGATOR:
            case self::MODULE_LAYOUT_PREVIEWPOST_STANCE_THUMBNAIL:
                return [UserStance_Module_Processor_CustomQuicklinkGroups::class, UserStance_Module_Processor_CustomQuicklinkGroups::MODULE_QUICKLINKGROUP_STANCECONTENT];
        }

        return parent::getQuicklinkgroupBottomSubmodule($componentVariation);
    }

    public function showPosttitle(array $componentVariation)
    {
        switch ($componentVariation[1]) {
            case self::MODULE_LAYOUT_PREVIEWPOST_STANCE_CONTENTAUTHOR:
            case self::MODULE_LAYOUT_PREVIEWPOST_STANCE_CONTENTREFERENCED:
            case self::MODULE_LAYOUT_PREVIEWPOST_STANCE_CONTENTAUTHORREFERENCED:
            case self::MODULE_LAYOUT_PREVIEWPOST_STANCE_NAVIGATOR:
            case self::MODULE_LAYOUT_PREVIEWPOST_STANCE_THUMBNAIL:
            case self::MODULE_LAYOUT_PREVIEWPOST_STANCE_EDIT:
                return false;
        }

        return parent::showPosttitle($componentVariation);
    }

    public function getContentSubmodule(array $componentVariation)
    {
        switch ($componentVariation[1]) {
            case self::MODULE_LAYOUT_PREVIEWPOST_STANCE_CONTENTAUTHOR:
            case self::MODULE_LAYOUT_PREVIEWPOST_STANCE_CONTENTREFERENCED:
            case self::MODULE_LAYOUT_PREVIEWPOST_STANCE_CONTENTAUTHORREFERENCED:
            case self::MODULE_LAYOUT_PREVIEWPOST_STANCE_NAVIGATOR:
            case self::MODULE_LAYOUT_PREVIEWPOST_STANCE_THUMBNAIL:
            case self::MODULE_LAYOUT_PREVIEWPOST_STANCE_EDIT:
                return [PoP_Module_Processor_ContentLayouts::class, PoP_Module_Processor_ContentLayouts::MODULE_LAYOUT_CONTENT_POSTCOMPACT];
        }

        return parent::getContentSubmodule($componentVariation);
    }

    public function getAbovecontentSubmodules(array $componentVariation)
    {
        $ret = parent::getAbovecontentSubmodules($componentVariation);

        switch ($componentVariation[1]) {
            case self::MODULE_LAYOUT_PREVIEWPOST_STANCE_CONTENTAUTHOR:
            case self::MODULE_LAYOUT_PREVIEWPOST_STANCE_CONTENTREFERENCED:
            case self::MODULE_LAYOUT_PREVIEWPOST_STANCE_CONTENTAUTHORREFERENCED:
            case self::MODULE_LAYOUT_PREVIEWPOST_STANCE_NAVIGATOR:
            case self::MODULE_LAYOUT_PREVIEWPOST_STANCE_THUMBNAIL:
                $ret[] = [UserStance_Module_Processor_Layouts::class, UserStance_Module_Processor_Layouts::MODULE_LAYOUTSTANCE];
                break;
        }

        return $ret;
    }

    public function getAuthorAvatarModule(array $componentVariation)
    {
        if (defined('POP_AVATARPROCESSORS_INITIALIZED')) {
            switch ($componentVariation[1]) {
                case self::MODULE_LAYOUT_PREVIEWPOST_STANCE_CONTENTAUTHOR:
                case self::MODULE_LAYOUT_PREVIEWPOST_STANCE_CONTENTREFERENCED:
                case self::MODULE_LAYOUT_PREVIEWPOST_STANCE_CONTENTAUTHORREFERENCED:
                    return [PoP_Module_Processor_PostAuthorAvatarLayouts::class, PoP_Module_Processor_PostAuthorAvatarLayouts::MODULE_LAYOUTPOST_AUTHORAVATAR60];
            }
        }

        return parent::getAuthorAvatarModule($componentVariation);
    }

    public function getBottomSubmodules(array $componentVariation)
    {
        $ret = parent::getBottomSubmodules($componentVariation);

        switch ($componentVariation[1]) {
            case self::MODULE_LAYOUT_PREVIEWPOST_STANCE_EDIT:
                $ret[] = [PoP_Module_Processor_StanceTargetSubcomponentLayouts::class, PoP_Module_Processor_StanceTargetSubcomponentLayouts::MODULE_LAYOUT_STANCETARGET_LINE];
                break;
        }

        return $ret;
    }

    public function getBelowcontentSubmodules(array $componentVariation)
    {
        $ret = parent::getBelowcontentSubmodules($componentVariation);

        switch ($componentVariation[1]) {
            case self::MODULE_LAYOUT_PREVIEWPOST_STANCE_CONTENTREFERENCED:
                $ret[] = [PoP_Module_Processor_StanceTargetSubcomponentLayouts::class, PoP_Module_Processor_StanceTargetSubcomponentLayouts::MODULE_LAYOUT_STANCETARGET_POSTTITLE];
                break;

            case self::MODULE_LAYOUT_PREVIEWPOST_STANCE_CONTENTAUTHORREFERENCED:
            case self::MODULE_LAYOUT_PREVIEWPOST_STANCE_NAVIGATOR:
            case self::MODULE_LAYOUT_PREVIEWPOST_STANCE_THUMBNAIL:
                $ret[] = [PoP_Module_Processor_StanceTargetSubcomponentLayouts::class, PoP_Module_Processor_StanceTargetSubcomponentLayouts::MODULE_LAYOUT_STANCETARGET_AUTHORPOSTTITLE];
                break;
        }

        return $ret;
    }

    public function getPostThumbSubmodule(array $componentVariation)
    {
        switch ($componentVariation[1]) {
            case self::MODULE_LAYOUT_PREVIEWPOST_STANCE_CONTENTAUTHOR:
            case self::MODULE_LAYOUT_PREVIEWPOST_STANCE_CONTENTREFERENCED:
            case self::MODULE_LAYOUT_PREVIEWPOST_STANCE_CONTENTAUTHORREFERENCED:
            case self::MODULE_LAYOUT_PREVIEWPOST_STANCE_NAVIGATOR:
            case self::MODULE_LAYOUT_PREVIEWPOST_STANCE_THUMBNAIL:
            case self::MODULE_LAYOUT_PREVIEWPOST_STANCE_EDIT:
                return null;
        }

        return parent::getPostThumbSubmodule($componentVariation);
    }

    public function authorPositions(array $componentVariation)
    {
        switch ($componentVariation[1]) {
            case self::MODULE_LAYOUT_PREVIEWPOST_STANCE_CONTENTAUTHOR:
            case self::MODULE_LAYOUT_PREVIEWPOST_STANCE_CONTENTAUTHORREFERENCED:
            case self::MODULE_LAYOUT_PREVIEWPOST_STANCE_NAVIGATOR:
            case self::MODULE_LAYOUT_PREVIEWPOST_STANCE_THUMBNAIL:
                return array(
                    GD_CONSTANT_AUTHORPOSITION_BELOWCONTENT
                );

            case self::MODULE_LAYOUT_PREVIEWPOST_STANCE_EDIT:
            case self::MODULE_LAYOUT_PREVIEWPOST_STANCE_CONTENTREFERENCED:
                return array();
        }

        return parent::authorPositions($componentVariation);
    }

    public function getImmutableConfiguration(array $componentVariation, array &$props): array
    {
        $ret = parent::getImmutableConfiguration($componentVariation, $props);

        switch ($componentVariation[1]) {
            case self::MODULE_LAYOUT_PREVIEWPOST_STANCE_CONTENTAUTHORREFERENCED:
            case self::MODULE_LAYOUT_PREVIEWPOST_STANCE_NAVIGATOR:
            case self::MODULE_LAYOUT_PREVIEWPOST_STANCE_THUMBNAIL:
                $ret[GD_JS_CLASSES]['authors'] = 'inline';
                $ret[GD_JS_CLASSES]['belowcontent'] = 'inline';
                $ret[GD_JS_CLASSES]['belowcontent-inner'] = 'inline';
                break;
        }

        switch ($componentVariation[1]) {
            case self::MODULE_LAYOUT_PREVIEWPOST_STANCE_CONTENTAUTHOR:
            case self::MODULE_LAYOUT_PREVIEWPOST_STANCE_CONTENTREFERENCED:
            case self::MODULE_LAYOUT_PREVIEWPOST_STANCE_CONTENTAUTHORREFERENCED:
            case self::MODULE_LAYOUT_PREVIEWPOST_STANCE_NAVIGATOR:
            case self::MODULE_LAYOUT_PREVIEWPOST_STANCE_THUMBNAIL:
                $ret[GD_JS_CLASSES]['wrapper'] .= ' media';
                $ret[GD_JS_CLASSES]['content-body'] .= ' media-body';
                $ret[GD_JS_CLASSES]['thumb-wrapper'] .= ' media-left';
                $ret[GD_JS_CLASSES]['content'] .= ' readable';
                break;
        }

        return $ret;
    }

    public function getTitleBeforeauthors(array $componentVariation, array &$props)
    {
        switch ($componentVariation[1]) {
            case self::MODULE_LAYOUT_PREVIEWPOST_STANCE_CONTENTAUTHOR:
            case self::MODULE_LAYOUT_PREVIEWPOST_STANCE_CONTENTAUTHORREFERENCED:
            case self::MODULE_LAYOUT_PREVIEWPOST_STANCE_NAVIGATOR:
            case self::MODULE_LAYOUT_PREVIEWPOST_STANCE_THUMBNAIL:
                return array(
                    'belowcontent' => sprintf(
                        '<span class="pop-pulltextright">%s</span>',
                        TranslationAPIFacade::getInstance()->__('—', 'pop-userstance-processors')
                    )
                );
        }

        return parent::getTitleBeforeauthors($componentVariation, $props);
    }

    public function initModelProps(array $componentVariation, array &$props): void
    {
        switch ($componentVariation[1]) {
            case self::MODULE_LAYOUT_PREVIEWPOST_STANCE_CONTENTAUTHOR:
            case self::MODULE_LAYOUT_PREVIEWPOST_STANCE_CONTENTREFERENCED:
            case self::MODULE_LAYOUT_PREVIEWPOST_STANCE_CONTENTAUTHORREFERENCED:
            case self::MODULE_LAYOUT_PREVIEWPOST_STANCE_NAVIGATOR:
            case self::MODULE_LAYOUT_PREVIEWPOST_STANCE_THUMBNAIL:
                $this->appendProp($componentVariation, $props, 'class', 'alert alert-stance stances');
                break;
        }

        parent::initModelProps($componentVariation, $props);
    }
}



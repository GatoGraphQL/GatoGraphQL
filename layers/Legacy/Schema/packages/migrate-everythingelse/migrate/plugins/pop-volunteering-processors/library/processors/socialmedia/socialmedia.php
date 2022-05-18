<?php

class PoPCore_GenericForms_Module_Processor_SocialMedia extends PoP_Module_Processor_SocialMediaBase
{
    public final const MODULE_POSTSOCIALMEDIA_SIMPLEVIEW_VOLUNTEER = 'post-socialmedia-simpleview-volunteer';
    public final const MODULE_POSTSOCIALMEDIA_VOLUNTEER = 'post-socialmedia-volunteer';

    public function getComponentVariationsToProcess(): array
    {
        return array(
            [self::class, self::MODULE_POSTSOCIALMEDIA_SIMPLEVIEW_VOLUNTEER],
            [self::class, self::MODULE_POSTSOCIALMEDIA_VOLUNTEER],
        );
    }

    public function getSubmodules(array $module): array
    {
        switch ($module[1]) {
            case self::MODULE_POSTSOCIALMEDIA_SIMPLEVIEW_VOLUNTEER:
                return array(
                    [GD_SocialNetwork_Module_Processor_QuicklinkButtonGroups::class, GD_SocialNetwork_Module_Processor_QuicklinkButtonGroups::MODULE_QUICKLINKBUTTONGROUP_POSTRECOMMENDUNRECOMMEND],
                    [PoP_Module_Processor_SocialMediaMultipleComponents::class, PoP_Module_Processor_SocialMediaMultipleComponents::MODULE_MULTICOMPONENT_POSTSOCIALMEDIA],
                    [PoPCore_GenericForms_Module_Processor_ViewComponentButtonWrappers::class, PoPCore_GenericForms_Module_Processor_ViewComponentButtonWrappers::MODULE_VIEWCOMPONENT_BUTTONWRAPPER_POST_VOLUNTEER_TINY],
                );

            case self::MODULE_POSTSOCIALMEDIA_VOLUNTEER:
                return array(
                    [GD_SocialNetwork_Module_Processor_QuicklinkButtonGroups::class, GD_SocialNetwork_Module_Processor_QuicklinkButtonGroups::MODULE_QUICKLINKBUTTONGROUP_POSTRECOMMENDUNRECOMMEND],
                    [PoP_Module_Processor_SocialMediaMultipleComponents::class, PoP_Module_Processor_SocialMediaMultipleComponents::MODULE_MULTICOMPONENT_POSTOPTIONS],
                    [PoPCore_GenericForms_Module_Processor_ViewComponentButtonWrappers::class, PoPCore_GenericForms_Module_Processor_ViewComponentButtonWrappers::MODULE_VIEWCOMPONENT_BUTTONWRAPPER_POST_VOLUNTEER_TINY],
                );
        }

        return parent::getSubmodules($module);
    }

    public function initModelProps(array $module, array &$props): void
    {
        switch ($module[1]) {
            case self::MODULE_POSTSOCIALMEDIA_SIMPLEVIEW_VOLUNTEER:
                foreach ($this->getSubmodules($module) as $submodule) {
                    $this->appendProp([$submodule], $props, 'class', 'inline');
                }
                break;
        }

        parent::initModelProps($module, $props);
    }
}



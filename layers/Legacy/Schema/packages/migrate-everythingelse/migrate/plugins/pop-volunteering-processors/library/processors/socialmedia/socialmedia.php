<?php

class PoPCore_GenericForms_Module_Processor_SocialMedia extends PoP_Module_Processor_SocialMediaBase
{
    public final const COMPONENT_POSTSOCIALMEDIA_SIMPLEVIEW_VOLUNTEER = 'post-socialmedia-simpleview-volunteer';
    public final const COMPONENT_POSTSOCIALMEDIA_VOLUNTEER = 'post-socialmedia-volunteer';

    public function getComponentsToProcess(): array
    {
        return array(
            [self::class, self::COMPONENT_POSTSOCIALMEDIA_SIMPLEVIEW_VOLUNTEER],
            [self::class, self::COMPONENT_POSTSOCIALMEDIA_VOLUNTEER],
        );
    }

    public function getSubcomponents(array $component): array
    {
        switch ($component[1]) {
            case self::COMPONENT_POSTSOCIALMEDIA_SIMPLEVIEW_VOLUNTEER:
                return array(
                    [GD_SocialNetwork_Module_Processor_QuicklinkButtonGroups::class, GD_SocialNetwork_Module_Processor_QuicklinkButtonGroups::COMPONENT_QUICKLINKBUTTONGROUP_POSTRECOMMENDUNRECOMMEND],
                    [PoP_Module_Processor_SocialMediaMultipleComponents::class, PoP_Module_Processor_SocialMediaMultipleComponents::COMPONENT_MULTICOMPONENT_POSTSOCIALMEDIA],
                    [PoPCore_GenericForms_Module_Processor_ViewComponentButtonWrappers::class, PoPCore_GenericForms_Module_Processor_ViewComponentButtonWrappers::COMPONENT_VIEWCOMPONENT_BUTTONWRAPPER_POST_VOLUNTEER_TINY],
                );

            case self::COMPONENT_POSTSOCIALMEDIA_VOLUNTEER:
                return array(
                    [GD_SocialNetwork_Module_Processor_QuicklinkButtonGroups::class, GD_SocialNetwork_Module_Processor_QuicklinkButtonGroups::COMPONENT_QUICKLINKBUTTONGROUP_POSTRECOMMENDUNRECOMMEND],
                    [PoP_Module_Processor_SocialMediaMultipleComponents::class, PoP_Module_Processor_SocialMediaMultipleComponents::COMPONENT_MULTICOMPONENT_POSTOPTIONS],
                    [PoPCore_GenericForms_Module_Processor_ViewComponentButtonWrappers::class, PoPCore_GenericForms_Module_Processor_ViewComponentButtonWrappers::COMPONENT_VIEWCOMPONENT_BUTTONWRAPPER_POST_VOLUNTEER_TINY],
                );
        }

        return parent::getSubcomponents($component);
    }

    public function initModelProps(array $component, array &$props): void
    {
        switch ($component[1]) {
            case self::COMPONENT_POSTSOCIALMEDIA_SIMPLEVIEW_VOLUNTEER:
                foreach ($this->getSubcomponents($component) as $subComponent) {
                    $this->appendProp([$subComponent], $props, 'class', 'inline');
                }
                break;
        }

        parent::initModelProps($component, $props);
    }
}



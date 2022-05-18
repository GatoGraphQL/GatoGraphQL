<?php

class PoPCore_GenericForms_Module_Processor_SocialMediaPostWrappers extends PoP_Module_Processor_SocialMediaPostWrapperBase
{
    public final const COMPONENT_POSTSOCIALMEDIA_SIMPLEVIEW_VOLUNTEERPOSTWRAPPER = 'volunteerpost-socialmedia-simpleview-wrapper';
    public final const COMPONENT_POSTSOCIALMEDIA_VOLUNTEERPOSTWRAPPER = 'volunteerpost-socialmedia-wrapper';

    public function getComponentsToProcess(): array
    {
        return array(
            [self::class, self::COMPONENT_POSTSOCIALMEDIA_SIMPLEVIEW_VOLUNTEERPOSTWRAPPER],
            [self::class, self::COMPONENT_POSTSOCIALMEDIA_VOLUNTEERPOSTWRAPPER],
        );
    }

    public function getSocialmediaModule(array $component)
    {
        $socialmedias = array(
            self::COMPONENT_POSTSOCIALMEDIA_SIMPLEVIEW_VOLUNTEERPOSTWRAPPER => [PoPCore_GenericForms_Module_Processor_SocialMedia::class, PoPCore_GenericForms_Module_Processor_SocialMedia::COMPONENT_POSTSOCIALMEDIA_SIMPLEVIEW_VOLUNTEER],
            self::COMPONENT_POSTSOCIALMEDIA_VOLUNTEERPOSTWRAPPER => [PoPCore_GenericForms_Module_Processor_SocialMedia::class, PoPCore_GenericForms_Module_Processor_SocialMedia::COMPONENT_POSTSOCIALMEDIA_VOLUNTEER],
        );

        if ($socialmedia = $socialmedias[$component[1]] ?? null) {
            return $socialmedia;
        }

        return parent::getSocialmediaModule($component);
    }
}



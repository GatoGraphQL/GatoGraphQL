<?php

class PoPCore_GenericForms_Module_Processor_SocialMediaPostWrappers extends PoP_Module_Processor_SocialMediaPostWrapperBase
{
    public final const COMPONENT_POSTSOCIALMEDIA_SIMPLEVIEW_VOLUNTEERPOSTWRAPPER = 'volunteerpost-socialmedia-simpleview-wrapper';
    public final const COMPONENT_POSTSOCIALMEDIA_VOLUNTEERPOSTWRAPPER = 'volunteerpost-socialmedia-wrapper';

    /**
     * @return string[]
     */
    public function getComponentNamesToProcess(): array
    {
        return array(
            self::COMPONENT_POSTSOCIALMEDIA_SIMPLEVIEW_VOLUNTEERPOSTWRAPPER,
            self::COMPONENT_POSTSOCIALMEDIA_VOLUNTEERPOSTWRAPPER,
        );
    }

    public function getSocialmediaComponent(\PoP\ComponentModel\Component\Component $component)
    {
        $socialmedias = array(
            self::COMPONENT_POSTSOCIALMEDIA_SIMPLEVIEW_VOLUNTEERPOSTWRAPPER => [PoPCore_GenericForms_Module_Processor_SocialMedia::class, PoPCore_GenericForms_Module_Processor_SocialMedia::COMPONENT_POSTSOCIALMEDIA_SIMPLEVIEW_VOLUNTEER],
            self::COMPONENT_POSTSOCIALMEDIA_VOLUNTEERPOSTWRAPPER => [PoPCore_GenericForms_Module_Processor_SocialMedia::class, PoPCore_GenericForms_Module_Processor_SocialMedia::COMPONENT_POSTSOCIALMEDIA_VOLUNTEER],
        );

        if ($socialmedia = $socialmedias[$component->name] ?? null) {
            return $socialmedia;
        }

        return parent::getSocialmediaComponent($component);
    }
}



<?php

class PoPCore_GenericForms_Module_Processor_SocialMediaPostWrappers extends PoP_Module_Processor_SocialMediaPostWrapperBase
{
    public final const MODULE_POSTSOCIALMEDIA_SIMPLEVIEW_VOLUNTEERPOSTWRAPPER = 'volunteerpost-socialmedia-simpleview-wrapper';
    public final const MODULE_POSTSOCIALMEDIA_VOLUNTEERPOSTWRAPPER = 'volunteerpost-socialmedia-wrapper';

    public function getComponentVariationsToProcess(): array
    {
        return array(
            [self::class, self::MODULE_POSTSOCIALMEDIA_SIMPLEVIEW_VOLUNTEERPOSTWRAPPER],
            [self::class, self::MODULE_POSTSOCIALMEDIA_VOLUNTEERPOSTWRAPPER],
        );
    }

    public function getSocialmediaModule(array $componentVariation)
    {
        $socialmedias = array(
            self::MODULE_POSTSOCIALMEDIA_SIMPLEVIEW_VOLUNTEERPOSTWRAPPER => [PoPCore_GenericForms_Module_Processor_SocialMedia::class, PoPCore_GenericForms_Module_Processor_SocialMedia::MODULE_POSTSOCIALMEDIA_SIMPLEVIEW_VOLUNTEER],
            self::MODULE_POSTSOCIALMEDIA_VOLUNTEERPOSTWRAPPER => [PoPCore_GenericForms_Module_Processor_SocialMedia::class, PoPCore_GenericForms_Module_Processor_SocialMedia::MODULE_POSTSOCIALMEDIA_VOLUNTEER],
        );

        if ($socialmedia = $socialmedias[$componentVariation[1]] ?? null) {
            return $socialmedia;
        }

        return parent::getSocialmediaModule($componentVariation);
    }
}



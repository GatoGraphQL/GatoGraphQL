<?php

class PoP_Module_Processor_SocialLoginElements extends PoP_Module_Processor_SocialLoginElementsBase
{
    public final const COMPONENT_SOCIALLOGIN_NETWORKLINKS = 'sociallogin-networklinks';

    public function getComponentsToProcess(): array
    {
        return array(
            [self::class, self::COMPONENT_SOCIALLOGIN_NETWORKLINKS],
        );
    }
}




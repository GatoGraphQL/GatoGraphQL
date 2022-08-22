<?php

class PoP_Module_Processor_SocialLoginElements extends PoP_Module_Processor_SocialLoginElementsBase
{
    public final const COMPONENT_SOCIALLOGIN_NETWORKLINKS = 'sociallogin-networklinks';

    /**
     * @return string[]
     */
    public function getComponentNamesToProcess(): array
    {
        return array(
            self::COMPONENT_SOCIALLOGIN_NETWORKLINKS,
        );
    }
}




<?php

class PoP_Module_Processor_SocialLoginElements extends PoP_Module_Processor_SocialLoginElementsBase
{
    public final const MODULE_SOCIALLOGIN_NETWORKLINKS = 'sociallogin-networklinks';

    public function getModulesToProcess(): array
    {
        return array(
            [self::class, self::MODULE_SOCIALLOGIN_NETWORKLINKS],
        );
    }
}




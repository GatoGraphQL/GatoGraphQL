<?php

class PoP_Avatar_FunctionsAPI_Base
{
    public function __construct()
    {
        PoP_Avatar_FunctionsAPIFactory::setInstance($this);
    }

    public function getAvatarSizes()
    {
        return PoP_AvatarFoundationManagerFactory::getInstance()->getSizes();
    }
}

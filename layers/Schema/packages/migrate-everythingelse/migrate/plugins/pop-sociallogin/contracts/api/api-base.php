<?php

class PoP_SocialLogin_API_Base
{
    public function __construct()
    {
        PoP_SocialLogin_APIFactory::setInstance($this);
    }
}

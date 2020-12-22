<?php

class PoP_CommonUserRoles_API_Base
{
    public function __construct()
    {
        PoP_CommonUserRoles_APIFactory::setInstance($this);
    }
}

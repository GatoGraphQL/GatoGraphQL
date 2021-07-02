<?php

class PoP_UserCommunities_API_Base
{
    public function __construct()
    {
        PoP_UserCommunities_APIFactory::setInstance($this);
    }
}

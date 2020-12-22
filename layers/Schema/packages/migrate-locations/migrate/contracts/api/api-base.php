<?php

class PoP_Locations_API_Base
{
    public function __construct()
    {
        PoP_Locations_APIFactory::setInstance($this);
    }
}

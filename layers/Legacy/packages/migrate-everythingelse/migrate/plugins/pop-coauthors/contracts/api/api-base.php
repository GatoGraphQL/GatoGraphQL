<?php

class PoP_Coauthors_API_Base
{
    public function __construct()
    {
        PoP_Coauthors_APIFactory::setInstance($this);
    }
}

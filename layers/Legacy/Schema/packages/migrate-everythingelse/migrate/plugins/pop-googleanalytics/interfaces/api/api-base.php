<?php

class PoP_GoogleAnalytics_API_Base
{
    public function __construct()
    {
        PoP_GoogleAnalytics_APIFactory::setInstance($this);
    }
}

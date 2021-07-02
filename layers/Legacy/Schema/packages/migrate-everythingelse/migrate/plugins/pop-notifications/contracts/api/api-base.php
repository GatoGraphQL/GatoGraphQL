<?php

class PoP_Notifications_FunctionsAPI_Base
{
    public function __construct()
    {
        PoP_Notifications_FunctionsAPIFactory::setInstance($this);
    }
}

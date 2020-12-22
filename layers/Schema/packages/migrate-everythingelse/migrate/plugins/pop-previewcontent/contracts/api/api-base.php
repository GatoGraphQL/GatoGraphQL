<?php

class PoP_PreviewContent_FunctionsAPI_Base
{
    public function __construct()
    {
        PoP_PreviewContent_FunctionsAPIFactory::setInstance($this);
    }
}

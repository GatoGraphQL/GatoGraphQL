<?php

class PoP_Multilingual_FunctionsAPI_Base
{
    public function __construct()
    {
        PoP_Multilingual_FunctionsAPIFactory::setInstance($this);
    }

    public function getUrlModificationMode()
    {
        return POP_MULTILINGUAL_URLMODIFICATIONMODE_PREPATH;
    }
}

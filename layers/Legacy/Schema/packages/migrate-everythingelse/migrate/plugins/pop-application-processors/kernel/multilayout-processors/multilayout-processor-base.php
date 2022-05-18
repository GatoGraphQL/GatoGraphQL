<?php

class PoP_Application_Multilayout_ProcessorBase implements PoP_Application_Multilayout
{
    public function __construct()
    {
        PoP_Application_MultilayoutManagerFactory::getInstance()->add($this);
    }

    public function addLayoutComponents(&$layouts, $handle, $format = '')
    {
    }
}

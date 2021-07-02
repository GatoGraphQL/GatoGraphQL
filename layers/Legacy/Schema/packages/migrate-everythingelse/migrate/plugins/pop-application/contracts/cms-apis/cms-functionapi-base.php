<?php
namespace PoP\Application;

abstract class FunctionAPI_Base implements FunctionAPI
{
    public function __construct()
    {
        FunctionAPIFactory::setInstance($this);
    }

    public function isAdminPanel()
    {
        return false;
    }
    public function getSiteName()
    {
    	return '';
    }
    public function getSiteDescription()
    {
    	return '';
    }
}

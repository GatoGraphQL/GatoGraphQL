<?php
namespace PoPSchema\Menus;

abstract class FunctionAPI_Base implements FunctionAPI
{
    public function __construct()
    {
        FunctionAPIFactory::setInstance($this);
    }
}

<?php
namespace PoPCMSSchema\Pages;

abstract class ObjectPropertyResolver_Base implements ObjectPropertyResolver
{
    public function __construct()
    {
        ObjectPropertyResolverFactory::setInstance($this);
    }
}

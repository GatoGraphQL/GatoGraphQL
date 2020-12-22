<?php
namespace PoPSchema\QueriedObject;

abstract class AbstractCMSRoutingState implements CMSRoutingStateInterface
{
    public function __construct()
    {
        CMSRoutingStateFactory::setInstance($this);
    }
}

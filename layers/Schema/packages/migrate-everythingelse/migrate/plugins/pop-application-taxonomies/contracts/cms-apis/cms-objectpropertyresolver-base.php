<?php
namespace PoP\ApplicationTaxonomies;

abstract class ObjectPropertyResolver_Base implements ObjectPropertyResolver
{
    public function __construct()
    {
        ObjectPropertyResolverFactory::setInstance($this);
    }
}

<?php

declare(strict_types=1);

namespace PoPSchema\Tags\ComponentContracts;

trait TagAPIRequestedContractTrait
{
    abstract protected function getTypeAPI(): \PoPSchema\Tags\FunctionAPI;
    abstract protected function getTypeResolverClass(): string;
    protected function getObjectPropertyAPI(): \PoPSchema\Tags\ObjectPropertyResolver
    {
        $cmstagsresolver = \PoPSchema\Tags\ObjectPropertyResolverFactory::getInstance();
        return $cmstagsresolver;
    }
}

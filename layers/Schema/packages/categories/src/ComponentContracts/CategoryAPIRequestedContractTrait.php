<?php

declare(strict_types=1);

namespace PoPSchema\Categories\ComponentContracts;

trait CategoryAPIRequestedContractTrait
{
    abstract protected function getTypeAPI(): \PoPSchema\Categories\FunctionAPI;
    abstract protected function getTypeResolverClass(): string;
    protected function getObjectPropertyAPI(): \PoPSchema\Categories\ObjectPropertyResolver
    {
        $cmscategoriesresolver = \PoPSchema\Categories\ObjectPropertyResolverFactory::getInstance();
        return $cmscategoriesresolver;
    }
}
